<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 09.02.2013 13:12
	 */

	class galaxyTemplateStratRes extends activeRecord {
		const TABLE_NAME = 'galaxy_templates_strat_res';

		/**
		 * @param int $idGalaxyTemplate
		 * @return galaxyTemplateStratRes[]
		 */
		public static function selectByGalaxyTemplate( $idGalaxyTemplate ) {
			$data = game::DB()->select( 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE idGalaxyTemplate = ? ORDER BY idStratRes', $idGalaxyTemplate );
			foreach ( $data as $key => $dataRecord ) {
				$data[$key] = self::createFromArray( $dataRecord );
			}
			return $data;
		}

		/**
		 * Type Hint wrapper
		 * @param $value
		 * @return int
		 */
		public function idStratRes( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param float $value
		 * @return float
		 */
		public function minR( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param float $value
		 * @return float
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