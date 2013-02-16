<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 3:16
	 */

	class universe extends DB {
		const TABLE_NAME = 'universes';

		/**
		 * Type Hint wrapper
		 * @param int $idUniverse
		 * @param FoxDB $DB
		 * @return universe
		 */
		public static function createFromDB( $idUniverse, $DB ) {
			return parent::createFromDB( $idUniverse, $DB );
		}

		/**
		 * @param int $idUniverse
		 * @return universe
		 * @throws Exception
		 */
		public function load( $idUniverse ) {
			$data = $this->DB()->selectRow( 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE idUniverse = ?', $idUniverse );
			if ( empty( $data ) ) {
				throw new Exception( sprintf( fConst::E_NOT_FOUND, __CLASS__, $idUniverse ) );
			}
			return $this->assignArray( $data );
		}

		/**
		 * @throws Exception
		 * @return universe
		 */
		public function save() {
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}

		/**
		 * Type Hint wrapper
		 * @param int $idUniverse
		 * @return int
		 */
		public function idUniverse( $idUniverse = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
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
					$galaxy->idUser( game::auth()->currentUser()->idUser() );
					$galaxy->centerX( $x );
					$galaxy->centerY( $y );
					$galaxy->radius( $radius );
					$galaxy->name( $name );
					$galaxy->description( 'New galaxy "' . $name . '"' );
					$galaxy->save();

					$r = $galaxyTemplate->galaxyMinR() + rand( 0, 5 );
					$galaxyDensityList = galaxyTemplateDensity::selectByGalaxyTemplate( $galaxyTemplate->idGalaxyTemplate(), $this->DB() );
					$prevR = 50;
					$density = 30;
					while ( $r <= $galaxyTemplate->radius() ) {
						foreach ( $galaxyDensityList as $galaxyTemplateDensity ) {
							if ( $galaxyTemplateDensity->radius() <= $r ) {
								$density = $galaxyTemplateDensity->density();
							} else {
								break;
							}
						}
						$d = 2 * pi() * $r;
						$aoff = utils::randomFloat( 0, pi() * 2 );
						$dangle = $density / $d * pi() * 0.9;
						for ( $i = 0; $i < (int)( $d / $density ); $i++ ) {	//for i in range(0, int(d / density)):
							$angle = $aoff + $i * $density / $d * pi() * 2;
							$angle += utils::randomFloat( -$dangle, $dangle );
							$tr = rand( $prevR + 1, $r );
							$acceptable = false;
							while ( !$acceptable ) {	//ToDo - extremely inefficient...
								$system = new system( $this->DB() );
								$planets = $this->generateSystem( $system );
								# check requirements
								foreach ( $planets as $planet ) {
									if ( in_array( $planet->idPlanetType(), array( 'D', 'R', 'C', 'H', 'M', 'E' ) ) and $planet->plSlots() > 0 ) {
										$acceptable = true;
										break;
									}
								}
								if ( $acceptable ) {
									$system->idGalaxy( $galaxy->idGalaxy() );
									$system->x( cos( $angle ) * $tr + $galaxy->centerX() );
									$system->y( sin( $angle ) * $tr + $galaxy->centerY() );
									$system->save();
									foreach ( $planets as $planet ) {
										$planet->idSystem( $system->idSystem() );
										$planet->save();
									}
								}
							}
						}
						$prevR = $r;
						$r += rand( 20, 40 );
					}
					# generate central black hole
					$system = new system( $this->DB() );
					$system->x( $galaxy->centerX() );
					$system->y( $galaxy->centerY() );
					$system->idStarClass( "b-" );
					$system->starSubclass( 7 );
					$system->idGalaxy( $galaxy->idGalaxy() );
					//system._moveable = 0

					# generate starting systems
					$galaxyPlayers = $galaxyTemplate->galaxyPlayers();
					$galaxyPlayerGroup = $galaxyTemplate->galaxyPlayerGroup();
					if ( !empty( $galaxyPlayers ) ) {
						$r = ( $galaxyTemplate->startRMin() + $galaxyTemplate->startRMax() ) / 2;
						$d = 2 * pi() * $r;
						log::message( sprintf( "Player distance: %.2f", $d / $galaxyPlayers ) );
						$gaoff = utils::randomFloat( 0, pi() * 2 );
						for ( $i = 0; $i < (int)( $galaxyPlayers / $galaxyPlayerGroup ); $i++ ) {	//for i in range(0, galaxyPlayers / galaxyPlayerGroup):
							log::message( sprintf( "Placing group: %d of %d", $i + 1, ceil( $galaxyPlayers / $galaxyPlayerGroup) ) );
							$angle = $gaoff + $i * pi() * 2 / ( $galaxyPlayers / $galaxyPlayerGroup );
							$tr = rand( $galaxyTemplate->startRMin(), $galaxyTemplate->startRMax() );
							$gx = cos( $angle ) * $tr + $galaxy->centerX();
							$gy = sin( $angle ) * $tr + $galaxy->centerY();
							$aoff = utils::randomFloat( 0, pi() * 2 );
							for ( $j = 0; $j < $galaxyPlayerGroup; $j++ ) {	//for j in range(0, galaxyPlayerGroup):
								$angle = $aoff + $j * pi() * 2 / $galaxyPlayerGroup;
								$system = new system( $this->DB() );
								$system->idGalaxy( $galaxy->idGalaxy() );
								$system->x( $angle * $galaxyTemplate->galaxyGroupDist() + $gx );
								$system->y( $angle * $galaxyTemplate->galaxyGroupDist() + $gy );
								while ( true ) {	//ToDo - extremely inefficient...
									$planets = $this->generateSystem( $system );
									# check system properties
									$e = 0;
									$h = 0;
									$d = 0;
									$ok = true;
									foreach ( $planets as $planet ) {
										$planet->plStarting( 0 );
										if ( $planet->idPlanetType() == 'E' ) {
											$e += 1;
											$planet->plStarting( 1 );
										} elseif ( in_array( $planet->idPlanetType(), array( 'D', 'R', 'C' ) ) ) {
											if ( $planet->plSlots() > 5 ) {
												$d += 1;
											} else {
												$ok = false;
												break;
											}
										} elseif ( $planet->idPlanetType() == 'H' ) {
											$h += 1;
										} elseif ( $planet->idPlanetType() == 'M' ) {
											$ok = false;
											break;
										}
									}
									# fast rule
									#if ok and e == 1:
									#	break
									# slow (better) rule
									if ( $ok and $e == 1 and $h == 1 and $d == 1 ) {
										$system->save();
										foreach ( $planets as $planet ) {
											$planet->idSystem( $system->idSystem() );
											$planet->save();
										}
										break;
									}
								}
							}
						}
					}
					# strategic resources
					/**
					 * @var galaxyTemplateStratRes[] $galaxyTemplateStratResArray
					 */
					$galaxyTemplateStratResArray = array_reverse( galaxyTemplateStratRes::selectByGalaxyTemplate( $galaxyTemplate->idGalaxyTemplate(), $this->DB() ) );
					foreach ( $galaxyTemplateStratResArray as $stratRes ) {
						log::message( sprintf( "Placing resource %d", $stratRes->idStratRes() ) );
						$aoff = utils::randomFloat( 0, pi() * 2 );
						for ( $i = 0; $i < $stratRes->count(); $i++ ){ //for i in range(0, count):
							$angle = $aoff + $i * pi() * 2 / $stratRes->count();
							$tr = rand( $stratRes->minR(), $stratRes->maxR() );
							$x = cos($angle) * $tr + $galaxy->centerX();
							$y = sin($angle) * $tr + $galaxy->centerY();
							# find planet in closest system with planet.type in ("D", "R", "C"), without idStratRes and not plStarting
							$closestSystem = $this->DB()->selectValue( "
								SELECT  s.idSystem
								FROM    systems s
								        INNER JOIN planets p ON p.idSystem = s.idSystem
								WHERE   p.idStratRes is null
								        AND p.idPlanetType in ('D', 'R', 'C')
								        AND p.plStarting = 0
								ORDER BY pow((s.x - ?), 2) + pow((s.y - ?), 2)
								LIMIT   1"
								, $x, $y
							);
							log::message( sprintf( "Closest system %d", $closestSystem ) );
							$randomPlanetIdPlanet = $this->DB()->selectValue( "
								SELECT  p.idPlanet
								FROM    planets p
								WHERE   p.idSystem = ?
										AND p.idStratRes is null
										AND p.idPlanetType in ('D', 'R', 'C')
										AND p.plStarting = 0
								ORDER BY RAND()
								LIMIT   1"
								, $closestSystem
							);
							$randomPlanet = new planet( $this->DB());
							$randomPlanet->load( $randomPlanetIdPlanet );
							$randomPlanet->idStratRes( $stratRes->idStratRes() );
							$randomPlanet->save();
							log::message( sprintf( "Planet %d - assigned strat res %d", $randomPlanetIdPlanet, $stratRes->idStratRes() ) );
						}
					}
					# diseases
					/**
					 * @var galaxyTemplateDisease[] $diseaseArray
					 */
					$diseaseArray = array_reverse( galaxyTemplateDisease::selectByGalaxyTemplate( $galaxyTemplate->idGalaxyTemplate(), $this->DB() ) );
					foreach ( $diseaseArray as $disease ) {
						log::message( sprintf( "Placing disease %d", $disease->idDisease() ) );
						$aoff = utils::randomFloat( 0, pi() * 2 );
						for ( $i = 0; $i < $disease->count(); $i++ ){ //for i in range(0, count):
							$angle = $aoff + $i * pi() * 2 / $disease->count();
							$tr = rand( $disease->minR(), $disease->maxR() );
							$x = cos($angle) * $tr + $galaxy->centerX();
							$y = sin($angle) * $tr + $galaxy->centerY();
							# find planet in closest system with planet.type in ("M", "E"), without idDisease and not plStarting
							$closestSystem = $this->DB()->selectValue( "
								SELECT  s.idSystem
								FROM    systems s
								        INNER JOIN planets p ON p.idSystem = s.idSystem
								WHERE   p.idDisease is null
								        AND p.idPlanetType in ('M', 'E')
								        AND p.plStarting = 0
								ORDER BY pow((s.x - ?), 2) + pow((s.y - ?), 2)
								LIMIT   1"
								, $x, $y
							);
							log::message( sprintf( "Closest system %d", $closestSystem ) );
							$randomPlanetIdPlanet = $this->DB()->selectValue( "
								SELECT  p.idPlanet
								FROM    planets p
								WHERE   p.idSystem = ?
										AND p.idDisease is null
										AND p.idPlanetType in ('M', 'E')
										AND p.plStarting = 0
								ORDER BY RAND()
								LIMIT   1"
								, $closestSystem
							);
							$randomPlanet = new planet( $this->DB());
							$randomPlanet->load( $randomPlanetIdPlanet );
							$randomPlanet->idDisease( $disease->idDisease() );
							$randomPlanet->save();
							log::message( sprintf( "Planet %d - assigned disease %d", $randomPlanetIdPlanet, $disease->idDisease() ) );
						}
					}
					$this->DB()->commit();
				} catch ( Exception $e ) {
					$this->DB()->rollBack();
					throw $e;
				}
			} else {
				throw new Exception( 'You can\'t createFromDB more galaxies.' );
			}
		}

		/**
		 * @param system $system
		 * @return planet[]
		 */
		public function generateSystem( $system ) {
			$result = array();
			$starClasses = game::starClasses();
			$starClass = $starClasses[utils::getRandomWeightedElement( $starClasses, 'chance' )];
			$system->idStarClass( $starClass->idStarClass() );
			$system->starSubclass( rand( $starClass->subclassChanceMin(), $starClass->subclassChanceMax() ) );
			$num = rand(0, 100);
			$planets = array(0, 0, 0);
			$mod = 1.0 / 2.0; # was 2 / 3
			if ( in_array( $starClass->starType(), array( 'c', 'g' ) ) ) {
				if ( $num < 25 ) {
					$planets = $this->distributePlanets( $mod * rand( 1, 7 ) );
				}
			} elseif ( in_array( $starClass->starClass(), array( 'O', 'B' ) ) ) {
				if ( $num < 25 ) {
					$planets = $this->distributePlanets( $mod * rand( 1, 11 ) );
				}
			} elseif ( $starClass->starClass() == 'A' ) {
				if ( $num < 75 ) {
					$planets = $this->distributePlanets( $mod * rand( 1, 11 ) );
				}
			} elseif ( $starClass->starClass() == 'F' or $starClass->starClass() == 'G' ) {
				if ( $num < 95 ) {
					$num = rand( 1, 7 ) + rand( 1, 7 ) + 3;
					$planets = $this->distributePlanets( $mod * $num );
				}
			} elseif ( $starClass->starClass() == 'K' ) {
				if ( $num < 95 ) {
					$num = rand( 1, 7 ) + rand( 1, 7 );
					$planets = $this->distributePlanets( $mod * $num );
				}
			} elseif ( $starClass->starClass() == 'M' ) {
				if ( $num < 95 ) {
					$num = rand( 1, 7 );
					$planets = $this->distributePlanets( $mod * $num );
				}
			} elseif ( $starClass->starType() == 'd' ) {
				if ( $num < 10 ) {
					$num = round( $mod * rand( 1, 7 ) / 2 );
					$planets = array( 0, 0, $num );
				}
			} elseif ( $starClass->starType() == 'n' or $starClass->starType() == 'b' ) {
				if ( $num < 5 ) {
					$num = round( $mod * rand( 1, 7 ) / 2 );
					$planets = array( 0, 0, $num );
				}
			}
			# planets
			foreach ( $planets as $zone => $num ) {
				for ( $i = 0; $i < $num; $i++ ) {	//for i in xrange(0, num):
					$planet = new planet( $this->DB() );
					$this->generatePlanet( $zone, $planet, $starClass );
					$result[] = $planet;
				}
			}
			return $result;
		}

		private function distributePlanets( $num ) {
			$num = round( $num );
			if ( $num <= 3 ) {
				return array( 0, 1, $num - 1 );
			} elseif ( $num <= 5 ) {
				return array( 1, 1, $num - 2 );
			} elseif ( $num <= 7 ) {
				return array( 1, 2, $num - 3 );
			} elseif ( $num <= 11 ) {
				return array( 2, 2, $num - 4 );
			} else {
				return array( 2, 3, $num - 5 );
			}
		}

		/**
		 * @param int $zone
		 * @param planet $planet
		 * @param starClass $starClass
		 */
		private function generatePlanet( $zone, $planet, $starClass ) {
			//NOT starting by default
			$planet->plStarting( 0 );
			$planet->idStratRes( null );
			$planet->idDisease( null );
			$sc = $starClass->idStarClass();
			$isFGK = ( $sc == 'mF' or $sc == 'mG' or $sc == 'mK' );
			$isDNB = ( $starClass->starType() == 'd' or $sc == 'n-' or $sc == 'b-' );
			# plDiameter and type of planet
			$num = rand( 0, 100 );
			if ( $zone == 0 ) { # Zone A
				if ( $num < 5 ) {
					$planet->idPlanetType( 'A' );
					$planet->plDiameter( 0 );
				} elseif ( $num < 10 ) {
					$planet->idPlanetType( 'G' );
					$planet->plDiameter( utils::dice( 3, 6, 0 ) * 10000 );
				} elseif ( $num < 60 ) {
					$planet->idPlanetType( 'R' );
					$planet->plDiameter( utils::dice( 1, 10, 0 ) * 1000 );
				} elseif ( $num < 70 ) {
					$planet->idPlanetType( 'D' );
					$planet->plDiameter( utils::dice( 2, 6, 2 ) * 1000 );
				} else {
					$planet->idPlanetType( 'H' );
					$planet->plDiameter( utils::dice( 3, 6, 1 ) * 1000 );
				}
			} elseif ( $zone == 1 ) { # Zone B
				if ( $num < 10 ) {
					$planet->idPlanetType( 'A' );
					$planet->plDiameter( 0 );
				} elseif ( $num < 15 ) {
					$planet->idPlanetType( 'G' );
					$planet->plDiameter( utils::dice( 3, 6, 0 ) * 10000 );
				} elseif ( $num < 25 ) {
					$planet->idPlanetType( 'R' );
					$planet->plDiameter( utils::dice( 1, 10, 0 ) * 1000 );
				} elseif ( $num < 45 ) {
					$planet->idPlanetType( 'D' );
					$planet->plDiameter( utils::dice( 2, 6, 2 ) * 1000 );
				} elseif ( $num < 70 ) {
					$planet->idPlanetType( 'H' );
					$planet->plDiameter( utils::dice( 3, 6, 1 ) * 1000 );
				} elseif ( $num < 90 ) {
					if ( $isFGK ) {
						$planet->idPlanetType( 'M' );
						$planet->plDiameter( utils::dice( 2, 6, 5 ) * 1000 );
					} else {
						$planet->idPlanetType( 'H' );
						$planet->plDiameter( utils::dice( 3, 6, 1 ) * 1000 );
					}
				} else {
					if ( $isFGK ) {
						#$planet->idPlanetType( 'E'); $planet->plDiameter( utils::dice(2, 6, 5) * 1000);
						$planet->idPlanetType( 'E' );
						$planet->plDiameter( utils::dice( 1, 4, 13 ) * 1000 );
					} else {
						$planet->idPlanetType( 'H' );
						$planet->plDiameter( utils::dice( 3, 6, 1 ) * 1000 );
					}
				}
			} elseif ( $zone == 2 ) { # Zone C
				if ( $num < 15 ) {
					$planet->idPlanetType( 'A' );
					$planet->plDiameter( 0 );
				} elseif ( $num < 75 ) {
					$planet->idPlanetType( 'G' );
					$planet->plDiameter( utils::dice( 3, 6, 0 ) * 10000 );
				} elseif ( $num < 80 ) {
					$planet->idPlanetType( 'R' );
					$planet->plDiameter( utils::dice( 1, 10, 0 ) * 1000 );
				} elseif ( $num < 90 ) {
					$planet->idPlanetType( 'C' );
					$planet->plDiameter( utils::dice( 1, 10, 0 ) * 1000 );
				} elseif ( $num < 95 ) {
					$planet->idPlanetType( 'D' );
					$planet->plDiameter( utils::dice( 2, 6, 2 ) * 1000 );
				} else {
					if ( $isDNB ) {
						$planet->idPlanetType( 'C' );
						$planet->plDiameter( utils::dice( 1, 10, 0 ) * 1000 );
					} else {
						$planet->idPlanetType( 'H' );
						$planet->plDiameter( utils::dice( 3, 6, 1 ) * 1000 );
					}
				}
			}
			# plEn
			$planet->plEn( rand( 100 - $zone * 50, 150 - $zone * 50 ) );
			# plMin
			if ( in_array( $planet->idPlanetType(), array( 'R', 'D', 'H', 'M' ) ) ) {
				$density = utils::dice(1, 6, 0) / 2.0 + 3;
				$planet->plMin( round( ( ($planet->plDiameter() ) / 500.0 ) + $density * 10.0 + rand( 1, 101 ) / 2.0 - 45 ) * 2 );
			} elseif ( $planet->idPlanetType() == 'A'){
				$diameter = utils::dice(1, 10, 0) * 1000; # rock planet
				$density = utils::dice(1, 6, 0) / 2.0 + 3;
				$planet->plMin( round( ( ( $diameter / 500.0 ) + $density * 10.0 + rand( 1, 101 ) / 2.0 - 45 ) * 2 ) );
			} elseif ( $planet->idPlanetType() == 'G' ) {
				$diameter = utils::dice(3, 6, 1) * 1000; # earth like planet
				$density = utils::dice(1, 6, 0) / 2.0 + 3;
				$planet->plMin( round( ( ( $diameter / 500.0 ) + $density * 10.0 + rand( 1, 101 ) / 2.0 - 45 ) * 2 ) );
			} elseif ( $planet->idPlanetType() == 'E' ) {
				$planet->plMin( 100 );
			} else {
				$planet->plMin( 0 );
			}
			if ( $planet->plMin() < 0 ) {
				$planet->plMin( 0 );
			}
			# environment
			if ( $planet->idPlanetType() == 'E' ) {
				$planet->plEnv( 100 );
			} elseif ( $planet->idPlanetType() == 'M' ) {
				$planet->plEnv( rand( 25, 51 ) );

			} elseif ( $planet->idPlanetType() == 'H' ) {
				$planet->plEnv( rand( 12, 26 ) );

			} elseif ( $planet->idPlanetType() == 'D' ) {
				$planet->plEnv( rand( 6, 13 ) );

			} elseif ( $planet->idPlanetType() == 'C' ) {
				$planet->plEnv( rand( 0, 7 ) );

			} elseif ( $planet->idPlanetType() == 'R' ) {
				$planet->plEnv( rand( 0, 7 ) );
			} else {
				$planet->plEnv( 0 );
			}
			# slots
			$slotsMod = 0.67;
			$planet->plMaxSlots( round( ( $planet->plDiameter() / 1000 ) * 1.5 * $slotsMod ) );
			if ( $planet->idPlanetType() == 'E' ) {
				$planet->plSlots( 9 );
				# $planet->slots = round($planet->maxSlots * 0.50)
			} elseif ( $planet->idPlanetType() == 'M' ) {
				$planet->plSlots( round( $planet->plMaxSlots() * 0.50 ) );
			} elseif ( $planet->idPlanetType() == 'H' ) {
				$planet->plSlots( round( $planet->plMaxSlots() * 0.50 ) );
			} elseif ( $planet->idPlanetType() == 'D' ) {
				$planet->plSlots( round( $planet->plMaxSlots() * 0.75 ) );
			} elseif ( $planet->idPlanetType() == 'C' ) {
				$planet->plSlots( round( $planet->plMaxSlots() * 0.75 ) );
			} elseif ( $planet->idPlanetType() == 'R' ) {
				$planet->plSlots( round( $planet->plMaxSlots() * 0.75 ) );
			} else {
				$planet->plSlots( 0 );
			}
			# make sure that all planets except A and G has at least one slot
			if ( in_array( $planet->idPlanetType() , array( 'E', 'M', 'H', 'D', 'C', 'R' ) ) and $planet->plSlots() == 0 ) {
				#@print "Fixing slots", $planet->idPlanetType(), $planet->slots, $planet->maxSlots
				$planet->plMaxSlots( max( 1, $planet->plMaxSlots() ) );
				$planet->plSlots( max( 1, $planet->plSlots() ) );
			}
			#print $planet->idPlanetType(), $planet->environ, $planet->plMin*/
		}

		/**
		 * @param int $idUniverse
		 * @throws Exception
		 * @return null|object
		 */
		public function getIntroInfo( $idUniverse ) {
			$result = $this->DB()->selectRow( "
				SELECT  idUniverse, name, turn, now() as serverTime
				FROM    ".self::TABLE_NAME."
				WHERE	idUniverse = ?
				LIMIT   1",
				(int)$idUniverse
			);
			if(empty($result)){
				throw new Exception( sprintf( fConst::E_NOT_FOUND, __CLASS__, $idUniverse ) );
			}
			$result['lastClientVersion'] = config::$ClientVersion;
			return (object)$result;
		}
	}