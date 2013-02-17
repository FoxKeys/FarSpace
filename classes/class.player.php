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
			if ( !$this->fieldIsSet( 'idPlanet' ) ) {
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
					'INSERT INTO ' . self::TABLE_PLAYERS_TECHS . ' ( idPlayer, idTech, level ) SELECT ?, idTech, ? FROM '.tech::TABLE_NAME.' WHERE isStarting <> 0',
					$player->idPlayer(),
					(rules::$techBaseImprovement + rules::$techMaxImprovement) / 2
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
/*
				# fleet
				# add basic ships designs
				tempTechs = [Tech.FTLENG1, Tech.SCOCKPIT1, Tech.SCANNERMOD1, Tech.CANNON1,
					Tech.CONBOMB1, Tech.SMALLHULL1, Tech.MEDIUMHULL2, Tech.COLONYMOD2]
				for techID in tempTechs:
					player.techs[techID] = 1
				dummy, scoutID = self.cmdPool[T_PLAYER].addShipDesign(tran, player, "Scout", Tech.SMALLHULL1,
					{Tech.FTLENG1:3, Tech.SCOCKPIT1:1, Tech.SCANNERMOD1:1})
				dummy, fighterID = self.cmdPool[T_PLAYER].addShipDesign(tran, player, "Fighter", Tech.SMALLHULL1,
					{Tech.FTLENG1:3, Tech.SCOCKPIT1:1, Tech.CANNON1:1})
				self.cmdPool[T_PLAYER].addShipDesign(tran, player, "Bomber", Tech.SMALLHULL1,
					{Tech.FTLENG1:3, Tech.SCOCKPIT1:1, Tech.CONBOMB1:1})
				dummy, colonyID = self.cmdPool[T_PLAYER].addShipDesign(tran, player, "Colony Ship", Tech.MEDIUMHULL2,
					{Tech.FTLENG1:4, Tech.SCOCKPIT1:1, Tech.COLONYMOD2:1})
				for techID in tempTechs:
					del player.techs[techID]
				# add small fleet
				log.debug('Creating fleet')
				system = self.db[planet.compOf]
				fleet = self.cmdPool[T_FLEET].new(T_FLEET)
				self.db.create(fleet)
				log.debug('Creating fleet - created', fleet.oid)
				self.cmdPool[T_FLEET].create(tran, fleet, system, playerID)
				log.debug('Creating fleet - addShips')
				self.cmdPool[T_FLEET].addNewShip(tran, fleet, scoutID)
				self.cmdPool[T_FLEET].addNewShip(tran, fleet, scoutID)
				self.cmdPool[T_FLEET].addNewShip(tran, fleet, fighterID)
				self.cmdPool[T_FLEET].addNewShip(tran, fleet, fighterID)
				self.cmdPool[T_FLEET].addNewShip(tran, fleet, colonyID)
				# add player to universe
				log.debug('Adding player to universe')
				universe.players.append(playerID)
				# initial scan
				system = self.db[planet.compOf]
				log.debug('Processing scan phase')
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