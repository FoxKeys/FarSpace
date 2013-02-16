<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 10.02.2013 7:19
	 */
	class RPC {
		public function ajaxLogin( ) {
			$login = preg_replace( auth::LOGIN_REGEX, '', $_POST['login'] );
			$password = $_POST['password'];
			return game::auth()->login( $login, $password );
		}
	}