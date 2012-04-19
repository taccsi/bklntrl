<?php
defined( '_JEXEC' ) or die( '=;)' );
//ini_set("display_errors", 1);
require_once("components/com_whp/models/kosar.php");
class whpModelRendeles extends whpPublic{
	var $xmlFile = "rendeles.xml";
	var $table = "#__whp_rendeles";

	function __construct(){
		parent::__construct(); 
		@$this->sess = JSession::getInstance();
		$this->value = JRequest::getVar("value", "");	
		$this->getData();
	 	//print_r($this->_data); die();
		$this->xmlParser = new xmlRendeles($this->xmlFile, $this->_data);
		$this->xmlParserFelhasznalo = new xmlFelhasznalo( "felhasznalo.xml", "" );
		$this->kosar = new whpModelKosar;
	}//function
/*

$this->oXMLout->writeAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');

$this->oXMLout->startElement("Offer"); 

$this->oXMLout->writeElement("Title",$this->getXmlNev($row));					

$this->oXMLout->writeElement("TitleInternal",$this->getXmlNev($row));		

*/

	function genXml( $rendeles, $tetelek ){
		//die;
		$oXMLout = new XMLWriter();
		$oXMLout->openMemory();
		$e_ = "\n";
		//$e_ = PHP_EOL;
		$ret="";
		$ret .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
		$datestring = date("d-m-Y H:i:s");
		$oXMLout->startElement("rendeles");
		$oXMLout->text( $e_ );
			$oXMLout->startElement("fejlec");
			$oXMLout->text( $e_ );
				$oXMLout->writeElement( "rend_id", $rendeles->id );
				$oXMLout->text( $e_ );
				$oXMLout->writeElement( "felh_id", @$this->user->id );
				$oXMLout->text( $e_ );
				$oXMLout->startElement( "szamlazasi_cim" );
				$oXMLout->text( $e_ );
					parse_str($rendeles->szamlazasi_cim, $arrSzamlazasi);	
					//print_r($rendeles);		
					//die;
					$oXMLout->writeElement( "SZAMLAZASI_NEV", $arrSzamlazasi["SZAMLAZASI_NEV"] );
					$oXMLout->text( $e_ );
					$oXMLout->writeElement( "IRANYITOSZAM", $arrSzamlazasi["IRANYITOSZAM"] );
					$oXMLout->text( $e_ );
					$oXMLout->writeElement( "VAROS", $arrSzamlazasi["VAROS"] );
					$oXMLout->text( $e_ );
					$oXMLout->writeElement( "UTCA", $arrSzamlazasi["UTCA"] );
					$oXMLout->text( $e_ );
				$oXMLout->endElement();
				$oXMLout->text( $e_ );
				$oXMLout->startElement("szallitasi_cim");
				$oXMLout->text( $e_ );
					//parse_str($rendeles->szallitasi_cim);			
					parse_str($rendeles->szallitasi_cim, $arrSzallitasi);						
					$oXMLout->writeElement( "SZALLITASI_NEV", $arrSzallitasi["SZALLITASI_NEV"] );
					$oXMLout->text( $e_ );
					$oXMLout->writeElement( "IRANYITOSZAM", $arrSzallitasi["IRANYITOSZAM"] );
					$oXMLout->text( $e_ );
					$oXMLout->writeElement( "VAROS", $arrSzallitasi["VAROS"] );
					$oXMLout->text( $e_ );
					$oXMLout->writeElement( "UTCA", $arrSzallitasi["UTCA"] );
					$oXMLout->text( $e_ );
				$oXMLout->endElement();
				$oXMLout->text( $e_ );
			$oXMLout->endElement();//fejléc vége
			$oXMLout->text( $e_ );
			//print_r($tetelek);
			$brutto_osszesen=0;
		foreach( $tetelek as $t ){
			if( $t->ar ){
				$oXMLout->startElement( "sorok" );
				$oXMLout->text( $e_ );
					$oXMLout->writeElement( "cikkszam", ($t->cikkszam) ? $t->cikkszam : $t->nev );
					$oXMLout->text( $e_ );
					
					$oXMLout->writeElement( "termeknev", $t->nev );
					$oXMLout->text( $e_ );
					
					$oXMLout->writeElement( "mennyiseg", $t->mennyiseg );
					$oXMLout->text( $e_ );
					$oXMLout->writeElement( "netto_ar", $t->ar );
	
					$brutto_ar = ar::getKerekitettAr(ar::getBrutto( $t->ar ,$t->afaErtek ));
					$oXMLout->text( $e_ );
					$oXMLout->writeElement( "brutto_ar", $brutto_ar );
					$oXMLout->text( $e_ );				
	
					$oXMLout->writeElement( "afa", $t->afaErtek );
					$oXMLout->text( $e_ );
					
					$brutto_osszesen += ( $t->ar * $t->mennyiseg * ( $t->afaErtek / 100 + 1 ) );
					
				$oXMLout->endElement();
				$oXMLout->text( $e_ );
			}
		}
			$oXMLout->writeElement( "brutto_osszesen", ar::getKerekitettAr( $brutto_osszesen ) );
			$oXMLout->text( $e_ );
		$oXMLout->endElement();
		$oXMLout->text( $e_ );
			//$oXMLout->startElement("szallitasi_cim");
			//$oXMLout->endElement();		
		$ret.= $oXMLout->outputMemory( true );
		//echo $ret;
		//die( $ret );
		$filename="szamlazo/rend".date( "ymdHi", time() ).".xml";
		$fp = fopen( $filename, "w" );
		fputs($fp, $ret);
		fclose($fp);
		//die("----");		
	}

