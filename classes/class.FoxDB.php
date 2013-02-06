<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox
	 * Date Time: 04.12.11 9:24
	 */

	class FoxDB extends PDO {

		public function __construct( $dsn, $username, $password, $options = null ) {

			$options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES utf8';

			parent::__construct( $dsn, $username, $password, $options );
			//$this->setAttribute( PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
			$this->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		}

		private function getPDOConstantType( $var ) {
			if ( is_int( $var ) ) {
				return PDO::PARAM_INT;
			}
			if ( is_bool( $var ) ) {
				return PDO::PARAM_BOOL;
			}
			if ( is_null( $var ) ) {
				return PDO::PARAM_NULL;
			}
			//Default
			return PDO::PARAM_STR;
		}

		public function select( $query ) { // ( $query, *$args )

			$args = func_get_args();
			array_shift( $args );
			$stmt = $this->prepare( $query );
			foreach ( $args as $index => $value ) {
				$stmt->bindValue( $index + 1, $value, $this->getPDOConstantType( $value ) );
			}
			$stmt->execute();
			return $stmt->fetchAll( PDO::FETCH_ASSOC );
			/*
			$result = array();
			if ( $stmt->rowCount() ) {
				do {
					$result[] = $stmt->fetchAll( PDO::FETCH_ASSOC );
				} while ( $stmt->nextRowset() );
			} else {
				do {
					$stmt->errorCode();
				} while ( $stmt->nextRowset() );
			}

			if ( count( $result ) == 1 ) {
				$result = $result[0];
			}

			return $result;
			*/
		}

		public function selectReindex( $query, $indexName ) { // ( $query, *$args )

			$args = func_get_args();
			array_shift( $args );	//$query
			array_shift( $args );	//$indexName
			$stmt = $this->prepare( $query );
			foreach ( $args as $index => $value ) {
				$stmt->bindValue( $index + 1, $value, $this->getPDOConstantType( $value ) );
			}
			$stmt->execute();

			$result = array();
			if ( $stmt->rowCount() ) {
				do {
					$records = array();
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC )) {
						$records[$row[$indexName]] = $row;
					}
					$result[] = $records;
				} while ( $stmt->nextRowset() );
			} else {//Нужно, чтобы вызвать exception при пустом ответе
				do {
					$stmt->errorCode();
				} while ( $stmt->nextRowset() );
			}

			if ( count( $result ) == 1 ) {
				$result = reset( $result );
			}

			return $result;
		}

		/**
		 * Для запросов, возвращающих один столбец (списки)
		 */
		public function selectList( $query ) { // ( $query, *$args )

			$args = func_get_args();
			array_shift( $args );
			$stmt = $this->prepare( $query );
			foreach ( $args as $index => $value ) {
				$stmt->bindValue( $index + 1, $value, $this->getPDOConstantType( $value ) );
			}
			$stmt->execute();

			$result = array();
			do {
				$result[] = $stmt->fetchAll( PDO::FETCH_COLUMN, 0 );
			} while ( $stmt->nextRowset() );

			if ( count( $result ) == 1 ) {
				$result = $result[0];
			}

			return $result;
		}

		/**
		 * Для запросов возвращающих одну строку
		 */
		public function selectRow( $query ) { // ( $query, *$args )
			$args = func_get_args();
			$result = call_user_func_array(
				array( $this, 'select' ), $args
			);
			if ( count( $result ) == 1 ) {
				return $result[0];
			} else {
				return $result;
			}
		}

		/**
		 * Для запросов возвращающих одно значение
		 */
		public function selectValue( $query ) { // ( $query, *$args )
			$args = func_get_args();
			$result = call_user_func_array(
				array( $this, 'select' ), $args
			);
			if ( count( $result ) > 0 ) {
				return reset( reset( $result ) );
			} else {
				return null;
			}
		}

		public function exec( $query ) { // ( $query, *$args )

			$args = func_get_args();
			array_shift( $args );
			$stmt = $this->prepare( $query );
			foreach ( $args as $index => $value ) {
				$stmt->bindValue( $index + 1, $value, $this->getPDOConstantType( $value ) );
			}
			$stmt->execute();

			do {
				$stmt->errorCode();
			} while ( $stmt->nextRowset() );

		}

	}

?>