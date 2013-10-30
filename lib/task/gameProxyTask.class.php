<?php

class GameClient {

    const AUTO_EQUIPMENT_INTERVAL = 60;
    const AUTO_EQUIPMENT_LIMIT = 180;

    public $useragent;
    public $server;
    public $user_id;
    public $session_id;
    public $reactor;
    public $testCount = 1;
    // номер сектора
    public $sector;
    public $socket = null;
    public $obuf = array();
    public $ibuf = '';
    // идентификатор игрока
    public $player_id = NULL;
    // имя игрока
    public $player_name;

    /**
     * Идентификатор главной базы
     *
     * @var integer
     */
    public $base_id = NULL;

    /**
     * Колонии
     *
     * @var array null
     */
    public $colonies = NULL;

    /**
     * Альянс
     *
     * @var array null
     */
    public $alliance = null;
    protected $curl;
    protected $task;
    public $jobs = array();

    /*
     * equipment_auto_upgrade
     *   upgrade
     *   repair
     *   repairing
     */

    public function __construct($user_id, sfTask &$task) {
        $task->logBlock(__FUNCTION__, 'INFO');
        $this->curl = curl_init();
        $this->socket = null;
        $this->user_id = $user_id;
        $this->task = $task;

        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    }

    public function init($server, $reactor, $session_id, $useragent) {
        $this->task->logBlock(__FUNCTION__, 'INFO');
        $this->useragent = $useragent;
        $this->reactor = $reactor;
        $this->session_id = $session_id;

        $cookie_file = sfConfig::get('sf_upload_dir') . '/cookie/' . $this->user_id . '.dat';
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($this->curl, CURLOPT_USERAGENT, $this->useragent);

        if ($server != $this->server) {
            if (preg_match('/^sector([0-9]+)\./', $server, $matches)) {
                $this->sector = $matches[1];
                printf("SECTOR: %u\n", $this->sector);
            } else {
                return false;
            }

            $this->server = $server;

            $r = $this->GET('/api/player');
            if (is_null($r)) {
                return false;
            }

//            file_put_contents(sfConfig::get('sf_upload_dir') . '/current-player.json', $r);
            $d = json_decode($r, true);

            $this->player_id = $d['response']['id'];
            $this->player_name = $d['response']['name'];
            $this->alliance = $d['response']['alliance'];
            $this->base_id = $d['response']['base']['id'];
            $this->alliance = $d['response']['alliance'];
            $this->colonies = $d['response']['colonies'];
            $this->testCount = 1;

            $this->jobs['auto_equipment'] = array();

            $this->jobs['auto_equipment']['next'] = time() + self::AUTO_EQUIPMENT_INTERVAL;

            $this->jobs['auto_equipment']['repairing'] = array();
            foreach ($d['response']['jobs'] as $job) {
                if ($job['type'] == 'RepairEquipment') {
                    if (!in_array($job['equipment_id'], $this->jobs['auto_equipment']['repairing'])) {
                        $this->jobs['auto_equipment']['repairing'][] = $job['equipment_id'];
                    }
                }
            }

            $this->autoEquipmentParse($d['response']['equipment']);

            return $this->connect();
        }

        return true;
    }

    protected function autoEquipmentParse(&$arr) {

        $this->jobs['auto_equipment']['used'] = 0;
        $this->jobs['auto_equipment']['upgrade'] = array();
        $this->jobs['auto_equipment']['repair'] = array();
        $this->jobs['auto_equipment']['craft1a'] = array();
        $this->jobs['auto_equipment']['craft1b'] = array();

        $a = array();

        foreach ($arr as $e) {
            $a[] = $e['id'];
            if (!$e['equipped']) {
                if ($e['durability'] > 0) {
                    $this->jobs['auto_equipment']['upgrade'][] = $e['id'];
                    if (($key = array_search($e['id'], $this->jobs['auto_equipment']['repairing'])) !== false) {
                        unset($this->jobs['auto_equipment']['repairing'][$key]);
                    }
                } else {
                    if (!in_array($e['id'], $this->jobs['auto_equipment']['repairing'])) {
                        $this->autoEquipmentBroken($e, count($arr));
                    }
                }
            } else {
                $this->jobs['auto_equipment']['used'] ++;
            }
        }

        foreach ($this->jobs['auto_equipment']['repairing'] as $key => $id) {
            if (!in_array($id, $a)) {
                unset($this->jobs['auto_equipment']['repairing'][$key]);
            }
        }

        $this->jobs['auto_equipment']['repairing'] = array_unique($this->jobs['auto_equipment']['repairing']);
        sort($this->jobs['auto_equipment']['repairing']);
    }

