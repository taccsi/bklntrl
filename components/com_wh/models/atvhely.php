<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModelatvhely extends modelbase
{
	var $xmlFile = "atvhely.xml";
	var $uploaded = "";
	var $tmpname = "";
	var $table = "#__wh_atvhely";
	//var $table ="wh_kategoria";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");
		$this->getData();
	 	$this->xmlParser = new xmlatvhely($this->xmlFile, $this->_data);
	}//function
	
	function getTelepules(){
		ob_start();
		$f_ = $this->xmlParser->getAllFormGroups();
		$id = jrequest::getVar("atvhely_id", 0);	
		//echo $id." -------------";
		if( $id ){	
			$obj = $this->getObj( "#__wh_atvhely", $id );
			//print_r($obj);
			$telepules_id = $obj->telepules_id;
			$irszam = $obj->irszam;
			$utca_hazszam = $obj->utca_hazszam;
		}else{
			foreach(array("telepules_id", "irszam", "utca_hazszam") as $a){
				$$a = $this->xmlParser->getAktVal($a);
			}
		}
		//$telepules_id = jrequest::getVar("telepules_id", "");
		if($megye = jrequest::getVar("megye", "") ){
			$megye = $this->getObj("#__wh_telepules", $megye, "megye" )->megye;
			$q = "select id as `value`, concat(telepules, '') as `option` 
			from #__wh_telepules where megye = '{$megye}' and ( lelekszam > 1000 or lelekszam = 0 ) order by telepules asc ";
			$this->_db->setQuery( $q );
			$rows = $this->_db->loadObjectList( );
			echo "<span class=\"span_cim\">".jtext::_("TELEPULES")."</span>".JHTML::_( 'Select.genericlist', $rows, "telepules_id", array("class"=>"alapinput cim" ), "value", "option", $telepules_id )."<br />";
			echo  "<span class=\"span_cim\">".jtext::_("IRANYITOSZAM")."</span>"."<input class=\"alapinput cim\" id=\"irszam\" name=\"irszam\" value=\"".$irszam."\" >"."<br />";
			echo"<span class=\"span_cim\">".jtext::_("UTCA_HAZSZAM")."</span>"."<input class=\"alapinput cim\" id=\"utca_hazszam\" name=\"utca_hazszam\" value=\"".$utca_hazszam."\" >"."<br />";
		}else{
			echo "&nbsp;";
		}
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
}// class
?>