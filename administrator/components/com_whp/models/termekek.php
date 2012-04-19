<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.model');

class whpModeltermekek extends whpAdmin
{
	var $limit = 30;
	var $uploaded = "media/whp/termekek/";
	var $w = 80;
	var $h = 120;
	var $mode = "resize";

	function __construct()
	{
	 	
		parent::__construct();
		global $mainframe, $option;
		// Get pagination request variables
		//$this->limitstart = $this->getLimitStart();		
		$this->limitstart = 20;
		$this->xmlParser = new xmltermek("termek.xml");	
		// In case limit has been changed, adjust it
		//$this->initKampanyok();		
		
		//$this->minta();die;

	}//function

}// class
?>