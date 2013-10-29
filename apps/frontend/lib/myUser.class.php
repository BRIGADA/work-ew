<?php

class myUser extends sfBasicSecurityUser {

    /**
     * Отправка на игровой сервер GET-запроса
     * 
     * @param string $path путь
     * @param unknown $query массив параметров запроса (служебные поля вставляются автоматически)
     * 
     * @return Ambigous <NULL, mixed>
     */
    public function RGET($path, $query = array(), $proxy = false) {
        return $proxy ? $this->PGET($path, $query) : $this->LGET($path, $query);
    }

    /**
     * Отправка на игровой сервер POST-зароса
     * 
     * @param string $path
     * @param array $query
     * 
     * @return string|null
     */
    public function RPOST($path, $query = array(), $proxy = false) {
        return $proxy ? $this->PPOST($path, $query) : $this->LPOST($path, $query);
    }

    public function LGET($path, $query = array()) {
        $query['meltdown'] = MeltdownTable::getCurrent();
        $query['reactor'] = $this->getAttribute('reactor', null, 'player/data');
        $query['user_id'] = $this->getAttribute('user_id', null, 'player/data');
        $query['_session_id'] = $this->getAttribute('_session_id', null, 'player/data');

        $query_str = http_build_query($query);

        $url = sprintf('http://%s%s?%s', $this->getAttribute('host', null, 'player/data'), $path, $query_str);

        sfContext::getInstance()->getLogger()->debug('LGET-url: ' . $url);
        sfContext::getInstance()->getLogger()->debug('LGET-query: ' . var_export($query, true));

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        $cookie_file = sfConfig::get('sf_upload_dir') . '/cookie/' . $this->getAttribute('user_id', 'unknown', 'player/data') . '.dat';
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);

        $result = curl_exec($ch);

        sfContext::getInstance()->getLogger()->debug('LGET-info: ' . var_export(curl_getinfo($ch), true));

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) {
            file_put_contents(sfConfig::get('sf_upload_dir') . '/LGET.dat', $result);
            $result = NULL;
        }

        curl_close($ch);

        return $result;
    }

    public function LPOST($path, $query = array()) {
        $query['meltdown'] = MeltdownTable::getCurrent();
        $query['reactor'] = $this->getAttribute('reactor', null, 'player/data');
        $query['user_id'] = $this->getAttribute('user_id', null, 'player/data');
        $query['_session_id'] = $this->getAttribute('_session_id', null, 'player/data');
        $query['testCount'] = $this->getAttribute('testCount', 1, 'player/data');


        $postdata = str_replace('_', '%5F', http_build_query($query));

        $url = sprintf('http://%s%s', $this->getAttribute('host', null, 'player/data'), $path);

        sfContext::getInstance()->getLogger()->debug('LPOST-url: ' . $url);
        sfContext::getInstance()->getLogger()->debug('LPOST-query: ' . var_export($query, true));

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

        $cookie_file = sfConfig::get('sf_upload_dir') . '/cookie/' . $this->getAttribute('user_id', 'unknown', 'player/data') . '.dat';
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);

        $hashsting = $url . $postdata . 'SF' . 'f0uR' . 'l1f3';

        $x_s3_cachebreak = sha1($hashsting);

        // sfContext::getInstance()->getLogger()->debug(sprintf('hash for: \'%s\' = %s', $hashsting, $x_s3_cachebreak));

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'x-s3-cachebreak: ' . $x_s3_cachebreak
        ));

        $result = curl_exec($ch);

        sfContext::getInstance()->getLogger()->debug('LPOST-info: ' . var_export(curl_getinfo($ch), true));

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) {
            file_put_contents(sfConfig::get('sf_upload_dir') . '/LPOST.dat', $result);
            $result = NULL;
        } else {
            $this->setAttribute('testCount', $this->getAttribute('testCount', 1, 'player/data') + 1, 'player/data');
        }

        curl_close($ch);
        return $result;
    }

    public function PGET($path, $query = array()) {
        $request = array();
        $request['user_id'] = $this->getAttribute('user_id', null, 'player/data');
        $request['cmd'] = 'get';
        $request['path'] = $path;
        $request['query'] = $query;

        return $this->proxy($request, 10);
    }

    public function PPOST($path, $query = array()) {
        $request = array();
        $request['user_id'] = $this->getAttribute('user_id', null, 'player/data');
        $request['cmd'] = 'post';
        $request['path'] = $path;
        $request['query'] = $query;

        return $this->proxy($request, 10);
    }

    public function proxy($request, $timeout = NULL) {
        $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
        if (!@socket_connect($socket, '/tmp/edgeworld-proxy.sock')) {
            sfContext::getInstance()->getLogger()->err('PROXY: unable connect');
            return NULL;
        }

        $buf_out = json_encode($request) . "\n";

        $buf_in = '';
        $result = NULL;

        while (true) {
            sfContext::getInstance()->getLogger()->debug("PROXY: begin");

            $r = array(
                $socket
            );
            $w = strlen($buf_out) ? array(
                $socket
                    ) : array();
            $e = NULL;

            $s = socket_select($r, $w, $e, null);

            if ($s === false) {
                sfContext::getInstance()->getLogger()->err('PROXY: select');
                break;
            }

            if ($s == 0) {
                sfContext::getInstance()->getLogger()->debug('PROXY: timeout');
                break;
            }

            // WRITE
            if (in_array($socket, $w)) {
                $l = socket_write($socket, $buf_out);
                $buf_out = substr($buf_out, $l);
            }

            // READ
            if (in_array($socket, $r)) {
                $s = socket_read($socket, 65536);
                if ($s === false) {
                    sfContext::getInstance()->getLogger()->err(sprintf("PROXY: read - %s (%u)", socket_strerror(socket_last_error($socket)), socket_last_error($socket)));
                    break;
                }
                if ($s == '') {
                    $result = $buf_in;
                    break;
                }
                $buf_in .= $s;
            }
            continue;
        }

        socket_shutdown($socket);
        socket_close($socket);
        return $result;
    }

    public function initPlayer($host, $reactor, $session_id, $user_id) {
        $this->setAttribute('host', $host, 'player/data');
        $this->setAttribute('reactor', $reactor, 'player/data');
        $this->setAttribute('_session_id', $session_id, 'player/data');
        $this->setAttribute('user_id', $user_id, 'player/data');

        $r = $this->RGET('/api/player');

        if (!$r)
            return false;
        $data = json_decode($r, true);

        $bases = array();
        $bases['main'] = $data['response']['base']['id'];
        foreach ($data['response']['colonies'] as $colony) {
            $bases[$colony['name']] = $colony['id'];
        }
        $this->setAttribute('bases', $bases, 'player');
        return true;
    }

    public function getBaseID($name = 'main') {
        $bases = $this->getAttribute('bases', array(), 'player');
        return isset($bases[$name]) ? $bases[$name] : NULL;
    }

}
