<?php
/**
 * @version SVN: $Id: builder.php 469 2011-07-29 19:03:30Z elkuku $
 * @package    wh
 * @subpackage Base
 * @author      {@link }
 * @author     Created on 28-Dec-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

jimport('joomla.plugin.plugin');

/**
 * System Plugin.
 *
 * @package    wh
 * @subpackage Plugin
 */
class plgSystemwh extends JPlugin
{
    /**
     * Constructor
     *
     * @param object $subject The object to observe
     * @param array $config  An array that holds the plugin configuration
     */
    public function __construct(& $subject, $config)
    {
        $doSomething = 'here';

        parent::__construct($subject, $config);
    }//function

	function incl(){
		if( file_exists("administrator") ){
			
			require_once( 'components/com_wh/helpers/initJsKonstansok.php' );
			require_once( 'components/com_wh/helpers/dbConnect.php' );
			require_once( 'components/com_wh/unitpl/base_template.php' );
			
			require_once( 'components/com_wh/helpers/menu.php' );
			require_once( 'components/com_wh/helpers/baseModel.php' );
			require_once( 'components/com_wh/helpers/xComponent.php' );
			
			require_once( 'components/com_wh/helpers/xml.php' );
			require_once( 'components/com_wh/helpers/kep.php' );
			require_once( 'components/com_wh/helpers/json.php' );

			require_once( 'components/com_wh/helpers/hirlevel.php' );
			require_once( 'components/com_wh/tables/wh_hirlevel.php' );

			require_once( 'components/com_wh/helpers/hirlevel_cim.php' );
			require_once( 'components/com_wh/tables/wh_hirlevel_cim.php' );
			
			require_once( 'components/com_wh/helpers/hirlevel_lista.php' );
			require_once( 'components/com_wh/tables/wh_hirlevel_lista.php' );
			
			require_once( 'components/com_wh/helpers/gallery.php' );
			require_once( 'components/com_wh/helpers/kupon.php' );
			require_once( 'components/com_wh/tables/wh_uzlet.php' );
			require_once( 'components/com_wh/helpers/uzlet.php' );
			require_once( 'components/com_wh/helpers/email.php' );
			require_once( 'components/com_wh/helpers/jogtul.php' );
			require_once( 'components/com_wh/helpers/szamlap.php' );
			require_once( 'components/com_wh/helpers/szerzo.php' );
			require_once( 'components/com_wh/helpers/atvhely.php' );
			require_once( 'components/com_wh/helpers/xmlBeszallito.php' );
			require_once( 'components/com_wh/helpers/xmlBeallitas.php' );
			require_once( 'components/com_wh/helpers/xmlKategoria.php' );
			require_once( 'components/com_wh/helpers/xmlWebshop.php' );
			require_once( 'components/com_wh/helpers/xmlGyarto.php' );
			require_once( 'components/com_wh/helpers/xmlKereses.php' );
			require_once( 'components/com_wh/helpers/termek.php' );
			require_once( 'components/com_wh/helpers/msablon.php' );
			require_once( 'components/com_wh/helpers/xmlMsablon_mezo.php' );
			require_once( 'components/com_wh/helpers/xmlKep.php' );
			require_once( 'components/com_wh/helpers/xmlRendeles.php' );
			require_once( 'components/com_wh/helpers/felhasznalo.php' );
			require_once( 'components/com_wh/helpers/komment.php' );
			require_once( 'components/com_wh/helpers/xmlTetel.php' );
			require_once( 'components/com_wh/helpers/ar.php' );
			require_once( 'components/com_wh/helpers/xmlKimutatas.php' );
			require_once( 'components/com_wh/helpers/xmlCimke.php' );
			require_once( 'components/com_wh/helpers/uzenet.php' );
			
			require_once( 'components/com_wh/helpers_futarszolgalat/dhl.php' );
			require_once( 'components/com_wh/helpers_futarszolgalat/pickpack.php' );
			require_once( 'components/com_wh/helpers_futarszolgalat/xmlDhl.php' );
			require_once( 'components/com_wh/helpers_futarszolgalat/xmlPickPack.php' );
			
			
			require_once( 'components/com_wh/helpers/fcsoport.php' );
			require_once( 'components/com_wh/helpers/kampany.php' );
			
			
			require_once( 'components/com_wh/helpers/baseController.php' );
			require_once( 'components/com_wh/helpers/kategoriafa.php' );
			require_once( 'components/com_wh/helpers/webContent.php' );
			require_once( 'components/com_wh/helpers/felhasznaloiJogok.php' );
			
			
			require_once( 'components/com_wh/tables/wh_ertekeles.php' );
			require_once( 'components/com_wh/tables/wh_gallery.php' );			
			require_once( 'components/com_wh/tables/wh_kupon.php' );
			require_once( 'components/com_wh/tables/wh_jogtul.php' );
			require_once( 'components/com_wh/tables/wh_kereses.php' );
			require_once( 'components/com_wh/tables/wh_szerzo.php' );
			require_once( 'components/com_wh/tables/wh_atvhely.php' );
			require_once( 'components/com_wh/tables/wh_fcsoport.php' );
			require_once( 'components/com_wh/tables/wh_kampany.php' );
			require_once( 'components/com_wh/tables/wh_tetel.php' );
			require_once( 'components/com_wh/tables/wh_msablonmezo.php' );
			require_once( 'components/com_wh/tables/wh_msablon.php' );
			require_once( 'components/com_wh/tables/wh_kategoria.php' );
			require_once( 'components/com_wh/tables/wh_beallitas.php' );
			require_once( 'components/com_wh/tables/wh_beszallito.php' );
			require_once( 'components/com_wh/tables/wh_komment.php' );
			require_once( 'components/com_wh/tables/wh_termek.php' );
			require_once( 'components/com_wh/tables/wh_gyarto.php' );
			require_once( 'components/com_wh/tables/wh_webshop.php' );
			require_once( 'components/com_wh/tables/wh_termek_proba.php' );
			require_once( 'components/com_wh/tables/wh_kep.php' );
			require_once( 'components/com_wh/tables/wh_rendeles.php' );
			
			require_once( 'components/com_wh/tables/wh_content.php' );
			require_once( 'components/com_wh/helpers/content.php' );
			
			jimport( "unitemplate.simpleimage.simpleimage");
			require_once( 'components/com_wh/helpers/listazo.php' );
		}
	}

