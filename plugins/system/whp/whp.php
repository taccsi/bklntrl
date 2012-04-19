<?php
defined('_JEXEC') or die('=;)');
jimport('joomla.plugin.plugin');

class plgSystemwhp extends JPlugin{
    function plgSystemwhp( &$subject, $config ){
		parent::__construct( $subject, $config );
       // Do some extra initialisation in this constructor if required
    }//function

	function style(){
		$document =& JFactory::getDocument();
		//$document->addStyleSheet("templates/drpadlo/js/jquery-tooltip/jquery.tooltip.css");				
		$document->addStylesheet('components/com_whp/assets/jquery/jquery-autocomplete/jquery.autocomplete.css');		
		$document->addStylesheet('components/com_whp/assets/jquery/jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.css');				
		//$document->addStylesheet('components/com_whp/assets/jquery/jquery.confirm/jquery.confirm/jquery.confirm.css');			
		//$document->addStylesheet('components/com_whp/assets/jquery/star-rating/jquery.rating.css');			
		//$document->addStylesheet('components/com_whp/assets/jquery/jquery-autocomplete/jquery.autocomplete.css');	
		//$document->addStylesheet('components/com_whp/assets/jquery/jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.css');					
		$document->addStylesheet("modules/mod_whptop10/assets/css/style.css");			
	}

	function incl(){
		$this->style();
		$document =& JFactory::getDocument();
		//$document->addScript("components/com_whp/assets/js/js_alap.js");

		$document->addScript("components/com_whp/assets/js/js_trifid.js");
				
			//return 1;
		//phpinfo();
		//JHTML::_('behavior.modal');
		//jimport('joomla.html.pane'); 
		
		
		//$document->addScript("templates/drpadlo/js/superfish/hoverintent.js");
		//$document->addScript("templates/drpadlo/js/superfish/superfish.js");
		//$document->addScript("templates/drpadlo/js/superfish/supersubs.js");
		//$document->addScript("templates/drpadlo/js/jquery-tooltip/jquery.tooltip.js");
		$document->addScript('components/com_whp/assets/jquery/jquery-autocomplete/lib/jquery.autocomplete.pack.js');
		$document->addScript('components/com_whp/assets/jquery/jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.pack.js');
		//$document->addScript('components/com_whp/assets/jquery/jquery.form.js');
		//$document->addScript( 'components/com_whp/assets/jquery/jquery.confirm/jquery.confirm/jquery.confirm.js' );
		//$document->addScript('components/com_whp/assets/jquery/star-rating/jquery.rating.pack.js');
		$document->addScript('components/com_whp/assets/jquery/jquery-autocomplete/lib/jquery.autocomplete.js');
		//$document->addScript("modules/mod_whptop10/assets/js/jcarousel.js");
		//$document->addScript("modules/mod_xkepvalto/assets/js/adgallery/jquery.adgallery.js");		


		//$document->addScript("components/com_whp/assets/js/js.js");
		//$document->addscript("components/com_whp/assets/js/cimkekereso.js");
		//$document->addScript("components/com_whp/assets/js/kosar.js");
		//$document->addScript( "components/com_whp/assets/js/comment.js" );
		//$document->addScript( 'components/com_whp/assets/js/kalkulator.js' );		
		
		
	}
	
	function inclFiles(){
		jimport( "unitemplate.simpleimage.simpleimage");
		require_once("components/com_whp/helpers/whpBeallitasok.php" );
		require_once("administrator/components/com_whp/helpers/json.php");		
		require_once("administrator/components/com_whp/helpers/listazo.php");
		require_once("administrator/components/com_whp/helpers/baseModel.php"); 
		require_once("administrator/components/com_whp/helpers/xml.php"); 
		require_once("administrator/components/com_whp/helpers/kep.php");
		require_once("administrator/components/com_whp/helpers/xComponent.php"); 
		require_once("administrator/components/com_whp/helpers/termek.php"); 
		require_once("administrator/components/com_whp/helpers/xComponent.php");
		require_once("administrator/components/com_whp/helpers/kategoriafa.php");		
		require_once("components/com_whp/helpers/xComponent.php");		
		require_once("administrator/components/com_whp/helpers/webContent.php");
		require_once("administrator/components/com_whp/helpers/ar.php");
		require_once("components/com_whp/models/termek.php");
		require_once("components/com_whp/models/epub.php");		
	}	

	function onAfterInitialise()
	{
	}//function
	
	function onAfterRoute(){
	  if( file_exists("administrator") && jrequest::getvar('option')!='com_wh' ){
		  //$this->inclFiles();
		  $this->incl();
	  }
	}//function
	
	function onAfterDispatch()
	{
	}//function
	
	function onAfterRender()
	{
	}//function
	
}