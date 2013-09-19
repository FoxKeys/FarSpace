<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 8:46
	 */
	class planetType extends activeRecord {
		const TABLE_NAME = 'planet_types';
		/**
		 * @var string idPlanetType
		 */
		private $idPlanetType = null;

		/**
		 * @param null|string $idPlanetType
		 * @return null|string
		 */
		public function idPlanetType( $idPlanetType = null ) {
			if ( isset( $idPlanetType ) ) {
				$this->idPlanetType = $idPlanetType;
			}
			return $this->idPlanetType;
		}

		/**
		 * @param string $name
		 * @return string
		 */
		public function namePlanetType( $name = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * alias for namePlanetType
		 * @return string
		 */
		public function name(){
			return $this->namePlanetType();
		}
	}