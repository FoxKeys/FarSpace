<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 08.02.2013 12:55
	 */

	class galaxyDensity extends DB {
		const GALAXY_DENSITY_TABLE = 'galaxyTemplatesDensity';

		/**
		 * @param int $idGalaxyTemplate
		 * @param FoxDB $DB
		 * @return galaxyDensity[]
		 */
		public static function selectByGalaxyTemplate( $idGalaxyTemplate, $DB ) {
			$data = $DB->select( 'SELECT * FROM ' . self::GALAXY_DENSITY_TABLE . ' WHERE idGalaxyTemplate = ?', $idGalaxyTemplate );
			foreach ( $data as $key => $galaxyDensityData ) {
				$data[$key] = galaxyDensity::createFromArray( $galaxyDensityData, $DB );
			}
			return $data;
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
		 * @param $density
		 * @return int
		 */
		public function density( $density = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
	}