<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 08.02.2013 9:19
	 */

	class slot extends DB {
		/**
		 * @throws Exception
		 * @return slot
		 */
		public function save() {
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}

		/**
		 *
		 * @param int $idSlot
		 * @throws Exception
		 * @return slot
		 */
		public function load( $idSlot ) {
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}
	}