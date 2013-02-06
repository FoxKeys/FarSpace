<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Fox foxkeys@gmail.com
 * Date Time: 06.02.2013 3:34
 */

	class game {
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
		 * @return galaxyGenerator
		 */
		public static function galaxyGenerator(){
			static $galaxyGenerator;
			if ( empty( $galaxyGenerator ) ){
				$galaxyGenerator = new galaxyGenerator( self::DB() );
			}
			return $galaxyGenerator;
		}

		/**
		 * @return starClasses
		 */
		public static function starClasses(){
			static $galaxyGenerator;
			if ( empty( $galaxyGenerator ) ){
				$galaxyGenerator = new galaxyGenerator( self::DB() );
			}
			return $galaxyGenerator;
		}
	}