<?php 
defined( '_JEXEC' ) or die( '=;)' );
class whpBeallitasok{
	function __construct(){
	$GLOBALS["whp_kozp_url"] = "http://dev.trifid.hu/bikelinetravel";
	$GLOBALS["whp_id"] = 5;
	//$GLOBALS["whp_email"] = "info@fusion.hu"; 
	//die("lefut?");
	}

	function getOption(){
		$option['driver'] = "mysql"; // Database driver name
		$option['host'] = "localhost"; // Database host name
		$option['user'] = "bikelinetravel"; // User for database authentication
		$option['password'] = "drtzhdfg"; // Password for database authentication
		$option['database'] = "bikelinetravel_hu"; // Database name
		$option['prefix'] = "btj25_"; // Database prefix (may be empty)
		return $option; 
	}
}

new whpBeallitasok;

?>