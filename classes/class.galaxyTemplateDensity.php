<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 08.02.2013 12:55
	 */

	class galaxyTemplateDensity extends DB {
		const TABLE_NAME = 'galaxy_templates_density';

		/**
		 * @param int $idGalaxyTemplate
		 * @param FoxDB $DB
		 * @return galaxyTemplateDensity[]
		 */
		public static function selectByGalaxyTemplate( $idGalaxyTemplate, $DB ) {
			$data = $DB->select( 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE idGalaxyTemplate = ?', $idGalaxyTemplate );
			foreach ( $data as $key => $dataRecord ) {
				$data[$key] = self::createFromArray( $dataRecord, $DB );
			}
			return $data;
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
		 * @param $value
		 * @return int
		 */
		public function density( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
	}