	function setKedvezmeny(){

		$azonosito_kod = $this->getSessionVar("azonosito_kod");

		$q = "select * from #__wh_kupon where azonosito_kod = '{$azonosito_kod}' ";

		$this->_db->setQuery( $q );

		$kupon = $this->_db->loadObject();

		$kupon ->felhasznalas_datum = date("Y-m-d H:i:s", time() );

		$kupon ->rendeles_id = $this->rendeles_id;

		$kupon ->szamlalo += 1;

		$this->_db->updateObject("#__wh_kupon", $kupon, "id");

		//echo $q."<br />";

		//print_r( $kupon );

		//die;

	}



	function getKosarOszzArTetelek($kosar){

		$osszar = 0;		

		if( count($kosar) ){

			foreach( $kosar as $k ){

				//print_r($k);

				if($k->cikkszam && $k->cikkszam != "KEDVEZMENY" ) $osszar += ( $k->ar*($k->afaErtek/100+1 ) * $k->mennyiseg);

			}

		}

		return $osszar;

	}



	function ellenorizKupon(){

		ob_start();

		$ret_="";

		$ret_->html = "";

		$ret_->error = "";//$error;	

		$error = array();	

		$azonosito_kod = jrequest::getVar( "azonosito_kod","" );

		$q = "select * from #__wh_kupon where 

		azonosito_kod = '{$azonosito_kod}' and webshop_id = {$GLOBALS['whp_id']} and aktiv = 'igen' "; 

		$this->_db->setQuery( $q );

		$kupon = $this->_db->loadObject();

		$kosar = $this->getSessionVar("kosar");

		$osszesen = $this->getKosarOszzArTetelek( $kosar );

		if(@$kupon){

			if(@$kupon->tipus == "EGYEDI" ){

				if( $kupon->felhasznalas_datum != '0000-00-00 00:00:00' ){

					$error[]= jtext::_("FELHASZNALT_KUPON");

				}

			}

			if( @$kupon->ertekhatar ){

				if( $osszesen < $kupon->ertekhatar ){

					$error[]= jtext::_("NEM_VASAROLT_MEGFELELO_ERTEKBEN" ) . ar::_( $kupon->ertekhatar );

				}

			}

			if( $kupon->datum_tol != "0000-00-00 00:00:00" || $kupon->datum_ig != "0000-00-00 00:00:00" ){

				if( time() < strtotime( $kupon->datum_tol ) || time() > strtotime( $kupon->datum_ig ) ){

					$error[]= jtext::_("LEJART_KUPON");

				}

			}

		}else{

			$error[]= jtext::_("HIBAS_AZONOSITO_KOD");

		}

		echo $this->_db->getErrorMsg(  );		

		if( !$error ){

			unset( $kosar["KEDVEZMENY"] );

			$o="";

			if( $kupon ->ertek_tipus == "%" ){

				//echo "<br />".$osszesen."<br />";
				
				$kedvezmeny = - $osszesen * ( $kupon->ertek/100 ) / ( ( $this->afa / 100 ) +1 ) ;

				//echo "<br />".$kedvezmeny."<br />";

			}else{

				$kedvezmeny = - $kupon->ertek / ( ( $this->afa / 100 ) +1 ) ;

			}

			$o->ar = $kedvezmeny;

			$o->afaErtek = $this->afa ;

			$o->cikkszam = "KEDVEZMENY";

			$o->mennyiseg = 1;

			$o->nev = jtext::_("AJANDEK_UTALVANY");

			$o->option = "AJANDEK_UTALVANY";

			$o->kategorianev="";

			$o->gyartonev="";

			$kosar["KEDVEZMENY"]=$o;

			$this->setSessionVar("kosar", $kosar );

			$ret_->html = jtext::_("AJANDEK_UTALVANY_SIKERESEN_ELLENORIZVE");

			//return $this->getjsonRet($ret_);

		}else{

			unset( $kosar["KEDVEZMENY"] );

			$this->setSessionVar("kosar", $kosar );			

			$j_ = "- ";

			$ret_->html.="";

			$ret_->html .= jtext::_("A_KOV_HIBAK")."<br />";

			$ret_->html.= $j_;

			$ret_->html.= implode("<br />{$j_} ", $error );

			$ret_->html.="";			

			//return $this->getjsonRet( $ret_ );

		}

		$ob = ob_get_contents();

		ob_end_clean();

		$ret_->html .= $ob;

		//$ret_->html = "";

		return $this->getjsonRet( $ret_ );

	}

	

