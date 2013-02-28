<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 17.02.2013 8:22
	 */

	class rules {
		public static $techBaseImprovement = 1;
		public static $techMaxImprovement = 5;
		public static $techImprEff = array( 1 => 0.750, 2 => 0.875, 3 => 1.000, 4 => 1.125, 5 => 1.250 );
		public static $startingPopulation = 9000;
		public static $startingBio = 1000;
		public static $startingEn = 1000;
		public static $startingScannerPwr = 100;
		public static $maxMorale = 100.0;
		public static $minDistance = 0.001;
		public static $level1InfoScanPwr = 1000;
		public static $level2InfoScanPwr = 1200;
		public static $level3InfoScanPwr = 1400;
		public static $level4InfoScanPwr = 1600;
		public static $maxScanPwr = 200000;
		public static $partnerScanPwr = 300000;
	}