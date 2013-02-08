<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 3:26
	 */
	class auth extends DB {
		public function load( $idUniverse ) {
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}

		public function save() {
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}

		/**
		 * @var user null
		 */
		private static $currentUser = null;

		/**
		 * @throws Exception
		 * @return user
		 */
		public function currentUser() {
			$user = new user( $this->DB() );
			return $user->load( 1 );
			//ToDo
			/*if ( empty( self::$currentUser ) ) {
				throw new Exception( 'Current player is not defined. Probable you are not logged in.' );
			}
			return self::$currentUser;
			*/
		}
	}