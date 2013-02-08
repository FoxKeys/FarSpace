<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Fox foxkeys@gmail.com
 * Date Time: 04.02.2013 14:25
 */
	define( 'FS_SERVER_PORT', 5190 );
	define( 'FS_SERVER_ADDR_LOCAL', 'localhost:' . FS_SERVER_PORT );

	class fConst {
		const E_NOT_TRANSLATED = 'Function %s not yet translated from Python';
		const E_NOT_IMPLEMENTED = 'Function %s not yet implemented';
		const E_PARTIALLY_IMPLEMENTED = 'Function %s not fully implemented';
		const E_NOT_FOUND = 'Object %s with id="%s" not found';
		const E_ACCESS_DENIED = 'Access denied. You can\'t modify "%" with id="%s"';
		const E_CANT_CREATE_MORE_GALAXIES = 'You can\'t createFromDB more galaxies.';
		## additional object types
		const T_GALAXY = 100;

		# reserved OIDs
		const OID_NONE = 0;
		const SERVER_PORT = FS_SERVER_PORT;
		const SERVER_ADDR_LOCAL = FS_SERVER_ADDR_LOCAL;
	}