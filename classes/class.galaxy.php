<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 04.02.2013 14:25
	 */

	class galaxy extends activeRecord {
		const TABLE_NAME = 'galaxies';

		/**
		 * @throws Exception
		 * @return galaxy
		 */
		public function save() {
			if ( !$this->fieldIsSet( 'idGalaxy' ) ) {
				game::DB()->exec(
					'INSERT INTO ' . $this::TABLE_NAME . ' ( idUniverse, idUser, name, description, centerX, centerY, radius ) VALUES (?, ?, ?, ?, ?, ?, ?)',
					$this->idUniverse(),
					$this->idUser(),
					$this->name(),
					$this->description(),
					$this->centerX(),
					$this->centerY(),
					$this->radius()
				);
				$this->idGalaxy( game::DB()->lastInsertId() );
			} else {
				throw new Exception( sprintf( fConst::E_PARTIALLY_IMPLEMENTED, __METHOD__ ) );
				/*game::DB()->exec(
					'UPDATE ' . $this::GALAXIES_TABLE . ' SET emrLevel = ? WHERE idGalaxy = ?',
					$this->emrLevel(),
					$this->idGalaxy()
				);*/
			}
			return $this;
		}

		/**
		 * @param int $idGalaxy
		 * @throws Exception
		 * @return galaxy
		 */
		public function load( $idGalaxy ) {
			$data = game::DB()->selectRow( 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE idGalaxy = ?', $idGalaxy );
			if ( empty( $data ) ) {
				throw new Exception( sprintf( fConst::E_NOT_FOUND, __CLASS__, $idGalaxy ) );
			}
			return $this->assignArray( $data );
		}

		/**
		 * Type Hint wrapper
		 * @param int $idGalaxy
		 * @return galaxy
		 */
		public static function createFromDB( $idGalaxy ) {
			return parent::createFromDB( $idGalaxy );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idGalaxy( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idUniverse( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idUser( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param $value
		 * @return int
		 */
		public function centerX( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param $value
		 * @return int
		 */
		public function centerY( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param $value
		 * @return int
		 */
		public function radius( $value = null ) {
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
		 * @param string $value
		 * @return string
		 */
		public function description( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * @return int[]
		 */
		public function freeStartingPositions() {
			$planets = game::DB()->select(
				'SELECT p.idPlanet FROM ' . planet::TABLE_NAME . ' p INNER JOIN ' . system::TABLE_NAME . ' s ON p.idSystem = s.IdSystem WHERE s.idGalaxy = ? AND p.plStarting <> 0 AND p.idPlayer IS NULL',
				$this->idGalaxy()
			);
			$result = array();
			foreach ( $planets as $planet ) {
				$result[] = $planet['idPlanet'];
			}
			return $result;
		}

	}