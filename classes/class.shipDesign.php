<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 20.02.2013 12:34
	 */
	class shipDesign extends activeRecord {
		const TABLE_NAME = 'ship_designs';
		const TABLE_NAME_EQUIPMENT = 'ship_designs_equipment';

		/**
		 * @param int|null $idPlayer
		 * @param string|null $name
		 * @param int|null $idPlayerTechHull
		 * @param int|null $idPlayerTechControl
		 * @param array|null $equipment
		 */
		public function __construct( $idPlayer, $name = null, $idPlayerTechHull = null, $idPlayerTechControl = null, $equipment = null ) {
			$reflectionMethod = new ReflectionMethod( $this, '__construct' );
			$parameters = $reflectionMethod->getParameters();
			foreach ( func_get_args() as $index => $value ) {
				$name = $parameters[$index]->name;
				$this->$name( $value );
			}
		}

		/**
		 * @throws Exception
		 * @return shipDesign
		 */
		public function save(){
			if ( !$this->fieldIsSet( 'idShipDesign' ) ) {
				game::DB()->exec(
					'INSERT INTO ' . $this::TABLE_NAME . ' ( idPlayer, name, idPlayerTechHull, idPlayerTechControl ) VALUES (?, ?, ?, ?)',
					$this->idPlayer(),
					$this->name(),
					$this->idPlayerTechHull(),
					$this->idPlayerTechControl()
				);
				$this->idShipDesign( game::DB()->lastInsertId() );
				$equipment = $this->equipment();
				if ( !empty( $equipment ) ) {
					foreach ( $equipment as $idPlayerTech => $qty ) {
						game::DB()->exec(
							'INSERT INTO ' . $this::TABLE_NAME_EQUIPMENT . ' ( idShipDesign, idPlayerTech, qty ) VALUES (?, ?, ?)',
							$this->idShipDesign(),
							$idPlayerTech,
							$qty
						);
					}
				}
			} else {
				throw new Exception( sprintf( fConst::E_PARTIALLY_IMPLEMENTED, __METHOD__ ) );
				/*game::DB()->exec(
					'UPDATE ' . $this::TABLE_NAME . ' SET
						idSystem = ?,
						idPlayer = ?,
						idPlanetType = ?,
						plDiameter = ?,
						plEn = ?,
						plMin = ?,
						plEnv = ?,
						plSlots = ?,
						plMaxSlots = ?,
						plStarting = ?,
						idStratRes = ?,
						idDisease = ?,
						storPop = ?,
						storBio = ?,
						storEn = ?,
						scannerPwr = ?,
						morale = ?
					WHERE idPlanet = ?',
					$this->idSystem(),
					$this->idPlayer(),
					$this->idPlanetType(),
					$this->plDiameter(),
					$this->plEn(),
					$this->plMin(),
					$this->plEnv(),
					$this->plSlots(),
					$this->plMaxSlots(),
					$this->plStarting(),
					$this->idStratRes(),
					$this->idDisease(),
					$this->storPop(),
					$this->storBio(),
					$this->storEn(),
					$this->scannerPwr(),
					$this->morale(),
					$this->idPlanet()
				);*/
			}
			return $this;
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
		 * @param int $value
		 * @return int
		 */
		public function idPlayer( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param string $value
		 * @return string
		 */
		public function name( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idPlayerTechHull( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idPlayerTechControl( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param array $value
		 * @return array
		 */
		public function equipment( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
	}