	function getKosar(){

		$ret ="";

		$ret .= $this->kosar->getkosarLista( "noform" );

		//$ret .= $this->getAjaxMezok();				

		return $ret ;

	}

	

	function getAjaxMezok(){

		$szallitasiMod = jrequest::getVar("szallitasiMod", "");

		if (!$szallitasiMod){$szallitasiMod= "CSOMAGKULDO_SZOLGALAT_FIZETES_ATVETELKOR";};

		$ret =""; 

		switch($szallitasiMod){

			case "CSOMAGKULDO_SZOLGALAT_FIZETES_BANKKARTYAVAL":

			case "CSOMAGKULDO_SZOLGALAT_FIZETES_ATVETELKOR" :

			case "CSOMAGKULDO_SZOLGALAT_ELORE_UTALAS" :

			//echo $this->getKosarOszzAr()."--------------";

				//die("sfákksdfkl");

				$f_ = $this->xmlParser->getAllFormGroups();

				//$ret = "<h2 class=\"h2_form\">".jtext::_("SZALLITASI_ADATOK")."</h2>";

				//$ret .= html_entity_decode($f_["data5"]);

				//$ret .= " * * ** *  *";

			break;

			

			case "SZEMELYES_ATVETEL" :

				$this->setSessionVar("szallitasiKoltseg", "");

				$f_ = $this->xmlParser->getAllFormGroups();

				$ret = $this->getSzemelyesAtvetelCim();

			break;

			default: 

				$this->setSessionVar("szallitasiKoltseg", "");

				$ret = "&nbsp;";

		}

		

		$ret_="";

		$ret_->error="";

		$ret_->html=$ret;		

		return $this->getJsonRet($ret_);
	}

	function getSzemelyesAtvetelCim(){

		$ret = "<h2 class=\"h2_form\">".jtext::_("SZEMELYES_ATVETEL")."</h2>";

		$ret .="<table class=\"paramlist admintable\"><tbody><tr><td valign=\"top\" class=\"key\">".Jtext::_('ATVEVOHELY')."</td><td></td></tr></tbody></table>";

		return $ret;	

	}



	function getXmlString($group){

		$group = $this->xmlParser->getGroup($group);

		$ret = "";

		foreach ($group->childNodes as $element ){

			if(is_a($element, "DOMElement")){ 

				$l = $element->getAttribute('label');

				$n = $element->getAttribute('name');				

				$v = JRequest::getVar($n);

				$ret.= "&{$l}={$v}";

			}
		}	  
		return $ret;
	}

