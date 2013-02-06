<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 3:33
	 */

	class player extends DB {
		/**
		 * @var int
		 */
		private $galaxyCreateLimit = 0;

		/**
		 * @return int
		 */
		public function galaxyCreateLimit() {
			return $this->galaxyCreateLimit;
		}
	}