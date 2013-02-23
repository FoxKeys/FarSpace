<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 06.02.2013 6:55
	 */
	class utils {
		public static function randomFloat( $min, $max ) {
			return $min + abs( $max - $min ) * mt_rand( 0, mt_getrandmax() ) / mt_getrandmax();
		}

		/**
		 * getRandomWeightedElement()
		 * http://stackoverflow.com/a/11872928
		 * Utility function for getting random values with weighting.
		 * Pass in an associative array, such as array('A'=>5, 'B'=>45, 'C'=>50)
		 * An array like this means that "A" has a 5% chance of being selected, "B" 45%, and "C" 50%.
		 * The return value is the array key, A, B, or C in this case.  Note that the values assigned
		 * do not have to be percentages.  The values are simply relative to each other.  If one value
		 * weight was 2, and the other weight of 1, the value with the weight of 2 has about a 66%
		 * chance of being selected.  Also note that weights should be integers.
		 *
		 * @param array $weightedValues
		 * @param null|string $weightField
		 * @throws Exception
		 * @return mixed
		 */
		public static function getRandomWeightedElement( array $weightedValues, $weightField = null ) {
			if ( !isset( $weightField ) ) {
				$sum = (int)array_sum( $weightedValues );
			} else {
				$sum = 0;
				foreach ( $weightedValues as $value ) {
					if(is_array($value)){
						$sum += $value[$weightField];
					} elseif(is_object($value)){
						$sum += $value->$weightField();
					} else {
						throw new Exception( 'Improper data type' );
					}
				}
			}
			$rand = mt_rand( 1, $sum );
			$result = null;
			foreach ( $weightedValues as $key => $value ) {
				if ( !isset( $weightField ) ) {
					$rand -= $value;
				} else {
					if(is_array($value)){
						$rand -= $value[$weightField];
					} elseif(is_object($value)){
						$rand -= $value->$weightField();
					} else {
						throw new Exception( 'Improper data type' );
					}
				}
				if ( $rand <= 0 ) {
					$result = $key;
					break;
				}
			}
			return $result;
		}

		/**
		 * @param array $array
		 * @return mixed
		 */
		public static function randomElement( $array ) {
			$array = array_values($array);
			return $array[rand( 0, count( $array ) - 1 )];
		}

		public static function dice( $num, $range, $offset ) {
			$result = $offset;
			for ( $i = 0; $i < $num; $i++ ) {	//for i in xrange(0, num):
				$result += rand( 1, $range + 1 );
			}
			return $result;
		}

		function openssl_random_pseudo_bytes( $length ) {
			$length_n = (int)$length; // shell injection is no fun
			$handle = popen( "/usr/bin/openssl rand $length_n", "r" );
			$data = stream_get_contents( $handle );
			pclose( $handle );
			return $data;
		}

		public static function sendMessage( $obj, $msgID, $whereID, $data ) {
			//ToDo
		}
	}