<?php
defined( '_JEXEC' ) or die( '=;)' );

		include("Classes/PackageResult.php");
		include("Classes/PartnerPudoService.php");
		include("Classes/RegisterShipmentRequest.php");
		include("Classes/RegisterShipmentResponse.php");
		include("Classes/RegisterShipment.php");
		include("Classes/RegisterReturningPackagesRequest.php");
		include("Classes/RegisterReturningPackagesResponse.php");
		include("Classes/RegisterReturningPackages.php");
		include("Classes/Package.php");
		
		
class pickpack extends xmlParser{
	
	function __construct(){

		//$this->base_template = new base_template;
		//$this->webContent = new webContent;
		$this->_db = JFactory::getDBO();
		$this->db =JFactory::getDBO();		
		$this->user=JFactory::getUser();
		$this->beallitas = $this->getBeallitas();
		$this->user->jog = $this->getJog();

	}
	function getCsomagId($cs){
		//print_r($cs); die();
		$rend_id = $cs->id;
		$prefix = "PC";
		$partner_azon = '';
		$cel_kod = $cs->pickpack_atvhely_id;
		$datum = date('Ymd',time());
		$ret = $prefix.$partner_azon.$cel_kod.$datum.$rend_id;
		return $ret;   
	
	}
	
	function getSzallitmanyId($sz){
		$szal_sorszam = '';
		$prefix = "SP";
		$ret = $prefix.date('Ymdhis',time()).$szal_sorszam;
		
		
		//echo $ret; die();
		return $ret;   
	
	}

	function setSzallitmanyok($szallitmanyok){
		ini_set("soap.wsdl_cache_enabled","0");
		//print_r($r); die();
		foreach ($szallitmanyok as $sz){
			
			$pickpack_szallitmany_id = $this->getSzallitmanyId($sz);
			
			$client=new PartnerPudoService("https://tsx.lapker.hu/PudoTest/PartnerPudoService?wsdl");
			$params=new RegisterShipment;
			$params->request= new RegisterShipmentRequest;
				
					//A kimenő kérés adatai:
					
					
			$params->request->ShipmentId=$pickpack_szallitmany_id;
			$params->request->PartnerCode="0000001000";
			$params->request->ArriveDate=date("Y-m-d");
			$params->request->IsDirectShipment=1;
			$params->request->PartnerAddress="postacím";
			$params->request->IsSupplementData=0;
			$params->request->Supplier="Lapker";

			
			foreach ($sz as $csomag){
				//print_r($csomag); die();
				$q = "update #__wh_rendeles set pickpack_szallitmany_id = '{$pickpack_szallitmany_id}' where id = '{$csomag->id}'";
				$this->_db->setquery($q);
				$this->_db->query();
				//echo $this->_db->geterrormsg();
				//die('dsf');

				$v = $this->getVasarlo( $csomag->user_id, $csomag->webshop_id );
				//print_r($v); die();
				parse_str($v->felhasznalo->szamlazasi_cim,$arr);
				
					//Csomag adatok megadása
				$package=new Package;
				$package->AutorizationCode='67654567654567654567898765';
				$package->BarCode=$this->getCsomagId($csomag);
				
				$q = "update #__wh_rendeles set pickpack_csomag_id = '{$package->BarCode}' where id = '{$csomag->id}'";
				$this->_db->setquery($q);
				$this->_db->query();

				
				$package->CustomerAddress=$arr['IRANYITOSZAM'].', '.$arr['VAROS'].', '.$arr['UTCA'];
				$package->CustomerEmail=$v->user->email;
				if (
					$arr['SZAMLAZASI_NEV'] != ''){$package->CustomerName = $arr['SZAMLAZASI_NEV'];
				} else {
					$package->CustomerName = $v->name;
				}				
				$package->CustomerPhone=$v->felhasznalo->telefon;
				$package->CustomerType="B2C";
				$package->PackagePrice=$this->getCod($csomag);
				$package->PackageType="Small";
				$package->PriceAtDelivery=$this->getCod($csomag);
				//$package->ShopId=$o->pickpack_atvhely_id;
				$package->ShopId='0000101117';
				$package->Tracking=0;
			
				//Csomag hozzáadása a kimenő paraméterekhez
				$params->request->Packages[]=$package;
			}
					//Adatok küldése a RegisterShipment függvénynek
				$response=$client->RegisterShipment($params);
					
					//Válasz kezelése
				if($response->RegisterShipmentResult->ErrorCode=="PSR_OK"){
					 /*
					  * A php "problémás" típuskonverziója miatt szükség van erre a ciklusra:
					  */
					$packages=array();
					foreach($response->RegisterShipmentResult->PackageResults as $package){
						if(isset($package->ErrorCode)){
							$packages[]=$package;
						}else{
							$packages=$package;
						}
					}
					$response->RegisterShipmentResult->PackageResults=$packages;
					
					
					echo ('A '.$pickpack_szallitmany_id.' szállítmányt feladtuk a PPP rendszernek!<br />');
						
					   /*
						* Ide lehet írni a válaszként érkező adatokat feldolgozó kódot.
						*
						* A " $response->RegisterShipmentResult"-ban szerepel minden adat ami válaszként érkezik.
						* pl. Az első csomagra vonatkozó hibakód ilyen formában elérhető:
						*     $response->RegisterShipmentResult->PackageResults[0]->ErrorCode;
						*
					   */
				
					}else{
						echo("Kéréssel valamilyen gond van. Hibakód:".$response->RegisterShipmentResult->ErrorCode);
					}
				
				
			
		}
		die();
	
	}
	