    protected function autoEquipmentBroken($equipment, $total) {
        $record = Doctrine::getTable('EquipmentLevel')
                ->createQuery('el')
                ->where('el.equipment_id = (SELECT e.id FROM Equipment e WHERE e.type = ?)', $equipment['type'])
                ->andWhere('el.level = ?', $equipment['level'])
                ->fetchOne();

        if ($record && $total > self::AUTO_EQUIPMENT_LIMIT && $equipment['level'] > 4 && in_array($record->tier, [1, 2])) {
            $common = ['hp', 'range', 'attack_rate', 'damage', 'simultaneous_targets', 'splash_radius', 'concussion_effect'];
            foreach ($record->stats as $stat => $value) {
                if (!in_array($stat, $common) && $value) {
                    $this->task->logBlock(sprintf('%u has unique stat: %s = %s', $equipment['id'], $stat, $value), 'ERROR');
                    $this->jobs['auto_equipment']['repair'][] = $equipment['id'];
                    return;
                }
            }

            if ($record->tier == 1) {
//                var_dump(json_encode($record->stats));
                $this->jobs['auto_equipment']['craft1a'][] = $equipment['id'];
                return;
            } elseif ($record->tier == 2) {
//                var_dump(json_encode($record->stats));
                $this->jobs['auto_equipment']['craft1b'][] = $equipment['id'];
                return;
            }
        }
        $this->jobs['auto_equipment']['repair'][] = $equipment['id'];
    }

    public function connect() {
        $this->task->logBlock(__METHOD__, 'INFO');
        if ($this->socket != NULL) {
            $this->disconnect();
        }

        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if (!socket_connect($this->socket, $this->server, 8000)) {
            return false;
        }

        $this->ibuf = '';
        $this->obuf = array();
        $this->obuf[] = json_encode(array(
                    'type' => 'subscribe',
                    'data' => array(
                        'player_id' => strval($this->player_id)
                    )
                )) . "\r\n";

        return true;
    }

    public function disconnect() {
        if ($this->socket == NULL) {
            return;
        }
        $this->task->logBlock(__METHOD__, 'INFO');
        socket_shutdown($this->socket);
        socket_close($this->socket);
        $this->obuf = array();
        $this->ibuf = '';
        $this->socket = NULL;
    }

