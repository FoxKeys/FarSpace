<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox
	 * Date Time: 09.12.2011 15:01
	 */
	abstract class DB {
		const E_PROPERTY_NOT_SET = 'Error accessing an unset property "%s"';
		/**
		 * @var array
		 */
		protected $data = array();

		/**
		 * @var FoxDB $DB
		 */
		protected $DB = null;

		/**
		 * @param FoxDB $DB
		 */
		public function __construct( $DB ) {
			$this->DB = $DB;
		}

		/**
		 * @return FoxDB|null
		 */
		public function DB() {
			return $this->DB;
		}

		/**
		 * @param $idObject
		 * @param FoxDB $DB
		 * @return DB
		 */
		public static function createFromDB( $idObject, $DB ) {
			$class = get_called_class();
			/**
			 * @var DB $instance
			 */
			$instance = new $class( $DB );
			return $instance->load( $idObject );
		}

		/**
		 * @param mixed[] $data
		 * @param FoxDB $DB
		 * @return DB
		 */
		public static function createFromArray( $data, $DB ) {
			$class = get_called_class();
			/**
			 * @var DB $instance
			 */
			$instance = new $class( $DB );
			return $instance->assignArray( $data );
		}

		/**
		 * @param array $data
		 * @return \DB
		 */
		public function assignArray( $data ) {
			foreach ( $data as $key => $value ) {
				if ( method_exists( $this, $key ) ) {
					$ReflectionMethod = new ReflectionMethod( $this, $key );
					if ( $ReflectionMethod->getNumberOfParameters() == 1 ) {
						$this->$key( $value );
						continue;
					}
				}
				$this->fieldSet( $key, $value );
			}
			return $this;
		}

		/**
		 * @throws Exception
		 * @return mixed
		 */
		public function save(){
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}

		/**
		 * @param mixed $idObject
		 * @throws Exception
		 * @return mixed
		 */
		public function load( $idObject ){
			throw new Exception( sprintf( fConst::E_NOT_IMPLEMENTED, __METHOD__ ) );
		}

		/**
		 * @param string $name
		 * @return mixed
		 * @throws Exception
		 */
		protected function fieldGet( $name ) {
			if ( !array_key_exists( $name, $this->data ) ) {
				throw new Exception( sprintf( self::E_PROPERTY_NOT_SET, $name ) );
			}
			return $this->data[$name];
		}

		/**
		 * @param string $name
		 * @param mixed $value
		 * @return \DB
		 */
		protected function fieldSet( $name, $value ) {
			$this->data[get_class( $this ) . '::' . $name] = $value;
			return $this;
		}

		/**
		 * @param string $name
		 * @param null|mixed $value
		 * @throws Exception
		 * @return mixed
		 */
		protected function fieldGetSet( $name, $value = null ) {
			$args = func_get_args();
			if ( count( $args ) > 1 ) {
				$this->data[$name] = $value;
			}
			if ( !array_key_exists( $name, $this->data ) ) {
				throw new Exception( sprintf( self::E_PROPERTY_NOT_SET, $name ) );
			}
			return $this->data[$name];
		}

		/**
		 * @param string $name
		 * @return bool
		 */
		public function fieldIsSet( $name ) {
			return array_key_exists( get_class( $this ) . '::' . $name, $this->data );
		}

	}