	function setReturningPackages($r, $t){

		ini_set("soap.wsdl_cache_enabled","0");
		//print_r($r); die();
		
			
			$pickpack_szallitmany_id = $this->getSzallitmanyId($r);
			
			$tetelek = implode(',',$t);
			$this->_db->setquery("update #__wh_tetel set visszaru = '{$pickpack_szallitmany_id}' where id in ('{$tetelek}')");
			$this->_db->query();
			
			
			$client=new PartnerPudoService("https://tsx.lapker.hu/PudoTest/PartnerPudoService?wsdl");
			$params=new RegisterReturningPackages;
			$params->request= new RegisterReturningPackagesRequest;
				
					//A kimenő kérés adatai:
					
					
			$params->request->ShipmentId=$pickpack_szallitmany_id;
			$params->request->PartnerCode="0000001000";
			$params->request->DeliverableDateFrom=date("Y-m-d");
			$params->request->DeliverableDateTo=date("Y-m-d", time()+24*60*60);
			$params->request->PartnerAddress="Fapadoskonyv.hu postacím";
			$params->request->IsSupplementData=0;
			$params->request->Supplier="Lapker";
	
			
				//print_r($csomag); die();
				$v = $this->getVasarlo( $r->user_id, $r->webshop_id );
				//print_r($v); die();
				parse_str($v->felhasznalo->szamlazasi_cim,$arr);
				
					//Csomag adatok megadása
	
	
				
				$package=new Package;
				$package->AutorizationCode='67654567654567654567898765';
				$package->BarCode=$this->getCsomagId($r);
				$package->OriginalBarCode = 'PC20110103810';
				$package->CustomerAddress=$arr['IRANYITOSZAM'].', '.$arr['VAROS'].', '.$arr['UTCA'];
				$package->CustomerEmail=$v->user->email;
				if (
					$arr['SZAMLAZASI_NEV'] != ''){$package->CustomerName = $arr['SZAMLAZASI_NEV'];
				} else {
					$package->CustomerName = $v->name;
				}				
				$package->CustomerPhone=$v->felhasznalo->telefon;
				$package->CustomerType="B2C";
				$package->PackagePrice=$this->getCod($r);
				$package->PriceAtDelivery=$this->getCod($r);
				$package->PackageType="Small";
				
				//$package->PriceAtDelivery=$this->getCod($r);
				//$package->ShopId=$o->pickpack_atvhely_id;
				$package->Tracking=0;
				//print_r($package); die();
				//Csomag hozzáadása a kimenő paraméterekhez
				$params->request->Packages[]=$package;
			
			//print_r($params); die();
					//Adatok küldése a RegisterReturningPackages függvénynek
			
			
				//print_r($params); die();
				$response=$client->RegisterReturningPackages($params);
				
				die('eddig');	
					//Válasz kezelése
				if($response->RegisterReturningPackagesResult->ErrorCode=="PSR_OK"){
					 /*
					  * A php "problémás" típuskonverziója miatt szükség van erre a ciklusra:
					  */
					$packages=array();
					foreach($response->RegisterReturningPackagesResult->PackageResults as $package){
						if(isset($package->ErrorCode)){
							$packages[]=$package;
						}else{
							$packages=$package;
						}
					}
					$response->RegisterReturningPackagesResult->PackageResults=$packages;
					
					
					echo ('A '.$pickpack_szallitmany_id.' visszáru szállítmányt feladtuk a PPP rendszernek!<br />');
					//print_r($packages);	
					   /*
						* Ide lehet írni a válaszként érkező adatokat feldolgozó kódot.
						*
						* A " $response->RegisterShipmentResult"-ban szerepel minden adat ami válaszként érkezik.
						* pl. Az első csomagra vonatkozó hibakód ilyen formában elérhető:
						*     $response->RegisterShipmentResult->PackageResults[0]->ErrorCode;
						*
					   */
				
					}else{
						echo("Kéréssel valamilyen gond van. Hibakód:".$response->RegisterReturningPackagesResult->ErrorCode);
					}
				
				
			
		
		die();
	
	}
	
