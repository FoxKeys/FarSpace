<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 19.09.2013 21:21
	 */

	class buildTask extends activeRecord {
		/**
		 * @return int
		 */
		public function idBuildTask() {
			return (int)$this->fieldGet( __METHOD__ );
		}

		/**
		 * @return int
		 */
		public function idTech() {
			return (int)$this->fieldGet( __METHOD__ );
		}
	}