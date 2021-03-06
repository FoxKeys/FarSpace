<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 6:06
	 */

	class galaxyTemplate extends activeRecord {
		const TABLE_NAME = 'galaxy_templates';

		/**
		 * Type Hint wrapper
		 * @param int $idGalaxyTemplate
		 * @return galaxyTemplate
		 */
		public static function createFromDB( $idGalaxyTemplate ) {
			return parent::createFromDB( $idGalaxyTemplate );
		}

		/**
		 * @param int $idGalaxyTemplate
		 * @throws Exception
		 * @return \galaxyTemplate
		 */
		public function load( $idGalaxyTemplate ) {
			$data = game::DB()->selectRow( 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE idGalaxyTemplate = ?', $idGalaxyTemplate );
			if ( empty( $data ) ) {
				throw new Exception( sprintf( fConst::E_NOT_FOUND, __CLASS__, $idGalaxyTemplate ) );
			}
			return $this->assignArray( $data );
		}

		/**
		 * @throws Exception
		 * @return \galaxyTemplate
		 */
		public function save() {
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idGalaxyTemplate( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param float $value
		 * @return float
		 */
		public function centerX( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param float $value
		 * @return float
		 */
		public function centerY( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
		
		/**
		 * Type Hint wrapper
		 * @param float $value
		 * @return float
		 */
		public function radius( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param float $value
		 * @return float
		 */
		public function galaxyMinR( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function galaxyPlayers( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param float $value
		 * @return float
		 */
		public function startRMin( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param float $value
		 * @return float
		 */
		public function startRMax( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function galaxyPlayerGroup( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param float $value
		 * @return float
		 */
		public function galaxyGroupDist( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param float $value
		 * @return float
		 */
		public function emrLevel( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
	}