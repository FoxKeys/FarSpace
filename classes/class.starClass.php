<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 10:33
	 */

	class starClass extends DB {
		/**
		 * @var string
		 */
		private $idStarClass = '';
		/**
		 * @var string
		 */
		private $starType = '';
		/**
		 * @var string
		 */
		private $starClass = '';
		/**
		 * @var int
		 */
		private $subclassChanceMin = 0;
		/**
		 * @var int
		 */
		private $subclassChanceMax = 0;

		/**
		 * @param $idStarClass
		 * @return string
		 */
		public function idStarClass( $idStarClass = null ) {
			if ( isset( $idStarClass ) ) {
				$this->idStarClass = $idStarClass;
			}
			return $this->idStarClass;
		}

		/**
		 * @param $starType
		 * @return string
		 */
		public function starType( $starType = null ) {
			if ( isset( $starType ) ) {
				$this->starType = $starType;
			}
			return $this->starType;
		}
		
		/**
		 * @param $starClass
		 * @return string
		 */
		public function starClass( $starClass = null ) {
			if ( isset( $starClass ) ) {
				$this->starClass = $starClass;
			}
			return $this->starClass;
		}
		
		/**
		 * @param $subclassChanceMin
		 * @return int
		 */
		public function subclassChanceMin( $subclassChanceMin = null ) {
			if ( isset( $subclassChanceMin ) ) {
				$this->subclassChanceMin = $subclassChanceMin;
			}
			return $this->subclassChanceMin;
		}

		/**
		 * @param $subclassChanceMax
		 * @return int
		 */
		public function subclassChanceMax( $subclassChanceMax = null ) {
			if ( isset( $subclassChanceMax ) ) {
				$this->subclassChanceMax = $subclassChanceMax;
			}
			return $this->subclassChanceMax;
		}

	}