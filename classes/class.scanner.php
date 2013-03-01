<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 23.02.2013 14:45
	 */

	class scanner {
		const TABLE_NAME = 'scanner';
		const TABLE_NAME_STATIC_MAP = 'static_map';

		/**
		 * @param galaxy $galaxy
		 */
		public function processScanPhase( $galaxy ) {
			$tSigMod = 0;//ToDo
			echo rules::$maxScanPwr;
			//Clean old data
			game::DB()->exec('DELETE FROM ' . self::TABLE_NAME);
			//Find active planet scanners
			game::DB()->exec('
				INSERT INTO ' . self::TABLE_NAME . ' (idPlayer, x, y, scannerPwr, idPlanet)
				SELECT
					p.idPlayer,
					s.x,
					s.y,
					p.scannerPwr,
					p.idPlanet
				FROM ' . system::TABLE_NAME . ' s INNER JOIN ' . planet::TABLE_NAME . ' p on s.idSystem = p.idSystem
				WHERE p.idPlayer IS NOT NULL AND p.scannerPwr > 0'
			);
			//Find active structures scanners - system.scannerPwr = max(int(tech.scannerPwr * techEff * (2.0 - emrLevel) * opStatus), system.scannerPwr)
			//ToDo
			//Find active fleet scanners
			//ToDo

			//Update static map
			game::DB()->exec('
				INSERT INTO ' . self::TABLE_NAME_STATIC_MAP . '(idPlayer, idSystem, `level` )
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
							FROM    ' . system::TABLE_NAME . ' sys
									CROSS JOIN ' . self::TABLE_NAME . ' scanner
						) t
					)t
					WHERE level > ' . rules::$level1InfoScanPwr . '
					GROUP BY idPlayer, idSystem
				) t
				ON DUPLICATE KEY UPDATE `level` = CASE WHEN level2 > `level` THEN level2 ELSE `level` END'
			);
			# adding systems with buoys (level1InfoScanPwr)
			game::DB()->exec('
				INSERT INTO ' . self::TABLE_NAME_STATIC_MAP . '(idPlayer, idSystem, `level` )
				SELECT	b.idPlayer, b.idSystem, ?
				FROM	' . buoy::TABLE_NAME . ' b
				ON DUPLICATE KEY UPDATE `level` = CASE WHEN ? > `level` THEN ? ELSE `level` END',
				rules::$level1InfoScanPwr,
				rules::$level1InfoScanPwr,
				rules::$level1InfoScanPwr
			);
			# add own systems
			game::DB()->exec('
				INSERT INTO ' . self::TABLE_NAME_STATIC_MAP . '(idPlayer, idSystem, `level` )
				SELECT		p.idPlayer, p.idSystem, ?
				FROM		' . planet::TABLE_NAME . ' p
				WHERE		p.idPlayer IS NOT NULL
				ON DUPLICATE KEY UPDATE `level` = CASE WHEN ? > `level` THEN ? ELSE `level` END',
				rules::$partnerScanPwr,
				rules::$partnerScanPwr,
				rules::$partnerScanPwr
			);

			//Update dynamic map
			//ToDo
		}

		//ToDo: mapForgetScanPwr

		public function getStaticMap( $idPlayer ) {
			//ToDo level3InfoScanPwr - result.owner = obj.owner ?
			$result = array();
			$systems = game::DB()->select('
				SELECT	sm.level,
						sm.idSystem,
						s.x,
						s.y,
						s.signature,
						s.idStarClass,
						CASE WHEN sm.level >= ? THEN s.name ELSE NULL END as name,
						CASE WHEN sm.level >= ? THEN s.combatCounter ELSE NULL END as combatCounter
				FROM	' . self::TABLE_NAME_STATIC_MAP . ' sm INNER JOIN ' . system::TABLE_NAME . ' s ON sm.idSystem = s.idSystem
				WHERE	sm.idPlayer = ?
						AND sm.level >= ?',
				rules::$level2InfoScanPwr,
				rules::$level2InfoScanPwr,
				$idPlayer,
				rules::$level1InfoScanPwr
			);
			foreach($systems as $system){
				$result['systems'][$system['idSystem']] = $system;
			}
			$planets = game::DB()->select('
				SELECT
				    level,
				    idSystem,
				    idPlanet,
				    signature,
				    idPlanetType,
				    CASE WHEN level >= :level2InfoScanPwr THEN plDiameter ELSE NULL END as plDiameter,
				    CASE WHEN level >= :level2InfoScanPwr THEN CASE WHEN idPlanetType = "G" THEN NULL ELSE plMin END ELSE NULL END as plMin,
				    CASE WHEN level >= :level2InfoScanPwr THEN plBio ELSE NULL END as plBio,
				    CASE WHEN level >= :level2InfoScanPwr THEN plEn ELSE NULL END as plEn,
				    CASE WHEN level >= :level2InfoScanPwr THEN plSlots ELSE NULL END as plSlots,
				    CASE WHEN level >= :level2InfoScanPwr THEN idStratRes ELSE NULL END as idStratRes,
				    CASE WHEN level >= :level2InfoScanPwr THEN plMaxSlots ELSE NULL END as plMaxSlots,
				    CASE WHEN level >= :level3InfoScanPwr THEN name ELSE NULL END as name,
				    CASE WHEN level >= :level3InfoScanPwr THEN storPop ELSE NULL END as storPop,
				    CASE WHEN level >= :level3InfoScanPwr THEN idPlayer ELSE NULL END as idPlayer,
				    CASE WHEN level >= :level4InfoScanPwr THEN CASE WHEN refuelInc > 0 THEN 1 ELSE 0 END ELSE NULL END as hasRefuel,
					CASE WHEN level >= :level4InfoScanPwr THEN shield ELSE NULL END as shield,
					CASE WHEN level >= :level4InfoScanPwr THEN -1 ELSE NULL END as prevShield,
					CASE WHEN level >= :level4InfoScanPwr THEN -1 ELSE NULL END as maxShield
				FROM
					(
						SELECT	(sm.level * p.signature / s.signature) as level,
								s.idSystem,
								p.idPlanet,
								p.signature,
								-- p.orbit,
								p.idPlanetType,
								p.plDiameter,
								p.plMin,
								p.plBio,
								p.plEn,
								p.plSlots,
								p.idStratRes,
								p.plMaxSlots,
								p.name,
								p.storPop,
								p.idPlayer,
								(SELECT MAX(refuelInc * techEff( pt.level ) /* ToDo opStatus*/) FROM ' . structure::TABLE_NAME . ' s INNER JOIN ' . playerTech::TABLE_NAME . ' pt ON s.idTech = pt.idTech INNER JOIN ' . tech::TABLE_NAME . ' t ON s.idTech = t.idTech WHERE s.idPlanet = p.idPlanet AND pt.idPlayer = p.idPlayer) as refuelInc,
								p.shield
						FROM	' . self::TABLE_NAME_STATIC_MAP . ' sm
								INNER JOIN ' . planet::TABLE_NAME . ' p ON sm.idSystem = p.idSystem
								INNER JOIN ' . system::TABLE_NAME . ' s ON sm.idSystem = s.idSystem
						WHERE	sm.idPlayer = :idPlayer
								AND (sm.level * p.signature / s.signature) >= :level1InfoScanPwr
						ORDER BY	s.idSystem, p.plEn
					) t',
				array(
					'idPlayer' => $idPlayer,
					'level1InfoScanPwr' => rules::$level1InfoScanPwr,
					'level2InfoScanPwr' => rules::$level2InfoScanPwr,
					'level3InfoScanPwr' => rules::$level3InfoScanPwr,
					'level4InfoScanPwr' => rules::$level4InfoScanPwr
				)
			);
			//ToDo
			//if scanPwr >= Rules.level4InfoScanPwr:
			//SELECT srtuctures

			//ToDo level5

			foreach ( $planets as $planet ) {
				$result['systems'][$planet['idSystem']]['planets'][] = $planet;
			}

			$scanners = game::DB()->select('
				SELECT	* FROM ' . self::TABLE_NAME . ' s
				WHERE	s.idPlayer = ?',
				$idPlayer	//ToDo - add pacts
			);
			$result['scanners'] = $scanners;
			return $result;
/*
			if scanPwr >= Rules.level4InfoScanPwr:
				result.fleets = obj.fleets
				for fleetID in obj.fleets:
					fleet = tran.db[fleetID]
					if fleet.owner == player:
						continue
					newPwr = scanPwr * fleet.signature / obj.signature
					results.extend(self.cmd(fleet).getScanInfos(tran, fleet, newPwr, player))
				result.hasmines = 0 #no
				if len(obj.minefield) > 0:
					result.hasmines = 1 #yes
				result.minefield = self.getMines(obj,player.oid) #only shows mines you own
				if len(obj.minefield) > 1 or (len(obj.minefield) == 1 and len(result.minefield) == 0):
					result.hasmines = 2 #yes, and some aren't my mines
			return results*/
		}
	}