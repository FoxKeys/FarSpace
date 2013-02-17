<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Fox foxkeys@gmail.com
 * Date Time: 06.02.2013 3:34
 */

	class game {
/*
		public static function init(){
			self::auth()->login();
		}*/

		/**
		 * @return FoxDB
		 */
		public static function DB(){
			static $DB;
			if ( empty( $DB ) ){
				$DB = new FoxDB( config::$DB['DSN'], config::$DB['username'], config::$DB['password'] );
			}
			return $DB;
		}

		/**
		 * @return auth
		 */
		public static function auth(){
			static $auth;
			if ( empty( $auth ) ){
				$auth = new auth( self::DB() );
			}
			return $auth;
		}

		/**
		 * @return starClass[]
		 */
		public static function starClasses(){
			static $starClasses;
			if ( empty( $starClasses ) ){
				$starClasses = starClass::selectAll( self::DB() );
			}
			return $starClasses;
		}

		/**
		 * @return starClass
		 */
		/*
		public function getRandom() {
			$starClass = new starClass( game::DB() );
			$data = $this->data();
			return $starClass->assignArray( $data[utils::getRandomWeightedElement( $data, 'chance' )] );
		}
		*/
		/**
		 * @return universe
		 */
		public static function universe(){
			static $idUniverse;
			if ( empty( $idUniverse ) ){
				$idUniverse = 0;
			}
			return $idUniverse;
		}

	}