	function js(){
		if(file_exists("administrator")){
			JHTML::_('behavior.modal');
			jimport('joomla.html.pane');
			$document =& JFactory::getDocument();
			//phpinfo();
			$document->addStyleSheet('templates/wh/css/style.css');
			 
			//$document->addScript('libraries/unitemplate/floatbox/floatbox.js.php');
			//$document->addScript('components/com_wh/assets/js/demo_tooltip.js');
			//$document->addStylesheet('libraries/unitemplate/floatbox/floatbox.css');
			$document->addStylesheet('templates/wh/css/general.css');	
			//$document->addStylesheet('components/com_wh/assets/css/demo_tooltip.css');	
			//$document->addScript( 'components/com_wh/assets/js/demo_tooltip.js' ); 
			
			//jquery
			
			//$document->addScript( 'components/com_wh/assets/js/jquery-1.4.1.min.js' );
			$document->addScript( 'components/com_wh/assets/js/hirlevel.js' ); 
			$document->addScript( 'components/com_wh/assets/js/webshop.js' ); 
			$document->addScript( 'components/com_wh/assets/js/kampany.js' );
			$document->addScript( 'components/com_wh/assets/js/datum.js' ); 
			$document->addScript( 'components/com_wh/assets/js/lang.js' ); 
			$document->addScript( 'components/com_wh/assets/js/kiegtermek.js' ); 
			$document->addScript( 'components/com_wh/assets/js/rendeles.js' ); 
			//$document->addScriptDeclaration('var $j = jQuery.noConflict();');
			
			$document->addScript('components/com_wh/assets/jquery/jquery-autocomplete/lib/jquery.autocomplete.js');
			$document->addScriptDeclaration('$j(document).ready(function() { initAutoComplete() });');
			$document->addStylesheet('components/com_wh/assets/jquery/jquery-autocomplete/jquery.autocomplete.css');
			
			//sortable
			//$document->addScript('components/com_wh/assets/js/jquery-ui-1.8.4.custom.min.js');
			//uploader
			$document->addScript( 'components/com_wh/assets/js/fileuploader/jquery.fileUploader.js' ); 
			
			$document->addScript('libraries/unitemplate/fancybox/jquery.fancybox-1.2.5.pack.js');
			$document->addStylesheet('libraries/unitemplate/fancybox/jquery.fancybox-1.2.5.css');
			//$document->addScriptDeclaration('$j(document).ready(function() { $j("a.zoom").fancybox({"zoomSpeedIn":300,"zoomSpeedOut":300,"overlayShow":false});});');
			
			
			
			
			//die("------");
			
			$document->addScript( 'components/com_wh/assets/js/cimke.js' ); 
			$document->addScript( 'components/com_wh/assets/js/product.js' ); 
			$document->addScript( 'components/com_wh/assets/js/wh.js' ); 
			$document->addScript( 'components/com_wh/assets/js/kategoria.js' ); 
			$document->addScript( 'components/com_wh/assets/js/msablon.js' ); 
			$document->addScript( 'components/com_wh/assets/js/kep.js' ); 
			$document->addScript( 'components/com_wh/assets/js/ajax.js' );
			$document->addScript( 'components/com_wh/assets/js/product.js' );

			
			//swfupload
			$document->addScript( 'components/com_wh/assets/js/swfupload/swfupload.js' );
			$document->addScript( 'components/com_wh/assets/js/swfupload/swfupload.swfobject.js' );
			$document->addScript( 'components/com_wh/assets/js/swfupload/swfupload.queue.js' );
			$document->addScript( 'components/com_wh/assets/js/swfupload/fileprogress.js' );
			$document->addScript( 'components/com_wh/assets/js/swfupload/handlers.js' );
			
			$document->addScript( 'components/com_wh/assets/js/swfupload/init.js' );
		}


	}

