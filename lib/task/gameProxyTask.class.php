<?php

class GameClient
{

    public $useragent;

    public $server;

    public $user_id;

    public $session_id;

    public $reactor;
    
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

    public function __construct($user_id, sfTask &$task)
    {
        $task->logBlock(__FUNCTION__, 'INFO');
        $this->curl = curl_init();
        $this->socket = null;
        $this->user_id = $user_id;
        $this->task = $task;
    }

    public function init($server, $reactor, $session_id, $useragent)
    {
        $this->task->logBlock(__FUNCTION__, 'INFO');
        $this->useragent = $useragent;
        $this->reactor = $reactor;
        $this->session_id = $session_id;
        
        if ($server != $this->server) {
            if (preg_match('/^sector([0-9]+)\./', $server, $matches)) {
                $this->sector = $matches[1];
                printf("SECTOR: %u\n", $this->sector);
            } else
                return false;
            
            $this->server = $server;
            
            $r = $this->RGET('/api/player');
            if (is_null($r))
                return false;
            
            file_put_contents(sfConfig::get('sf_upload_dir').'/current-player.json', $r);
            $r = json_decode($r, true);
            
            $this->player_id = $r['response']['id'];
            $this->player_name = $r['response']['name'];
            $this->alliance = $r['response']['alliance'];
            $this->base_id = $r['response']['base']['id'];
            $this->alliance = $r['response']['alliance'];
            $this->colonies = $r['response']['colonies'];
            
            $this->jobs['equipment_auto_update'] = array();
            $this->jobs['equipment_auto_update']['repairing'] = array();
            $this->jobs['equipment_auto_update']['update'] = array();
            $this->jobs['equipment_auto_update']['repair'] = array();
            
            foreach ($r['response']['jobs'] as $job) {
                if($job['type'] == 'RepairEquipment') {
                    $this->jobs['equipment_auto_update']['repairing'][] = $job['equipment_id'];
                }
            }
            
            foreach ($r['response']['equipment'] as $equipment) {
                if(!$equipment['equipped']) {
                    if($equipment['durability'] > 0) {
                        // обновить
                        $this->jobs['equipment_auto_update']['update'][] = $equipment['id'];
                    }
                    elseif(!in_array($equipment['id'], $this->jobs['equipment_auto_update']['repairing'])) {
                        // починить, если уже не чинится
                        $this->jobs['equipment_auto_update']['repair'][] = $equipment['id'];
                    }
                }
            }
            
            return $this->connect();
        }
        
        return true;
    }

    public function connect()
    {
        $this->task->logBlock(__METHOD__, 'INFO');
        if ($this->socket != NULL) {
            $this->disconnect();
        }
        
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        
        if (! socket_connect($this->socket, $this->server, 8000)) {
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

    public function disconnect()
    {
        if ($this->socket == NULL)
            return;
        $this->task->logBlock(__METHOD__, 'INFO');
        socket_shutdown($this->socket);
        socket_close($this->socket);
        $this->obuf = array();
        $this->ibuf = '';
        $this->socket = NULL;
    }

    public function RGET($path, $query = array())
    {
        $this->task->logBlock(__METHOD__, 'INFO');
        $query['meltdown'] = MeltdownTable::getCurrent();
        $query['reactor'] = $this->reactor;
        $query['user_id'] = $this->user_id;
        $query['_session_id'] = $this->session_id;
        
        $query_str = http_build_query($query);
        
        $url = sprintf('http://%s%s?%s', $this->server, $path, $query_str);
        
        $this->task->logSection('url', $url);
        
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_USERAGENT, $this->useragent);
        
        $cookie_file = sfConfig::get('sf_upload_dir') . '/cookie/' . $this->user_id . '.dat';
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, $cookie_file);
        
        $result = curl_exec($this->curl);
        
        var_dump(curl_getinfo($this->curl));
        
        if (curl_getinfo($this->curl, CURLINFO_HTTP_CODE) != 200) {
            var_dump($result);
            $result = NULL;
        }
        
        return $result;
    }

    public function __destruct()
    {
        $this->disconnect();
        curl_close($this->curl);
    }

