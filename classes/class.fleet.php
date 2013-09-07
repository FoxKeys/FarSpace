<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 20.02.2013 15:27
	 */
	class fleet extends activeRecord {
		const TABLE_NAME = 'fleets';
		const TABLE_FLEETS_POSITIONS = 'fleets_positions';

		/**
		 * @param int|null $idPlayer
		 * @param int|null $idSystem
		 * @param float $storEn
		 */
		public function __construct( $idPlayer = null, $idSystem = null, $storEn = null ) {
			$reflectionMethod = new ReflectionMethod( $this, '__construct' );
			$parameters = $reflectionMethod->getParameters();
			foreach ( func_get_args() as $index => $value ) {
				$name = $parameters[$index]->name;
				$this->$name( $value );
			}
		}

		/**
		 * @throws Exception
		 * @return fleet
		 */
		public function save(){
			if ( !$this->fieldIsSet( 'idFleet' ) ) {
				game::DB()->exec(
					'INSERT INTO ' . $this::TABLE_NAME . ' ( idPlayer, idSystem, storEn ) VALUES (?, ?, ?)',
					$this->idPlayer(),
					$this->idSystem(),
					$this->storEn()
				);
				$this->idFleet( game::DB()->lastInsertId() );
			} else {
				game::DB()->exec(
					'UPDATE ' . $this::TABLE_NAME . ' SET
						idPlayer = :idPlayer,
						idSystem = :idPlayer,
						storEn = :idPlayer
					WHERE idFleet = :idPlayer',
					array( ':idFleet' => $this->idFleet() ),
					array( ':idPlayer' => $this->idPlayer() ),
					array( ':idSystem' => $this->idSystem() ),
					array( ':storEn' => $this->storEn() )
				);
			}
			return $this;
		}

		/**
		 * Type Hint wrapper
		 * @return fleet
		 */
		public static function createNew( /*$args*/ ) {
			return call_user_func_array(array('parent', 'createNew'), func_get_args());
		}

		/**
		 * @param ship $ship
		 * @param float $storEn
		 * @return fleet
		 */
		public function addShip( $ship, $storEn ) {
			if ( $ship->idFleet() != $this->idFleet() ) {
				$ship->idFleet( $this->idFleet() );
			}
			$this->storEn( $this->storEn() + $storEn );
			return $this;
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idFleet( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idPlayer( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idSystem( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param float $value
		 * @return float
		 */
		public function storEn( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
	}