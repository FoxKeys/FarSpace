<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 7:15
	 */
	class system extends DB {
		const NOT_OWNER = 'You can\'t add systems into galaxy with id="%s" since you are not the owner.';
		const SYSTEMS_TABLE = 'systems';

		/**
		 * @throws Exception
		 * @return system
		 */
		public function save() {
			if ( !$this->fieldIsSet( 'idSystem' ) ) {
				$this->DB()->exec(
					'INSERT INTO ' . $this::SYSTEMS_TABLE . ' ( idGalaxy, x, y, idStarClass, starSubclass ) VALUES (?, ?, ?, ?, ?)',
					$this->idGalaxy(),
					$this->x(),
					$this->y(),
					$this->idStarClass(),
					$this->starSubclass()
				);
				$this->idSystem( $this->DB()->lastInsertId() );
			} else {
				throw new Exception( sprintf( fConst::E_PARTIALLY_IMPLEMENTED, __METHOD__ ) );
				/*$this->DB()->exec(
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
		 * @param int $idSystem
		 * @return int
		 */
		public function idSystem( $idSystem = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $idStarClass
		 * @return int
		 */
		public function idStarClass( $idStarClass = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $idGalaxy
		 * @return int
		 */
		public function idGalaxy( $idGalaxy = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $starSubclass
		 * @return int
		 */
		public function starSubclass( $starSubclass = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $x
		 * @return int
		 */
		public function x( $x = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $y
		 * @return int
		 */
		public function y( $y = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
	}