<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Fox foxkeys@gmail.com
 * Date Time: 06.02.2013 3:16
 */

	class universe extends DB {
		public function createNewGalaxy( $x, $y, $galaxyName ) {
			log::message( sprintf( "Adding new galaxy '%s' to (%d, %d)", $galaxyName, $x, $y ) );

			if ( game::auth()->currentPlayer()->galaxyCreateLimit() > 0 ) {
				game::galaxyGenerator()->generateGalaxy( 'strGalaxyID' );
			} else {
				throw new Exception( 'You can\'t create more galaxies.' );
			}
		}
	}