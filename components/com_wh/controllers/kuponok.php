<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerkuponok extends controllBase
{
	var $view = "kuponok";
	var $model = "kuponok";
	var $controller = "kuponok";
	var $addView = "kupon";
	var $addLink = "index.php?option=com_wh&controller=kupon&task=edit&fromlist=1&cid[]=";
	var $redirectSaveOk = "index.php?option=com_wh&controller=kuponok";	
	var $jTable = "wh_kupon";
	function __construct($config = array())
	{
		parent::__construct($config);
	}// function
	

}//class
?>