<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 10:33
	 */

	class starClass extends activeRecord {
		const TABLE_NAME = 'starClasses';

		/**
		 * Stub to prevent starClass() method execution as constructor (old-style constructor)
		 */
		public function __construct(){
		}

		/**
		 * @return starClass[]
		 */
		public static function selectAll( ){
			$data = game::DB()->select( 'SELECT * FROM ' . self::TABLE_NAME );
			foreach($data as $key => $starClassData){
				$data[$key] = starClass::createFromArray( $starClassData );
			}
			return $data;
		}

		/**
		 * Type Hint wrapper
		 * @return string
		 */
		public function idStarClass( ) {
			return $this->fieldGet( __METHOD__ );
		}

		/**
		 * Type Hint wrapper
		 * @return string
		 */
		public function starType( ) {
			return $this->fieldGet( __METHOD__ );
		}
		
		/**
		 * Type Hint wrapper
		 * @return string
		 */
		public function starClass( ) {
			return $this->fieldGet( __METHOD__ );
		}

		/**
		 * Type Hint wrapper
		 * @return int
		 */
		public function chance( ) {
			return $this->fieldGet( __METHOD__ );
		}

		/**
		 * Type Hint wrapper
		 * @return int
		 */
		public function subclassChanceMin( ) {
			return $this->fieldGet( __METHOD__ );
		}

		/**
		 * Type Hint wrapper
		 * @return int
		 */
		public function subclassChanceMax( ) {
			return $this->fieldGet( __METHOD__ );
		}

	}