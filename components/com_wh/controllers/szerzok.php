<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerszerzok extends controllBase
{
	var $view = "szerzok";
	var $model = "szerzok";
	var $controller = "szerzok";
	var $addView = "szerzo";
	var $addLink = "index.php?option=com_wh&controller=szerzo&task=edit&fromlist=1&cid[]=";
	var $redirectSaveOk = "index.php?option=com_wh&controller=szerzok";	
	var $jTable = "wh_szerzo";	
	function __construct($config = array())
	{
		parent::__construct($config);
	}// function
	

}//class
?>