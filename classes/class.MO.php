<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 01.04.2013 18:02
	 */


	class MO {
		const ERROR_FILE_MAGIC = 'Bad MO file format. Magic not found or have unknown value.';
		const ERROR_BAD_HEADER_SIZE = 'Bad MO header size';
		const ERROR_HEADER_UNPACK = 'MO file header unpack error';
		const ERROR_UNSUPPORTED_REVISION = 'Unsupported MO revision.';

		var $entries = array();
		var $headers = array();

		protected $nPlurals = 2;

		public function __construct( $fileName ) {
			$this->loadFromFile( $fileName );
		}

		public function translate( $singular, $context = null ) {
			$entry = new translationEntry( array( 'singular' => $singular, 'context' => $context ) );
			$translated = $this->translateEntry( $entry );
			return ( $translated && !empty( $translated->translations ) ) ? $translated->translations[0] : $singular;
		}

		public function translateEx( $singular, $context = null ) {
			$entry = new translationEntry( array( 'singular' => $singular, 'context' => $context ) );
			$translated = $this->translateEntry( $entry );
			return ( $translated && !empty( $translated->translations ) ) ? $translated->translations[0] : null;
		}

		private function setHeaders( $headers ) {
			foreach ( $headers as $header => $value ) {
				$this->setHeader( $header, $value );
			}
		}

		private function getHeader( $header ) {
			return isset( $this->headers[$header] ) ? $this->headers[$header] : false;
		}

		/**
		 * @param translationEntry $entry
		 * @return translationEntry | bool
		 */
		private function translateEntry( $entry ) {
			$key = $entry->key();
			return isset( $this->entries[$key] ) ? $this->entries[$key] : false;
		}

		function translatePlural( $singular, $plural, $count, $context = null ) {
			$entry = new translationEntry( array( 'singular' => $singular, 'plural' => $plural, 'context' => $context ) );
			$translated = $this->translateEntry( $entry );
			$index = $this->select_plural_form( $count );
			if ( $translated && 0 <= $index && $index < $this->nPlurals && is_array( $translated->translations ) && isset( $translated->translations[$index] ) ) {
				return $translated->translations[$index];
			} else {
				return 1 == $count ? $singular : $plural;
			}
		}

		/**
		 * The gettext implementation of select_plural_form.
		 *
		 * It lives in this class, because there are more than one descendand, which will use it and
		 * they can't share it effectively.
		 *
		 */
		function gettext_select_plural_form( $count ) {
			if ( !isset( $this->_gettext_select_plural_form ) || is_null( $this->_gettext_select_plural_form ) ) {
				$this->parsePluralForms( $this->getHeader( 'Plural-Forms' ) );
			}
			return call_user_func( $this->_gettext_select_plural_form, $count );
		}

		function parsePluralForms( $header ) {
			if ( preg_match( '/^\s*nplurals\s*=\s*(\d+)\s*;\s+plural\s*=\s*(.+)$/', $header, $matches ) ) {
				$this->nPlurals = (int)$matches[1];
				$this->_gettext_select_plural_form = $this->make_plural_form_function( $this->nPlurals, trim( $this->parenthesize_plural_exression( $matches[2] ) ) );
			} else {
				$this->nPlurals = 2;
				$this->_gettext_select_plural_form = $this->make_plural_form_function( $this->nPlurals, 'n != 1' );
			}
		}

		/**
		 * Makes a function, which will return the right translation index, according to the
		 * plural forms header
		 */
		function make_plural_form_function( $nplurals, $expression ) {
			$expression = str_replace( 'n', '$n', $expression );
			$func_body = "
				\$index = (int)($expression);
				return (\$index < $nplurals)? \$index : $nplurals - 1;";
			return create_function( '$n', $func_body );
		}

		/**
		 * Adds parantheses to the inner parts of ternary operators in
		 * plural expressions, because PHP evaluates ternary oerators from left to right
		 *
		 * @param string $expression the expression without parentheses
		 * @return string the expression with parentheses added
		 */
		function parenthesize_plural_exression( $expression ) {
			$expression .= ';';
			$res = '';
			$depth = 0;
			for ( $i = 0; $i < strlen( $expression ); ++$i ) {
				$char = $expression[$i];
				switch ( $char ) {
					case '?':
						$res .= ' ? (';
						$depth++;
						break;
					case ':':
						$res .= ') : (';
						break;
					case ';':
						$res .= str_repeat( ')', $depth ) . ';';
						$depth = 0;
						break;
					default:
						$res .= $char;
				}
			}
			return rtrim( $res, ';' );
		}

		function buildHeaders( $translation ) {
			$headers = array();
			// sometimes \ns are used instead of real new lines
			$translation = str_replace( '\n', "\n", $translation );
			$lines = explode( "\n", $translation );
			foreach ( $lines as $line ) {
				$parts = explode( ':', $line, 2 );
				if ( isset( $parts[1] ) ) {
					$headers[trim( $parts[0] )] = trim( $parts[1] );
				}
			}
			return $headers;
		}

		function setHeader( $header, $value ) {
			$this->headers[$header] = $value;

			if ( 'Plural-Forms' == $header ) {
				$this->parsePluralForms( $value );
			}
		}

		function loadFromFile( $fileName ) {
			$reader = new MOFileReader( $fileName );

			$magic = $reader->readInt32( );
			if ( dechex($magic) == '950412de' ) {
				$reader->bigEndian( false );
			} elseif ( dechex($magic) == 'de120495' ) {
				$reader->bigEndian( true );
			} else {
				throw new Exception( self::ERROR_FILE_MAGIC );
			}

			$endian = $reader->bigEndian() ? 'N' : 'V';

			$header = $reader->read( 24 );
			if ( strlen( $header ) != 24 ) {
				throw new Exception( self::ERROR_BAD_HEADER_SIZE );
			}

			// parse header
			$header = unpack( "{$endian}revision/{$endian}total/{$endian}originals_lenghts_addr/{$endian}translations_lenghts_addr/{$endian}hash_length/{$endian}hash_addr", $header );
			if ( !is_array( $header ) ) {
				throw new Exception( self::ERROR_HEADER_UNPACK );
			}
			/**
			 * @var int $revision
			 * @var int $originals_lenghts_addr
			 * @var int $translations_lenghts_addr
			 */
			extract( $header );

			// Only 0 revision supported
			if ( $revision != 0 ) {
				throw new Exception( self::ERROR_UNSUPPORTED_REVISION );
			}

			// seek to data blocks
			$reader->seek( $originals_lenghts_addr );

			// read originals' indices
			$originals_lengths_length = $translations_lenghts_addr - $originals_lenghts_addr;
			if ( $originals_lengths_length != $total * 8 ) {
				return false;
			}

			$originals = $reader->read( $originals_lengths_length );
			if ( strlen( $originals ) != $originals_lengths_length ) {
				return false;
			}

			// read translations' indices
			$translations_lenghts_length = $hash_addr - $translations_lenghts_addr;
			if ( $translations_lenghts_length != $total * 8 ) {
				return false;
			}

			$translations = $reader->read( $translations_lenghts_length );
			if ( strlen( $translations ) != $translations_lenghts_length ) {
				return false;
			}

			// transform raw data into set of indices
			$originals = str_split( $originals, 8 );
			$translations = str_split( $translations, 8 );

			// skip hash table
			$strings_addr = $hash_addr + $hash_length * 4;

			$reader->seek( $strings_addr );

			$strings = $reader->readAll();
			$reader->close();

			for ( $i = 0; $i < $total; $i++ ) {
				$o = unpack( "{$endian}length/{$endian}pos", $originals[$i] );
				$t = unpack( "{$endian}length/{$endian}pos", $translations[$i] );
				if ( !$o || !$t ) {
					return false;
				}

				// adjust offset due to reading strings to separate space before
				$o['pos'] -= $strings_addr;
				$t['pos'] -= $strings_addr;

				$original = substr( $strings, $o['pos'], $o['length'] );
				$translation = substr( $strings, $t['pos'], $t['length'] );

				if ( '' === $original ) {
					$this->setHeaders( $this->buildHeaders( $translation ) );
				} else {
					$entry = & $this->make_entry( $original, $translation );
					$this->entries[$entry->key()] = & $entry;
				}
			}
			return true;
		}

		function is_entry_good_for_export( $entry ) {
			if ( empty( $entry->translations ) ) {
				return false;
			}

			if ( !array_filter( $entry->translations ) ) {
				return false;
			}

			return true;
		}

		/**
		 * Build a translationEntry from original string and translation strings,
		 * found in a MO file
		 *
		 * @static
		 * @param string $original original string to translate from MO file. Might contain
		 *    0x04 as context separator or 0x00 as singular/plural separator
		 * @param string $translation translation string from MO file. Might contain
		 *    0x00 as a plural translations separator
		 */
		function &make_entry( $original, $translation ) {
			$entry = new translationEntry();
			// look for context
			$parts = explode( chr( 4 ), $original );
			if ( isset( $parts[1] ) ) {
				$original = $parts[1];
				$entry->context = $parts[0];
			}
			// look for plural original
			$parts = explode( chr( 0 ), $original );
			$entry->singular = $parts[0];
			if ( isset( $parts[1] ) ) {
				$entry->is_plural = true;
				$entry->plural = $parts[1];
			}
			// plural translations are also separated by \0
			$entry->translations = explode( chr( 0 ), $translation );
			return $entry;
		}

		function select_plural_form( $count ) {
			return $this->gettext_select_plural_form( $count );
		}

	}


	class translationEntry {

		/**
		 * Whether the entry contains a string and its plural form, default is false
		 *
		 * @var boolean
		 */
		var $is_plural = false;

		var $context = null;
		var $singular = null;
		var $plural = null;
		var $translations = array();
		var $translator_comments = '';
		var $extracted_comments = '';
		var $references = array();
		var $flags = array();

		/**
		 * @param array $args associative array, support following keys:
		 *    - singular (string) -- the string to translate, if omitted and empty entry will be created
		 *    - plural (string) -- the plural form of the string, setting this will set {@link $is_plural} to true
		 *    - translations (array) -- translations of the string and possibly -- its plural forms
		 *    - context (string) -- a string differentiating two equal strings used in different contexts
		 *    - translator_comments (string) -- comments left by translators
		 *    - extracted_comments (string) -- comments left by developers
		 *    - references (array) -- places in the code this strings is used, in relative_to_root_path/file.php:linenum form
		 *    - flags (array) -- flags like php-format
		 */
		function translationEntry( $args = array() ) {
			// if no singular -- empty object
			if ( !isset( $args['singular'] ) ) {
				return;
			}
			// get member variable values from args hash
			foreach ( $args as $varname => $value ) {
				$this->$varname = $value;
			}
			if ( isset( $args['plural'] ) ) {
				$this->is_plural = true;
			}
			if ( !is_array( $this->translations ) ) {
				$this->translations = array();
			}
			if ( !is_array( $this->references ) ) {
				$this->references = array();
			}
			if ( !is_array( $this->flags ) ) {
				$this->flags = array();
			}
		}

		/**
		 * Generates a unique key for this entry
		 *
		 * @return string|bool the key or false if the entry is empty
		 */
		function key() {
			if ( is_null( $this->singular ) ) {
				return false;
			}
			// prepend context and EOT, like in MO files
			return is_null( $this->context ) ? $this->singular : $this->context . chr( 4 ) . $this->singular;
		}

		function merge_with( &$other ) {
			$this->flags = array_unique( array_merge( $this->flags, $other->flags ) );
			$this->references = array_unique( array_merge( $this->references, $other->references ) );
			if ( $this->extracted_comments != $other->extracted_comments ) {
				$this->extracted_comments .= $other->extracted_comments;
			}

		}
	}

	abstract class MOReader {
		const ERROR_UNEXPECTED_EOF = 'Unexpected end of file when reading MO file';
		private $bigEndian = false;

		/**
		 * @param int $length
		 * @return string
		 */
		protected abstract function read( $length );

		/**
		 * @return bool
		 */
		protected abstract function close();

		/**
		 * @param int $pos
		 * @return int
		 */
		protected abstract function seek( $pos );

		/**
		 * @return string
		 */
		protected abstract function readAll();

		/**
		 * Get / set "endianness" of the file
		 * @param boolean $value
		 * @return $this|bool
		 */
		public function bigEndian( $value = null ) {
			$args = func_get_args();
			if ( count( $args ) == 0 ) {	//Getter
				return $this->bigEndian;
			} else {						//Setter
				$this->bigEndian = $value;
				return $this;
			}
		}

		/**
		 * Reads a 32bit Integer from the Stream
		 */
		function readInt32() {
			$bytes = $this->read( 4 );
			if ( 4 != strlen( $bytes ) ) {
				throw new Exception( self::ERROR_UNEXPECTED_EOF );
			}
			return array_shift( unpack( $this->bigEndian() ? 'N' : 'V', $bytes ) );
		}

	}

	class MOFileReader extends MOReader {
		const CHUNK_SIZE = 4096;
		const ERROR_FILE_NOT_FOUND = 'Translation file "%s" not found';
		private $fHandle;

		public function __construct( $fileName ) {
			$this->fHandle = fopen( $fileName, 'rb' );
			if ( $this->fHandle === false ) {
				throw new Exception( sprintf( self::ERROR_FILE_NOT_FOUND, $fileName ) );
			}
		}

		public function read( $length ) {
			return fread( $this->fHandle, $length );
		}

		public function seek( $pos ) {
			fseek( $this->fHandle, $pos, SEEK_SET );
		}

		public function close() {
			return fclose( $this->fHandle );
		}

		public function readAll() {
			$result = '';
			while ( !feof( $this->fHandle ) ) {
				$result .= $this->read( self::CHUNK_SIZE );
			}
			return $result;
		}
	}