	function getAtvevohelyString(){

		if($atvhely_id = jrequest::getVar("atvhely_id") ){

			//die($atvhely_id. "---------------");

			$ret = '';

			$atv_hely = $this->getobj( "#__wh_atvhely", $atvhely_id );

			$ret.= "&ATVEVOHELY={$atv_hely->nev}";

			$telepules = $this->getobj("#__wh_telepules",$atv_hely->telepules_id)->telepules;

			$ret.= "&TELEPULES={$telepules}";

			$ret.= "&UTCA_HAZSZAM={$atv_hely->utca_hazszam}";

			$ret.= "&EMAIL={$atv_hely->email}";

		}else{

			$ret = "&nbsp;";

		}

		return $ret;

	}

	

	function store(){
		$o="";
		$o->datum = date("Y-m-d H:i:s", time() );
		$o->user_id = $this->user->id;
		//$o->rendelesi_adatok = $this->getRendelesiAdatok();
		$o->szamlazasi_cim = $this->getXmlString("data3");
		$o->szallitasi_cim = $this->getXmlString("data5");
		//$u = $this->getObj("#__wh_felhasznalo", $this->user->id, "user_id" );
		$q = "select * from #__wh_felhasznalo where user_id = {$this->user->id} and webshop_id = {$GLOBALS['whp_id']} ";
		$this->_db->setQuery($q);
		$u = $this->_db->loadObject();
		echo $this->_db->getErrorMsg(); 
		//echo $this->_db->getQuery(); 		
		$u->szamlazasi_cim = $o->szamlazasi_cim;
		$u->szallitasi_cim = $o->szallitasi_cim;		
		$this->_db->updateObject("#__wh_felhasznalo", $u, "id" );		
		$o->atvevohely = $this->getAtvevohelyString(  );
		$o->atvhely_id = jrequest::getVar("atvhely_id");
		$o->allapot = "UJ_MEGRENDELES";
		$o->megjegyzes = jrequest::getVar("megjegyzes");
		$o->webshop_id = $GLOBALS['whp_id'];
		$o->szallitas = Jrequest::getvar('szallitas');
		@$o->kiszallitas_ar = $this->getsessionvar('szallitasiKoltseg')->ar*(1+$this->getsessionvar('szallitasiKoltseg')->afaErtek/100);
		$o->telefon = Jrequest::getvar('telefon');
		$o->email = Jrequest::getvar('email');
		$this->_db->insertObject("#__wh_rendeles", $o, "id");
		echo $this->_db->geterrormsg();
		$this->rendeles_id = $this->_db->insertID();
		$rendeles = $o;
		$rend_ = $this->getObj( "#__wh_rendeles", $this->rendeles_id );
		$rend_->email_tartalom = $this->getEmailTetelek( $rend_ );
		$this->_db->updateObject( "#__wh_rendeles", $rend_, "id" );
		echo $this->_db->getErrorMsg( );		
		//die( "*****************" );
		$rendeles->id = $this->rendeles_id;
		$this->tetel_valtozok = $this->xmlParser->getGroupElementNames( "tetel_valtozok" );		
		$tetelek = $this->getsessionVar("kosar");
		/*
		if($k = $this->getSessionVar( "szallitasiKoltseg" )){
			$tetelek[] = $k;
		}
			$tetelek = array_map(array($this, "setTetelAdatok"), $tetelek );
			foreach($tetelek as $t){
				$this->_db->insertObject("#__wh_tetel", $t, "id");
		}
		*/
		foreach($tetelek as $t){
			$t_ = "";
			$t_->nev = $t->nev;
			$t_->rendeles_id = $this->rendeles_id;
			$t_->quantity = $t->mennyiseg;
			$t_->cikkszam = $t->cikkszam;
			($t_->cikkszam) ? $t_->cikkszam : $t_->cikkszam = "szallitas";
			$t_->termek_id = @$t->id;

			$t_->netto_ar = $t->ar;
			$t_->brutto_ar = ar::getKerekitettAr(ar::getBrutto( $t->ar ,$t->afaErtek ));
			
			$t_->afa = $t->afaErtek;
			$this->_db->insertObject("#__wh_tetel", $t_, "id");
		}
		//print_r( $tetelek );
		//die;
		$this->genXml( $rendeles, $tetelek );
		
		$this->kuldRendelesVisszaigazoloMail($o);
		$this->setKedvezmeny();
		$this->setSessionVar("kosar",array() );
		//$this->setSessionVar("szallitasiKoltseg",array() );
		return 1;
	}

