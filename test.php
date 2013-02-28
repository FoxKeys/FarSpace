<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 11:32
	 */
	error_reporting( E_ALL );
	ini_set( "display_errors", 1 );


	require_once( 'config.php' );
	require_once( 'autoloader.php' );
	/*
		$system = new system( game::DB() );
		for ( $i = 0; $i < 1000; $i++ ) {
			echo $system->idStarClass( game::starClasses()->getRandom()->idStarClass() ) . PHP_EOL;
		}
	*/
	//game::galaxyGenerator()->generateGalaxy( 'Test', 1 );
/*
	$universe = universe::createFromDB( 1, game::DB() );
	$galaxyTemplate = galaxyTemplate::createFromDB( 1, game::DB() );
	$currentUser = game::auth()->currentUser();
	if ( $currentUser->galaxyCreateLimit() > 0 ) {
		$universe->createNewGalaxy( $galaxyTemplate, 'New test galaxy', 0, 0, 100 );
		$currentUser->galaxyCreateLimit( $currentUser->galaxyCreateLimit() - 1 );
		$currentUser->save();
	} else {
		throw new Exception( fConst::E_CANT_CREATE_MORE_GALAXIES );
	}
*/

	//game::scanner()->processScanPhase( galaxy::createFromDB( 51 ) );
	print_r( game::scanner()->getStaticMap( 42 ) );
	//player::createNewPlayer( 1, 51 );

	//var_dump( $universe );
	//var_dump( $galaxyTemplate );

//	$planets = planet::selectByIdSystem( 1, game::DB() );
