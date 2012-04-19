<?php
defined( '_JEXEC' ) or die( '=;)' );
require_once("components/com_whp/models/kosar.php");
class whpModelbkfizetes extends whpPublic{
	var $xmlFile = "bkfizetes.xml";
	var $table = "#__whp_bkfizetes";
	//http://teszt10.trifid.hu/index.php?option=com_whp&controller=bkfizetes&rendeles_id=153
	
	function __construct(){
		$this->paymentGateway = new paymentGateway;		
		parent::__construct();
		@$this->sess = JSession::getInstance();
		$this->rendeles_id = JRequest::getVar( "rendeles_id", "" );	
		//$this->getData();
	 	//print_r($this->_data); die();
		/*
		$this->xmlParser = new xmlbkfizetes($this->xmlFile, $this->_data);
		$this->xmlParserFelhasznalo = new xmlFelhasznalo("felhasznalo.xml", "");
		$this->kosar = new whpModelKosar;
		*/
		
	}//function

	function result(){
		return "------";
	}

	function response( ){
		$responseArray = array();
	    $responseArray = $this->paymentGateway->result( $_GET["TransactionId"] );
		$rendeles = $this->getObj("#__wh_rendeles", $_GET["TransactionId"], "TransactionId" );
		$ret = "";
		if($rendeles){
			$ret ="";
			if( $responseArray['ResultCode'] == "SUCCESSFUL" ){
				$ret .= jtext::_("SIKERES_BANKI_TRANZAKCIO_CIM");
			}else{
				$ret .= jtext::_( "SIKERTELEN_BANKI_TRANZAKCIO_CIM" )."<br />";
				$ret .= jtext::_( "FIZETES_ALLAPOTA" ).": ".jtext::_( $responseArray['ResultCode'] )."<br />";
			}
			$rendeles->fizetes_allapot = $responseArray['ResultCode'];
			$this->_db->updateObject( "#__wh_rendeles", $rendeles, "id" );
			$this->kuldEmail( $responseArray, $rendeles );
		}
		return $ret;	
	}

	function kuldEmail( $responseArray, $rendeles = "" ){
		$params = &JComponentHelper::getParams( 'com_whp' );
		$from = $this->params->get( 'felado_email' );
		$fromname = $this->params->get( 'felado_nev' );
		$subject = ( $responseArray['ResultCode'] == "SUCCESSFUL") ? jtext::_("SIKERES_BANKI_TRANZAKCIO") : jtext::_("SIKERTELEN_BANKI_TRANZAKCIO");
		$body = "";
		$body .= ( $responseArray['ResultCode'] == "SUCCESSFUL") ? jtext::_("SIKERES_BANKI_TRANZAKCIO_BODY") : jtext::_("SIKERTELEN_BANKI_TRANZAKCIO_BODY");	
		$body .= "<br />";		
		$a = $this->getFizetesiAdatok( $rendeles->id );
		$body .= jtext::_("BANK").": {$a->provider}<br />";	
		$body .= jtext::_("OSSZEG").": ".ar::_($this->getOsszertek($rendeles->id)+$rendeles->kiszallitas_ar)."<br />";
		$body .= jtext::_("VALUTA").": {$a->currency}<br />";
		$body .= jtext::_("NYELV").": {$a->language}<br />";
		$body .= jtext::_("FIZETES_ALLAPOTA").": ".jtext::_($rendeles->fizetes_allapot)."<br />";		
		$body .= jtext::_("RENDELES_AZONOSITO").": {$rendeles->id}<br />";			
		$body .= jtext::_("TRANZAKCIO_AZONOSITO").": {$rendeles->TransactionId}<br />";
		$body .= "<br /><br />";				
		$body .= $this->params->get( 'BANKOS_LEVEL_INFORMACIO' );

		$recipient=array();
		$q = "select * from #__users as u where id = {$rendeles->user_id} ";
		//echo $q;
		$this->db->setQuery( $q );
		$u_ = $this->db->loadObject(  );
		echo $this->db->getErrorMsg(  );
		//echo $this->_db->getQuery(  );
		$recipient=array();
		$recipient[]=$u_->email;
		$recipient[]="szabolcs@trifid.hu";
		$mode = 1;
		//die("$from, $fromname, $recipient, $subject, $body, $mode ") ;		
		JUtility::sendMail( $from, $fromname, $recipient, $subject, $body, $mode );
	}

	function init(){
		global $mainframe;
		//echo $mainframe->getCfg("live_site")."<br />";
		$r = $this->getObj("#__wh_rendeles", $this->rendeles_id);
		if( $r->fizetes_allapot == "PENDING" ){
			//echo $url;
			$o_ = $this->getFizetesiAdatok( $this->rendeles_id, "response" );
			$responseArray = $this->paymentGateway->init(
				$o_->provider,
				$o_->responseUrl,
				$o_->amount,
				$o_->orderId,
				$o_->userId,
				$o_->currency,
				$o_->language
			);
			//print_r($o_);
			//die;
			if ( $responseArray['ResultCode'] == "SUCCESSFUL" && $responseArray['TransactionId'] ){
				$r->TransactionId = $responseArray["TransactionId"];
				$this->_db->updateObject("#__wh_rendeles", $r, "id" );				
				$this->paymentGateway->start($responseArray["TransactionId"]);
			}else{
				$paymentgatewayErrorMessage = $responseArray["ResultCode"].": ".$responseArray["ResultMessage"];
				$paymentgatewayErrorMessage.= "<br/><br/><xmp>".print_r($responseArray, true)."</xmp>";
			}			
			//print_r($ret);
			//die;			
		}
		//$this->paymentGateway->start('0a62408585bdfbb54851fd454078641d');
		//print_r( $ret );
		//return $ret;
		//paymentGateway
	}
	
	function getFizetesiAdatok( $rendeles_id, $task = "init" ){
		global $mainframe;
		$r = $this->getObj( "#__wh_rendeles", $rendeles_id );
		$ret = "";
		$ret->provider = "CIB"; //CIB;
		$ret->responseUrl = $mainframe->getCfg( "live_site" )."/index.php?option=com_whp&controller=bkfizetes&task={$task}";
		$ret->amount = round($this->getOsszertek($rendeles_id)+$r->kiszallitas_ar);
		$ret->orderId = $rendeles_id;
		$ret->userId = $this->user->id;
		$ret->currency = "HUF";
		$ret->language = "HU";
		return $ret;
	}

	function getOsszertek($rendeles_id){
		$q = "select  sum( netto_ar * quantity *( afa/100 + 1 )  ) as osszertek from #__wh_tetel where rendeles_id = {$rendeles_id}";
		$this->_db->setQuery($q);
		return $this->_db->loadResult();
	}	
}// class
?>
