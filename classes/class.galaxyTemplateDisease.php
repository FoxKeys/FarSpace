<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 09.02.2013 13:12
	 */

	class galaxyTemplateDisease extends DB {
		const TABLE_NAME = 'galaxy_templates_diseases';

		/**
		 * @param int $idGalaxyTemplate
		 * @param FoxDB $DB
		 * @return galaxyTemplateDisease[]
		 */
		public static function selectByGalaxyTemplate( $idGalaxyTemplate, $DB ) {
			$data = $DB->select( 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE idGalaxyTemplate = ? ORDER BY idDisease', $idGalaxyTemplate );
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
		public function idDisease( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param $value
		 * @return int
		 */
		public function minR( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param $value
		 * @return int
		 */
		public function maxR( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param $value
		 * @return int
		 */
		public function count( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

	}