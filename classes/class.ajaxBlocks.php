<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Fox foxkeys@gmail.com
 * Date Time: 29.05.2013 15:02
 */

class ajaxBlocks {
	public function blockSystemDialog(){
		$block = new blockSystemDialog();
		$system = new system();
		$player = new player();
		$player->load( 54 );
		$system->load( 54, filter_input( INPUT_GET, 'idSystem', FILTER_VALIDATE_INT ) );//ToDo - replace hardcoded test to actual data
		$block->render( $system, $player, filter_input( INPUT_GET, 'idPlanet', FILTER_VALIDATE_INT ) );
	}

	public static function JS(){
		if ( empty( $_GET['module'] ) ) {
			throw new Exception( t::__( 'Required parameter "module" missing' ) );
		}
		//Register core scripts
		JS::registerJS( config::$coreJSArray );
		echo JS::renderScript( JS::getScript( $_GET['module'] ) );
	}
}