	function setTemplate(){
		if(Jrequest::getvar("option") == "com_wh") {
			$app = JFactory::getApplication();
			$app->setTemplate("fusion");
		}
	}


    /**
     * Do something onAfterInitialise
     */
    public function onAfterInitialise()
    {
       if (Jrequest::getvar("option") == "com_wh"){
		    $this->_log(
	            'onAfterInitialise',
	            'After framework load and application initialise.'
	            );
			$this->setTemplate();
		}
    }//function

    /**
     * Do something onAfterRoute
     */
    public function onAfterRoute(){
		if (Jrequest::getvar("option") == "com_wh"){
			$this->js();
			$this->incl();
		}	
	
		
		
    }//function

    /**
     * Do something onAfterDispatch
     */
    public function onAfterDispatch()
    {
        $this->_log(
            'onAfterDispatch',
            'After the framework has dispatched the application.'
            );
				
    }//function

    public function onBeforeCompileHead()
    {
        $this->_log(
            'onBeforeCompileHead',
            'Before the framework creates the head section of the document.'
            );
		
    }

    /**
     * Do something onAfterRender
     */
    public function onBeforeRender()
    {
        $this->_log(
            'onBeforeRender',
            'Before the framework renders the application.'
            );
    }//function

    /**
     * Do something onAfterRender
     */
    public function onAfterRender()
    {
        $this->_log(
            'onAfterRender',
            'After the framework has rendered the application.'
            );
    }//function

    private function _log ($status, $comment)
    {
        jimport('joomla.error.log');
        //JLog::getInstance('plugin_system_example_log.php')->addEntry(array('event' => $event, 'comment' => $comment));
    }//function
}//class