	function setSzallitmany($r){
		ini_set("soap.wsdl_cache_enabled","0");
		//print_r($r); die();
		$v = $this->getVasarlo( $r->user_id, $r->webshop_id );
		//print_r($v); die();
		parse_str($v->felhasznalo->szamlazasi_cim,$arr);
		try{
			$client=new PartnerPudoService("https://tsx.lapker.hu/PudoTest/PartnerPudoService?wsdl");
			$params=new RegisterShipment;
			$params->request= new RegisterShipmentRequest;
		
			//A kimenő kérés adatai:
			
			
			$params->request->ShipmentId=$r->pickpack_szallitmany_id;
			$params->request->PartnerCode="0000001000";
			$params->request->ArriveDate=date("Y-m-d");
			$params->request->IsDirectShipment=1;
			$params->request->PartnerAddress="Fapadoskonyv.hu postacím";
			$params->request->IsSupplementData=0;
			
			$params->request->Supplier="Lapker";
			//Csomag adatok megadása
			$package=new Package;
			$package->AutorizationCode='67654567654567654567898765';
			$package->BarCode="21545458951254789654222256";
			$package->CustomerAddress=$arr['IRANYITOSZAM'].', '.$arr['VAROS'].', '.$arr['UTCA'];
			$package->CustomerEmail=$v->email;
			if ($arr['SZAMLAZASI_NEV'] != ''){$package->CustomerName = $arr['SZAMLAZASI_NEV'];} else {$package->CustomerName = $v->name;}
			$package->CustomerPhone=$v->felhasznalo->telefon;
			$package->CustomerType="B2C";
			$package->PackagePrice=$this->getCod($r);
			$package->PackageType="Small";
			$package->PriceAtDelivery=$this->getCod($r);
			//$package->ShopId=$o->pickpack_atvhely_id;
			$package->ShopId='0000101117';
			$package->Tracking=1;
		
			//Csomag hozzáadása a kimenő paraméterekhez
			$params->request->Packages[]=$package;
		
			//Adatok küldése a RegisterShipment függvénynek
			$response=$client->RegisterShipment($params);
			
			//Válasz kezelése
			if($response->RegisterShipmentResult->ErrorCode=="PSR_OK"){
			 /*
			  * A php "problémás" típuskonverziója miatt szükség van erre a ciklusra:
			  */
				$packages=array();
				foreach($response->RegisterShipmentResult->PackageResults as $package){
					if(isset($package->ErrorCode)){
						$packages[]=$package;
					}else{
						$packages=$package;
					}
				}
				$response->RegisterShipmentResult->PackageResults=$packages;
				
				return ('A szállítmányt feladtuk a PPP rendszernek!');
		
			   /*
				* Ide lehet írni a válaszként érkező adatokat feldolgozó kódot.
				*
				* A " $response->RegisterShipmentResult"-ban szerepel minden adat ami válaszként érkezik.
				* pl. Az első csomagra vonatkozó hibakód ilyen formában elérhető:
				*     $response->RegisterShipmentResult->PackageResults[0]->ErrorCode;
				*
			   */
		
			}else{
				return("Kéréssel valamilyen gond van. Hibakód:".$response->RegisterShipmentResult->ErrorCode);
			}
		
		} catch(SoapFault $e){
			var_dump($e);
			return("Hiba történt a kérés elküldése közben.");
			
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
	
	

}// class
?>
