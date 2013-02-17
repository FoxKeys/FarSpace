<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 8:46
	 */
	class planetType extends activeRecord {
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
	}