    public function GET($path, $query = array()) {
        $this->task->logSection('GET', $path);

        $query['meltdown'] = MeltdownTable::getCurrent();
        $query['reactor'] = $this->reactor;
        $query['user_id'] = $this->user_id;
        $query['_session_id'] = $this->session_id;

        $query_str = http_build_query($query);

        $url = sprintf('http://%s%s?%s', $this->server, $path, $query_str);

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, false);
        curl_setopt($this->curl, CURLOPT_HTTPGET, true);

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'x-s3-cachebreak:'
        ));

        $result = curl_exec($this->curl);


        if (curl_getinfo($this->curl, CURLINFO_HTTP_CODE) != 200) {
            var_dump(curl_getinfo($this->curl));
            var_dump($result);
            $result = NULL;
        }

        return $result;
    }

    public function POST($path, $query = array()) {
        $this->task->logSection('POST', $path);

        $query['meltdown'] = MeltdownTable::getCurrent();
        $query['reactor'] = $this->reactor;
        $query['user_id'] = $this->user_id;
        $query['_session_id'] = $this->session_id;
        $query['testCount'] = $this->testCount;

        $postdata = str_replace('_', '%5F', http_build_query($query));

        $url = sprintf('http://%s%s', $this->server, $path);

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_HTTPGET, false);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postdata);

        $hashsting = $url . $postdata . 'SF' . 'f0uR' . 'l1f3';

        $x_s3_cachebreak = sha1($hashsting);

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'x-s3-cachebreak: ' . $x_s3_cachebreak
        ));

        $result = curl_exec($this->curl);

        if (curl_getinfo($this->curl, CURLINFO_HTTP_CODE) != 200) {
//            file_put_contents(sfConfig::get('sf_upload_dir') . '/LPOST.dat', $result);
            $result = NULL;
        } else {
            $this->testCount++;
        }

        return $result;
    }

    public function __destruct() {
        $this->disconnect();
        curl_close($this->curl);
    }

    public function read() {
        $r = socket_read($this->socket, 4096);
        if (strlen($r) == 0) {
            $this->task->logSection($this->user_id, 'connection closed, reconnecting...');
            $this->connect();
        } else {
            $messages = explode("\r\n", $this->ibuf . $r);
            while (count($messages) > 1) {
                $cur = array_shift($messages);
                $c = json_decode($cur, true);

                $this->task->logSection("---->", $cur);

                $record = new Proxy();
                $record->type = $c['type'];
                $record->params = $c['data'];
                if (isset($c['timestamp'])) {
                    $record->timestamp = $c['timestamp'];
                }
                $record->save();

                switch ($c['type']) {
                    case 'subscribe':
                        $this->subscribe();
                        break;
                    case 'job_completed':
                        $this->job_completed($c['data']);
                        break;
                    default:
                        break;
                }
            }
            $this->ibuf = implode("\r\n", $messages);
        }
    }

    protected function subscribe() {
        $this->obuf[] = json_encode(array(
                    'type' => 'chat_join',
                    'data' => array(
                        'player_id' => strval($this->player_id),
                        'room' => sprintf('global::%u', $this->sector)
                    )
                )) . "\r\n";

        if ($this->alliance) {
            $this->obuf[] = json_encode(array(
                        'type' => 'chat_join',
                        'data' => array(
                            'player_id' => strval($this->player_id),
                            'room' => sprintf('alliance::%u::%u', $this->alliance['id'], $this->sector)
                        )
                    )) . "\r\n";
        }
        $this->obuf[] = json_encode(array(
                    'type' => 'chat_join',
                    'data' => array(
                        'player_id' => strval($this->player_id),
                        'room' => sprintf('locale::%u::en', $this->sector),
                        'player_name' => $this->player_name
                    )
                )) . "\r\n";
    }

    protected function job_completed($data) {
        switch ($data['type']) {
            case 'RepairEquipment':
                if (isset($this->jobs['auto_equipment'])) {
                    if (($key = array_search($data['equipment_id'], $this->jobs['auto_equipment']['repairing'])) !== false) {
                        unset($this->jobs['auto_equipment']['repairing'][$key]);
                    }
                    $this->jobs['auto_equipment']['upgrade'][] = $data['equipment_id'];
                }
                break;
            default:
                break;
        }
    }

    public function job() {

        if (isset($this->jobs['auto_equipment'])) {

            if (time() >= $this->jobs['auto_equipment']['next']) {
                $r = $this->GET('/api/player/equipment');

                if ($r) {
                    $this->jobs['auto_equipment']['next'] = time() + self::AUTO_EQUIPMENT_INTERVAL;
                    $d = json_decode($r, true);
                    $this->autoEquipmentParse($d['response']['equipment']);
                } else {
                    unset($this->jobs['auto_equipment']);
                    $this->task->logBlock('STOP AUTO UPGRADE', 'ERROR');
                }
                return;
            }

            $this->task->log(sprintf('used: %u, repair: %u, repairing: %u, upgrade: %u, craft1a: %u, craft1b: %u, total: %u', $this->jobs['auto_equipment']['used'], count($this->jobs['auto_equipment']['repair']), count($this->jobs['auto_equipment']['repairing']), count($this->jobs['auto_equipment']['upgrade']), count($this->jobs['auto_equipment']['craft1a']), count($this->jobs['auto_equipment']['craft1b']), $this->autoEquipmentTotal()));
            
            // craft1a
            if (count($this->jobs['auto_equipment']['craft1a']) >= 10) {
                $ids = array_splice($this->jobs['auto_equipment']['craft1a'], 0, 10);

                $this->task->logSection('Craft1A', implode(', ', $ids));

                $input = array();
                foreach ($ids as $id) {
                    $input[] = array('type' => 'equipment', 'id' => intval($id));
                }

                $query = array();
                $query['name'] = 'rarebox1a';
                $query['input'] = json_encode($input);

                $this->POST('/api/player/craft', $query);

                return;
            }

            // craft1b
            if (count($this->jobs['auto_equipment']['craft1b']) >= 5) {
                $ids = array_splice($this->jobs['auto_equipment']['craft1b'], 0, 5);

                $this->task->logSection('Craft1B', implode(', ', $ids));

                $input = array();
                foreach ($ids as $id) {
                    $input[] = array('type' => 'equipment', 'id' => intval($id));
                }

                $query = array();
                $query['name'] = 'rarebox1b';
                $query['input'] = json_encode($input);

                $this->POST('/api/player/craft', $query);

                return;
            }

            // repair
            if (count($this->jobs['auto_equipment']['repair'])) {
                $id = array_shift($this->jobs['auto_equipment']['repair']);

//                $this->task->logSection('REPAIR', $id);

                $query = array();
                $query['basis_id'] = $this->base_id;
                $query['_method'] = 'post';

                $r = $this->POST("/api/player/equipment/{$id}/repair", $query);
                if ($r && !in_array($id, $this->jobs['auto_equipment']['repairing'])) {
                    $this->jobs['auto_equipment']['repairing'][] = $id;
                }
                return;
            }

            // upgrade
            if (count($this->jobs['auto_equipment']['upgrade'])) {
                $id = array_shift($this->jobs['auto_equipment']['upgrade']);

//                $this->task->logSection('UPGRADE', $id);

                $query = array();
                $query['basis_id'] = $this->base_id;
                $query['_method'] = 'post';

                $r = $this->POST(sprintf('/api/player/equipment/%s/instant_upgrade', $id), $query);
                if ($r) {
                    $d = json_decode($r, true);
                    if (isset($d['response']['job'])) {
                        if ($d['response']['job']['successful']) {
                            $this->jobs['auto_equipment']['upgrade'][] = $id;
                        } else {
                            $this->autoEquipmentBroken($d['response']['job']['equipment'], $this->autoEquipmentTotal() + 1);
                        }

                        $level = $d['response']['job']['equipment']['level'] - ($d['response']['job']['successful'] ? 1 : 0);
                        if (!isset($this->jobs['auto_equipment']['stat'][$level])) {
                            $this->jobs['auto_equipment']['stat'][$level]['done'] = 0;
                            $this->jobs['auto_equipment']['stat'][$level]['fail'] = 0;
                        }

                        $this->jobs['auto_equipment']['stat'][$level][$d['response']['job']['successful'] ? 'done' : 'fail'] ++;

                        return;
                    }
                    elseif(isset($d['response']['errors'])) {
                        $this->task->logBlock($d['response']['errors'], 'ERROR');
                    }
                    else {
                        $this->task->logBlock($r, 'ERROR');
                    }
                }
                else {
                    $this->task->logBlock(var_export($r, true), 'ERROR');
                }
                
                $this->jobs['auto_equipment']['repair'][] = $id;

                return;
            }
        }
    }

    protected function autoEquipmentTotal() {
        return $this->jobs['auto_equipment']['used'] +
                count($this->jobs['auto_equipment']['repair']) +
                count($this->jobs['auto_equipment']['repairing']) +
                count($this->jobs['auto_equipment']['upgrade']) +
                count($this->jobs['auto_equipment']['craft1a']) +
                count($this->jobs['auto_equipment']['craft1b']);
    }

    public function getTimeout() {
        if (isset($this->jobs['auto_equipment'])) {
            if (count($this->jobs['auto_equipment']['repair'])) {
                return 1;
            }
            if (count($this->jobs['auto_equipment']['upgrade'])) {
                return 1;
            }
            if (count($this->jobs['auto_equipment']['craft1a']) >= 10) {
                return 1;
            }
            if (count($this->jobs['auto_equipment']['craft1b']) >= 5) {
                return 1;
            }
            if ($this->jobs['auto_equipment']['next'] < time()) {
                return 1;
            }

            return $this->jobs['auto_equipment']['next'] - time();
        }
        return NULL;
    }

    public function write() {
        if (count($this->obuf)) {
            $cur = array_shift($this->obuf);
            $this->task->logSection('<----', trim($cur));
            $r = socket_write($this->socket, $cur);
            if ($r === false) {
                $this->task->logBlock(socket_strerror(socket_last_error($this->socket)), 'ERROR');
            } elseif ($r < strlen($cur)) {
                array_unshift($this->obuf, substr($cur, $r));
            }
        }
    }

}