	function setTetelAdatok($item){
		$tetel_adatok = "&";
		foreach($item as $key=>$v){
			if(in_array($key, $this->tetel_valtozok ) && $v ){
				$tetel_adatok.="{$key}={$v}&";
			}
		}
		$item="";
		$item->tetel_adatok = $tetel_adatok;
		$item->rendeles_id = $this->rendeles_id;
		return $item;
	}

	function szallitasiKoltsegTetelObj(){

	}

	function getFelhasznaloiAdatok(){
		//$vasarlo = $this->getVasarlo($this->user->id);
		$ret ="";
		$felhasznalo = $this->getObj("#__wh_felhasznalo", $this->user->id, "user");
		$q = "select * from #__wh_felhasznalo where user_id = {$this->user->id} and webshop_id = {$GLOBALS['whp_id']} ";
		$this->_db->setQuery($q);
		$felhasznalo = $this->_db->setQuery($q);
		$ret .= "&NEV={$this->user->name}&FELHASZNALONEV={$this->user->username}&EMAIL={$this->user->email}&TELEFON={$felhasznalo->telefon}";
		return $ret;	
	}

	function getRendelesiAdatok(){
		$arr = $this->xmlParser->getGroupElementNames( "publikusform" );
		$rendelesi_adatok = "&";
		foreach($arr as $a){
			$v = jrequest::getvar($a,"");
			$rendelesi_adatok.="{$a}={$v}&";
		}
		return $rendelesi_adatok;	
	}

	function getosszesenAdatok(){
		$felhasznaloi_adatok = "&";
		$osszObj = $this->getSessionVar("osszObj");
		$osszesen_adatok = "&";
		foreach($this->xmlParser->getGroupElementNames( "osszesen_valtozok" ) as $v ){
			$osszesen_adatok.="{$v}={$osszObj->$v}&";
		}
		return $osszesen_adatok;	
	}

	function kuldRendelesVisszaigazoloMail( $o){
		$params = &JComponentHelper::getParams( 'com_whp' );
		$from = $this->params->get( 'felado_email' );
		$fromname = $this->params->get( 'felado_nev' );
		$subject=$this->params->get( 'targy' ).' '.$o->id;
		$line = "<br /><br />-------------------------------------------------------------<br />";
		//$header ="<h2>".$this->params->get( 'udvozlet' )."</h2>";
		$header ="";
		//szallitasi adatok
		//print_r($o); die();
		//$this->params->get( 'szoveg' )."<br /><br />";

		switch($o->szallitas){
			case "SZEMELYES_ATVETEL" :
				$atvetelicim= $this->getSzemelyesAtvetelCim();
				$obj = $this->getobj('#__wh_webshop',$GLOBALS['whp_id']);
				$visszaigazolo_text = $obj->visszaigazolo_atvetelkor."<br />";
				//die();				
				break;
			case "CSOMAGKULDO_SZOLGALAT_FIZETES_ATVETELKOR" :
				$str = $this->getXmlString( "data5" )."<br />";
				parse_str($str);
				//$atvetelicim= "<strong>".Jtext::_('ATVETEL_FIZETES').": </strong>".Jtext::_($o->szallitas).'<br /><br />';
				$atvetelicim = "<strong>".Jtext::_('SZALLITASI_ADATOK')."</strong><br />";
				$atvetelicim .= jtext::_("SZALLITASI_NEV").": {$SZALLITASI_NEV}<br />";
				$atvetelicim .= jtext::_("SZALLITASI_CIM").": {$IRANYITOSZAM} {$VAROS}, {$UTCA}<br />";				
				$obj = $this->getobj('#__wh_webshop',$GLOBALS['whp_id']);
				//print_r($obj);
				$visszaigazolo_text = "<strong>Tisztelt {$SZALLITASI_NEV}!</strong><br />".$obj->visszaigazolo_atvetelkor."<br />";
				//die();		 		
				break;
			case "CSOMAGKULDO_SZOLGALAT_ELORE_UTALAS" :  
				$str = $this->getXmlString( "data5" )."<br />";
				parse_str($str);
				//$atvetelicim= "<strong>".Jtext::_('ATVETEL_FIZETES').": </strong>".Jtext::_($o->szallitas).'<br /><br />';
				$atvetelicim = "<strong>".Jtext::_('SZALLITASI_ADATOK')."</strong><br />";
				$atvetelicim .= jtext::_("SZALLITASI_NEV").": {$SZALLITASI_NEV}<br />";
				$atvetelicim .= jtext::_("SZALLITASI_CIM").": {$IRANYITOSZAM} {$VAROS}, {$UTCA}<br />";				
				$obj = $this->getobj('#__wh_webshop',$GLOBALS['whp_id']);
				$visszaigazolo_text = "<strong>Tisztelt {$SZALLITASI_NEV}!</strong><br />".$obj->visszaigazolo_elore_utalas."<br />";
				//print_r($obj);
				//die();		 		
				break;
		}
		
		$body .= $visszaigazolo_text.'<br /><br />';
		
		$body .= $this->getEmailTetelek( $o,$atvetelicim );
		$body .= $line;
		$body .= $this->params->get( 'elkoszones' );
		//die($body);
		$mode = 1;
		$recipient = array();
		$email__ = $o->email;
		$recipient[]= $email__;
		//die( $body );
		$email = new xCemail;
		$w = $this->getObj( "#__wh_webshop", $GLOBALS["whp_id"] );
		$rAdmin = explode(",", $w->inc_mail );
		//print_r($rAdmin);
		//die;
		$email->kuldLevel($from, $fromname, $recipient, $subject, $body, $footer, $header, $mode );
		$email->kuldLevel($from, $fromname, $rAdmin, "Új megrendelés érkezett", $body, "", "<h1>Rendszerüzenet</h1>", $mode );
		//die;
	}
	
