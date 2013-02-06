<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 5:45
	 */

	class galaxyGenerator extends DB {

		public function generateGalaxy( $galaxyID, $idGalaxyTemplate ) {
			$galaxy = new galaxy( game::DB() );
			$galaxyTemplate = ( new galaxyTemplate( game::DB() ) )->load( $idGalaxyTemplate );
			$this->doGenerateGalaxy( $galaxy, $galaxyTemplate );
		}

		/**
		 * @param galaxy $galaxy
		 * @param galaxyTemplate $galaxyTemplate
		 */
		private function doGenerateGalaxy( $galaxy, $galaxyTemplate ) {
			$galaxy->centerX( $galaxyTemplate->centerX() );
			$galaxy->centerY( $galaxyTemplate->centerY() );
			$galaxy->radius( $galaxyTemplate->radius() );
			$r = $galaxyTemplate->galaxyMinR() + rand( 0, 5 );
			$galaxyDensity = $galaxyTemplate->galaxyDensity();
			$prevR = 50;
			$density = 3;
			while ( $r <= $galaxyTemplate->radius() ) {
				foreach ( $galaxyDensity as $radius => $newDensity ) {
					if ( $radius <= $r ) {
						$density = $newDensity;
					} else {
						break;
					}
				}
				$d = 2 * pi() * $r;
				$aoff = utils::randomFloat( 0, pi() * 2 );
				$dangle = $density / $d * pi() * 0.9;
				for ( $i = 0; $i <= round( $d / $density ); $i++ ) {
					$angle = $aoff + $i * $density / $d * pi() * 2;
					$angle += utils::randomFloat( -$dangle, $dangle );
					$tr = rand( $prevR + 1, $r );
					while ( true ) {
						$acceptable = false;
						$system = new system( game::DB() );
						$this->generateSystem( $system );
						# check requirements
						foreach ( $system->planets() as $planet ) {
							if ( in_array( $planet->type()->idPlanetType() , array( 'D', 'R', 'C', 'H', 'M', 'E' ) ) and $planet->slots() > 0 ) {
								$acceptable = true;
								break;
							}
						}
						if ( $acceptable ) {
							break;
						}
					}
				}
				$prevR = $r;
				$r += rand( 20, 40 );
			}
		}

		/**
		 * @param system $system
		 */
		private function generateSystem( $system ) {

			$system->idStarClass( game::starClasses()->getRandom()->idStarClass() );
		}
	}