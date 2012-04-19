<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerhirlevel_cim extends controllBase
{
	var $view = "hirlevel_cim";
	var $model = "hirlevel_cim";
	var $controller = "hirlevel_cim";
	var $redirectSaveOk = "index.php?option=com_wh&controller=hirlevel_cimek";
	var $cancelLink = "index.php?option=com_wh&controller=hirlevel_cimek"; 
	var $addLink = "index.php?option=com_wh&controller=hirlevel_cim&task=edit";	 

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>