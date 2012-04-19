<?php
defined( '_JEXEC' ) or die( '=;)' );
class dbConnect{
	function __construct( ){

	}
	
	function getDb($host, $user, $password, $database, $prefix){
		$option="";
		$option['driver'] = "mysql"; // Database driver name
		$option['host'] = $host; // Database host name
		$option['user'] = $user; // User for database authentication
		$option['password'] = $password; // Password for database authentication
		$option['database'] = $database; // Database name
		$option['prefix'] = $prefix; // Database prefix (may be empty)
		return JDatabase::getInstance( $option );
	}
}