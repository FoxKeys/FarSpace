<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 15.09.2013 22:16
	 */

	class structures extends activeRecordCollection {
		/**
		 * @param int $idPlayer
		 * @param null|int $idPlanet
		 * @return array
		 */
		protected function selectData( $idPlayer, $idPlanet = null ) {
			return game::DB()->select('
				SELECT
						sms.*
				FROM	star_map_structures sms
				WHERE	(sms.smIdPlayer = :idPlayer)
						AND (:idPlanet IS NULL OR sms.idPlanet=:idPlanet)
						AND (sms.level >= :level2InfoScanPwr)',
				array( ':idPlanet' => $idPlanet ),
				array( ':idPlayer' => $idPlayer ),
				//array( ':level1InfoScanPwr' => rules::$level1InfoScanPwr ),
				array( ':level2InfoScanPwr' => rules::$level2InfoScanPwr )
				//array( ':level3InfoScanPwr' => rules::$level3InfoScanPwr )
			);
		}

		/**
		 * @param array $data
		 * @return structure
		 */
		protected function createObject( $data ) {
			return structure::createFromArray( $data );
		}

		/**
		 * @param int $idPlayer
		 * @param int|null $idPlanet
		 * @return structure[]
		 */
		public function select( $idPlayer, $idPlanet = null ) {
			return parent::select( $idPlayer, $idPlanet );
		}

	}