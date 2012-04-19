<?php
defined( '_JEXEC' ) or die( '=;)' );


class whModelrendeles extends modelbase
{
	var $xmlFile = "rendeles.xml";
	var $uploaded = "";
	var $tmpname = "";
	var $table = "#__wh_rendeles";
	
	function __construct()   
	{
		parent::__construct();
		$this->value = JRequest::getVar("value", "");
		$this->getData();
	 	$this->xmlParser = new xmlRendeles($this->xmlFile, $this->_data);
		
		//print_r($this->_data);
		
	}//function
	
	function kuldAllapotvaltozas(){
		
		$rendeles_id = jrequest::getVar("rendeles_id", 0);
		$allapot = jrequest::getVar("allapot", 0);
		$allapot_megjegyzes= trim(jrequest::getVar("allapot_megjegyzes", 0));
		$r = $this->getObj("#__wh_rendeles", $rendeles_id );
		$r->allapot = $allapot;
		$datum = date("Y-m-d H:i:s");
		$r->allapotv_email_datum = $datum;		
		$this->_db->updateObject("#__wh_rendeles", $r, "id");
		ob_start();
		?>
		<h1>Tisztelt Vásárló!</h1>
        Webáruházunkban leadott <strong><?php echo $r->id ?></strong> azonosítójú rendelése a következők szerint módosult:<br />
		Rendelés állapota: <strong><?php echo jtext::_($r->allapot) ?></strong><br />
        <?php echo ($allapot_megjegyzes) ? "Munkatársunk megjegyzése: {$allapot_megjegyzes}<br />" : "";?>
        
        <?php
		$body = ob_get_contents();
		ob_end_clean();
		$from = "";
		$fromname = "";
		$recipient = array();
		parse_str($r->szamlazasi_cim);		
		$recipient[]= "balazs@trifid.hu" ;
		if(@$EMAIL)$recipient[]= $EMAIL;		
		$subject = "Rendelési értesítő";
		$mode =1;
		JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode);		
		return $datum;
	}
	
	function getClientnr(){
		
		$group = jrequest::getvar("group", "");
		$rendeles_id = jrequest::getvar("rendeles_id", "");		
		$ge_ = $this->xmlParser->getGroup( $group );
		$arr = array();
		$r = $this->getObj("#__wh_rendeles", $rendeles_id );
		
		foreach($ge_->childNodes as $e){
			if(is_a($e, "DOMElement")){
				$name = $e->getAttribute('name');
				$this->setSessionVar( $name, $r->$name );
			}
		}
		$o_ = $this->xmlParser->getAllFormGroups();	
		$ret = html_entity_decode($o_[$group] );
		$rendeles = $this->getObj("#__wh_rendeles", $rendeles_id);
		
		
		return $ret;
	}

	function tetelmasol($cidTetel){ 
		foreach($cidTetel as $id){
			$o = $this->getObj("#__wh_tetel", $id);
			unset($o->id);
			//$o->allapot = jtext::_("MASOLAT");
			$this->_db->insertObject("#__wh_tetel", $o, "id");
		}
		//$q = "select * from  
	}
	
	function visszaruosszeallit($cidTetel){ 
		
		
		
		$this->_db->setquery("select r.* from #__wh_rendeles as r inner join 
		#__wh_tetel as t on t.rendeles_id = r.id where t.id = '$cidTetel[0]'");
		
		$rendeles = $this->_db->loadobject();
		
		
		if (isset($rendeles)){
			$o_ = new pickpack;
			$o_->setReturningPackages($rendeles, $cidTetel);
		}
		die('ok');
		//$q = "select * from  
	}
	
	function teteltorol($cidTetel){
		/*
		print_r($cidTetel);
		die; 
		*/
		foreach($cidTetel as $id){
			$q = "delete from #__wh_tetel where id = {$id}";
			$this->_db->setQuery($q);
			$this->_db->Query();			
		}
	}

	function store()
	   {
		$row =& $this->getTable( str_replace("#__", "", $this->table) );
		foreach($this->getFormFieldArray() as $parName){//ha tömböt kell menteni
			$val = JRequest::getVar($parName,"", "",2,2,2);
			//echo $parName."---<br />";
			if(is_array($val)){
				$data[$parName] = ",".implode(",", $val).",";
			}else{
				$data[$parName] = $val;
			}

		}
		
		//állapotváltozás dátum
		$obj = $this->getObj( "#__wh_rendeles", $data["id"] );
		if($obj->allapot != $data["allapot"]){
			$data["allapotvaltozas_datum"] = date("Y-m-d h:i:s", time() );
		}
		
		  if (!$row->bind($data)) {
			 $this->setError($this->_db->stderr());
			 return false;
		  }
		  if (!$row->check()) {
			 $this->setError($this->_db->stderr());
			 return false;
		  }
		  if (!$row->store()) {
			 $this->setError( $row->getError() );
		   return false;
		  }else{
	   		$id = $this->_db->insertId();
			 if(!$id){
			 $id = $this->getSessionVar("id");
		   }
		  }
		  $this->saveTetelek($id);
		  $this->saveFutarszolgalat();
		  
		  return $id;
	  }   	
	
	function saveFutarszolgalat(){
		$szallitas_admin = $this->getSessionVar("szallitas_admin");
		//die($szallitas_admin."------");
			
		switch( $szallitas_admin ){
			case "házhoszállítás - GLS" : 
				
				$o_ = new gls;
				//die("------"); 
				$o_ ->writeDB();
			break;
			
			case "SZEMELYES_ATVETEL_PICKPACK" : 
				$rendeles_id = $this->getSessionVar("id");
				$r = $this->getObj("#__wh_rendeles", $rendeles_id);
				
				//$o_ = new pickpack;
				//$o_->setSzallitmany($r);
				
				//die("------"); 
				//$o_ ->writeDB();
			break;
			
			case "DPD_CSOMAGKULDO_SZOLGALAT" : 
				$rendeles_id = $this->getSessionVar("id");
				$r = $this->getObj("#__wh_rendeles", $rendeles_id);
				
				//$o_ = new pickpack;
				//$o_->setSzallitmany($r);
				
				//die("------"); 
				//$o_ ->writeDB();
			break;
			
			case "házhoszállítás - DHL" : 
				
				$dhl_feltoltes_datum = $this->getSessionVar("dhl_feltoltes_datum");
				//die($dhl_feltoltes_datum."------");
				if ($dhl_feltoltes_datum == "0000-00-00 00:00:00" || $dhl_feltoltes_datum =="" ){
					$o_ = new dhl;
					if( $o_->export() ){
						//die("----");
						$rendeles_id = $this->getSessionVar("id");
						$r = $this->getObj("#__wh_rendeles", $rendeles_id);
						//print_r($r);
						//die;
						$r->dhl_feltoltes_datum =  date("Y-m-d H:i:s", time() );
						$this->_db->updateObject("#__wh_rendeles", $r, "id");
						$this->setSessionVar("dhl_feltoltes_datum", $r->dhl_feltoltes_datum );
						//die($this->_db->geterrorMsg() );
					}else{
						//die("basás");
					}
				}
				break;			
			default: ;
		}
	}
	
	function saveTetelek($id){
		//die ($id);
		$tetel_id = jrequest::getvar('tetel_id', array() );
		$netto_ar = jrequest::getvar('netto_ar', array() );
		$quantity = jrequest::getvar('quantity', array() );
		$tomeg = jrequest::getvar('tomeg', array() );		
		$gyariszam = jrequest::getvar('gyariszam', array() );
		$netto_ar_beszallito = jrequest::getvar('netto_ar_beszallito', array() );		
		$beszallito_id = jrequest::getvar('beszallito_id', array() );		
		//print_r($tetel_id);
		//die;

		foreach($tetel_id as $id){
			$ind = array_search($id, $tetel_id);
			$o="";
			$o->id = $id;
			$o->netto_ar = $netto_ar[$ind];
			$o->quantity = $quantity[$ind];
			$o->tomeg = $tomeg[$ind];
			$o->netto_ar = $netto_ar[$ind];
			$o->beszallito_id = $beszallito_id[$ind];			
			$o->netto_ar_beszallito = $netto_ar_beszallito[$ind];
			$o->gyariszam = $gyariszam[$ind];
			//print_r($o);
			//die;
			$this->_db->updateObject("#__wh_tetel", $o, "id");
		}
		
	}
}// class
?>