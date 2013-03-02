<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 08.02.2013 12:55
	 */

	class galaxyTemplateDensity extends activeRecord {
		const TABLE_NAME = 'galaxy_templates_density';

		/**
		 * @param int $idGalaxyTemplate
		 * @return galaxyTemplateDensity[]
		 */
		public static function selectByGalaxyTemplate( $idGalaxyTemplate ) {
			$data = game::DB()->select( 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE idGalaxyTemplate = ?', $idGalaxyTemplate );
			foreach ( $data as $key => $dataRecord ) {
				$data[$key] = self::createFromArray( $dataRecord );
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