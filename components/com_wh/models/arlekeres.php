<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModelarlekeres extends modelbase
{
	var $uploaded = "components/com_wh/uploaded";
	var $tmpname = "";
	var $limit = 1;
	var $xmlFile = "termek.xml";
	var $table = "#__wh_termek";
	
	//var $table ="wh_arlekeres";
	
	function __construct()
	{
		parent::__construct(); 
		$this->limitstart = JREquest::getVar( "limitstart", 0 );
		if(!$this->limitstart) $this->setSessionVar("arlekeresLista", "");
		$this->webContent = new webContent;
	 	$this->xmlParser = new xmlTermek($this->xmlFile, "");		
		//$this->lekerAr();
		//die; 
	}//function
	function getSearchArr(){
		$arr = array();

		$obj = "";		
		$name = "cond_kategoria_id";
		$value = JRequest::getVar($name);
		$kategoriafa = new kategoriafa(array(), 1 );
		$o="";
		$o->value = $o->option = "";
		array_unshift($kategoriafa ->catTree, $o);
		$obj->KATEGORIA = JHTML::_('Select.genericlist', $kategoriafa ->catTree, $name, array( "class"=>"alapinput" ), "value", "option", $value);
		$arr[] = $obj;
		return 	$arr;
	}

	function lekerAr(){
	 	$cond = $this->getCond();
		$q = "select t.* from #__wh_termek as t inner join #__wh_kategoria as k on t.kategoria_id = k.id 
		{$cond} limit {$this->limitstart}, {$this->limit} ";
		$this->_db->setQuery($q);
		ob_start();
		$arr = array();
		foreach($this->_db->loadObjectList() as $t){
			foreach($this->webContent->konkurenciaArr as $k=> $func){
				$obj = $this->webContent->getKonkurenciaAr ( $t->id, $k, 1);
				//echo "termék: {$t->nev} konkurencia: {$k} ár: {$obj->ar} url: {$obj->url}<br />";
				$o="";
				$o->TERMEK = $t->nev;
				$o->KONKURENCIA = $k;
				$o->AR = $obj->ar;
				$o->URL = $obj->url;				
				$arr[] = $o;
			}
			if(count($arr)){
				$listazo = new listazo($arr);
				$arlekeresLista=$this->getSessionVar("arlekeresLista");
				$arlekeresLista.= $listazo->getLista();
				$this->setSessionVar("arlekeresLista", $arlekeresLista);
			}
		
		}
		echo $this->getSessionVar("arlekeresLista");
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	 
	 function getTotal(){
	 	$cond = $this->getCond();
		$q = "select count(t.id) from #__wh_termek as t inner join #__wh_kategoria as k on t.kategoria_id = k.id {$cond}";
		//echo $q;
		$this->_db->setQuery($q);
		return $this->_db->loadResult();
	 }
	 
	 function getCond_(){
	 	return "";
	 }
}// class
?>