<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 15.09.2013 15:02
	 */

	class planetTypes extends activeRecordCollection {
		/**
		 * @param null $idPlanetType
		 * @return array
		 */
		protected function selectData( $idPlanetType = null ) {
			return game::DB()->select( 'SELECT * FROM planet_types WHERE (:idPlanetType IS NULL OR idPlanetType = :idPlanetType)', array( ':idPlanetType' => $idPlanetType ) );
		}

		/**
		 * @param array $data
		 * @return planetType
		 */
		protected function createObject( $data ) {
			return planetType::createFromArray( $data );
		}

		/**
		 * @param string $idPlanetType
		 * @return planetType
		 */
		public function get( $idPlanetType ) {
			return parent::get( $idPlanetType );
		}

	}