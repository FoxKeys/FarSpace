<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 6:06
	 */

	class galaxyTemplate extends DB {
		const TABLE_NAME = 'galaxyTemplates';
		const DENSITY_TABLE_NAME = 'galaxyTemplatesDensity';
		/**
		 * @var int
		 */
		private $centerX = 500;
		/**
		 * @var int
		 */
		private $centerY = 500;
		/**
		 * @var int
		 */
		private $radius = 500;
		/**
		 * @var int
		 */
		private $galaxyMinR = 75;
		/**
		 * @var array
		 */
		private $galaxyDensity = array(0 => 3);

		/**
		 * @param int $idGalaxyTemplate
		 * @throws Exception
		 * @return \galaxyTemplate
		 */
		public function load( $idGalaxyTemplate ) {
			$data = $this->DB()->selectRow( 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE idGalaxyTemplate = ?', $idGalaxyTemplate );
			if ( empty( $data ) ) {
				throw new Exception( sprintf( fConst::E_NOT_FOUND, __CLASS__, $idGalaxyTemplate ) );
			}
			$density = $this->DB()->select( 'SELECT * FROM ' . self::DENSITY_TABLE_NAME . ' WHERE idGalaxyTemplate = ?', $idGalaxyTemplate );
			if ( empty( $density ) ) {
				throw new Exception( sprintf( 'Density data not found for galaxy template with id="%d"', $idGalaxyTemplate ) );
			}
			$this->assignArray( $data );
			$this->galaxyDensity( $density );
		}

		/**
		 * @param null|int $centerX
		 * @return int
		 */
		public function centerX( $centerX = null ) {
			if ( isset( $centerX ) ) {
				$this->centerX = $centerX;
			}
			return $this->centerX;
		}

		/**
		 * @param null|int $centerY
		 * @return int
		 */
		public function centerY( $centerY = null ) {
			if ( isset( $centerY ) ) {
				$this->centerY = $centerY;
			}
			return $this->centerY;
		}
		
		/**
		 * @param null|int $radius
		 * @return int
		 */
		public function radius( $radius = null ) {
			if ( isset( $radius ) ) {
				$this->radius = $radius;
			}
			return $this->radius;
		}

		/**
		 * @param null|int $galaxyMinR
		 * @return int
		 */
		public function galaxyMinR( $galaxyMinR = null ) {
			if ( isset( $galaxyMinR ) ) {
				$this->galaxyMinR = $galaxyMinR;
			}
			return $this->galaxyMinR;
		}

		/**
		 * @param null|array $galaxyDensity
		 * @return array
		 */
		public function galaxyDensity( $galaxyDensity = null ) {
			if ( isset( $galaxyDensity ) ) {
				$this->galaxyDensity = $galaxyDensity;
			}
			return $this->galaxyDensity;
		}
	}