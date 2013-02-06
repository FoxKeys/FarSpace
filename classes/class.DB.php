<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox
	 * Date Time: 09.12.2011 15:01
	 */
	class DB {

		/**
		 * @var FoxDB $DB
		 */
		protected $DB;

		/**
		 * @param FoxDB $DB
		 */
		public function __construct( $DB ) {
			$this->DB = $DB;
		}

	}
