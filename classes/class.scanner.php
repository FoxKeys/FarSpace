<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 23.02.2013 14:45
	 */

	class scanner {

		/**
		 * @param galaxy $galaxy
		 */
		public function processScanPhase( $galaxy ) {
			$tSigMod = 0;//ToDo
			echo rules::$maxScanPwr;
			game::DB()->exec('
				INSERT INTO static_map(idPlayer, idSystem, `level` )
				SELECT idPlayer, idSystem, level2
				FROM(
					SELECT
						idPlayer,
						idSystem,
						CASE WHEN max(level) > ' . rules::$maxScanPwr . ' THEN ' . rules::$maxScanPwr . ' ELSE max(level) END as level2
					FROM (
						SELECT *, (signature + ' . $tSigMod . ') * scannerPwr * ( 2.0 - ' . $galaxy->emrLevel() . ' ) * 10 / CASE WHEN distance < ' . rules::$minDistance . ' THEN ' . rules::$minDistance . ' ELSE distance END as level
						FROM (
							SELECT
								sys.idSystem,
								SQRT((sys.x - scanner.x) * (sys.x - scanner.x) + (sys.y - scanner.y) * (sys.y - scanner.y)) as distance,
								sys.signature as signature,
								scanner.scannerPwr as scannerPwr,
								scanner.idPlayer
								from    systems sys
										CROSS JOIN (
											SELECT
												s.idSystem,
												s.x,
												s.y,
												max(p.scannerPwr) as scannerPwr,
												p.idPlayer
											FROM systems s INNER JOIN planets p on s.idSystem = p.idSystem
											WHERE p.idPlayer IS NOT NULL AND p.scannerPwr > 0
											GROUP BY s.idSystem
										) scanner
						) t
					)t
					WHERE level > ' . rules::$level1InfoScanPwr . '
					GROUP BY idPlayer, idSystem
				) t
				ON DUPLICATE KEY UPDATE `level` = CASE WHEN level2 > `level` THEN level2 ELSE `level` END'
			);
		}

		//ToDo: mapForgetScanPwr

	}