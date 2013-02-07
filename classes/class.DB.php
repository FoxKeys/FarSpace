<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox
	 * Date Time: 09.12.2011 15:01
	 */
	abstract class DB {

		/**
		 * @var FoxDB $DB
		 */
		protected $DB = null;

		/**
		 * @param FoxDB $DB
		 */
		public function __construct( $DB ) {
			$this->DB = $DB;
		}

		/**
		 * @return FoxDB|null
		 */
		public function DB() {
			return $this->DB;
		}

		/**
		 * @param array $data
		 * @return \DB
		 */
		public function assignArray( $data ) {
			foreach ( $data as $key => $value ) {
				if ( method_exists( $this, $key ) ) {
					$this->$key( $value );
				}
			}
			return $this;
		}

		abstract public function save();
	}
