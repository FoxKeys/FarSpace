<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 10:02
	 */
	class starClasses extends DB {
		const TABLE_NAME = 'starClasses';
		/**
		 * @var array
		 */
		private $data = null;

		/**
		 * @return array
		 */
		public function data() {
			if ( !isset( $this->data ) ) {
				$this->data = $this->DB()->select( 'SELECT * FROM ' . self::TABLE_NAME );
			}
			return $this->data;
		}

		/**
		 * @return starClass
		 */
		public function getRandom() {
			$starClass = new starClass( game::DB() );
			$data = $this->data();
			return $starClass->assignArray( $data[utils::getRandomWeightedElement( $data, 'chance' )] );
		}
	}