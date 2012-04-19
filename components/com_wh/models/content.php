<?php
defined('_JEXEC') or die('=;)');

class whModelcontent extends modelbase {
	var $xmlFile = "content.xml";
	var $uploaded = "components/com_wh/uploaded";
	var $tmpname = "";
	var $table = "#__wh_content";
	//var $table ="wh_content";

	function __construct() {
		parent::__construct();
		//die;
		$this -> value = JRequest::getVar("value", "");
		$this -> getData();
		$this -> xmlParser = new xmlcontent($this -> xmlFile, $this -> _data);
		$this->document->addScriptDeclaration("\$j(document).ready(function(){ initDateField()})");
	}//function

	//index.php?option=com_wh&controller=content&task=getSzallitasiDijtetelek&format=raw&content_id=1

	function getcontent($id) {
		$this -> _db -> setQuery("SELECT * FROM #__wh_content WHERE id = {$id}");
		//die;
		return $this -> _db -> loadObject();
	}
}