<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 8:44
	 */
	class planet extends activeRecord {
		const TABLE_NAME = 'planets';

		/**
		 * @throws Exception
		 * @return planet
		 */
		public function save() {
			if ( !$this->fieldIsSet( 'idPlanet' ) ) {
				game::DB()->exec(
					'INSERT INTO ' . $this::TABLE_NAME . ' ( idSystem, idPlanetType, plDiameter, plEn, plMin, plEnv, plSlots, plMaxSlots, plStarting, idStratRes, idDisease, name ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
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
					$this->idDisease(),
					$this->name()
				);
				$this->idPlanet( game::DB()->lastInsertId() );
			} else {
				game::DB()->exec(
					'UPDATE ' . $this::TABLE_NAME . ' SET
						idSystem = :idSystem,
						idPlayer = :idPlayer,
						idPlanetType = :idPlanetType,
						plDiameter = :plDiameter,
						plEn = :plEn,
						plMin = :plMin,
						plEnv = :plEnv,
						plSlots = :plSlots,
						plMaxSlots = :plMaxSlots,
						plStarting = :plStarting,
						idStratRes = :idStratRes,
						idDisease = :idDisease,
						storPop = :storPop,
						storBio = :storBio,
						storEn = :storEn,
						scannerPwr = :scannerPwr,
						morale = :morale,
						name = :name
					WHERE idPlanet = :idPlanet',
					array( ':idPlanet' => $this->idPlanet() ),
					array( ':idSystem' => $this->idSystem() ),
					array( ':idPlayer' => $this->idPlayer() ),
					array( ':idPlanetType' => $this->idPlanetType() ),
					array( ':plDiameter' => $this->plDiameter() ),
					array( ':plEn' => $this->plEn() ),
					array( ':plMin' => $this->plMin() ),
					array( ':plEnv' => $this->plEnv() ),
					array( ':plSlots' => $this->plSlots() ),
					array( ':plMaxSlots' => $this->plMaxSlots() ),
					array( ':plStarting' => $this->plStarting() ),
					array( ':idStratRes' => $this->idStratRes() ),
					array( ':idDisease' => $this->idDisease() ),
					array( ':storPop' => $this->storPop() ),
					array( ':storBio' => $this->storBio() ),
					array( ':storEn' => $this->storEn() ),
					array( ':scannerPwr' => $this->scannerPwr() ),
					array( ':morale' => $this->morale() ),
					array( ':name' => $this->name() )
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
			$data = game::DB()->selectRow( 'SELECT p.*, (SELECT count(*) FROM ' . structure::TABLE_NAME . ' WHERE idPlanet = p.idPlanet) as plStructures FROM ' . self::TABLE_NAME . ' p WHERE p.idPlanet = ?', $idPlanet );
			if ( empty( $data ) ) {
				throw new Exception( sprintf( fConst::E_NOT_FOUND, __CLASS__, $idPlanet ) );
			}
			return $this->assignArray( $data );
		}

		/**
		 * Type Hint wrapper
		 * @param int $idPlanet
		 * @return planet
		 */
		public static function createFromDB( $idPlanet ) {
			return parent::createFromDB( $idPlanet );
		}

		/**
		 * @param int $idSystem
		 * @return planet[]
		 */
		public static function selectByIdSystem( $idSystem ) {
			$planetsData = game::DB()->select( 'SELECT p.*, (SELECT count(*) FROM ' . structure::TABLE_NAME . ' WHERE idPlanet = p.idPlanet) as plStructures FROM ' . self::TABLE_NAME . ' p WHERE p.idSystem = ?', $idSystem );
			foreach($planetsData as $key => $planetData){
				$planetsData[$key] = planet::createFromArray( $planetData );
			}
			return $planetsData;
		}

		/**
		 * @return int
		 */
		public function plStructures() {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
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

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idDisease( $value = null ) {
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
		public function storPop( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function storBio( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function storEn( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function scannerPwr( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return float
		 */
		public function morale( $value = null ) {//ToDo - level mod
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
		 * @param float $level
		 * @return float
		 */
		public function level( $level = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function plBio( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
	}