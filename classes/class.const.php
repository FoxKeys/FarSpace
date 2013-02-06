<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Fox foxkeys@gmail.com
 * Date Time: 04.02.2013 14:25
 */
	define( 'FS_SERVER_PORT', 5190 );
	define( 'FS_SERVER_ADDR_LOCAL', 'localhost:' . FS_SERVER_PORT );

	class fConst {
		CONST E_NOT_TRANSLATED = 'Function %s not yet translated from Python';
		CONST E_NOT_IMPLEMENTED = 'Function %s not yet implemented';
		## additional object types
		CONST T_GALAXY = 100;

		# reserved OIDs
		CONST OID_NONE = 0;
		CONST SERVER_PORT = FS_SERVER_PORT;
		CONST SERVER_ADDR_LOCAL = FS_SERVER_ADDR_LOCAL;
	}