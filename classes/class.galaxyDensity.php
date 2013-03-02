<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 08.02.2013 12:55
	 */

	class galaxyDensity extends activeRecord {
		const GALAXY_DENSITY_TABLE = 'galaxyTemplatesDensity';

		/**
		 * @param int $idGalaxyTemplate
		 * @return galaxyDensity[]
		 */
		public static function selectByGalaxyTemplate( $idGalaxyTemplate ) {
			$data = game::DB()->select( 'SELECT * FROM ' . self::GALAXY_DENSITY_TABLE . ' WHERE idGalaxyTemplate = ?', $idGalaxyTemplate );
			foreach ( $data as $key => $galaxyDensityData ) {
				$data[$key] = galaxyDensity::createFromArray( $galaxyDensityData );
			}
			return $data;
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
		public function density( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
	}