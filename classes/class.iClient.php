<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 04.02.2013 15:03
	 */

	class clientException extends Exception {

	};

	class iClient {
		private $connected = false;
		private $server = '';
		private $clientIdent = '';
		private $sid = null;
		private $challenge = null;

		public function __construct( $server, $proxy, $msgHandler, $idleHandler, $clientIdent ) {
			$this->clientIdent = $clientIdent;
			//$this->gameID = None
			$this->server = $server;
			//$this->logged = 0
			//$this->httpConn = None
			//$this->keepAliveTime = 180
			//$this->proxy = proxy
			//$this->msgHandler = msgHandler
			//$this->idleHandler = idleHandler
			//$this->lastCommand = time . time()
			//$this->hostID = 'FILLMEWITHREALVALUE'
			//$this->statsBytesIn = 0
			//$this->statsBytesOut = 0
			//$this->lastClientVersion = None
			throw new Exception( sprintf( fConst::E_NOT_TRANSLATED, __METHOD__ ) );
		}

		public function connect( $login ) {
			# to enable sending commands
			$this->connected = true;
			# create connection
			log::debug( 'Connecting to the server', $this->server );
			# send hello message
			log::debug( 'Sending hello' );
			try {
				$this->hello( $login );
			} catch ( Exception $e ) {
				log::warning( 'Cannot connect to the server.' );
				$this->connected = false;
				throw new clientException( 'Cannot connect to the server.' );
			}
			log::debug( $this->sid, $this->challenge );
		}

		private function hello( $login ) {
			throw new Exception( sprintf( fConst::E_NOT_TRANSLATED, __METHOD__ ) );
			$this->sid = '';	//ToDo
			$this->challenge = '';	//ToDo
		}
	}