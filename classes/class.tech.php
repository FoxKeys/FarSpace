<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 17.02.2013 8:17
	 */

	class tech extends activeRecord {
		const TABLE_NAME = 'techs';
		const E_SYMBOL_NOT_FOUND = 'Tech with symbol="%s" not found';

		/**
		 * @return tech[]
		 */
		public static function startingTechs(){
			//
		}

		/**
		 * @param string $symbol
		 * @throws Exception
		 * @return tech
		 */
		public static function createFromSymbol( $symbol ) {
			$data = game::DB()->selectRow(
				'SELECT idTech, TL, symbol, name, maxHP FROM ' . self::TABLE_NAME . ' WHERE symbol = ?',
				$symbol
			);
			if(empty($data)){
				throw new Exception( sprintf( self::E_SYMBOL_NOT_FOUND, $symbol ) );
			}
			return self::createFromArray( $data );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idTech( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function maxHP( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

	}