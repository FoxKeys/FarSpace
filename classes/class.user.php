<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 07.02.2013 14:39
	 */

	class user extends activeRecord {
		const TABLE_NAME = 'users';

		/**
		 * @param int $idUser
		 * @throws Exception
		 * @return user
		 */
		public function load( $idUser ) {
			$data = game::DB()->selectRow( 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE idUser = ?', $idUser );
			if ( empty( $data ) ) {
				throw new Exception( sprintf( fConst::E_NOT_FOUND, __CLASS__, $idUser ) );
			}
			$this->assignArray( $data );
			$this->fieldSet( 'idUser', $idUser );
			return $this;
		}

		/**
		 * @throws Exception
		 * @return user
		 */
		public function save() {
			if ( !$this->fieldIsSet( 'idUser' ) ) {
				throw new Exception( sprintf( fConst::E_PARTIALLY_IMPLEMENTED, __METHOD__ ) );
			} else {
				$idUser = $this->idUser();
				if ( $idUser != game::auth()->currentUser()->idUser() ) {
					throw new Exception( sprintf( fConst::E_ACCESS_DENIED, __CLASS__, $idUser ) );
				}
				game::DB()->exec(
					'UPDATE ' . $this::TABLE_NAME . ' SET galaxyCreateLimit = :galaxyCreateLimit WHERE idUser = :idUser',
					array( ':galaxyCreateLimit' => $this->galaxyCreateLimit() ),
					array( ':idUser' => $this->idUser() )
				);
			}
			return $this;
		}

		/**
		 * Type Hint wrapper
		 * @param int $idUser
		 * @return user
		 */
		public static function createFromDB( $idUser ) {
			return parent::createFromDB( $idUser );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function galaxyCreateLimit( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idUser( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
	}