<?php
defined('_JEXEC') or die('=;)');

class whModelgallery extends modelbase {
	var $xmlFile = "gallery.xml";
	var $uploaded = "components/com_wh/uploaded";
	var $tmpname = "";
	var $table = "#__wh_gallery";
	//var $table ="wh_gallery";

	function __construct() {
		parent::__construct(); 
		//die;
		$this -> value = JRequest::getVar("value", "");
		$this->galleryId = Jrequest::getvar("id",0);
		$this -> getData();
		$this -> xmlParser = new xmlgallery($this -> xmlFile, $this -> _data);
		//die("admin rész");
		$this->document->addScriptDeclaration("\$j(document).ready(function(){ initDateField()})");
	}//function

	//index.php?option=com_wh&controller=gallery&task=getSzallitasiDijtetelek&format=raw&gallery_id=1

	function getgallery($id) {
		$this -> _db -> setQuery("SELECT * FROM #__wh_gallery WHERE id = {$id}");
		//die;
		return $this -> _db -> loadObject();
	}
	
	function getItems(){
		$this -> _db -> setQuery("SELECT * FROM #__wh_fajl WHERE kapcsoloNev = 'gallery' and kapcsolo_id = '{$this->galleryId}'");
		$this -> _db ->Query();
		return $this -> _db ->LoadObjectList();
	} 
	
	function getItemList(){
		$rows = $this->getItems();
		if (count($rows) > 0){
			//print_r($rows);
			jimport("unitemplate.unitemplate");
			$uniparams->cols = 1;
			$uniparams->cellspacing = 0;
			$uniparams->templatePath = "components/com_wh/unitpl";
			$uniparams->pair = false;
			$ut = new unitemplate("gallery_items", $rows, "div", "gallery_items", $uniparams);
			$ret = $ut -> getContents(); 
		}else{
			$ret = "<div align=center>".JText::_("TOLTSON_FEL_KEPET_A_GALERIABA")."</div>";			
		}
		return $ret;
	}
	
	
}