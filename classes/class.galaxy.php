<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 04.02.2013 14:25
	 */

	class galaxy extends DB {
		private $forums = array( "PUBLIC" => 112, "NEWS" => 112 );
		/**
		 * @var int
		 */
		private $centerX;
		private $centerY;
		private $radius;
		private $centerWeight;
		private $systems;
		private $startingPos;
		private $numOfStartPos;
		private $timeEnabled;
		private $timeStopped;
		private $creationTime;
		private $imperator;
		private $description;
		private $emrLevel;
		private $emrTrend;
		private $emrTime;

		public function init( $obj ) {
			#
			$this->centerX = 0.0;
			$this->centerY = 0.0;
			$this->radius = 0.0;
			$this->centerWeight = 250.0;
			$this->systems = array();
			$this->startingPos = array();
			$this->numOfStartPos = 0;
			$this->timeEnabled = 0; # TODO change to 0
			$this->timeStopped = 0;
			$this->creationTime = 0.0;
			$this->imperator = fConst::OID_NONE;
			$this->description = "";
			# electromagnetic radiation
			$this->emrLevel = 1.0;
			$this->emrTrend = 1.0;
			$this->emrTime = 0;
		}

		private function update( $tran, $obj ) {
			throw new Exception( sprintf( fConst::E_NOT_TRANSLATED, __METHOD__ ) );
		}

		private function getReferences( $tran, $obj ) {
			return $obj->systems;
		}

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

	}