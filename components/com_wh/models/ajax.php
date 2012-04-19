<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModelajax extends modelbase
{
	//var $table ="wh_kategoria";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
	}//function
	
	function beszar(){		
		$beszallito_id = jrequest::getVar("beszallito_id");
		$termek_id = jrequest::getVar("termek_id");		
		@$q = "select bszarT.*, afaT.ertek as afa from #__wh_termek_beszallito_ar as bszarT 
		inner join #__wh_beszallito as bszT on bszarT.beszallito_id = bszT.id
		inner join #__wh_afa as afaT on bszT.afa_id = afaT.id
		where bszarT.beszallito_id = {$beszallito_id} and bszarT.termek_id = {$termek_id}";
		$this->_db->setQuery($q);
		$arO = $this->_db->loadObject();
		$js="";
		ob_start();
		//print_r($arO);
		//echo $q;
		echo @$this->getNettoBruttoInput("netto_ar_beszallito", "brutto_ar_beszallito", $arO->netto_ar, $arO->afa, $id, "[]", "<br>", $js );		
		$ret = ob_get_contents();		
		ob_end_clean();
		return $ret;
		///return $beszallito_id;
	}
	
}// class
?>