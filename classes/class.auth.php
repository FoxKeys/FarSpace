<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Fox foxkeys@gmail.com
 * Date Time: 06.02.2013 3:26
 */
	class auth extends DB {
		/**
		 * @var player null
		 */
		private static $currentPlayer = null;

		/**
		 * @throws Exception
		 * @return player
		 */
		public function currentPlayer(){
			if ( empty( self::$currentPlayer ) ){
				throw new Exception( 'Current player is not defined. Probable you are not logged in.' );
			}
			return self::$currentPlayer;
		}
	}