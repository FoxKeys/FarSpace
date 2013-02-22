<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox
	 * Date Time: 09.12.2011 15:01
	 */
	abstract class activeRecord {
		const E_PROPERTY_NOT_SET = 'Error accessing an unset property "%s"';
		/**
		 * @var array
		 */
		protected $data = array();

		/**
		 * @return activeRecord
		 */
		public static function createNew( /*$args*/ ) {
			$className = get_called_class();
			$args = func_get_args();
			/**
			 * @var activeRecord $instance
			 */
			if(count($args) == 0)
			   return new $className();
			else {
			   $r = new ReflectionClass($className);
				return $r->newInstanceArgs($args);
			}
		}

		/**
		 * @param $idObject
		 * @return activeRecord
		 */
		public static function createFromDB( $idObject ) {
			$class = get_called_class();
			/**
			 * @var activeRecord $instance
			 */
			$instance = new $class( );
			return $instance->load( $idObject );
		}

		/**
		 * @param mixed[] $data
		 * @return activeRecord
		 */
		public static function createFromArray( $data ) {
			$class = get_called_class();
			/**
			 * @var activeRecord $instance
			 */
			$instance = new $class( );
			return $instance->assignArray( $data );
		}

		/**
		 * @param array $data
		 * @return \activeRecord
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
		 * @return \activeRecord
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
