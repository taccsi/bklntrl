<?php
defined( '_JEXEC' ) or die( '=;)' );
new initJsKonstansok;
class initJsKonstansok{
	var $arr = array("KOSZONJUK_AZ_ERTEKELEST", "KEREM_MINDEN_ADATOT_TOLTSON_KI", "SIKERES_AJANLAS", "SIKERES_MENTES", "HIBASAN_KITOLTOTT_MEZOK", "KEREM_ADJON_MEG_ERTEKET", "CSOMAG", "SZAMOLT_AR", "SZAMOLT_MENNYISEG", "ON_REGISZTRALT_FELHASZNALO", "RENDSZERUZENET" );
	function __construct(){
		$d = jfactory::getDocument();
		$str = "";
		foreach( $this->arr as $a ){
			$str .= "var {$a}='".jtext::_($a)."'; ";
		}
		$d->addscriptDeclaration($str);
	}
}
?>