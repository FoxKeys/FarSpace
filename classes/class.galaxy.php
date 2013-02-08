<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 04.02.2013 14:25
	 */

	class galaxy extends DB {
		const GALAXIES_TABLE = 'galaxies';
		const CANT_CREATE_MORE = 'You can\'t create more galaxies.';

		public function save() {
			if ( !$this->propIsSet( 'idGalaxy' ) ) {
				$currentUser = game::auth()->currentUser();
				if ( $currentUser->galaxyCreateLimit() > 0 ) {
					$this->DB()->exec(
						'INSERT INTO ' . $this::GALAXIES_TABLE . ' ( idUniverse, name, description, centerX, centerY, radius ) VALUES (?, ?, ?, ?, ?, ?)',
						$this->idUniverse(),
						$this->name(),
						$this->description(),
						$this->centerX(),
						$this->centerY(),
						$this->radius()
					);
					$this->set( 'idGalaxy', $this->DB()->lastInsertId() );
					$currentUser->galaxyCreateLimit( $currentUser->galaxyCreateLimit() - 1 );
					$currentUser->save();
				} else {
					throw new Exception( self::CANT_CREATE_MORE );
				}
			} else { //ToDo: Check rights
				throw new Exception( sprintf( fConst::E_PARTIALLY_IMPLEMENTED, __METHOD__ ) );
				/*$this->DB()->exec(
					'UPDATE ' . $this::GALAXIES_TABLE . ' SET emrLevel = ? WHERE idGalaxy = ?',
					$this->emrLevel(),
					$this->idGalaxy()
				);*/
			}
		}

		public function load( $idUniverse ) {
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}

		/**
		 * @return int
		 */
		public function idGalaxy( ) {
			return $this->get( __METHOD__ );
		}

		/**
		 * @param int $idUniverse
		 * @return int
		 */
		public function idUniverse( $idUniverse = null ) {
			return call_user_func_array( array( $this, 'getSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * @param $centerX
		 * @return int
		 */
		public function centerX( $centerX = null ) {
			return call_user_func_array( array( $this, 'getSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * @param $centerY
		 * @return int
		 */
		public function centerY( $centerY = null ) {
			return call_user_func_array( array( $this, 'getSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * @param $radius
		 * @return int
		 */
		public function radius( $radius = null ) {
			return call_user_func_array( array( $this, 'getSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * @param string $name
		 * @return string
		 */
		public function name( $name = null ) {
			return call_user_func_array( array( $this, 'getSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
		
		/**
		 * @param string $description
		 * @return string
		 */
		public function description( $description = null ) {
			return call_user_func_array( array( $this, 'getSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

	}