class PeerSocket extends BufferedSocket {

    public function __construct($socket) {
        $this->fd = $socket;
    }

    public function disconnect() {
        if ($this->fd) {
            socket_shutdown($this->fd);
            socket_close($this->fd);
        }
        $this->fd = null;
    }

    public function __destruct() {
        $this->disconnect();
    }

}

class BufferedSocket {

    public $ibuf = '';
    public $obuf = '';
    public $fd = null;

}

class gameProxyTask extends sfBaseTask {

    // @var resource Сокет, принимающий запросы на соединение от клиентов
    protected $server = null;
    // @var BufferedSocket[] Команды от клиентов
    protected $peer = array();
    // Прокси-сокеты, поддерживающие соединение до сервера игры
    protected $client = array();

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        // new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));
        
//        $this->dispatcher->connect('task.logBlock', array($this, 'listenLogBlock'));
        
        $this->namespace = 'game';
        $this->name = 'proxy';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [game:proxy|INFO] task does things.
Call it with:

  [php symfony game:proxy|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        /* $connection = */ $databaseManager->getDatabase($options['connection'])->getConnection();

        $socket_file = '/tmp/edgeworld-proxy.sock';

        if (file_exists($socket_file)) {
            if (!unlink($socket_file)) {
                throw new sfException("unlink");
            }
        }

        $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);

