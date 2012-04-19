<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControllermsablonmezo extends controllBase
{
	var $view = "msablonmezo";
	var $model = "msablonmezo";
	var $controller = "msablonmezo";	
	var $redirectSaveOk = "index.php?option=com_whp&controller=msablonmezok";
	var $cancelLink = "index.php?option=com_whp&controller=msablonmezok";
	var $addLink = "index.php?option=com_whp&controller=msablonmezo&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>