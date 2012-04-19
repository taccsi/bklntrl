<?php
// Database config & class
$db_config = array(
	"servername"=> "localhost",
	"username"	=> "admin",
	"password"	=> "trdp05",
	"database"	=> "fusion_admin"
);
if(extension_loaded("mysqli")) require_once("_inc/class._database_i.php"); 
else require_once("_inc/class._database.php"); 

// Tree class
require_once("_inc/class.tree.php"); 
?>