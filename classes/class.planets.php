<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 14.09.2013 21:24
	 */

	class planets extends activeRecordCollection {
		/**
		 * @param int $idPlayer
		 * @param int|null $idSystem
		 * @return array
		 */
		protected function selectData( $idPlayer, $idSystem = null ) {
			return game::DB()->select('
				SELECT
						smp.idPlanet,
						smp.idPlanetType,
						smp.level,
						CASE WHEN smp.level >= :level2InfoScanPwr THEN smp.plDiameter ELSE null END as plDiameter,
						CASE WHEN smp.level >= :level2InfoScanPwr THEN smp.plBio ELSE null END as plBio,
						CASE WHEN smp.level >= :level2InfoScanPwr THEN CASE WHEN smp.idPlanetType = \'G\' THEN NULL ELSE plMin END ELSE NULL END as plMin,
						CASE WHEN smp.level >= :level2InfoScanPwr THEN smp.plEn ELSE null END as plEn,
						CASE WHEN smp.level >= :level2InfoScanPwr THEN smp.plSlots ELSE null END as plSlots,
						CASE WHEN smp.level >= :level2InfoScanPwr THEN smp.plMaxSlots ELSE null END as plMaxSlots,
						CASE WHEN smp.level >= :level3InfoScanPwr THEN smp.name ELSE null END as name,
						CASE WHEN smp.level >= :level3InfoScanPwr THEN smp.storPop ELSE null END as storPop,
						CASE WHEN smp.idPlayer = :idPlayer THEN morale ELSE null END as morale,
						CASE WHEN smp.idPlayer = :idPlayer THEN storBio ELSE null END as storBio,
						CASE WHEN smp.idPlayer = :idPlayer THEN storEn ELSE null END as storEn
				FROM	star_map_planets smp
				WHERE	(smp.smIdPlayer = :idPlayer)
						AND (:idSystem IS NULL OR smp.idSystem=:idSystem)
						AND (smp.level >= :level1InfoScanPwr)',
				array( ':idSystem' => $idSystem ),
				array( ':idPlayer' => $idPlayer ),
				array( ':level1InfoScanPwr' => rules::$level1InfoScanPwr ),
				array( ':level2InfoScanPwr' => rules::$level2InfoScanPwr ),
				array( ':level3InfoScanPwr' => rules::$level3InfoScanPwr )
			);
		}

		/**
		 * @param array $data
		 * @return planet
		 */
		protected function createObject( $data ) {
			return planet::createFromArray( $data );
		}

		/**
		 * @param int $idPlayer
		 * @param int|null $idSystem
		 * @return planet[]
		 */
		public function select( $idPlayer, $idSystem = null ) {
			return parent::select( $idPlayer, $idSystem );
		}

	}