<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlgallery extends xmlParser{
	
	function getCountry(){
		$this->db->setQuery("select `name` as `option`, id as `value` from #__ic_country order by `name` asc");
		$rows = $this->db->loadObjectList();
		$country= modelBase::getSessionVar("country_id");
		if(JRequest::getVar("fromlist","")){
			$cid = JRequest::getVar("cid",array());
			$obj = $this->getObj($cid[0]);
			@$country = explode(",", $obj->country_id);
		}
		return JHTML::_('Select.genericlist', $rows, "country_id[]", array("multiple"=>"multiple"), "value", "option", $country);
	}

	function getObj($id){
		$q = "select * from #__ic_beszallito where id = {$id}";
		$db = JFactory::getDBO();
		$db->setQuery($q);
		return $db->loadObject();
	}
	
	function getSzallitasiDijtetelek( $node ){
		//szallitasiDijtetelek
		$name = $node->getAttribute("name");
		$value = $this->getAktVal( $name );
		$table = "#__wh_szallitasi_tetel";
		$fields_ = $this->_db->getTableFields($table, 1);
		$o="";
		foreach($fields_[$table] as $f => $v){
			$o->$f="";
		}
				
		$this->document->addscriptDeclaration("\$j(document).ready(function(){getSzallitasiDijtetelek()})");
		$ret = "";
		$ret .= "<div id=\"div_hozzaad\">";
		$ret .= $this->getDijTetelInput( $o );
		$ret .= "<input type=\"button\" onclick=\"hozzaadSzallitasiDijtetel()\" value='" . jtext::_("HOZZAAD") . "' /><br />";
		$ret .= "</div>";
		$ret .= "<div id=\"ajaxContentSzallitasiDijtetelek\" ></div>";
		

		//$ret .= "<input name=\"{$name}\" id=\"{$name}\" type=\"hidden_\" value='{$value}' />";
		return $ret;
	}
}