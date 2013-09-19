<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 09.04.2013 9:02
	 */
	session_start();

	require_once( 'config.php' );

	if ( config::DEBUG_MODE ) {
		error_reporting( E_ALL );
		ini_set( 'display_errors', 1 );
	} else {
		ini_set( 'display_errors', 0 );
	}

	if ( config::DEBUG_LOG ) {
		ini_set( 'log_errors', 1 );
		ini_set( 'error_log', __DIR__ . '/debug.log' );
	}

	//Setup autoloader
	require_once( 'autoloader.php' );

	//Setup exception handler
	//set_exception_handler( array( 'core', 'exceptionHandler' ) );	//ToDo
