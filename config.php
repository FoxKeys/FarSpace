<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 04.02.2013 15:11
	 */

	define( 'FS_SERVER_PORT', 5190 );
	define( 'FS_SERVER_ADDR_LOCAL', 'localhost:' . FS_SERVER_PORT );

	class conf {
		CONST SERVER_PORT = FS_SERVER_PORT;
		CONST SERVER_ADDR_LOCAL = FS_SERVER_ADDR_LOCAL;
	}

	class config{
		public static $DB = array( 'DSN' => '', 'username' => '', 'password' => '' );
		public static $galaxyTemplates = array( array( 'centerX' => 50, 'centerY' => 50 ) );
	}