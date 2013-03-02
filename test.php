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
	//game::auth()->login( 'root', 'cydvb' );
	//$currentUser = game::auth()->currentUser();
	$currentUser = user::createFromDB( 1 );
	$galaxy = $universe->createNewGalaxy( $currentUser, $galaxyTemplate, 'New test galaxy', 0, 0, 5 );
*/

	$galaxy = galaxy::createFromDB( 67 );

	$player = player::createNewPlayer( 1, $galaxy );

	print_r( game::scanner()->getStaticMap( $player->idPlayer() ) );

	//var_dump( $universe );
	//var_dump( $galaxyTemplate );

//	$planets = planet::selectByIdSystem( 1, game::DB() );
