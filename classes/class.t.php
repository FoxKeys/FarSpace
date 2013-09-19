<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 30.05.2012 13:36
	 */
	class t {
		const ERROR_DOMAIN_NOT_LOADED = 'Translate domain "%s" not loaded.';
		private static $translations = array();

		/**
		 * @param string $domain
		 * @return MO
		 * @throws Exception
		 */
		private static function getDomainMO( $domain ) {
			return isset( self::$translations[$domain] ) ? self::$translations[$domain] : null;
		}

		/**
		 * @param string $fileName
		 * @param string $domain
		 */
		public static function loadMO( $fileName, $domain ) {
			self::$translations[$domain] = new MO( $fileName );
		}

		public static function __( $text ) {
			$themeMO = self::getDomainMO( 'theme' );
			$result = null;
			if ( !empty( $themeMO ) ) {
				$result = $themeMO->translateEx( $text, null );
			}
			if ( $result === null ) {
				$coreMO = self::getDomainMO( 'core' );
				$result = !empty( $coreMO ) ? $coreMO->translate( $text, null ) : $text;
			}
			return $result;
		}

		public static function _x( $text, $context ) {
			$themeMO = self::getDomainMO( 'theme' );
			$result = null;
			if ( !empty( $themeMO ) ) {
				$result = $themeMO->translateEx( $text, $context );
			}
			if ( $result === null ) {
				$coreMO = self::getDomainMO( 'core' );
				$result = !empty( $coreMO ) ? $coreMO->translate( $text, $context ) : $text;
			}
			return $result;
		}

		public static function _n( $text, $plural, $number, $context = null ) {
			$themeMO = self::getDomainMO( 'theme' );
			$result = null;
			if ( !empty( $themeMO ) ) {
				$result = $themeMO->translatePlural( $text, $plural, $number, $context );
			}
			if ( $result === null ) {
				$coreMO = self::getDomainMO( 'core' );
				$result = !empty( $coreMO ) ? $coreMO->translatePlural( $text, $plural, $number, $context ) : $text;
			}
			return $result;
		}

		public static function c_( $text ){
			$coreMO = self::getDomainMO( 'core' );
			return !empty( $coreMO ) ? $coreMO->translate( $text, null ) : $text;
		}

		public static function t_( $text ){
			$themeMO = self::getDomainMO( 'theme' );
			return !empty( $themeMO ) ? $themeMO->translate( $text, null ) : $text;
		}

		public static function tx( $text, $context ){
			$themeMO = self::getDomainMO( 'theme' );
			return !empty( $themeMO ) ? $themeMO->translate( $text, $context ) : $text;
		}

		public static function cx( $text, $context ){
			$coreMO = self::getDomainMO( 'core' );
			return !empty( $coreMO ) ? $coreMO->translate( $text, $context ) : $text;
		}

	}