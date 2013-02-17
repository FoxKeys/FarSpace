<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 17.02.2013 11:12
	 */

	class structure extends activeRecord {
		const TABLE_NAME = 'structures';

		public function __construct( $idTech = null, $idPlanet = null, $slot = null, $idPlayer = null, $hitPoints = null, $statusOn = null, $statusNew = null ) {
			$reflectionMethod = new ReflectionMethod( $this, '__construct' );
			$parameters = $reflectionMethod->getParameters();
			foreach ( func_get_args() as $index => $value ) {
				$name = $parameters[$index]->name;
				$this->$name( $value );
			}
		}

		/**
		 * @throws Exception
		 * @return structure
		 */
		public function save() {
			if ( !$this->fieldIsSet( 'idStructure' ) ) {
				game::DB()->exec(
					'INSERT INTO ' . $this::TABLE_NAME . ' ( idTech, idPlanet, slot, idPlayer, hitPoints, statusOn, statusNew ) VALUES (?, ?, ?, ?, ?, ?, ?)',
					$this->idTech(),
					$this->idPlanet(),
					$this->slot(),
					$this->idPlayer(),
					$this->hitPoints(),
					$this->statusOn(),
					$this->statusNew()
				);
				$this->idPlanet( game::DB()->lastInsertId() );
			} else {
				throw new Exception( sprintf( fConst::E_PARTIALLY_IMPLEMENTED, __METHOD__ ) );
				/*
				game::DB()->exec(
					'UPDATE ' . $this::TABLE_NAME . ' SET idSystem = ?, idPlayer = ?, idPlanetType = ?, plDiameter = ?, plEn = ?, plMin = ?, plEnv = ?, plSlots = ?, plMaxSlots = ?, plStarting = ?, idStratRes = ?, idDisease = ? WHERE idPlanet = ?',
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
		public function idTech( $value = null ) {
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
		public function idPlayer( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function slot( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function hitPoints( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param bool $value
		 * @return bool
		 */
		public function statusOn( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param bool $value
		 * @return bool
		 */
		public function statusNew( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

	}