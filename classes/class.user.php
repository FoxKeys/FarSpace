<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 07.02.2013 14:39
	 */

	class user extends DB {
		public function load( $idUniverse ) {
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}

		public function save() {
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}

		/**
		 *
		 * @param int $galaxyCreateLimit
		 * @return int
		 */
		public function galaxyCreateLimit( $galaxyCreateLimit = null ) {
			return call_user_func_array( array( $this, 'getSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
	}