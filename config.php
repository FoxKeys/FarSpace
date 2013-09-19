<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 04.02.2013 15:11
	 */

	class config{
		const FILE_NAME_REGEX = '/[^a-zA-Z0-9_]/i';
		const DEBUG_MODE = true;
		const DEBUG_LOG = true;
		const JS_DIR = 'browserClient/js/';
		const BLOCKS_DIR = 'browserClient/blocks/';

		const TECH_ADDSLOT3 = 3802;

		public static $DB = array( 'DSN' => 'mysql:host=localhost;dbname=fox_ospace;charset=utf8', 'username' => 'fox_ospace', 'password' => 'TPN@J1vLyfd2+tA;' );
		public static $ClientVersion = array(
			'version' => array( 0, 0, 0, "" ),
			'build' => 0,
			'revision' => 0,
			'versionString' => 'Version not specified'
		);

		public static $planetImgCnt= array( 'A' => 2, 'C' => 2, 'D' => 5, 'E' => 3, 'G' => 9, 'H' => 4, 'I' => 3, 'M' => 1, 'R' => 5, 'X' => 1 );

		public static $coreJSArray = array(
			array( 'name' => 'jquery', 'URL' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', 'depend' => array( ) ),
			array( 'name' => 'jquery.migrate', 'URL' => 'http://code.jquery.com/jquery-migrate-1.2.1.min.js', 'depend' => array( 'jquery' ) ),
			array( 'name' => 'jquery.ui', 'URL' => 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js', 'depend' => array( 'jquery' ) ),
			array( 'name' => 'html5', 'URL' => 'http://html5shim.googlecode.com/svn/trunk/html5.js', 'depend' => array( ) ),

			array( 'name' => 'kinetic', 'coreFile' => 'kinetic-v4.3.3.js', 'depend' => array( 'jquery' ) ),
			array( 'name' => 'jquery.mousewheel', 'coreFile' => 'jquery.mousewheel-3.1.1.js', 'depend' => array( 'jquery' ) ),

			array( 'name' => 'f', 'coreFile' => 'f.js', 'depend' => array( 'jquery' ) ),
			array( 'name' => 'f.imageCache', 'coreFile' => 'class.imageCache.js', 'depend' => array( 'jquery' ) ),
			array( 'name' => 'f.grid', 'coreFile' => 'class.grid.js', 'depend' => array( 'kinetic' ) ),
			array( 'name' => 'f.scanner', 'coreFile' => 'class.scanner.js', 'depend' => array( 'kinetic' ) ),
			array( 'name' => 'f.planetRenderer', 'coreFile' => 'class.planetRenderer.js', 'depend' => array( 'kinetic' ) ),
			array( 'name' => 'f.systemRenderer', 'coreFile' => 'class.systemRenderer.js', 'depend' => array( 'f', 'kinetic' ) ),

			array( 'name' => 'f.system', 'blockName' => 'scriptFSystem', 'depend' => array( 'jquery' ) ),
			array( 'name' => 'f.messages', 'blockName' => 'scriptFMessages', 'depend' => array( 'f' ) ),
		);

	}