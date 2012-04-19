<?php
defined( '_JEXEC' ) or die( '=;)' );
//require_once("components/com_prsgal/models/base.php");
require_once( 'administrator/components/com_whp/helpers/ar.php' );
class mod_whpkosar
{
	function __construct($params){
		$this->params=$params;
		$this->incl();		
		@$sess = jsession::getinstance();
		(array)$this->kosar = $sess->get("kosar");
		echo "<div id=\"ajaxContentKiskosar\"></div>";
		$doc = jfactory::getDocument();
		//$doc->addScript("components/com_whp/assets/js/js.js");
		$doc->addscriptDeclaration("\$j(document).ready(function(){getKiskosar()});");
		//$this->html();
	}

	function incl(){
		require_once("components/com_whp/helpers/whpBeallitasok.php" );		
		jimport( "unitemplate.simpleimage.simpleimage");
		require_once("administrator/components/com_whp/helpers/json.php");		
		require_once("administrator/components/com_whp/helpers/listazo.php");
		require_once("administrator/components/com_whp/helpers/baseModel.php"); 
		require_once("administrator/components/com_whp/helpers/xml.php"); 
		require_once("administrator/components/com_whp/helpers/kep.php");
		require_once("administrator/components/com_whp/helpers/xComponent.php"); 
		require_once("administrator/components/com_whp/helpers/termek.php"); 
		require_once("administrator/components/com_whp/helpers/xComponent.php");
		require_once("components/com_whp/helpers/xComponent.php");		
		require_once("administrator/components/com_whp/helpers/webContent.php");
		require_once("administrator/components/com_whp/helpers/ar.php");
		require_once("components/com_whp/models/termek.php");
		
		//$this->xmlParser = new xmlParser( "termek.xml" );
		//JHTML::_('behavior.modal');
	}	

}
new mod_whpkosar($params);
?>