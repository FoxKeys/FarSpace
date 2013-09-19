<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 12:15
	 */

	spl_autoload_register( array( 'autoloader', 'setupAutoloader' ) );

	class autoloader {
		public static function setupAutoloader( $class_name ) {

			$class_name = preg_replace( '/[^a-zA-Z0-9_]/i', '', $class_name );
			$file = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'class.' . $class_name . '.php';

			if ( !file_exists( $file ) ) {
				$file = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . config::BLOCKS_DIR . 'class.' . $class_name . '.php';
			}
			if ( file_exists( $file ) ) {
				require_once $file;
			}
		}
	}