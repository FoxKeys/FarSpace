<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 17.02.2013 12:24
	 */

	class ePlayerTechNotFound extends Exception {};

	class playerTech extends activeRecord {
		const TABLE_NAME = 'players_techs';
		const E_SYMBOL_NOT_FOUND = 'Player %d have no tech with symbol="%s"';

		/**
		 * @param int $idPlayer
		 * @param string $symbol
		 * @throws ePlayerTechNotFound
		 * @return playerTech
		 */
		public static function createFromSymbol( $idPlayer, $symbol ) {
			$data = game::DB()->selectRow(
				'SELECT pt.idTech, pt.level, t.TL, t.symbol, t.name, t.maxHP FROM ' . self::TABLE_NAME . ' pt INNER JOIN ' . tech::TABLE_NAME . ' t ON pt.idTech = t.idTech WHERE pt.idPlayer = ? AND t.symbol = ?',
				$idPlayer, $symbol
			);
			if(empty($data)){
				throw new ePlayerTechNotFound( sprintf( self::E_SYMBOL_NOT_FOUND, $idPlayer, $symbol ) );
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

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function level( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
	}