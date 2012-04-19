<?php
defined( '_JEXEC' ) or die( '=;)' );
new initJsKonstansok;
class initJsKonstansok{
	var $arr = array("NEM_ADOTT_MEG_LISTAT", "SIKERES_MENTES_REGISZTRACIO", "SIKERES_MENTES", "BIZTOS_VAGY_BENNE", "KOSZONJUK_AZ_ERTEKELEST", "NEM_JELOLT_BE_CSILLAGOT", "ONK_MUNK_INFO_HELYI", "ONK_MUNK_INFO_KULFOLD" );
	function __construct(){
		$d = jfactory::getDocument();
		$str = "";
		foreach( $this->arr as $a ){
			$v = ( jtext::_($a) ) ? jtext::_($a) : $a;
			$str .= "var {$a}='".$v."'; ";
		}
		$d->addscriptDeclaration($str);
	}
}
?>