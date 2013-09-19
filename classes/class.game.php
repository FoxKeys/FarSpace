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
			static $singleton;
			return !empty( $singleton ) ? $singleton : $singleton = new auth();
		}

		/**
		 * @return starClass[]
		 */
		public static function starClasses(){
			static $singleton;
			return !empty( $singleton ) ? $singleton : $singleton = starClass::selectAll( self::DB() );
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
		
		/**
		 * @return scanner
		 */
		public static function scanner(){
			static $singleton;
			return !empty( $singleton ) ? $singleton : $singleton = new scanner();
		}
	
		/**
		 * @return buoy
		 */
		public static function buoy(){
			static $singleton;
			return !empty( $singleton ) ? $singleton : $singleton = new buoy();
		}

		/**
		 * @static
		 * @return ajaxBlocks
		 */
		public static function ajaxBlocks() {
			static $singleton;
			return !empty( $singleton ) ? $singleton : $singleton = new ajaxBlocks();
		}

		public static function baseURL(){
			return ( isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . '/';
		}

		public static function jsURL() {
			return game::baseURL() . config::JS_DIR;
		}

		/**
		 * @static
		 * @return planets
		 */
		public static function planets() {
			static $singleton;
			return !empty( $singleton ) ? $singleton : $singleton = new planets();
		}

		/**
		 * @static
		 * @return planetTypes
		 */
		public static function planetTypes() {
			static $singleton;
			return !empty( $singleton ) ? $singleton : $singleton = new planetTypes();
		}

		/**
		 * @static
		 * @return structures
		 */
		public static function structures() {
			static $singleton;
			return !empty( $singleton ) ? $singleton : $singleton = new structures();
		}

		/**
		 * @static
		 * @return playersTechs
		 */
		public static function playersTechs() {
			static $singleton;
			return !empty( $singleton ) ? $singleton : $singleton = new playersTechs();
		}

		/**
		 * @static
		 * @return buildTasks
		 */
		public static function buildTasks() {
			static $singleton;
			return !empty( $singleton ) ? $singleton : $singleton = new buildTasks();
		}

	}