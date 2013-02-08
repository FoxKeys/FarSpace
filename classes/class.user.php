<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 07.02.2013 14:39
	 */

	class user extends DB {
		const USERS_TABLE_NAME = 'users';

		/**
		 * @param int $idUser
		 * @throws Exception
		 * @return user
		 */
		public function load( $idUser ) {
			$data = $this->DB()->selectRow( 'SELECT * FROM ' . self::USERS_TABLE_NAME . ' WHERE idUser = ?', $idUser );
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
				$this->DB()->exec(
					'UPDATE ' . $this::USERS_TABLE_NAME . ' SET galaxyCreateLimit = ? WHERE idUser = ?',
					$this->galaxyCreateLimit(),
					$idUser
				);
			}
			return $this;
		}

		/**
		 *
		 * @param int $galaxyCreateLimit
		 * @return int
		 */
		public function galaxyCreateLimit( $galaxyCreateLimit = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * @return int
		 */
		public function idUser(){
			return $this->fieldGet( __METHOD__ );
		}
	}