        if (!socket_bind($socket, $socket_file)) {
            throw new sfException('socket_bind');
        }

        chmod($socket_file, 0777);

        if (!socket_listen($socket)) {
            throw new sfException('socket_listen');
        }


        // главный цикл
        while (true) {
            // $this->logBlock('MAIN LOOP', 'INFO');

            $timeout = null;

            $r_sock = array();
            $w_sock = array();
            $e_sock = NULL;

            $r_sock[] = $socket;

            foreach ($this->peer as $peer) {
                $r_sock[] = $peer->fd;
                if (strlen($peer->obuf)) {
                    $w_sock[] = $peer->fd;
                }
            }

            foreach ($this->client as $client) {
                $r_sock[] = $client->socket;
                if (count($client->obuf)) {
                    $w_sock[] = $client->socket;
                }

                $timeoutClient = $client->getTimeout();
                if ($timeout === NULL || ($timeoutClient !== NULL && $timeout > $timeoutClient)) {
                    $timeout = $timeoutClient;
                }
            }

//            $this->logSection('SELECT', $timeout === null ? 'inf' : $timeout);

            if (socket_select($r_sock, $w_sock, $e_sock, $timeout, $timeout === 0 ? 500 : 0) != 0) {
                if (in_array($socket, $r_sock)) {
                    // new peer connection;
                    $this->logBlock('new connection', 'INFO');
                    $peer_socket = socket_accept($socket);
                    $this->peer[] = new PeerSocket($peer_socket);
                }

                foreach ($this->peer as $i => $peer) {
                    if (in_array($peer->fd, $r_sock)) {
                        $this->logSection('PEER', '>>>>>>>>');
                        $d = socket_read($peer->fd, 65536);
                        if ($d === FALSE || $d == '') {
                            $peer->disconnect();
                            unset($this->peer[$i]);
                            continue;
                        }

                        $peer->ibuf .= $d;
                        if (strpos($peer->ibuf, "\n")) {
                            $peer->obuf = $this->peerMessage(json_decode($peer->ibuf, true));
                            if (strlen($peer->obuf) == 0) {
                                $peer->disconnect();
                                unset($this->peer[$i]);
                                continue;
                            }
                        }
                    }

                    if (in_array($peer->fd, $w_sock)) {
                        $this->logSection('PEER', '<<<<<<<<<');
                        $l = socket_write($peer->fd, $peer->obuf);
                        if ($l === FALSE || $l == strlen($peer->obuf)) {
                            $peer->disconnect();
                            unset($this->peer[$i]);
                            continue;
                        }
                        $peer->obuf = substr($peer->obuf, $l);
                    }
                }

                foreach ($this->client as $i => $client) {
                    if (in_array($client->socket, $r_sock)) {
                        $client->read();
                    }
                    if (in_array($client->socket, $w_sock)) {
                        $client->write();
                    }
                }
            } else {
                foreach ($this->client as $client) {
                    $client->job();
                }
            }
        }
    }

    protected function peerMessage($data) {
        switch ($data['cmd']) {
            case 'status':
                $this->logSection('status', "for uid = {$data['uid']}");
                $result = array();
                $result['online'] = isset($this->client[$data['uid']]);
                return json_encode($result);

            case 'login':
                $uid = $data['user_id'];
                if (!isset($this->client[$uid])) {
                    $this->client[$uid] = new GameClient($uid, $this);
                }
                $result = $this->client[$uid]->init($data['server'], $data['reactor'], $data['session'], $data['useragent']);
                if (!$result) {
                    unset($this->client[$uid]);
                }
                return json_encode($result);

            case 'get':
                $uid = $data['user_id'];
                $path = $data['path'];
                $query = $data['query'];
                return isset($this->client[$uid]) ? $this->client[$uid]->GET($path, $query) : '';

            case 'post':
                $uid = $data['user_id'];
                $path = $data['path'];
                $query = $data['query'];
                return isset($this->client[$uid]) ? $this->client[$uid]->POST($path, $query) : '';

            default:
                break;
        }

        return '';
    }

}
