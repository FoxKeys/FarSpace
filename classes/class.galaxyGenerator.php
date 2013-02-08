<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 5:45
	 */

	class galaxyGenerator {

		/**
		 * @param system $system
		 */
		private function generateSystem( $system ) {
			$starClass = game::starClasses()->getRandom();
			$system->starClass( $starClass );
			$system->starSubclass( rand( $starClass->subclassChanceMin(), $starClass->subclassChanceMax() ) );
			$num = rand(0, 100);
			$planets = array(0, 0, 0);
			$mod = 1.0 / 2.0; # was 2 / 3
			if ( in_array( $system->starClass()->starType(), array( 'c', 'g' ) ) ) {
				if ( $num < 25 ) {
					$planets = $this->distributePlanets( $mod * rand( 1, 7 ) );
				}
			} elseif ( in_array( $system->starClass()->starClass(), array( 'O', 'B' ) ) ) {
				if ( $num < 25 ) {
					$planets = $this->distributePlanets( $mod * rand( 1, 11 ) );
				}
			} elseif ( $system->starClass()->starClass() == 'A' ) {
				if ( $num < 75 ) {
					$planets = $this->distributePlanets( $mod * rand( 1, 11 ) );
				}
			} elseif ( $system->starClass()->starClass() == 'F' or $system->starClass()->starClass() == 'G' ) {
				if ( $num < 95 ) {
					$num = rand( 1, 7 ) + rand( 1, 7 ) + 3;
					$planets = $this->distributePlanets( $mod * $num );
				}
			} elseif ( $system->starClass()->starClass() == 'K' ) {
				if ( $num < 95 ) {
					$num = rand( 1, 7 ) + rand( 1, 7 );
					$planets = $this->distributePlanets( $mod * $num );
				}
			} elseif ( $system->starClass()->starClass() == 'M' ) {
				if ( $num < 95 ) {
					$num = rand( 1, 7 );
					$planets = $this->distributePlanets( $mod * $num );
				}
			} elseif ( $system->starClass()->starType() == 'd' ) {
				if ( $num < 10 ) {
					$num = round( $mod * rand( 1, 7 ) / 2 );
					$planets = array( 0, 0, $num );
				}
			} elseif ( $system->starClass()->starType() == 'n' or $system->starClass()->starType() == 'b' ) {
				if ( $num < 5 ) {
					$num = round( $mod * rand( 1, 7 ) / 2 );
					$planets = array( 0, 0, $num );
				}
			}
			# planets
			$zone = 0;
			foreach ( $planets as $num ) {
				for ( $i = 0; $i <= $num; $i++ ) {
					$planet = new planet( game::DB() );
					$system->planets()->append( $planet );
					$this->generatePlanet( $zone, $planet );
				}
				$zone += 1;
			}
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
			# sort planets by energy
			//ToDo ? system.planets.sort(lambda a, b: cmp(b.energy, a.energy))
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

		private function generatePlanet($planet){
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}
	}