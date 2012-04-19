<?php
defined( '_JEXEC' ) or die( '=;)' );
/*
require_once( 'components/com_whp/helpers/xmlTermek.php' );
require_once( 'administrator/components/com_whp/helpers/baseModel.php' );
require_once( 'administrator/components/com_whp/helpers/xComponent.php' );
require_once( 'components/com_whp/helpers/xComponent.php' );
require_once( 'components/com_whp/unitpl/base_template.php' );
*/

require_once( 'components/com_whp/helpers/whpBeallitasok.php' );
require_once( 'administrator/components/com_whp/helpers/baseModel.php' );

require_once( 'administrator/components/com_whp/helpers/xml.php' );
require_once( 'administrator/components/com_whp/helpers/kategoriafa.php' );
require_once( 'administrator/components/com_whp/helpers/xComponent.php' );
require_once( 'components/com_whp/helpers/xComponent.php' );
require_once( 'components/com_whp/unitpl/base_template.php' );

class mod_whp_kereso_mini extends modelbase{
  
	function __construct($params){

		$this->document =& JFactory::getDocument();
		$this->_db = JDatabase::getInstance( whpBeallitasok::getOption() );
		if (JRequest::getvar('controller') == 'atvevohely' or JRequest::getvar('controller') == 'atvevohelyek'){
			echo $this->getAtvhelyKersoHTML();		
		} else {
			echo $this->getEgyebKeresoHTML();
			
		}
		//print_r($this->jkategoriak);
		
	}

	function getEgyebKeresoHTML(){
		$a_ = array(
		"termekek"=>"select nev from #__wh_termek where aktiv = 'igen' order by nev", 
		"szerzok"=>"select nev from #__wh_szerzo order by nev"
		);
		foreach($a_ as $k =>$q){
			$this->_db->setQuery($q);		
			$arr = $this->_db->loadResultArray();
			$arr = $this->tisztitArr( $arr );
			$$k =  implode("','", $arr );
			$this->document->addScriptDeclaration("var {$k} = new Array ('{$$k}');");	
		}
		$this->o_ = new modelBase;		

		$sw_kereso = ( $this->o_->getsessionVar("sw_kereso") ) ? $this->o_->getsessionVar("sw_kereso") : 1;
		$this->document->addScriptDeclaration("$j(document).ready(function() { getKersoHTML( '{$sw_kereso}' ) });");
		
		
		
		if( jrequest::getvar("kategoria_id") || 1 ){
		//ini_set('display_errors',1);
			$this->_db = JDatabase::getInstance( whpBeallitasok::getOption() );
			$this->xmlParser = new xmlParser("termek.xml");
			//$this->jkategoriak = implode(',',modelbase::getjog()->kategoriak);
			$this->o_->xmlParser = new xmlParser( "termek.xml" );
			//$q = "select nev REGEXP '^a.*$' from #__wh_termek where aktiv = 'igen' and kategoria_id in ({$this->jkategoriak}) order by nev limit 1000 ";
			$this->document =& JFactory::getDocument();
			//$this->kategoria_id = jrequest::getvar("kategoria_id", 0);
			?>
			<div id="ajaxContentKereso"></div>
			<?php
		}
	}
	
	function tisztitArr( $arr ){
		foreach( $arr as $a ){
			$ind = array_search($a, $arr);
			if(strstr($a, "\\")){
				$a[strpos($a, "\\")] = " ";
			}
			$a  = str_replace( array( "'" ), "", $a);			
			$arr[ $ind ] = $a;
		}
		return $arr;	
	}
	
	function getEgyebKeresoHTML_(){
		$q = "select nev from #__wh_termek where aktiv = 'igen' order by nev";
		$this->_db->setQuery($q);		
		$arr = $this->_db->loadResultArray();
		
		foreach( $arr as $a ){
			$ind = array_search($a, $arr);
			if(strstr($a, "\\")){
				$a[strpos($a, "\\")] = " ";
			}
			$a  = str_replace( array( "'" ), "", $a);			
			$arr[ $ind ] = $a;
		}
		//die;
		$this->o_ = new modelBase;		
		$termekek =  implode("','", $arr );
		$this->document->addScriptDeclaration("var termekek = new Array ('{$termekek}');");	
		$sw_kereso = ( $this->o_->getsessionVar("sw_kereso") ) ? $this->o_->getsessionVar("sw_kereso") : 1;
		$this->document->addScriptDeclaration("$(document).ready(function() { getKersoHTML( '{$sw_kereso}' ) });");
		
		
		
		if( jrequest::getvar("kategoria_id") || 1 ){
		//ini_set('display_errors',1);
			$this->_db = JDatabase::getInstance( whpBeallitasok::getOption() );
			$this->xmlParser = new xmlParser("termek.xml");
			//$this->jkategoriak = implode(',',modelbase::getjog()->kategoriak);
			$this->o_->xmlParser = new xmlParser( "termek.xml" );
			//$q = "select nev REGEXP '^a.*$' from #__wh_termek where aktiv = 'igen' and kategoria_id in ({$this->jkategoriak}) order by nev limit 1000 ";
			$this->document =& JFactory::getDocument();
			//$this->kategoria_id = jrequest::getvar("kategoria_id", 0);
			?>
			<div id="ajaxContentKereso"></div>
			<?php
		}
	}
	
	function getAtvhelyKersoHTML(){ 
		ob_start();
		global $Itemid;
		$this->xmlParser = new xmlRendeles("atvevohely.xml");
		$f_ = $this->xmlParser->getAllFormGroups();
		/*$sw_kereso = jrequest::getvar("sw_kereso", 0);
		$this->setSessionVar("sw_kereso", $sw_kereso);*/	
		?>
        <form action="index.php" method="get" id="vsSearchForm_mini" nev="vsSearchForm_mini" class="search">
       <table border="0" cellspacing="0" cellpadding="0">
          <tr>
          <td>
		  <?php 
			echo html_entity_decode($f_["kereso"]); 
			       
		  ?>
          </td>
            <td id="button">
            	<input id="a_mini" class="a_megveszem" type="submit" value="<?php echo JText::_('KERESES') ?>" />
            </td>
          </tr>
        </table>
       <?php echo $this->xmlParser->getOrderHiddenFields(); 
		//die ("-----"); ?>
			<input type="hidden" name="orderField" id="orderField" value="" />
            <input type="hidden" name="option" value="com_whp" />
            <input type="hidden" name="controller" value="atvevohelyek" />
            <input type="hidden" name="Itemid" value="<?php echo $Itemid ?>" />
            <input type="hidden" name="search_sw" value="1" />
			<input type="hidden" name="wh_search" value="1" />
        </form>
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
   }
	
	
	function getSessionVar($name){
		@$sess = jsession::getinstance();
		return $sess->get( $name );		
	}
	  
   function getOrderHiddenFields(){
      ob_start();
      $group = $this->xmlParser->getGroup("ordFields");
      foreach ($group->childNodes as $element ){
         if(is_a($element, "DOMElement")){
            $f = $element->getAttribute('name');
            $v = JRequest::getVar($f);
            echo "<input type=\"hidden\" id=\"{$f}_order\" name=\"{$f}_order\" value=\"{$v}\" />";
         }
      }    
      $ret = ob_get_contents();
      ob_end_clean();
      return $ret;
   }
}
?>