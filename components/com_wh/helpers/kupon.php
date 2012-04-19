<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlkupon extends xmlParser{
	function getFelhasznalDatum($node){
		$name = $node->getAttribute("name");
		$value = $this->getAktVal($name);
		$ret = "<input name=\"{$name}\" id=\"{$name}\" type=\"text\" value=\"{$value}\" />";
		$value = ( in_array($value, array("0000-00-00 00:00:00" ) ) ) ? $value = "" : $value;
		$ret_ = ($value) ? jtext::_("FELHASZNALVA").": ".$ret : jtext::_("MEG_NEM_KERULT_FELHASZNALASRA");
		$ret_ .="<br />".jtext::_("SZAMLALO").": ".$this->getAktVal("szamlalo");		
		return $ret_;
	}
}