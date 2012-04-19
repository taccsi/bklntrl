<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModelszerzo extends modelbase
{
	var $xmlFile = "szerzo.xml";
	var $uploaded = "";
	var $tmpname = "";
	var $table = "#__wh_szerzo";
	//var $table ="wh_kategoria";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");
		$this->getData();
	 	$this->xmlParser = new xmlszerzo($this->xmlFile, $this->_data);
	}//function

	function getJogtulValtozok(){
		$arr = array("tulhanyad", "szerzo_id", "jogtulajdonos_id", "kapcsolo_id");
		$o ="";
		foreach($arr as $a){
			if( $v = jrequest::getVar($a, "" ) ){
				$o->$a = $v;
			}
		}
		return $o;
	}

	function torolJogtulajdonos(){
		ob_start();
		$o = "";
		$o->szerzo_id = $this->getJogtulValtozok()->szerzo_id;
		$o->kapcsolo_id = $this->getJogtulValtozok()->kapcsolo_id;		
		$q = "delete from #__wh_jogtul_kapcsolo  where id = {$o->kapcsolo_id} ";
		$this->_db->setQuery($q);
		$this->_db->query();
		echo $this->_db->getErrorMsg();				
		$ret = ob_get_contents();
		ob_end_clean();		
		return $ret.$this->getJogtulajdonosok();
	}

	function hozzaadJogtulajdonos(){
		ob_start();
		$o = "";
		@$o->szerzo_id = $this->getJogtulValtozok()->szerzo_id;
		@$o->tulhanyad = $this->getJogtulValtozok()->tulhanyad;
		@$o->jogtulajdonos_id = $this->getJogtulValtozok()->jogtulajdonos_id;	
		preg_match_all("/\(.*\)/", $o->jogtulajdonos_id, $matches);
		//print_r($matches[0]);
		@$o->jogtulajdonos_id = str_replace(array("(", ")"), "", $matches[0][0] );
		//print_r( $o->jogtulajdonos_id);
		$q = "select id from #__wh_jogtul_kapcsolo as kapcsolo 
		where szerzo_id = {$o->szerzo_id}
		and jogtulajdonos_id = {$o->jogtulajdonos_id}";
		$this->_db->setQuery($q);
		//echo $this->_db->loadResult()."**********<br />";
		//echo $this->_db->getErrorMsg();				
		if($o->szerzo_id && $o->tulhanyad && $o->jogtulajdonos_id && !$this->_db->loadResult() ){
			$this->_db->insertObject("#__wh_jogtul_kapcsolo", $o, "id" );
		}
		$ret = ob_get_contents();
		ob_end_clean();		
		return $ret.$this->getJogtulajdonosok();
	}

	function getJogtulajdonosok(){
		ob_start();
		$szerzo_id = $this->getJogtulValtozok()->szerzo_id;		
		$q = "select kapcsolo.*, szerzo.nev as szerzo_nev, jogtul.nev as jogtul_nev 
		from #__wh_jogtul_kapcsolo as kapcsolo 
		inner join #__wh_szerzo as szerzo on kapcsolo.szerzo_id = szerzo.id
		inner join #__wh_jogtul as jogtul on kapcsolo.jogtulajdonos_id = jogtul.id		
		where szerzo.id = {$szerzo_id}
		order by kapcsolo.id
		";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();
		echo $this->_db->getErrorMsg();		
		$arr = array();
		foreach($rows as $a){
			$o = "";
			$o->JOGTULAJDONOS = $a->jogtul_nev;
			$o->TULAJDONHANYAD = $a->tulhanyad;
			$o->TOROL = "<input type=\"button\" onclick=\"if(confirm('".jtext::_("BIZTOS_HOGY_TORLOD")."')){torolJogtulajdonos('{$a->id}')}\" value=\"".jtext::_("TOROL")."\" >";						
			$arr []=$o;
		}
		$listazo = new listazo($arr, "table_jogtul" );
		echo $listazo->getLista();
		$ret = ob_get_contents();
		ob_end_clean();		
		return $ret."&nbsp;";
	}
	
}// class
?>