<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 8:44
	 */
	class planet extends DB {
		const PLANETS_TABLE = 'planets';

		/**
		 * @throws Exception
		 * @return planet
		 */
		public function save() {
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}

		/**
		 *
		 * @param int $idPlanet
		 * @throws Exception
		 * @return planet
		 */
		public function load( $idPlanet ) {
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}

		/**
		 * @param int $idSystem
		 * @param FoxDB $DB
		 * @return planet[]
		 */
		public static function selectByIdSystem( $idSystem, $DB ) {
			$planetsData = $DB->select( 'SELECT * FROM ' . self::PLANETS_TABLE . ' WHERE idSystem = ?', $idSystem );
			foreach($planetsData as $key => $planetData){
				$planetsData[$key] = planet::createFromArray( $planetData, $DB );
			}
			return $planetsData;
		}

		/**
		 * Type Hint wrapper
		 * @param int $idPlanet
		 * @return int
		 */
		public function idPlanet( $idPlanet = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
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
		 * @param int $idPlanetType
		 * @return int
		 */
		public function idPlanetType( $idPlanetType = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $diameter
		 * @return int
		 */
		public function diameter( $diameter = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}
		
		/**
		 * Type Hint wrapper
		 * @param int $energy
		 * @return int
		 */
		public function energy( $energy = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $minerals
		 * @return int
		 */
		public function minerals( $minerals = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $plEnv
		 * @return int
		 */
		public function plEnv( $plEnv = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $plSlots
		 * @return int
		 */
		public function plSlots( $plSlots = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

		/**
		 * Type Hint wrapper
		 * @param int $plMaxSlots
		 * @return int
		 */
		public function plMaxSlots( $plMaxSlots = null ) {
			return call_user_func_array( array( $this, 'fieldGetSet' ), array( 1 => __METHOD__ ) + func_get_args() );
		}

	}