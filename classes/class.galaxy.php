<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 04.02.2013 14:25
	 */

	class galaxy extends activeRecord {
		const TABLE_NAME = 'galaxies';

		/**
		 * @throws Exception
		 * @return galaxy
		 */
		public function save() {
			if ( !$this->fieldIsSet( 'idGalaxy' ) ) {
				game::DB()->exec(
					'INSERT INTO ' . $this::TABLE_NAME . ' ( idUniverse, idUser, name, description, centerX, centerY, radius, emrLevel ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
					$this->idUniverse(),
					$this->idUser(),
					$this->name(),
					$this->description(),
					$this->centerX(),
					$this->centerY(),
					$this->radius(),
					$this->emrLevel()
				);
				$this->idGalaxy( game::DB()->lastInsertId() );
			} else {
				game::DB()->exec('
					UPDATE ' . $this::TABLE_NAME . '
					SET name = :name,
						description = :description,
						emrLevel = :emrLevel,
						timeEnabled = :timeEnabled
					WHERE	idGalaxy = :idGalaxy',
					array( ':idGalaxy' => $this->idGalaxy() ),
					array( ':name' => $this->name() ),
					array( ':description' => $this->description() ),
					array( ':emrLevel' => $this->emrLevel() ),
					array( ':timeEnabled' => $this->timeEnabled() )
				);
			}
			return $this;
		}

		/**
		 * @param int $idGalaxy
		 * @throws Exception
		 * @return galaxy
		 */
		public function load( $idGalaxy ) {
			$data = game::DB()->selectRow( 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE idGalaxy = ?', $idGalaxy );
			if ( empty( $data ) ) {
				throw new Exception( sprintf( fConst::E_NOT_FOUND, __CLASS__, $idGalaxy ) );
			}
			return $this->assignArray( $data );
		}

		/**
		 * Type Hint wrapper
		 * @param int $idGalaxy
		 * @return galaxy
		 */
		public static function createFromDB( $idGalaxy ) {
			return parent::createFromDB( $idGalaxy );
		}

		/**
		 * @return int[]
		 */
		public function freeStartingPositions() {
			$planets = game::DB()->select(
				'SELECT p.idPlanet FROM ' . planet::TABLE_NAME . ' p INNER JOIN ' . system::TABLE_NAME . ' s ON p.idSystem = s.IdSystem WHERE s.idGalaxy = ? AND p.plStarting <> 0 AND p.idPlayer IS NULL',
				$this->idGalaxy()
			);
			$result = array();
			foreach ( $planets as $planet ) {
				$result[] = $planet['idPlanet'];
			}
			return $result;
		}

		public function enableTime( $force = false, $deleteSP = false, $enable = true ) {
			log::debug( 'IGalaxy', 'Checking for time...' );
			if ( !$force ) {
				if ( $this->timeEnabled() ) {
					return $this;
				}
				$canRun = false;
				# there must be at least 1/2 positions already assigned
				#if len(obj.startingPos) <= obj.numOfStartPos / 2 and obj.creationTime < time.time() - 2 * 24 * 3600:
				#   log.debug("Half galaxy populated", len(obj.startingPos), obj.numOfStartPos)
				#   canRun = 1
				# at least two days must pass from creation
				if ( count( $this->freeStartingPositions() ) == 0 ) {
					log::debug( "All positions taken, starting galaxy" );
					$canRun = true;
				}
				#			if obj.creationTime < time.time() - 2 * 24 * 3600:
				#				log.debug("Two days passed", obj.creationTime, time.time() - 2 * 24 * 3600)
				#				canRun = 1
				if ( !$canRun ) {
					return $this;
				}
			}
			# ok, enable time
			log::message( sprintf( 'Galaxy - enabling time for %d', $this->idGalaxy() ) );
			$this->timeEnabled( $enable );
			# close galaxy
			if ( $deleteSP ) {
				game::DB()->exec(
					' UPDATE ' . planet::TABLE_NAME . ' p, ' . system::TABLE_NAME . ' s SET plStarting = 0 WHERE p.idSystem = s.IdSystem AND s.idGalaxy = ? AND p.plStarting <> 0 AND p.idPlayer IS NULL',
					$this->idGalaxy()
				);
			}
			# load new galaxy
			# TODO
			# enable time for players
			$idPlayers = game::DB()->select('

			', $this->idGalaxy());
			foreach ( $idPlayers as $idPlayer ) {
				$player = player::createFromDB( $idPlayer );
				$player->lastLogin( date( 'Y-m-d H:i:s' ) );
				$player->save();
				if ( $enable ) {
					utils::sendMessage( $player, fConst::MSG_ENABLED_TIME, $player->idPlayer(), null );
				} else {
					utils::sendMessage( $player, fConst::MSG_DISABLED_TIME, $player->idPlayer(), null );
				}
			}
			return $this;
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
		public function idUniverse( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
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
		 * @param float $value
		 * @return float
		 */
		public function centerX( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param float $value
		 * @return float
		 */
		public function centerY( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param float $value
		 * @return float
		 */
		public function radius( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param string $value
		 * @return string
		 */
		public function name( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
		
		/**
		 * Type Hint wrapper
		 * @param string $value
		 * @return string
		 */
		public function description( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param float $value
		 * @return float
		 */
		public function emrLevel( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param bool $value
		 * @return bool
		 */
		public function timeEnabled( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

	}