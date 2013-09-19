<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 14.09.2013 21:25
	 */

	abstract class activeRecordCollection {
		/**
		 * @param mixed $args,... unlimited OPTIONAL number of select arguments
		 * @return array
		 */
		abstract protected function selectData( $args );

		/**
		 * @param array $data
		 * @return activeRecord
		 */
		abstract protected function createObject( $data );

		/**
		 * @param mixed $args,... unlimited OPTIONAL number of select arguments
		 * @return activeRecord[]
		 */
		public function select( $args ) {
			$data = call_user_func_array( array( $this, 'selectData' ), func_get_args() );
			foreach ( $data as $index => $recordData ) {
				$data[$index] = $this->createObject( $recordData );
			}
			return $data;
		}

		/**
		 * @param mixed $args,... unlimited OPTIONAL number of select arguments
		 * @throws Exception
		 * @return activeRecord
		 */
		public function get( $args ) {
			$data = call_user_func_array( array( $this, 'selectData' ), func_get_args() );
			if ( count( $data ) == 0 ) {
				throw new Exception( sprintf( fConst::E_NOT_FOUND, __CLASS__, print_r( func_get_args(), true ) ) );
			}
			if ( count( $data ) > 1 ) {
				throw new Exception( sprintf( fConst::E_FOUND_REDUNDANT, __CLASS__, print_r( func_get_args(), true ) ) );
			}
			return $this->createObject( $data[0] );
		}

		/**
		 * @param mixed $args,... unlimited OPTIONAL number of select arguments
		 * @throws Exception
		 * @return activeRecord
		 */
		public function getNoException( $args ) {
			$data = call_user_func_array( array( $this, 'selectData' ), func_get_args() );
			if ( count( $data ) == 0 ) {
				return null;
			} else {
				return $this->createObject( $data[0] );
			}
		}
	}