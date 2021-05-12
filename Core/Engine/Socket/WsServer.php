<?php

namespace App\Core\Factory\Socket;

use App\Core\Factory\Socket\Interfaces\WebSocketEventsInterface;
use Exception;

class WsServer implements WebSocketEventsInterface {


    public $host = "localhost";


    public $port = 3000;


    public $_connections = [];


    public $on = true;


    public $bufferSize = 1024;


    public $headers = [];


    public $gets = [];

    


    public function run() {

        if($this->createConnection())
        {
            while($this->on) 
            {
                $this->client = $this->acceptConnections();
                foreach($this->_connections as $client) {
                    $this->listening($client);
                }
            }
        }

    }

    public function listening($connection) {

        while(socket_recv($connection, $socketData, 1024, 0) >= 1)
        {
			$message = $this->unseal($socketData);
			$this->readClientMessage($connection, $message);
			break;
		}
		
		$socketData = @socket_read($connection, 1024, PHP_NORMAL_READ);
		if ($socketData === false) 
        { 
			$message = $this->connectionDisconnect($connection);
			$connectionIndex = array_search($connection, $this->_connections);
			unset($this->_connection[$connectionIndex]);		
		}

    }

    public function send($connection, $message) {

        $message = $this->seal($message);
        $messageLength = strlen($message);
        @socket_write($connection, $message, $messageLength);
        // $this->onSend($connection, $message);
    }

    public function createConnection() {

        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        
        try {

            //code...
            if($this->socket) {

                socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);
                socket_bind($this->socket, $this->host, $this->port);
                socket_listen($this->socket);
    
                return true;
            }

        } catch (\Exception $ex) {

            //throw $th;
            $this->onError($this->socket, $ex);
            return false;

        }
    }


    public function doHandShake($header, $connection) {
        
        $lines = preg_split("/\r\n/", $header);
        foreach($lines as $line)
        {
            $line = chop($line);
            if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
            {
                $this->setHeader($matches[1], $matches[2]);
            }
            elseif(preg_match('/GET/', $line, $matches))
            {
                $line = str_replace("/?", "", $line);
                $splits = explode(" ", $line);
                $params = $splits[1];
                if(strpos($params, "&")) {
                    $splits = explode("&", $params);
                    foreach($splits as $params) {
                        $params = explode("=", $params);
                        $this->setGetData($params[0], $params[1]);
                    }
                }
                else {
                    $params = explode("=", $params);
                    $this->setGetData($params[0], $params[1]);
                }
            }
        }

        $secKey = $this->getHeader('Sec-WebSocket-Key');

        $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
        $buffer  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
        "Upgrade: websocket\r\n" .
        "Connection: Upgrade\r\n" .
        "WebSocket-Origin: $this->host\r\n" .
        "WebSocket-Location: ws://$this->host:$this->port/demo/shout.php\r\n".
        "Sec-WebSocket-Accept:$secAccept\r\n\r\n";
        
        socket_write($connection, $buffer, strlen($buffer));
        
    }

    public function newConnection($connection) {
		$this->onOpen($connection);
	}

    function connectionDisconnect($connection) {
        $this->onClose($connection);
	}
	
	function readClientMessage($connection,$message) {
		$this->onMessage($connection, $message);
	}

    public function seal($socketData) {
		$b1 = 0x80 | (0x1 & 0x0f);
		$length = strlen($socketData);
		
		if($length <= 125)
			$header = pack('CC', $b1, $length);
		elseif($length > 125 && $length < 65536)
			$header = pack('CCn', $b1, 126, $length);
		elseif($length >= 65536)
			$header = pack('CCNN', $b1, 127, $length);
		return $header.$socketData;
	}

    public function unseal($socketData) {

		$length = ord($socketData[1]) & 127;
		if($length == 126) {
			$masks = substr($socketData, 4, 4);
			$data = substr($socketData, 8);
		}
		elseif($length == 127) {
			$masks = substr($socketData, 10, 4);
			$data = substr($socketData, 14);
		}
		else {
			$masks = substr($socketData, 2, 4);
			$data = substr($socketData, 6);
		}
		$socketData = "";
		for ($i = 0; $i < strlen($data); ++$i) {
			$socketData .= $data[$i] ^ $masks[$i%4];
		}
		return $socketData;

	}

    public function getClientIpAddress($connection) {
        socket_getpeername($connection, $ip_address);
        return $ip_address;
    }

    public function closeConnection($connection) {
        socket_close($connection);
        $this->onClose($connection);
    }

    public function acceptConnections() {

        $connection = socket_accept($this->socket);

        if(!in_array($connection, $this->_connections)) 
        {
            array_push($this->_connections, $connection);
            $this->readHeader($connection);
        }

        return $connection;
    }

    public function readHeader($connection) {

        $header = socket_read($connection, $this->bufferSize);
        if($header) 
        {
            $this->doHandShake($header, $connection);
            
            $this->newConnection($connection);
        }
        else {
            $ex = new Exception("Unable to read message from client");
            $this->onError($connection, $ex);
        }

    }

    public function setHost($host) {
        $this->host = $host;
    }

    public function setPort($port) {
        $this->port = $port;
    }

    public function setBufferSize($bufferSize) {
        $this->bufferSize = $bufferSize;
    }

    public function setHeader($name, $value) {
        $this->headers[$name] = $value;
    }

    public function getHeader($name) {
        if(array_key_exists($name, $this->headers)) {
            return $this->headers[$name];
        }
        return null;
    } 

    public function inHeader($name) {
        if(array_key_exists($name, $this->headers)) {
            return true;
        }
        return false;
    }

    public function setGetData($name, $value) {
        $this->gets[$name] = $value;
    }

    public function get($name = "") {
        
        if(!empty($name)) {
            if(isset($this->gets[$name])) {
                return $this->gets[$name];
            }
            else 
            {
                return null;
            }
        }

        return $this->gets;
    }

    public function onOpen($connection) { /**code here.. */ }
    public function onSend($connection, $message) { /**code here.. */ }
    public function onMessage($connection, $message) { /**code here.. */ }
    public function onError($connection, Exception $ex) { /**code here.. */ }
    public function onClose($connection) { /**code here.. */ }

}