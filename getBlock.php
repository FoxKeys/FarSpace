<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 29.05.2013 15:45
	 */
	require_once( 'commonInit.php' );//ToDo! session_start() sends "no-store, no-cache, ..." headers!

	try {
		if ( !empty( $_GET['blockName'] ) ){

			$blockName = preg_replace( config::FILE_NAME_REGEX, '', $_GET['blockName'] );

			if ( !method_exists( game::ajaxBlocks(), $blockName ) ){
				throw new Exception( sprintf( t::__( 'Ajax block "%s" not found in game::ajaxBlocks()' ), $blockName ) );
			}

			ob_start();
			game::ajaxBlocks()->$blockName();
			$content = ob_get_contents();
			ob_clean();

			$ETag = md5( $content );
			header( 'ETag: ' . $ETag );
			$seTag = isset( $_SERVER['HTTP_IF_NONE_MATCH'] ) ? isset( $_SERVER['HTTP_IF_NONE_MATCH'] ) : '';
			if ( $seTag == $ETag ) {
				header( 'HTTP/1.1 304 Not Modified' );
			} else {
				header( 'Content-Type: text/html' );
				echo $content;
			}
		} else {
			header( 'HTTP/1.0 404 Not Found' );
		}
	} catch ( Exception $e ) {
		if ( config::DEBUG_MODE ) {
			echo e::html( $e->getMessage() );
		}
	}
