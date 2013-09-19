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
					'INSERT INTO ' . $this::TABLE_NAME . ' ( idGalaxy, x, y, idStarClass, starSubclass, name ) VALUES (?, ?, ?, ?, ?, ?)',
					$this->idGalaxy(),
					$this->x(),
					$this->y(),
					$this->idStarClass(),
					$this->starSubclass(),
					$this->name()
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
		 * @param int $idPlayer
		 * @param int $idSystem
		 * @throws Exception
		 * @return system
		 */
		public function load( $idPlayer, $idSystem ) {
			$data = game::DB()->selectRow( 'SELECT s.*, sm.level FROM ' . system::TABLE_NAME . ' s INNER JOIN ' . scanner::TABLE_NAME_STATIC_MAP . ' sm ON s.idSystem = sm.idSystem  WHERE s.idSystem = ? AND sm.IdPlayer = ?', $idSystem, $idPlayer );
			if ( empty( $data ) ) {
				throw new Exception( sprintf( fConst::E_NOT_FOUND, __CLASS__, $idSystem ) );
			}
			return $this->assignArray( $data );
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
		 * @param float $value
		 * @return float
		 */
		public function x( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param float $value
		 * @return float
		 */
		public function y( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param string $value
		 * @return string|null
		 */
		public function name( $value = null ) {
			if ( func_num_args() > 0 || $this->level() >= rules::$level2InfoScanPwr ) {
				return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
			} else {
				return null;
			}
		}

		/**
		 * Type Hint wrapper
		 * @param float $level
		 * @return float
		 */
		public function level( $level = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

	}