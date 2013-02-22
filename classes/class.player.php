<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 3:33
	 */

	class player extends activeRecord {
		const TABLE_NAME = 'players';
		const TABLE_PLAYERS_TECHS = 'players_techs';
		private static $defaultStructures = array( 'PWRPLANTNUK1', 'FARM1', 'FARM1', 'FARM1', 'ANCFACTORY', 'ANCFACTORY', 'ANCRESLAB', 'REPAIR1' );

		public function load( $idUniverse ) {
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}

		public function save() {
			if ( !$this->fieldIsSet( 'idPlayer' ) ) {
				game::DB()->exec(
					'INSERT INTO ' . $this::TABLE_NAME . ' ( idUser, idGalaxy ) VALUES (?, ?)',
					$this->idUser(),
					$this->idGalaxy()
				);
				$this->idPlayer( game::DB()->lastInsertId() );
			} else {
				throw new Exception( sprintf( fConst::E_PARTIALLY_IMPLEMENTED, __METHOD__ ) );
				/*game::DB()->exec(
					'UPDATE ' . $this::TABLE_NAME . ' SET idSystem = ?, idPlanetType = ?, plDiameter = ?, plEn = ?, plMin = ?, plEnv = ?, plSlots = ?, plMaxSlots = ?, plStarting = ?, idStratRes = ?, idDisease = ? WHERE idPlanet = ?',
					$this->idSystem(),
					$this->idPlanetType(),
					$this->plDiameter(),
					$this->plEn(),
					$this->plMin(),
					$this->plEnv(),
					$this->plSlots(),
					$this->plMaxSlots(),
					$this->plStarting(),
					$this->idStratRes(),
					$this->idDisease(),
					$this->idPlanet()
				);*/
			}
			return $this;
		}

		public function getScannerMap() {
			/*scanLevels = {}
			# full map for the admin
			if obj.oid == OID_ADMIN:
				universe = tran.db[OID_UNIVERSE]
				for galaxyID in universe.galaxies:
					galaxy = tran.db[galaxyID]
					for systemID in galaxy.systems:
						system = tran.db[systemID]
						obj.staticMap[systemID] = 111111
						for planetID in system.planets:
							obj.staticMap[planetID] = 111111
			# adding systems with buoys
			for objID in obj.buoys:
				scanLevels[objID] = Rules.level1InfoScanPwr
			# fixing system scan level for mine fields
			systems = {}
			for planetID in obj.planets:
				systems[tran.db[planetID].compOf] = None
			for systemID in systems.keys():
				scanLevels[systemID] = Rules.partnerScanPwr
			# player's map
			for objID in obj.staticMap:
				scanLevels[objID] = max(scanLevels.get(objID, 0), obj.staticMap[objID])
			for objID in obj.dynamicMap:
				scanLevels[objID] = max(scanLevels.get(objID, 0), obj.dynamicMap[objID])
			# parties' map
			for partnerID in obj.diplomacyRels:
				if self.cmd(obj).isPactActive(tran, obj, partnerID, PACT_SHARE_SCANNER):
					# load partner's map
					partner = tran.db[partnerID]
					for objID in partner.staticMap:
						scanLevels[objID] = max(scanLevels.get(objID, 0), partner.staticMap[objID])
					for objID in partner.dynamicMap:
						scanLevels[objID] = max(scanLevels.get(objID, 0), partner.dynamicMap[objID])
					# partner's fleets and planets
					for objID in partner.fleets:
						scanLevels[objID] = Rules.partnerScanPwr
					for objID in partner.planets:
						scanLevels[objID] = Rules.partnerScanPwr

			# create map
			map = dict()
			for objID, level in scanLevels.iteritems():
				tmpObj = tran.db.get(objID, None)
				if not tmpObj:
					continue
				# add movement validation data
				if tmpObj.type in (T_SYSTEM,T_WORMHOLE) and objID not in obj.validSystems:
					obj.validSystems.append(objID)
				for info in self.cmd(tmpObj).getScanInfos(tran, tmpObj, level, obj):
					if (info.oid not in map) or (info.scanPwr > map[info.oid].scanPwr):
						map[info.oid] = info

			return map*/
		}

		public static function createNewPlayer( $idUser, $idGalaxy ) {
			game::DB()->beginTransaction();
			try{
				log::debug( sprintf( 'Creating new player for user %d', $idUser ) );
				$galaxy = galaxy::createFromDB( $idGalaxy );
				$startingPositions = $galaxy->freeStartingPositions();
				if ( empty( $startingPositions ) ) {
					throw new Exception( 'No such starting position.' );
				}
				# create player
				$player = new player( );
				$player->idUser( $idUser );
				//player.timeEnabled = galaxy.timeEnabled	//ToDo - do we need this?
				$player->idGalaxy( $idGalaxy );
				# TODO tweak more player's attrs
				$player->save();
				log::debug( sprintf( 'Player %d created', $player->idPlayer() ) );

				# Grant starting technologies (at medium improvement)
				game::DB()->exec(
					'INSERT INTO ' . self::TABLE_PLAYERS_TECHS . ' ( idPlayer, idTech, level, available ) SELECT ?, idTech, startingLevel, startingAvailable FROM '.tech::TABLE_NAME.' WHERE startingLevel > 0',
					$player->idPlayer()
				);

				# select starting point randomly
				log::debug( 'Selecting starting point' );
				$idPlanet = utils::randomElement( $startingPositions );
				log::debug( sprintf( 'Starting point %d', $idPlanet ) );
				$planet = planet::createFromDB( $idPlanet );
				$planet->idPlayer( $player->idPlayer() );
				$planet->storPop( rules::$startingPopulation );
				$planet->storBio( rules::$startingBio );
				$planet->storEn( rules::$startingEn );
				$planet->scannerPwr( rules::$startingScannerPwr );
				$planet->morale( rules::$maxMorale );
				# TODO tweak more planet's attrs
				$planet->save();

				//Build structures
				if ( count( self::$defaultStructures ) > ($planet->plSlots() - $planet->plStructures()) ) {
					throw new Exception( sprintf( 'Planet %d have no free slots to build %d structures', $planet->idPlanet(), count( self::$defaultStructures ) ) );
				}
				$slot = $planet->plStructures();
				foreach ( self::$defaultStructures as $symbol ) {
					try{
						$tech = playerTech::createFromSymbol( $player->idPlayer(), $symbol );
						$level = $tech->level();
					} catch ( ePlayerTechNotFound $e ) {
						$tech = tech::createFromSymbol( $symbol );
						$level = rules::$techBaseImprovement;
					}
					$structure = new structure($tech->idTech(), $planet->idPlanet(), $slot, $player->idPlayer(), (int)( $tech->maxHP() * rules::$techImprEff[$level] ), true, false );
					$structure->save();
					$slot++;
				}

				# fleet
				# add basic ships designs
				$SMALLHULL1 = playerTech::createFromSymbol( $player->idPlayer(), 'SMALLHULL1' );
				$FTLENG1 = playerTech::createFromSymbol( $player->idPlayer(), 'FTLENG1' );
				$SCOCKPIT1 = playerTech::createFromSymbol( $player->idPlayer(), 'SCOCKPIT1' );
				$SCANNERMOD1 = playerTech::createFromSymbol( $player->idPlayer(), 'SCANNERMOD1' );
				$CANNON1 = playerTech::createFromSymbol( $player->idPlayer(), 'CANNON1' );
				$CONBOMB1 = playerTech::createFromSymbol( $player->idPlayer(), 'CONBOMB1' );
				$MEDIUMHULL2 = playerTech::createFromSymbol( $player->idPlayer(), 'MEDIUMHULL2' );
				$COLONYMOD2 = playerTech::createFromSymbol( $player->idPlayer(), 'COLONYMOD2' );

				$scoutDesign = shipDesign::createNew( $player->idPlayer(), 'Scout', $SMALLHULL1->idPlayerTech(), $SCOCKPIT1->idPlayerTech(), array( $FTLENG1->idPlayerTech() => 3, $SCANNERMOD1->idPlayerTech() => 1 ) )->save();
				$fighterDesign = shipDesign::createNew( $player->idPlayer(), 'Fighter', $SMALLHULL1->idPlayerTech(), $SCOCKPIT1->idPlayerTech(), array( $FTLENG1->idPlayerTech() => 3, $CANNON1->idPlayerTech() => 1 ) )->save();
				$bomberDesign = shipDesign::createNew( $player->idPlayer(), 'Bomber', $SMALLHULL1->idPlayerTech(), $SCOCKPIT1->idPlayerTech(), array( $FTLENG1->idPlayerTech() => 3, $CONBOMB1->idPlayerTech() => 1 ) )->save();
				$colonyDesign = shipDesign::createNew( $player->idPlayer(), 'Colony Ship', $MEDIUMHULL2->idPlayerTech(), $SCOCKPIT1->idPlayerTech(), array( $FTLENG1->idPlayerTech() => 4, $COLONYMOD2->idPlayerTech() => 1 ) )->save();

				# add small fleet
				log::debug( 'Creating fleet' );
				$fleet = fleet::createNew( $player->idPlayer(), $planet->idSystem(), 0 )->save();
				log::debug( sprintf( 'Creating fleet - created %d', $fleet->idFleet() ) );

				log::debug( 'Creating fleet - addShips' );
				$fleet->addShip( ship::createNew( $fleet->idFleet(), $scoutDesign->idShipDesign(), $scoutDesign->HP(), $scoutDesign->shield(), 0 )->save(), $scoutDesign->storEn() );
				$fleet->addShip( ship::createNew( $fleet->idFleet(), $scoutDesign->idShipDesign(), $scoutDesign->HP(), $scoutDesign->shield(), 0 )->save(), $scoutDesign->storEn() );
				$fleet->addShip( ship::createNew( $fleet->idFleet(), $fighterDesign->idShipDesign(), $fighterDesign->HP(), $fighterDesign->shield(), 0 )->save(), $fighterDesign->storEn() );
				$fleet->addShip( ship::createNew( $fleet->idFleet(), $fighterDesign->idShipDesign(), $fighterDesign->HP(), $fighterDesign->shield(), 0 )->save(), $fighterDesign->storEn() );
				$fleet->addShip( ship::createNew( $fleet->idFleet(), $colonyDesign->idShipDesign(), $colonyDesign->HP(), $colonyDesign->shield(), 0 )->save(), $colonyDesign->storEn() );
				$fleet->save();

				# initial scan
				//system = self.db[planet.compOf]
				//$planet->idSystem()
/*				log::debug( 'Processing scan phase' );
				system.scannerPwrs[playerID] = Rules.startingScannerPwr
				self.cmdPool[T_GALAXY].processSCAN2Phase(tran, galaxy, None)
				# check if galaxy can be "started"
				self.cmdPool[T_GALAXY].enableTime(tran, galaxy)
				# save game info
				self.generateGameInfo()
				return playerID, None*/
				game::DB()->commit();
			} catch ( Exception $e ) {
				game::DB()->rollBack();
				throw $e;
			}
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idUser( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idGalaxy( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idPlayer( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
	}