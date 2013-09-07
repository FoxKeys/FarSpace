<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 20.02.2013 12:34
	 */
	class shipDesign extends activeRecord {
		const TABLE_NAME = 'ship_designs';
		const TABLE_NAME_EQUIPMENT = 'ship_designs_equipment';

		private $summary = null;

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
					'INSERT INTO ' . $this::TABLE_NAME . ' ( idPlayer, name ) VALUES (?, ?)',
					$this->idPlayer(),
					$this->name()
				);
				$this->idShipDesign( game::DB()->lastInsertId() );
				game::DB()->exec(
					'INSERT INTO ' . $this::TABLE_NAME_EQUIPMENT . ' ( idShipDesign, idPlayerTech, qty, hull ) VALUES (?, ?, 1, 1)',
					$this->idShipDesign(),
					$this->idPlayerTechHull()
				);
				game::DB()->exec(
					'INSERT INTO ' . $this::TABLE_NAME_EQUIPMENT . ' ( idShipDesign, idPlayerTech, qty, control ) VALUES (?, ?, 1, 1)',
					$this->idShipDesign(),
					$this->idPlayerTechControl()
				);
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
		 * @param int $idObject
		 * @return shipDesign
		 */
		public static function createFromDB( $idObject ) {
			return parent::createFromDB( $idObject );
		}

		/**
		 * Type Hint wrapper
		 * @return shipDesign
		 */
		public static function createNew( /*$args*/ ) {
			return call_user_func_array(array('parent', 'createNew'), func_get_args());
		}

		/**
		 *
		 */
		private function calcSummary(){
			$sum = game::DB()->selectRow('
				SELECT	IFNULL(SUM(t.maxHP * techEff(pt.level) * e.qty), 0) as HP,
						IFNULL(SUM(t.storEn * techEff(pt.level) * e.qty), 0) as storEn,
						IFNULL(SUM(t.signature * e.qty), 0) as signature,
				FROM ' . playerTech::TABLE_NAME . ' pt
						INNER JOIN ' . $this::TABLE_NAME_EQUIPMENT . ' e ON pt.idPlayerTech = e.idPlayerTech
						INNER JOIN ' . tech::TABLE_NAME . ' t on pt.idTech = t.idTech
				WHERE e.idShipDesign = ?',
				$this->idShipDesign()
			);
			$best = game::DB()->selectRow('
				SELECT	IFNULL(MAX(t.maxHP * t.shieldPerc * techEff(pt.level) * e.qty), 0) as shieldHP,
				FROM ' . playerTech::TABLE_NAME . ' pt
						INNER JOIN ' . $this::TABLE_NAME_EQUIPMENT . ' e ON pt.idPlayerTech = e.idPlayerTech
						INNER JOIN ' . tech::TABLE_NAME . ' t on pt.idTech = t.idTech
				WHERE e.idShipDesign = ?',
				$this->idShipDesign()
			);
			$this->summary = array_merge($sum, $best);
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idShipDesign( $value = null ) {
			if ( func_num_args() > 0 ) {
				$this->summary = null;
			}
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
			if ( func_num_args() > 0 ) {
				$this->summary = null;
			}
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idPlayerTechControl( $value = null ) {
			if ( func_num_args() > 0 ) {
				$this->summary = null;
			}
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param array $value
		 * @return array
		 */
		public function equipment( $value = null ) {
			if ( func_num_args() > 0 ) {
				$this->summary = null;
			}
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * @return float
		 */
		public function HP() {
			if(is_null($this->summary)){
				$this->calcSummary();
			}
			return $this->summary['HP'];
		}

		/**
		 * @return float
		 */
		public function shieldHP() {
			if(is_null($this->summary)){
				$this->calcSummary();
			}
			return $this->summary['shieldHP'];
		}

		/**
		 * @return float
		 */
		public function storEn(){
			if(is_null($this->summary)){
				$this->calcSummary();
			}
			return $this->summary['storEn'];
		}
	}