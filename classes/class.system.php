<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 7:15
	 */
	class system extends activeRecord {
		const NOT_OWNER = 'You can\'t add systems into galaxy with id="%s" since you are not the owner.';
		const TABLE_NAME = 'systems';

		/**
		 * @throws Exception
		 * @return system
		 */
		public function save() {
			if ( !$this->fieldIsSet( 'idSystem' ) ) {
				game::DB()->exec(
					'INSERT INTO ' . $this::TABLE_NAME . ' ( idGalaxy, x, y, idStarClass, starSubclass ) VALUES (?, ?, ?, ?, ?)',
					$this->idGalaxy(),
					$this->x(),
					$this->y(),
					$this->idStarClass(),
					$this->starSubclass()
				);
				$this->idSystem( game::DB()->lastInsertId() );
			} else {
				throw new Exception( sprintf( fConst::E_PARTIALLY_IMPLEMENTED, __METHOD__ ) );
				/*game::DB()->exec(
					'UPDATE ' . $this::GALAXIES_TABLE . ' SET emrLevel = ? WHERE idGalaxy = ?',
					$this->emrLevel(),
					$this->idGalaxy()
				);*/
			}
			return $this;
		}

		/**
		 *
		 * @param int $idSystem
		 * @throws Exception
		 * @return system
		 */
		public function load( $idSystem ) {
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idSystem( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idStarClass( $value = null ) {
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
		public function starSubclass( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function x( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function y( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		public function getScanInfos( $scanPwr, $player ) {
			//
		}
	}