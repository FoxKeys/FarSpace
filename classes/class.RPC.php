<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 10.02.2013 7:19
	 */
	class RPC {
		public function authLogin( ) {
			$login = preg_replace( auth::LOGIN_REGEX, '', $_POST['login'] );
			$password = $_POST['password'];
			return game::auth()->login( $login, $password );
		}

		public function authGetToken(){
			return 'ToDo - implement';
		}

		public function universeGetIntroInfo() {
			$idUniverse = (int)$_REQUEST['idUniverse'];
			return game::universe()->getIntroInfo( $idUniverse );
		}

		public function scannerGetMap(){
			//ToDo - session to idUser
			$idPlayer = 54;
			return game::scanner()->getMap( $idPlayer );
		}

	}