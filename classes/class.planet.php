<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 8:44
	 */
	class planet extends DB {
		/**
		 * @var planetType
		 */
		private $planetType = null;
		/**
		 * @var int
		 */
		private $slots = 0;

		/**
		 * @param null|planetType $planetType
		 * @return null|planetType
		 */
		public function type( $planetType = null ) {
			if ( isset( $planetType ) ) {
				$this->planetType = $planetType;
			}
			return $this->planetType;
		}

		/**
		 * @param int|null $slots
		 * @return int|null
		 */
		public function slots( $slots = null ) {
			if ( isset( $slots ) ) {
				$this->slots = $slots;
			}
			return $this->slots;
		}

	}