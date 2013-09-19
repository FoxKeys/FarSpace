<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 30.05.2012 13:36
	 */

	/**
	 * Encode (escape) class
	 */
	class e {
		static $isPCRE;
		static $entityWhiteList = array(
			'AElig', 'Aacute', 'Acirc', 'Agrave', 'Alpha', 'Aring', 'Atilde', 'Auml', 'Beta', 'Ccedil', 'Chi', 'Dagger',
			'Delta', 'ETH', 'Eacute', 'Ecirc', 'Egrave', 'Epsilon', 'Eta', 'Euml', 'Gamma', 'Iacute', 'Icirc', 'Igrave',
			'Iota', 'Iuml', 'Kappa', 'Lambda', 'Mu', 'Ntilde', 'Nu', 'OElig', 'Oacute', 'Ocirc', 'Ograve', 'Omega',
			'Omicron', 'Oslash', 'Otilde', 'Ouml', 'Phi', 'Pi', 'Prime', 'Psi', 'Rho', 'Scaron', 'Sigma', 'THORN',
			'Tau', 'Theta', 'Uacute', 'Ucirc', 'Ugrave', 'Upsilon', 'Uuml', 'Xi', 'Yacute', 'Yuml', 'Zeta', 'aacute',
			'acirc', 'acute', 'aelig', 'agrave', 'alefsym', 'alpha', 'amp', 'and', 'ang', 'apos', 'aring', 'asymp',
			'atilde', 'auml', 'bdquo', 'beta', 'brvbar', 'bull', 'cap', 'ccedil', 'cedil', 'cent', 'chi', 'circ',
			'clubs', 'cong', 'copy', 'crarr', 'cup', 'curren', 'dArr', 'dagger', 'darr', 'deg', 'delta', 'diams',
			'divide', 'eacute', 'ecirc', 'egrave', 'empty', 'emsp', 'ensp', 'epsilon', 'equiv', 'eta', 'eth', 'euml',
			'euro', 'exist', 'fnof', 'forall', 'frasl', 'gamma', 'ge', 'gt', 'hArr', 'harr', 'hearts', 'hellip',
			'iacute', 'icirc', 'iexcl', 'igrave', 'image', 'infin', 'int', 'iota', 'iquest', 'isin', 'iuml', 'kappa',
			'lArr', 'lambda', 'lang', 'laquo', 'larr', 'lceil', 'ldquo', 'le', 'lfloor', 'lowast', 'loz', 'lrm',
			'lsaquo', 'lsquo', 'lt', 'macr', 'mdash', 'micro', 'middot', 'minus', 'mu', 'nabla', 'nbsp', 'ndash', 'ne',
			'ni', 'not', 'notin', 'nsub', 'ntilde', 'nu', 'oacute', 'ocirc', 'oelig', 'ograve', 'oline', 'omega',
			'omicron', 'oplus', 'or', 'ordf', 'ordm', 'oslash', 'otilde', 'otimes', 'ouml', 'para', 'part', 'permil',
			'perp', 'phi', 'pi', 'piv', 'plusmn', 'pound', 'prime', 'prod', 'prop', 'psi', 'quot', 'rArr', 'radic',
			'rang', 'raquo', 'rarr', 'rceil', 'rdquo', 'real', 'reg', 'rfloor', 'rho', 'rlm', 'rsaquo', 'rsquo',
			'sbquo', 'scaron', 'sdot', 'sect', 'shy', 'sigma', 'sigmaf', 'sim', 'spades', 'sub', 'sube', 'sum', 'sup',
			'supe', 'szlig', 'tau', 'theta', 'thetasym', 'thinsp', 'thorn', 'tilde', 'times', 'trade', 'uArr', 'uacute',
			'uarr', 'ucirc', 'ugrave', 'uml', 'upsih', 'upsilon', 'uuml', 'weierp', 'xi', 'yacute', 'yen', 'yuml',
			'zeta', 'zwj', 'zwnj'
		);

		public static function html( $text ){//ToDo - check
			$safe_text = self::removeBadUTF( $text );
			$safe_text = self::specialChars( $safe_text, ENT_QUOTES );
			return $safe_text;
		}

		public static function attr( $text ) {//ToDo - check
			$safe_text = self::removeBadUTF( $text );
			$safe_text = self::specialChars( $safe_text, ENT_QUOTES );
			return $safe_text;
		}

		public static function richText( $text, $allowedTags = null ) {
			/*static $purifier;
			require_once( core::root() . 'extlib/htmlpurifier-4.5.0-lite/library/HTMLPurifier.auto.php' );
			if ( empty( $purifier ) ) {
				$config = HTMLPurifier_Config::createDefault();
				$config->set( 'HTML.DefinitionID', 'Gabius default' );
				$config->set( 'HTML.DefinitionRev', 1 );
				//$config->set( 'Cache.DefinitionImpl', null ); // TODO: remove this later!
				$config->set( 'HTML.Allowed', 'p,b,em,strong,ol,ul,li,i,h1,h2,h3,br' );
				//$config->set('URI.Base', 'http://www.example.com');
				//$config->set('URI.MakeAbsolute', true);
				//$config->set( 'AutoFormat.AutoParagraph', true );
				$purifier = new HTMLPurifier( $config );
			}
			if ( isset( $allowedTags ) ) {
				$config = HTMLPurifier_Config::createDefault();
				//$config->set( 'HTML.DefinitionID', 'Gabius custom' );
				//$config->set( 'HTML.DefinitionRev', 1 );
				//$config->set( 'Cache.DefinitionImpl', null ); // TODO: remove this later!
				$config->set( 'HTML.Allowed', $allowedTags );
				$config->set( 'Attr.EnableID', true );
				//$config->set('URI.Base', 'http://www.example.com');
				//$config->set('URI.MakeAbsolute', true);
				//$config->set( 'AutoFormat.AutoParagraph', true );
				$customPurifier =  new HTMLPurifier( $config );
				return $customPurifier->purify( $text );
			}
			return $purifier->purify( $text );*/
			return 'ToDo';
		}

		public static function specialChars( $text, $quoteStyle = ENT_NOQUOTES ) {
			$text = (string)$text;

			if ( 0 === strlen( $text ) ) {
				return '';
			}

			$text = self::specialCharsDecode( $text, $quoteStyle );
			$text = self::normalizeEntities( $text );
			$text = preg_split( '/(&#?x?[0-9a-z]+;)/i', $text, -1, PREG_SPLIT_DELIM_CAPTURE );

			for ( $i = 0; $i < count( $text ); $i += 2 ) {
				$text[$i] = @htmlspecialchars( $text[$i], $quoteStyle, 'UTF-8' );
			}

			$text = implode( '', $text );

			return $text;
		}

		public static function specialCharsDecode( $text, $quoteStyle = ENT_NOQUOTES ) {
			$text = (string)$text;

			if ( 0 === strlen( $text ) ) {
				return '';
			}

			//return if no entities found
			if ( false === strpos( $text, '&' ) ) {
				return $text;
			}

			$single = array( '&#039;' => '\'', '&#x27;' => '\'' );
			$singlePreg = array( '/&#0*39;/' => '&#039;', '/&#x0*27;/i' => '&#x27;' );
			$double = array( '&quot;' => '"', '&#034;' => '"', '&#x22;' => '"' );
			$doublePreg = array( '/&#0*34;/' => '&#034;', '/&#x0*22;/i' => '&#x22;' );
			$others = array( '&lt;' => '<', '&#060;' => '<', '&gt;' => '>', '&#062;' => '>', '&amp;' => '&', '&#038;' => '&', '&#x26;' => '&' );
			$othersPreg = array( '/&#0*60;/' => '&#060;', '/&#0*62;/' => '&#062;', '/&#0*38;/' => '&#038;', '/&#x0*26;/i' => '&#x26;' );

			if ( $quoteStyle === ENT_QUOTES ) {
				$translation = array_merge( $single, $double, $others );
				$translationPreg = array_merge( $singlePreg, $doublePreg, $othersPreg );
			} elseif ( $quoteStyle === ENT_COMPAT ) {
				$translation = array_merge( $double, $others );
				$translationPreg = array_merge( $doublePreg, $othersPreg );
			} else {
				$translation = $others;
				$translationPreg = $othersPreg;
			}

			// Remove number zero padding
			$text = preg_replace( array_keys( $translationPreg ), array_values( $translationPreg ), $text );

			return strtr( $text, $translation );
		}

		public static function normalizeEntities( $text ) {
			$text = str_replace( '&', '&amp;', $text );
			$text = preg_replace_callback( '/&amp;([A-Za-z]{2,8});/', array( __CLASS__, 'namedEntitiesCB' ), $text );
			$text = preg_replace_callback( '/&amp;#(0*[0-9]{1,7});/', array( __CLASS__, 'numberEntitiesCB' ), $text );
			$text = preg_replace_callback( '/&amp;#[Xx](0*[0-9A-Fa-f]{1,6});/', array( __CLASS__, 'hexEntitiesCB' ), $text );
			return $text;
		}

		public static function namedEntitiesCB( $matches ) {
			$ent = $matches[1];
			if ( !empty( $ent ) ) {
				return ( ( !in_array( $ent, self::$entityWhiteList ) ) ? '&amp;' . $ent . ';' : '&' . $ent . ';' );
			} else {
				return '';
			}
		}

		public static function numberEntitiesCB( $matches ) {
			$ent = $matches[1];
			if ( !empty( $ent ) ) {
				if ( self::checkUnicode( $ent ) ) {
					$ent = str_pad( ltrim( $ent, '0' ), 3, '0', STR_PAD_LEFT );
					$ent = '&#' . $ent . ';';
				} else {
					$ent = '&amp;#' . $ent . ';';
				}
				return $ent;
			} else {
				return '';
			}
		}

		function hexEntitiesCB($matches) {
			$ent = $matches[1];
			if ( !empty( $ent ) ) {
				return ( ( !self::checkUnicode( hexdec( $ent ) ) ) ? '&amp;#x' . $ent . ';' : '&#x' . ltrim( $ent, '0' ) . ';' );
			} else {
				return '';
			}
		}

		public static function checkUnicode( $code ) {
			return ( $code == 0x9 || $code == 0xa || $code == 0xd || ( $code >= 0x20 && $code <= 0xd7ff ) ||
				( $code >= 0xe000 && $code <= 0xfffd ) || ( $code >= 0x10000 && $code <= 0x10ffff ) );
		}

		public static function removeBadUTF( $text ){
			$text = (string) $text;

			if ( 0 === strlen( $text )  ) {
				return '';
			}

			if ( !isset( self::$isPCRE ) ) {
				self::$isPCRE = @preg_match( '/^./u', 'a' );
			}

			if ( self::$isPCRE ) {
				if ( 1 === @preg_match( '/^./us', $text ) ) {
					return $text;
				} else {
					return '';
				}
			} else {
				return $text;
			}

		}

		public static function excerpt($str, $maxLen, $words = false, $dots = '...' ) {
			return ( mb_strlen( $str, 'UTF-8' ) > $maxLen ) ? mb_substr( $str, 0, $maxLen - 3, 'UTF-8' ) . $dots : $str;
			// todo use $words
		}

		public static function renderTag( $tag, $attributes = array(), $content = false, $forceClosingTag = false ) {
			$result = '<' . $tag;
			foreach ( $attributes as $attrName => $attrValue ) {
				if ( isset( $attrValue ) ) {
					$result .= ' ' . $attrName . '="' . e::attr( $attrValue ) . '"';
				}
			}
			if ( !empty( $content ) || $forceClosingTag ) {
				$result .= '>' . $content . '</' . $tag . '>';
			} else {
				$result .= '/>';
			}
			return $result . PHP_EOL;
		}

		/**
		 * Modifies query string part of URL
		 * @param array $replacementParams
		 * @param null|string $URL
		 * @return string
		 */
		public static function modifyURL( $replacementParams, $URL ) {
		    // Parse the url into pieces
		    $url_array = parse_url($URL);
		    // The original URL had a query string, modify it.
			if ( !empty( $url_array['query'] ) ) {
				parse_str( $url_array['query'], $query_array );
				$query_array = array_merge( $query_array, $replacementParams );
			} else {
				$query_array = $replacementParams;
			}
			return $url_array['scheme'] . '://' . $url_array['host'] . $url_array['path'] . '?' . http_build_query( $query_array );
		}

		public static function gcLink( $text, $module, $method, $args = array(), $linkAttr = array() ) {
			$linkAttr = array_merge( $linkAttr, array( 'href' => '#', 'class' => trim( (isset( $linkAttr['class'] ) ? $linkAttr['class'] : '' ) . ' gCall' ), 'data-gc-module' => $module, 'data-gc-method' => $method ) );
			$index = 0;
			foreach ( $args as $arg ) {
				$linkAttr['data-gc-param' . $index] = (string)$arg;
				$index++;
			}
			return e::renderTag(
				'a',
				$linkAttr,
				$text,
				true
			);
		}

		public static function attrMerge( $array1, $array2 ) {
			if ( isset( $array1['class'] ) && isset( $array2['class'] ) ) {
				$array2['class'] = trim( $array1['class'] . ' ' . $array2['class'] );
			}
			return array_merge( $array1, $array2 );
		}

		public static function levelValue( $value ) {
			return is_null( $value ) ? '?' : e::html( $value );
		}

	}