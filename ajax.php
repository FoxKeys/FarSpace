<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 20.08.2012 6:35
	 */
/*
	error_reporting( E_ALL );
	ini_set( 'display_errors', 'On' );
	ini_set( 'display_startup_errors', 1);
*/

	$response = (object)array( 'result' => false, 'data' => null, 'token' => null, 'errorCode' => null, 'errorMessage' => null  );

	try {
		try {
			//Access control (note! Real access control is using $client['origin'])
			header( 'Access-Control-Allow-Origin: *' );
			//Prevent caching
			// Date in the past
			header( "Expires: Mon, 26 Jul 1990 05:00:00 GMT" );
			// always modified
			header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
			// HTTP/1.1
			header( "Cache-Control: no-store, no-cache, must-revalidate" );
			header( "Cache-Control: post-check=0, pre-check=0", false );
			// HTTP/1.0
			header( "Pragma: no-cache" );
			header( 'Content-Type: application/json; charset=utf-8' );

			$action = !empty( $_POST['action'] ) ? preg_replace( '/[^a-zA-Z0-9_]/i', '', $_POST['action'] ) : '';
			if ( empty( $action ) ) {
				throw new Exception( 'Empty "action" parameter' );
			}

			/*if ( get_magic_quotes_gpc() ) {
				$request = stripslashes( $request );
			}*/

			require_once( 'config.php' );
			require_once( 'autoloader.php' );

			$RPC = new RPC();

			if ( !method_exists( $RPC, $action ) ) {
				throw new Exception( 'RPC method "' . $action . '" not found.' );
			}

			$response->data = $RPC->$action();

			//Get new token (if set)
			//ToDo: $response->token = core::DB()->selectValue( 'SELECT @token' );
			$response->result = true;
		} catch ( PDOException $e ) {
			if ( isset( $e->errorInfo ) && isset( $e->errorInfo[1] ) && isset( $e->errorInfo[2] ) ) {
				$response->errorCode = -1;
				$response->errorMessage = '[' . $e->errorInfo[1] . '] - ' . $e->errorInfo[2];
			} else {
				throw $e;
			}
		}
	} catch ( Exception $e ) {
		$response->errorCode = $e->getCode();
		$response->errorMessage = $e->getMessage();
	}
	echo json_encode( $response );