    public function read()
    {
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
                        $this->processSubscribe();
                        break;
                    case 'job_completed':
                        $this->processJobCompleted($c['data']);
                        break;
                    default:
                        break;
                }
            }
            $this->ibuf = implode("\r\n", $messages);
        }
    }

    protected function processSubscribe()
    {
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
    
    protected function processJobCompleted($data)
    {
        switch($data['type'])
        {
        	case 'RepairEquipment':
        	    if(isset($this->jobs['equipment_auto_update'])) {
        	        $this->jobs['equipment_auto_update']['update'][] = $data['equipment_id'];
        	    }
        	    break;
        	default:
        	    break;
        }
    }

    public function write()
    {
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

class PeerSocket extends BufferedSocket
{

    public function __construct($socket)
    {
        $this->fd = $socket;
    }

    public function disconnect()
    {
        if ($this->fd) {
            socket_shutdown($this->fd);
            socket_close($this->fd);
        }
        $this->fd = null;
    }

    public function __destruct()
    {
        $this->disconnect();
    }
}

class BufferedSocket
{

    public $ibuf = '';

    public $obuf = '';

    public $fd = null;
}

class gameProxyTask extends sfBaseTask
{
    
    // @var resource Сокет, принимающий запросы на соединение от клиентов
    protected $server = null;
    
    // @var BufferedSocket[] Команды от клиентов
    protected $peer = array();
    
    // Прокси-сокеты, поддерживающие соединение до сервера игры
    protected $client = array();

    protected function configure()
    {
        // // add your own arguments here
        // $this->addArguments(array(
        // new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));
        
        $this->namespace = 'game';
        $this->name = 'proxy';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [game:proxy|INFO] task does things.
Call it with:

  [php symfony game:proxy|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        
        $socket_file = '/tmp/edgeworld-proxy.sock';
        
        if (file_exists($socket_file)) {
            if (! unlink($socket_file)) {
                throw new sfException("unlink");
            }
        }
        
        $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
        
        if (! socket_bind($socket, $socket_file)) {
            throw new sfException('socket_bind');
        }
        
        chmod($socket_file, 0777);
        
        if (! socket_listen($socket)) {
            throw new sfException('socket_listen');
        }
        
        $timeout = null;
        
        // главный цикл
        while (true) {
            // $this->logBlock('MAIN LOOP', 'INFO');
            
            $r_sock = array();
            $w_sock = array();
            $e_sock = NULL;
            
            $r_sock[] = $socket;
            
            foreach ($this->peer as $peer) {
                $r_sock[] = $peer->fd;
                if (strlen($peer->obuf))
                    $w_sock[] = $peer->fd;
            }
            
            foreach ($this->client as $client) {
                $r_sock[] = $client->socket;
                if (count($client->obuf)) {
                    $w_sock[] = $client->socket;
                }
            }
            
            if (socket_select($r_sock, $w_sock, $e_sock, $timeout) != 0) {
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
                            $peer->obuf = $this->processPeerMessage(json_decode($peer->ibuf, true));
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
                $this->logBlock('TIMEOUT', 'ERROR');
            }
        }
    }

    protected function processPeerMessage($data)
    {
        switch ($data['cmd']) {
            case 'status':
                $this->logSection('status', "for uid = {$data['uid']}");
                $result = array();
                $result['online'] = isset($this->client[$data['uid']]);
                return json_encode($result);
            
            case 'login':
                $uid = $data['user_id'];
                if (! isset($this->client[$uid])) {
                    $this->client[$uid] = new GameClient($uid, $this);
                }
                $result = $this->client[$uid]->init($data['server'], $data['reactor'], $data['session'], $data['useragent']);
                if (! $result)
                    unset($this->client[$uid]);
                return json_encode($result);
            
            default:
                break;
        }
        
        return '';
    }

    protected function initCommandSocket($socket_file)
    {
        if (file_exists($socket_file)) {
            if (! unlink($socket_file)) {
                throw new sfException("unlink");
            }
        }
        
        $this->socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
        
        if (! socket_bind($this->socket, $socket_file)) {
            throw new sfException('socket_bind');
        }
        
        chmod($socket_file, 0777);
        
        if (! socket_listen($this->socket)) {
            throw new sfException('socket_listen');
        }
    }
}
