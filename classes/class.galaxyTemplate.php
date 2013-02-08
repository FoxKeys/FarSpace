<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 6:06
	 */

	class galaxyTemplate extends DB {
		const TABLE_NAME = 'galaxyTemplates';
		const DENSITY_TABLE_NAME = 'galaxyTemplatesDensity';

		/**
		 * Type Hint wrapper
		 * @param int $idGalaxyTemplate
		 * @param FoxDB $DB
		 * @return galaxyTemplate
		 */
		public static function createFromDB( $idGalaxyTemplate, $DB ) {
			return parent::createFromDB( $idGalaxyTemplate, $DB );
		}

		/**
		 * @param int $idGalaxyTemplate
		 * @throws Exception
		 * @return \galaxyTemplate
		 */
		public function load( $idGalaxyTemplate ) {
			$data = $this->DB()->selectRow( 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE idGalaxyTemplate = ?', $idGalaxyTemplate );
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
		 * @param int $idGalaxyTemplate
		 * @return int
		 */
		public function idGalaxyTemplate( $idGalaxyTemplate = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $centerX
		 * @return int
		 */
		public function centerX( $centerX = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $centerY
		 * @return int
		 */
		public function centerY( $centerY = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
		
		/**
		 * Type Hint wrapper
		 * @param int $radius
		 * @return int
		 */
		public function radius( $radius = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $galaxyMinR
		 * @return int
		 */
		public function galaxyMinR( $galaxyMinR = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

	}