<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 3:26
	 */
	class auth extends activeRecord {
		const LOGIN_REGEX = '/[^a-zA-Z0-9_]/i';
		const TABLE_USERS = 'users';
		const TABLE_SESSIONS = 'users_sessions';
		const SESSION_TIMEOUT = 3600;
		const COOKIE_NAME = 'ssid';
		const LOGIN_ERROR = 'Login error. Bad password or user name.';

		public function load( $idObject ) {
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}

		public function save() {
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}

		/**
		 * @var int
		 */
		private $idUser = null;

		/**
		 * @var string
		 */
		private $ssid = null;

		/**
		 * @param string $login
		 * @param string $password
		 * @throws Exception
		 * @return string|null
		 */
		public function login( $login, $password ) {
			//Try to login
			$idUser = game::DB()->selectValue(
				'SELECT idUser FROM ' . self::TABLE_USERS . ' WHERE login = ? and hash = md5( concat( salt, ?) )', $login, $password
			);
			if ( !empty( $idUser ) ) {
				//Create and store new session on success
				$rand = openssl_random_pseudo_bytes( 128 );
				if ( $rand === false ) {
					throw new Exception( 'openssl_random_pseudo_bytes error' );
				}
				$ssid = md5( $rand );
				game::DB()->exec(
					'INSERT INTO ' . self::TABLE_SESSIONS . ' (idUser, ssid, endTime) VALUES (?, ?, DATE_ADD(now(), INTERVAL ? SECOND))',
					$idUser, $ssid, self::SESSION_TIMEOUT
				);
				//remember current user and session id
				$this->setSSID( $ssid );
				$this->idUser = $idUser;
				//Remove obsolete sessions from DB
				game::DB()->exec(
					'DELETE FROM ' . self::TABLE_SESSIONS . ' WHERE idUser = ? and endTime < now()',
					$idUser
				);
			} else {
				throw new Exception( self::LOGIN_ERROR );
			}
			return $idUser;
		}

		private function setSSID( $ssid ) {
			if( setcookie( self::COOKIE_NAME, $ssid, time() + self::SESSION_TIMEOUT, '/', '.' . $_SERVER["HTTP_HOST"], false, true ) ){
				$this->ssid = $ssid;
			} else {
				throw new Exception( 'errorSettingCookie' );
			};
		}

		private function getSSID(){
			//
		}

		public function currentUserId(){
			//
		}

		/**
		 * @throws Exception
		 * @return user|null
		 */
		public function currentUser() {
			$user = new user( );
			return $user->load( $this->idUser );
			//ToDo
			/*if ( empty( self::$currentUser ) ) {
				throw new Exception( 'Current player is not defined. Probable you are not logged in.' );
			}
			return self::$currentUser;
			*/
		}
	}