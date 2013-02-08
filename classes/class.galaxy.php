<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 04.02.2013 14:25
	 */

	class galaxy extends DB {
		const TABLE_NAME = 'galaxies';

		/**
		 * @throws Exception
		 * @return galaxy
		 */
		public function save() {
			if ( !$this->fieldIsSet( 'idGalaxy' ) ) {
				$this->DB()->exec(
					'INSERT INTO ' . $this::TABLE_NAME . ' ( idUniverse, idUser, name, description, centerX, centerY, radius ) VALUES (?, ?, ?, ?, ?, ?, ?)',
					$this->idUniverse(),
					$this->idUser(),
					$this->name(),
					$this->description(),
					$this->centerX(),
					$this->centerY(),
					$this->radius()
				);
				$this->idGalaxy( $this->DB()->lastInsertId() );
			} else {
				throw new Exception( sprintf( fConst::E_PARTIALLY_IMPLEMENTED, __METHOD__ ) );
				/*$this->DB()->exec(
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
			$data = $this->DB()->selectRow( 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE idGalaxy = ?', $idGalaxy );
			if ( empty( $data ) ) {
				throw new Exception( sprintf( fConst::E_NOT_FOUND, __CLASS__, $idGalaxy ) );
			}
			return $this->assignArray( $data );
		}

		/**
		 * Type Hint wrapper
		 * @param int $idGalaxy
		 * @param FoxDB $DB
		 * @return galaxy
		 */
		public static function createFromDB( $idGalaxy, $DB ) {
			return parent::createFromDB( $idGalaxy, $DB );
		}

		/**
		 * Type Hint wrapper
		 * @param int $idGalaxy
		 * @return int
		 */
		public function idGalaxy( $idGalaxy = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
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
		 * Type Hint wrapper
		 * @param int $idUser
		 * @return int
		 */
		public function idUser( $idUser = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param $centerX
		 * @return int
		 */
		public function centerX( $centerX = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param $centerY
		 * @return int
		 */
		public function centerY( $centerY = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param $radius
		 * @return int
		 */
		public function radius( $radius = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param string $name
		 * @return string
		 */
		public function name( $name = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
		
		/**
		 * Type Hint wrapper
		 * @param string $description
		 * @return string
		 */
		public function description( $description = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

	}