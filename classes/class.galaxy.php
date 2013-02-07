<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 04.02.2013 14:25
	 */

	class galaxy extends DB {
		CONST GALAXIES_TABLE = 'galaxies';
		private $forums = array( "PUBLIC" => 112, "NEWS" => 112 );
		private $idGalaxy = 0;
		/**
		 * @var int
		 */
		private $centerX = 500;
		/**
		 * @var int
		 */
		private $centerY = 50;
		/**
		 * @var int
		 */
		private $radius = 500;

		/**
		 * @param $centerX
		 * @return int
		 */
		public function centerX( $centerX = null ) {
			if ( isset( $centerX ) ) {
				$this->centerX = $centerX;
			}
			return $this->centerX;
		}

		/**
		 * @param $centerY
		 * @return int
		 */
		public function centerY( $centerY = null ) {
			if ( isset( $centerY ) ) {
				$this->centerY = $centerY;
			}
			return $this->centerY;
		}

		/**
		 * @param $radius
		 * @return int
		 */
		public function radius( $radius = null ) {
			if ( isset( $radius ) ) {
				$this->radius = $radius;
			}
			return $this->radius;
		}

		public function save() {
			//ToDo: Check rights
			if(!isset($this->idGalaxy)){
				$this->DB()->exec('INSERT INTO ' . $this::GALAXIES_TABLE . ' (idUniverse) VALUES (?)',
					$this->idUniverse()
				);
			} else {
				$this->DB()->exec('UPDATE ' . $this::GALAXIES_TABLE . ' SET emrLevel = ? WHERE idGalaxy = ?',
					$this->emrLevel(),
					$this->idGalaxy()
				);
			}
		}

	}