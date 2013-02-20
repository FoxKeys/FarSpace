<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 20.02.2013 15:27
	 */
	class fleet extends activeRecord {
		const TABLE_NAME = 'fleets';

		public function __construct( $idPlayer = null, $idSystem = null ) {
			$reflectionMethod = new ReflectionMethod( $this, '__construct' );
			$parameters = $reflectionMethod->getParameters();
			foreach ( func_get_args() as $index => $value ) {
				$name = $parameters[$index]->name;
				$this->$name( $value );
			}
		}

		/**
		 * @throws Exception
		 * @return shipDesign
		 */
		public function save(){
			if ( !$this->fieldIsSet( 'idFleet' ) ) {
				game::DB()->exec(
					'INSERT INTO ' . $this::TABLE_NAME . ' ( idPlayer, idSystem ) VALUES (?, ?)',
					$this->idPlayer(),
					$this->idSystem()
				);
				$this->idFleet( game::DB()->lastInsertId() );
			} else {
				throw new Exception( sprintf( fConst::E_PARTIALLY_IMPLEMENTED, __METHOD__ ) );
				/*game::DB()->exec(
					'UPDATE ' . $this::TABLE_NAME . ' SET
						idSystem = ?,
					WHERE idPlanet = ?',
					$this->idSystem(),
				);*/
			}
			return $this;
		}

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idFleet( $value = null ) {
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

		/**
		 * Type Hint wrapper
		 * @param int $value
		 * @return int
		 */
		public function idSystem( $value = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
	}