<?php
defined( '_JEXEC' ) or die( '=;)' );
class whpAdmin extends modelBase{ 
	function __construct(){
		$this->params = &JComponentHelper::getParams( 'com_whp' );
		parent::__construct();	
	}
	
	function getTermvarErtekById($ertek, $id, $arr=array() ){
		foreach( $arr as $a ){
			$vN_ = "mezoid_{$a->id}";
			$$vN_ = "";
		}
		parse_str($ertek);
		$vN_ = "mezoid_{$id}";
		return ( isset( $$vN ) ) ? $$vN : false;
	}
	
	function setListaKep($item){
		//die("--");
		$q = "select id from #__whp_kep where termek_id = {$item->id} order by sorrend limit 1 ";
		$this->_db->setQuery($q);
		$item->listaKep = $this->getListakep($this->_db->loadResult());
		return $item;
	}
	
	function myUrlEncode($string) {
		$entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D', '+');
		$replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]",'%20');
    return str_replace($entities, $replacements, urlencode($string));
}

	function getListaKep( $id ){
		//die("***");
		$kO = $this->getObj("#__whp_kep", $id);
		@$termek = $this->getObj("#__whp_termek", $kO->termek_id);
		$class="zoom";
		$buborek_kep="";
		@$alt=$termek->nev;
		$forras_kep= $this->xmlParser->getKepNev( $id );
	
		$cel_kep=$this->xmlParser->getCelKepNev( $id, $this->w, $this->h, $this->mode );
		//die($cel_kep);
		$link = $forras_kep;		
		//image($forras_kep, $cel_kep, $link="", $w="", $h="", $mode="", $class="", $buborek_kep="", $alt="")
		$img = $this->xmlParser->image($forras_kep, $cel_kep, $link, $this->w, $this->h, $this->mode, "class=\"zoom\" rel=\"group\"", "{$alt}", "{$alt}");
		//$ret ="<a rel=\"group\" class=\"zoom\" href=\"{$forras_kep}\">{$img}</a>";
		//die($ret);
		return $img;

	}
}
?>