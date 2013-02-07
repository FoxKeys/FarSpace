<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 7:15
	 */
	class system extends DB {
		/**
		 * @var starClass
		 */
		private $starClass = null;
		/**
		 * @var int
		 */
		private $starSubclass = 0;

		/**
		 * @return planet[]
		 */
		public function planets() {
			return array();
		}

		/**
		 * @param null|starClass $starClass
		 * @return null|starClass
		 */
		public function starClass( $starClass = null ) {
			if ( isset( $starClass ) ) {
				$this->starClass = $starClass;
			}
			return $this->starClass;
		}

		/**
		 * @param null|int $starSubclass
		 * @return int
		 */
		public function starSubclass( $starSubclass = null ) {
			if ( isset( $starSubclass ) ) {
				$this->starSubclass = $starSubclass;
			}
			return $this->starSubclass;
		}
	}