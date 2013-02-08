<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 8:44
	 */
	class planet extends DB {
		const TABLE_NAME = 'planets';

		/**
		 * @throws Exception
		 * @return planet
		 */
		public function save() {
			if ( !$this->fieldIsSet( 'idPlanet' ) ) {
				$this->DB()->exec(
					'INSERT INTO ' . $this::TABLE_NAME . ' ( idSystem, idPlanetType, plDiameter, plEn, plMin, plEnv, plSlots, plMaxSlots, plStarting, idStratRes ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
					$this->idSystem(),
					$this->idPlanetType(),
					$this->plDiameter(),
					$this->plEn(),
					$this->plMin(),
					$this->plEnv(),
					$this->plSlots(),
					$this->plMaxSlots(),
					$this->plStarting(),
					$this->idStratRes()
				);
				$this->idPlanet( $this->DB()->lastInsertId() );
			} else {
				$this->DB()->exec(
					'UPDATE ' . $this::TABLE_NAME . ' SET idSystem = ?, idPlanetType = ?, plDiameter = ?, plEn = ?, plMin = ?, plEnv = ?, plSlots = ?, plMaxSlots = ?, plStarting = ?, idStratRes = ? WHERE idPlanet = ?',
					$this->idSystem(),
					$this->idPlanetType(),
					$this->plDiameter(),
					$this->plEn(),
					$this->plMin(),
					$this->plEnv(),
					$this->plSlots(),
					$this->plMaxSlots(),
					$this->plStarting(),
					$this->idStratRes(),
					$this->idPlanet()
				);
			}
			return $this;
		}

		/**
		 *
		 * @param int $idPlanet
		 * @throws Exception
		 * @return planet
		 */
		public function load( $idPlanet ) {
			$data = $this->DB()->selectRow( 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE idPlanet = ?', $idPlanet );
			if ( empty( $data ) ) {
				throw new Exception( sprintf( fConst::E_NOT_FOUND, __CLASS__, $idPlanet ) );
			}
			return $this->assignArray( $data );
		}

		/**
		 * @param int $idSystem
		 * @param FoxDB $DB
		 * @return planet[]
		 */
		public static function selectByIdSystem( $idSystem, $DB ) {
			$planetsData = $DB->select( 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE idSystem = ?', $idSystem );
			foreach($planetsData as $key => $planetData){
				$planetsData[$key] = planet::createFromArray( $planetData, $DB );
			}
			return $planetsData;
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idPlanet( $value = null ) {
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
		 * @param int $value
		 * @return int
		 */
		public function idPlanetType( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function plDiameter( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
		
		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function plEn( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function plMin( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function plEnv( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function plSlots( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function plMaxSlots( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function plStarting( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idStratRes( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
	}