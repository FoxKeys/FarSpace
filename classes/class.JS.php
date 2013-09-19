<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 21.08.2012 8:48
	 */
	class JS {
		const INDEFINITE_LOOP_FOUND = 'Indefinite loop found in script dependencies';
		const DEPEND_NOT_REGISTERED = 'Required script "%s" not registered.';
		const UNKNOWN_SCRIPT_TYPE = 'Unknown script type';
		const HEADER_SCRIPT = 'header';
		const FOOTER_SCRIPT = 'footer';
		private static $knownScripts = array();
		private static $sorted = false;

		public static function registerJS( $scriptsArray ) {
			foreach ( $scriptsArray as $script ) {
				if ( !empty( $script['coreFile'] ) ) {
					self::registerCoreJS( $script['name'], $script['coreFile'], $script['depend'] );
				} elseif(!empty( $script['URL'] )){
					self::registerCDNJS( $script['name'], $script['URL'], $script['depend'] );
				} elseif(!empty( $script['blockName'] )){
					self::registerBlockJS( $script['name'], $script['blockName'], $script['depend'] );
				} else {
					throw new Exception( e::html( sprintf( t::__( self::UNKNOWN_SCRIPT_TYPE ) ) ) );
				}
			}
		}

		public static function registerCoreJS( $name, $file, $depend = array() ) {
			self::doRegister( $name, game::jsURL() . $file, $depend);
		}

		public static function registerCDNJS( $name, $URL, $depend = array() ) {
			self::doRegister( $name, $URL, $depend);
		}

		public static function registerBlockJS( $name, $blockName, $depend = array() ) {
			self::doRegister( $name, null, $depend, false, $blockName );
		}

		public static function addToHeader( $name ) {
			self::dependenciesSort();
			if ( is_array( $name ) ) {
				foreach ( $name as $nameName ) {
					self::addToHeader( $nameName );
				}
			} else {
				if ( empty( self::$knownScripts[$name] ) ) {
					throw new Exception( e::html( sprintf( t::__( 'Script "%s" not registered' ), $name ) ) );
				} else {
					$script = self::$knownScripts[$name];
					if ( !$script[self::HEADER_SCRIPT] ) {
						self::$knownScripts[$name][self::HEADER_SCRIPT] = true;
						self::$knownScripts[$name][self::FOOTER_SCRIPT] = false;
						self::addToHeader( $script['depend'] );
					}
				}
			}
		}

		public static function addToFooter( $name ) {
			self::dependenciesSort();
			if ( is_array( $name ) ) {
				foreach ( $name as $nameName ) {
					self::addToFooter( $nameName );
				}
			} else {
				if ( empty( self::$knownScripts[$name] ) ) {
					throw new Exception( e::html( sprintf( t::__( 'Script "%s" not registered' ), $name ) ) );
				} else {
					$script = self::$knownScripts[$name];
					if ( !$script[self::HEADER_SCRIPT] && !$script[self::FOOTER_SCRIPT] ) {
						self::$knownScripts[$name][self::FOOTER_SCRIPT] = true;
						self::addToFooter( $script['depend'] );
					}
				}
			}
		}

		public static function getHeaderScripts() {
			$result = '';
			foreach ( self::$knownScripts as $script ) {
				if ( $script[self::HEADER_SCRIPT] ) {
					$result .= self::renderScript( $script );
				}
			}
			return $result;
		}

		public static function getFooterScripts() {
			$result = '';
			foreach ( self::$knownScripts as $script ) {
				if ( $script[self::FOOTER_SCRIPT] ) {
					$result .= self::renderScript( $script );
				}
			}
			return $result;
		}

		public static function renderScript( $script ) {
			if ( !empty( $script['file'] ) ) {
				$result = '<script type="text/javascript" src="' . $script['file'] . '"></script>' . PHP_EOL;
			} else {
				/**
				 * @var baseBlock $block
				 */
				$block = new $script['blockName']();
				ob_start();
				$block->render();
				$result = ob_get_contents();
				ob_clean();
			}
			return $result;
		}

		public static function getSettings( ) {
			$scripts = array();
			$loadedScripts = array();
			foreach ( self::$knownScripts as $script ) {
				$scripts[$script['name']] = $script['depend'];
				if ( $script[self::HEADER_SCRIPT] || $script[self::FOOTER_SCRIPT] ) {
					$loadedScripts[$script['name']] = true;
				}
			}
			$scripts = json_encode( $scripts );
			$loadedScripts = json_encode( $loadedScripts );
			$result = <<<SCRIPT
				<script>
					var fSettings = {
						scripts: $scripts,
						loadedScripts: $loadedScripts
					};
				</script>
SCRIPT;
				return $result . PHP_EOL;
		}

		private static function doRegister( $name, $file, $depend, $ignoreExisting = false, $blockName = null ) {
			if ( isset( self::$knownScripts[$name] ) ) {
				if ( !$ignoreExisting ) {
					throw new Exception( sprintf( 'Script "%s" already registered', $name ) );
				}
			} else {
				self::$knownScripts[$name] = array(
					'name' => $name,
					'file' => $file,
					'depend' => $depend,
					'blockName' => $blockName,
					self::HEADER_SCRIPT => false,
					self::FOOTER_SCRIPT => false
				);
				self::$sorted = false;
			}
		}

		private static function sourceRemovalSortAlgo( &$L, &$result ) {
			$haveProgress = false;
			foreach ( $L as $name => $script ) {
				if ( empty( $script['dependCopy'] ) ) {
					$result[$name] = $script;
					unset( $L[$name] );
					foreach ( $L as $name2 => $script2 ) {
						$index = array_search( $script['name'], $script2['dependCopy'] );
						if ( $index !== false ) {
							unset( $L[$name2]['dependCopy'][$index] );
						}
					}
					$haveProgress = true;
				}
			}
			if ( !$haveProgress && !empty( $L ) ) {
				throw new Exception( self::INDEFINITE_LOOP_FOUND );
			}

			if ( !empty( $L ) ) {
				self::sourceRemovalSortAlgo( $L, $result );
			}
		}

		private static function dependenciesSort() {
			if ( !self::$sorted ) {
				$L = self::$knownScripts;
				foreach ( $L as $name => $script ) {
					foreach ( $script['depend'] as $dep ) {
						if ( !isset( $L[$dep] ) ) {
							throw new Exception( e::html( sprintf( t::__( self::DEPEND_NOT_REGISTERED ), $dep ) ) );
						}
					}
					$L[$name]['dependCopy'] = $script['depend'];
				}
				$result = array();
				self::sourceRemovalSortAlgo( $L, $result );
				self::$knownScripts = $result;
				self::$sorted = true;
			}
		}

		public static function getScript( $name ) {
			if ( !isset( self::$knownScripts[$name] ) ) {
				throw new Exception( e::html( sprintf( t::__( 'Script "%s" not registered.' ), $name ) ) );
			}
			return self::$knownScripts[$name];
		}

	}
