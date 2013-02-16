<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 3:33
	 */

	class player extends DB {
		public function load( $idUniverse ) {
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}

		public function save() {
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
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
	}