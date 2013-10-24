<?php

class ClientData
{

    public $player_id;

    public $player_name;

    public $user_id;

    public $alliance_id;

    public $session_id;

    public $reactor;

    public $sector;

    public $server;

    public $useragent;

    public $socket;

    public $obuf = array();

    public $ibuf = '';

    protected $curl;

    public function __construct()
    {
        $this->curl = curl_init();
        $this->socket = null;
    }

    public function needWrite()
    {
        return count($this->obuf);
    }

    public function connect()
    {
        if ($this->socket != NULL) {
            disconnect();
        }
        
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        
        if (! socket_connect($this->sock, $this->remote_addr, $this->remote_port)) {
            return false;
        }
        
        $this->obuf = array();
        $this->obuf = array(
            'type' => 'subscribe',
            'data' => array(
                'player_id' => strval($this->player_id)
            )
        );
        return true;
    }

    public function disconnect()
    {
        if ($this->socket == NULL)
            return;
        socket_shutdown($this->socket);
        socket_close($this->socket);
        $this->obuf = array();
        $this->ibuf = '';
        $this->socket = NULL;
    }

    public function processread()
    {}

    public function processwrite()
    {}

    public function __destruct()
    {
        curl_close($this->curl);
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
        
        $socket = initCommandSocket('/tmp/edgeworld-proxy.sock');
        
        $timeout = null;
        
        while (true) {
            $r_sock = array();
            $w_sock = array();
            $e_sock = NULL;
            
            $r_sock[] = $socket;
            
            foreach ($this->peer as $peer) {
                $r_sock[] = $pear->fd;
                if (strlen($peer->obuf))
                    $w_sock[] = $peer->fd;
            }
            
            if (socket_select($r_sock, $w_sock, $e_sock, $timeout) != 0) {
                if (in_array($socket, $r_sock)) {
                    // new peer connection;
                    $peer_socket = socket_accept($socket);
                    $this->peer[] = new PeerSocket($peer_socket);
                }
                
                foreach ($this->peer as $i => $peer) {
                    if (in_array($peer->fd, $r_sock)) {
                        $d = socket_read($peer->fd, 65536);
                        if ($d === FALSE || $d == '') {
                            $peer->disconnect();
                            unset($this->peer[$i]);
                            continue;
                        }
                        
                        $peer->ibuf .= $d;
                        if (strpos($peer->ibuf, "\n")) {
                            $peer->obuf = $this->processPeerMessage(json_decode($peer->ibuf, true));
                            if (strlen($pear->obuf) == 0) {
                                $peer->disconnect();
                                unset($this->peer[$i]);
                                continue;
                            }
                        }
                    }
                    
                    if (in_array($peer->fd, $w_sock)) {
                        $l = socket_write($peer->fd, $peer->obuf);
                        if ($l === FALSE) {
                            $peer->disconnect();
                            unset($this->peer[$i]);
                            continue;
                        }
                        
                        $peer->obuf = substr($peer->obuf, $l);
                        
                        if (strlen($peer->obuf) == 0) {
                            $peer->disconnect();
                            unset($this->peer[$i]);
                            continue;
                        }
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
                if(!isset($this->client[$uid])) {
                    $this->client[$uid] = new ClientSocket();
                }
                
                $this->client[$uid]->init($data['server'], $uid, $data['reactor'], $data['session']);
                
                
				if (! isset($this->client[$uid])) {
                    $this->client[$uid] = new ClientData();
                }
                
                $uid = $data['player_id'];
                
                $this->client[$uid]->server = $data['server'];
                $this->client[$uid]->sector = $data['sector'];
                $this->client[$uid]->player_id = $data['player_id'];
                $this->client[$uid]->player_name = $data['player_name'];
                $this->client[$uid]->user_id = $data['user_id'];
                $this->client[$uid]->session = $data['session'];
                $this->client[$uid]->reactor = $data['reactor'];
                $this->client[$uid]->alliance_id = $data['alliance_id'];
                $this->client[$uid]->useragent = $data['useragent'];
                
                $this->client[$uid]->connect();
                
                return json_encode(true);
            
            default:
                break;
        }
        return 'NULL';
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
