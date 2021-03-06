<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 20.02.2013 15:27
	 */
	class ship extends activeRecord {
		const TABLE_NAME = 'ships';

		/**
		 * @param int|null $idFleet
		 * @param int|null $idShipDesign
		 * @param float $HP
		 * @param float $shield
		 * @param float $experience
		 */
		public function __construct( $idFleet = null, $idShipDesign = null, $HP = null, $shield = null, $experience = null ) {
			$reflectionMethod = new ReflectionMethod( $this, '__construct' );
			$parameters = $reflectionMethod->getParameters();
			foreach ( func_get_args() as $index => $value ) {
				$name = $parameters[$index]->name;
				$this->$name( $value );
			}
		}

		/**
		 * @throws Exception
		 * @return ship
		 */
		public function save(){
			if ( !$this->fieldIsSet( 'idShip' ) ) {
				game::DB()->exec(
					'INSERT INTO ' . $this::TABLE_NAME . ' ( idFleet, idShipDesign, HP, shield, experience ) VALUES (?, ?, ?, ?, ?)',
					$this->idFleet(),
					$this->idShipDesign(),
					$this->HP(),
					$this->shieldHP(),
					$this->experience()
				);
				$this->idShip( game::DB()->lastInsertId() );
			} else {
				throw new Exception( sprintf( fConst::E_PARTIALLY_IMPLEMENTED, __METHOD__ ) );
				/*game::DB()->exec(
					'UPDATE ' . $this::TABLE_NAME . ' SET
						idSystem = ?,
					WHERE idPlanet = ?',
					$this->idSystem(),
				);*/
			}
			return $this;
		}

		/**
		 * Type Hint wrapper
		 * @return ship
		 */
		public static function createNew( /*$args*/ ) {
			return call_user_func_array(array('parent', 'createNew'), func_get_args());
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idShip( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
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
		public function idShipDesign( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param float $value
		 * @return float
		 */
		public function HP( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param float $value
		 * @return float
		 */
		public function shieldHP( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param float $value
		 * @return float
		 */
		public function experience( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
	}