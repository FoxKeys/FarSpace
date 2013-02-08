<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 04.02.2013 15:20
	 */
	class log {
		public static function debug() {
			throw new Exception( sprintf( fConst::E_NOT_TRANSLATED, __METHOD__ ) );
		}

		public static function warning() {
			throw new Exception( sprintf( fConst::E_NOT_TRANSLATED, __METHOD__ ) );
		}

		/**
		 * @param string $message
		 */
		public static function message( $message ) {
			echo $message . '<br/>' . PHP_EOL;
		}
	}