	function getEmailTetelek( $o,$atvetelicim ){
		echo $atvetelicim;	
		//print_r($o); die('xx');	
		$body = "";
		$body .= "<strong>".Jtext::_('RENDELESSZAM')."</strong>: {$o->id}<br /><br />";
		$body .= "<strong>".Jtext::_('SZALLITAS')."</strong>:" .Jtext::_($o->szallitas)."<br /><br />";
		$body .= "<strong>".Jtext::_('TETELEK')."</strong><br />";
		$kosar = $this->kosar->getkosarLista( "email", 10 ); 
		ob_start();
		echo '<table cellpadding="5" width="880" border="0">';
		echo '<tr>';
		$first = $kosar[0];
		foreach ($first as $key => $t){
			echo '<th>'.Jtext::_($key).'</th>';
		}
		echo '</tr>';
		foreach ($kosar as $tetel){
			if (isset($tetel->EXTRA_HTML)){
				echo '<tr>';
				echo $tetel->EXTRA_HTML;
				echo '</tr>';
			} else {
			echo '<tr>';
			foreach ($tetel as $t){
				echo '<td>'.$t.'</td>';
			}
			echo '</tr>';
			}
		}
		echo '</table>';
		$body .= ob_get_contents();
		ob_end_clean();
		//print_r($kosar);
		//echo'<br />-----------------------------------------------------------<br />';
		//$body .= $this->getSzallitasiAdatok( $o );
		$body .= "<br /><br />";
		//print_r($o);
		//szamlazasi adatok
		$str = $this->getXmlString( "data3" );
		parse_str($str);
		$body .= "<strong>".Jtext::_('SZAMLAZASI_ADATOK')."</strong><br />";
		$body.= jtext::_("SZAMLAZASI_NEV").": {$SZAMLAZASI_NEV}<br />";
		$body.= jtext::_("SZAMLAZASI_CIM").": {$IRANYITOSZAM} {$VAROS}, {$UTCA}<br />";	
		$body .= "<br />";
		$body .= $atvetelicim."<br />";
		$body .= "<br/><strong>".Jtext::_('EMAIL').'</strong>: '.$o->email;		
		$body .= "<br/><strong>".Jtext::_('TELEFON').'</strong>: '.$o->telefon;
		$body .= "<br/><strong>".Jtext::_('MEGJEGYZES').'</strong>:<br /> '.$o->megjegyzes;
		//die($body);
		return $body;
	}
	
}// class

?>

