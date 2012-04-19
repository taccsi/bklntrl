<?php
defined( '_JEXEC' ) or die( '=;)' );
class gls extends xmlParser{
	function __construct(){
		//$this->base_template = new base_template;
		//$this->webContent = new webContent;
		$this->glsDb =  &JDatabase::getInstance( $this->getOption() );
		$this->_db = JFactory::getDBO();
		$this->db =JFactory::getDBO();		
		$this->user=JFactory::getUser();
		$this->beallitas = $this->getBeallitas();
		$this->user->jog = $this->getJog();
	 	$this->xmlParser = new xmlGls("tours.xml", "" );
	}

	function getOsszesTomeg(){
		$id = $this->getSessionVar("id");
		$q = "select sum(tomeg*quantity) as tomeg from #__wh_tetel where rendeles_id = {$id}";
		$this->_db->setQuery($q);
		return $this->_db->loadResult();		
	}
	
	function writeDB(){
		$o = $this->getObj("#__wh_rendeles", $this->getSessionVar("id") );
		if(!$o->csomagszam){
			//$arr = array("IRSZAM", "VAROS", "UTCA");
			$v = $this->getVasarlo( $o->user_id, $o->webshop_id );
			parse_str($o->sz_cim); //szállítási cím	
			//die($o->sz_cim);	
			$o_ = "";
			$o_->parcelnr = $o->csomagszam = $this->getCsomagszam( $o->clientnr  );
			$this->_db->updateObject("#__wh_rendeles", $o, "id");
			$o_->pcount = 1;
			$o_->nr = 1;
			$o_->from_name = "Gsm Takács Kft.";
			$o_->from_address = "Pesti u. 65.";
			$o_->from_zip = "2730";
			$o_->from_city = "Albertirsa";
			$o_->from_contact = "20/92-75-727";
			$o_->from_country = "HU - Magyarország";																
	
			$o_->to_name = $v->user->name;
			$o_->to_address = $UTCA;
			$o_->to_zip = ($IRANYITOSZAM) ? $IRANYITOSZAM : $IRSZAM;		
			$o_->to_city = $VAROS;	
			$o_->to_contact = $v->felhasznalo->telefon;
			$o_->to_country = "HU - Magyarország";
			$o_->info = "";
			$depoDriver = $this->getDepoDriver($IRANYITOSZAM);
			$o_->depo = $depoDriver->depo;
			$o_->driver = $depoDriver->driver;
			$o_->imported = date( "Y-m-d H:i:s", time() );
			$o_->weight = $this->getOsszesTomeg();
			$o_->cod= $this->getCod($o);
			$o_->curr = "HUF";
			$o_->clientnr = $o->clientnr;
			$o_->pcname = "PC";
			$o_->firstpnr = $o_->parcelnr;
			$o_->codref = $o->megjegyzes;
			$o_->clientref = $o->id;
			//print_r($o_);
			//die;
			$this->glsDb->insertObject("parcels", $o_, "parcelnr");
			//echo $this->glsDb->getErrorMsg();
		}else{
		
		}
	}
	
	function getCod($o){
		$q = "select (sum(netto_ar) * (afa/100+1) * quantity ) as osszertek from #__wh_tetel where rendeles_id = {$o->id}";
		$this->_db->setQuery($q);
		$osszertek = $this->_db->loadResult()+$o->kiszallitas_ar;
		$k = 1;
		$osszertek = floor($osszertek/$k)*$k+$k;
		return $osszertek;				
	}
	
	function getDepoDriver($IRSZAM){
		$node = $this->xmlParser->getNode("zipcode", "{$IRSZAM}" );
		//echo $IRSZAM." *****************************"; 
		$ret ="";
		$ret ->depo="";
		$ret ->driver="";
		if(is_a($node, "DOMElement")){
			$ret->depo = $node->getAttribute("depo")."B";			
			$ret->driver = $node->getAttribute("driver")."B";						
		}
		return $ret;
	}
	
	function getCsomagszam( $clientnr ){
		$q = "select CONV(pnnext,16,10) as nextCsomag, CONV(pnlast,16,10) as utolsoCsomag from pnrange 
		where CONV(pnnext,16,10) <= CONV(pnlast ,16,10) and clientnr = {$clientnr} limit 1 ";
		$this->glsDb->setQuery($q);
		$o = $this->glsDb->loadObject();
		echo $this->glsDb->getErrorMsg();		
		if($res = $this->glsDb->loadObject()->nextCsomag ){
			$res++;
			$csomagszam = "000" . $res;
			$q = "update pnrange set pnnext = CONV('{$res}',10,16) where clientnr = {$clientnr} limit 1";
			//$q = "update pnrange set pnnext = CONV('41000535',10,16) ";			
			$this->glsDb->setQuery($q);
			$this->glsDb->Query();
			//die( "cssz: {$csomagszam} -------". $q );			
		}
		return $csomagszam;	
	}
	
	function getOption(){
		$option['driver'] = "mysql"; // Database driver name
		$option['host'] = "im12"; // Database host name
		$option['user'] = "gsmtakacs_hu"; // User for database authentication
		$option['password'] = "zbscagem"; // Password for database authentication
		$option['database'] = "gsmtakacs_hu_gls"; // Database name
		$option['prefix'] = ""; // Database prefix (may be empty)
		return $option;
	}

}// class
?>
