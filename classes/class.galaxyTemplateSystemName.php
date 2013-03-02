<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 09.02.2013 13:12
	 */

	class galaxyTemplateSystemName extends activeRecord {
		const TABLE_NAME = 'galaxy_templates_system_names';

		/**
		 * @param int $idGalaxyTemplate
		 * @return galaxyTemplateSystemName[]
		 */
		public static function selectByGalaxyTemplate( $idGalaxyTemplate ) {
			$data = game::DB()->select( 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE idGalaxyTemplate = ? ORDER BY RAND()', $idGalaxyTemplate );
			foreach ( $data as $key => $dataRecord ) {
				$data[$key] = self::createFromArray( $dataRecord );
			}
			return $data;
		}

		/**
		 * Type Hint wrapper
		 * @param string $value
		 * @return string
		 */
		public function name( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

	}