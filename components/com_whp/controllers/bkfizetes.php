<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControllerbkfizetes extends controllBase{
	var $view = "bkfizetes";
	var $model = "bkfizetes";
	var $controller = "bkfizetes";	
	var $redirectSaveOk = "";
	var $cancelLink = "";

	function __construct($config = array()){
		parent::__construct($config);
		//$this->session();
		if( !$this->user->id && 0 ){
			$link = "index.php?option=com_whp&controller=felhasznalo";
			$msg =JText::_("KEREM JELENTKEZZEN BE VAGY REGISZTRALJON");
			$this->setRedirect ( $link, $msg );
		}else{
		/*
			$link = "index.php";
			$msg =JText:_("kerem jelentkezzen be");			
		*/	
		}
	}// functiondie()
}//class
?>