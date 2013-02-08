<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 3:16
	 */

	class universe extends DB {
		const UNIVERSES_TABLE_NAME = 'universes';

		/**
		 * @param int $idUniverse
		 * @return universe
		 * @throws Exception
		 */
		public function load( $idUniverse ) {
			$data = $this->DB()->selectRow( 'SELECT * FROM ' . self::UNIVERSES_TABLE_NAME . ' WHERE idUniverse = ?', $idUniverse );
			if ( empty( $data ) ) {
				throw new Exception( sprintf( fConst::E_NOT_FOUND, __CLASS__, $idUniverse ) );
			}
			return $this->assignArray( $data )->set( 'idUniverse', $data['idUniverse'] );
		}

		/**
		 * @throws Exception
		 * @return universe
		 */
		public function save() {
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}

		/**
		 * @return int
		 */
		public function idUniverse( ) {
			return $this->get( __METHOD__ );
		}

		/**
		 * @param galaxyTemplate $galaxyTemplate
		 * @param string $name
		 * @param int $x
		 * @param int $y
		 * @param int $radius
		 * @throws Exception
		 */
		public function createNewGalaxy( $galaxyTemplate, $name, $x = null, $y = null, $radius = null ) {
			if ( game::auth()->currentUser()->galaxyCreateLimit() > 0 ) {
				$this->DB()->beginTransaction();
				try {
					$x = isset($x) ? $x : $galaxyTemplate->centerX();
					$y = isset($y) ? $y : $galaxyTemplate->centerY();
					$radius = isset($radius) ? $radius : $galaxyTemplate->radius();

					log::message( sprintf( "Adding new galaxy '%s' to (%d, %d) radius %d", $name, $x, $y, $radius ) );

					$galaxy = new galaxy( $this->DB() );
					$galaxy->idUniverse( $this->idUniverse() );
					$galaxy->centerX( $x );
					$galaxy->centerY( $y );
					$galaxy->radius( $radius );
					$galaxy->name( $name );
					$galaxy->description( 'New galaxy "' . $name . '"' );
					$galaxy->save();

					$r = $galaxyTemplate->galaxyMinR() + rand( 0, 5 );
					$galaxyDensity = $galaxyTemplate->galaxyDensity();
					$prevR = 50;
					$density = 3;
					/*while ( $r <= $galaxyTemplate->radius() ) {
						foreach ( $galaxyDensity as $radius => $newDensity ) {
							if ( $newDensity['radius'] <= $r ) {
								$density = $newDensity['density'];
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
								$system = new system( $this->DB() );
								$system->idGalaxy = $galaxy->idGalaxy();
								$this->generateSystem( $system );
								# check requirements
								foreach ( $system->planets() as $planet ) {
									if ( in_array( $planet->type()->idPlanetType(), array( 'D', 'R', 'C', 'H', 'M', 'E' ) ) and $planet->slots() > 0 ) {
										$acceptable = true;
										break;
									}
								}
								if ( $acceptable ) {
									$system->save();
									break;
								}
							}
						}
						$prevR = $r;
						$r += rand( 20, 40 );
					}*/
					//ToDo - $this->DB()->commit();
				} catch ( Exception $e ) {
					$this->DB()->rollBack();
					throw $e;
				}
			} else {
				throw new Exception( 'You can\'t create more galaxies.' );
			}
		}


	}