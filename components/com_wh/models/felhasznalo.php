<?php
defined( '_JEXEC' ) or die( '=;)' );
class whModelfelhasznalo extends modelbase{
	var $xmlFile = "felhasznalo.xml";
	var $uploaded = "";
	var $tmpname = "";
	var $table = "drpadlo.drp_users";
	//var $table ="wh_kategoria";
	
	function __construct(){
		parent::__construct(); 
		$this->value = JRequest::getVar("value", "");
		//$this->getData();
	 	//$this->xmlParser = new xmlFelhasznalo( $this->xmlFile, $this->_data );
		$this->xmlParser = new xmlFelhasznalo( $this->xmlFile, "" );
		$this->user_id = jrequest::getVar("user_id", 0);
		$this->webshop_id = jrequest::getVar("webshop_id", 0)  ;
	}//function

	function getVasarlasok(){
		//$v = $this->getVasarlo($this->user_id, $this->webshop_id );
		//print_r($v);
		ob_start();		
		$q = "select sum( tetel.netto_ar * tetel.quantity * ( 1 + tetel.afa/100 ) ) as osszesen 
		from #__wh_tetel as tetel 
		inner join #__wh_rendeles as rendeles on rendeles.id = tetel.rendeles_id
		where user_id = {$this->user_id} and webshop_id = {$this->webshop_id}";
		$this->_db->setQuery($q);
		ob_start();
		echo jtext::_("OSSZES_RENDELESI_ERTEK")." ".ar::_($this->_db->loadResult());
		
		$q = "select rendeles.*, sum( tetel.netto_ar * ( 1 + tetel.afa/100 ) * tetel.quantity ) as osszesen 
		from #__wh_rendeles as rendeles 
		right join #__wh_tetel as tetel on rendeles.id = tetel.rendeles_id
		where user_id = {$this->user_id} and webshop_id = {$this->webshop_id}
		group by rendeles.id
		";
		$this->_db->setQuery($q);
		//print_r($this->_db->loadObjectList());
		//die;
		
		
		foreach($this->_db->loadObjectList() as $r){
			$q = "select * , tetel.netto_ar * ( 1 + tetel.afa/100 ) as brutto_ar
			from #__wh_tetel as tetel where tetel.rendeles_id = {$r->id} ";
			$this->_db->setQuery($q);			
			$tetelek = $this->_db->loadObjectList();			
			$ossz=0;			
			foreach($tetelek as $t){
				$ossz += $t->netto_ar * (1 + $t->afa / 100) * $t->quantity;
			}
			echo "<br />";
			echo "<div>{$r->datum} ".jtext::_("RENDELES_OSSZEGE").": ".ar::_($ossz)."</div>";
			echo "<ul>";			
			foreach($tetelek as $t){
				echo "<li>";
				echo $t->nev." ";
				echo jtext::_("EGYSEGAR").": ".ar::_($t->brutto_ar)." ";
				echo jtext::_("MENNYISEG").": ".$t->quantity.jtext::_("DB")." ";
				echo jtext::_("OSSZESEN").": ".ar::_($t->brutto_ar*$t->quantity)." ";				
				echo "</li>";
			}
			echo "</ul>";
			
		}	
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

}// class
?>