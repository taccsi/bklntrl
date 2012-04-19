<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.model');
//ini_set("display_errors" ,1);

class whpModelatvevohelyek extends whpPublic
{
	var $limit = 10;
	var $uploaded = "media/whp/atvhelyek/";
	var $w = 80;
	var $h = 120;
	var $mode = "resize";
	function __construct()
	{
	 	parent::__construct();
		global $mainframe, $option;
		// Get pagination request variables
		$this->limitstart = JRequest::getVar( "limitstart", 0 ); 
		$this->xmlParser = new xmlatvevohely("atvevohely.xml");
		$telepules_id=jrequest::getvar('cond_telepules_id');
		//$this->document->addscriptdeclaration("window.addEvent(\"domready\",function(){getTelepules('atvevohelyek', '{$telepules_id}')}) ");
		$this->document->addScriptDeclaration("\$j(document).ready(function() { getTelepules('atvevohelyek', '{$telepules_id}') });");

	
	}//function
	
	function getOldalcim(){
		//echo $item->kampany_id_;
		$Itemid = $this->Itemid;
		$megye = urldecode(Jrequest::getvar('megye',''));
		$telepules_id = Jrequest::getvar('cond_telepules_id','0');
		$arr= array();
		if( $megye ){
			
			$ret = "<div class=\"utvonal\">";
			//print_r($this->_db->loadObjectList());
			$link = "index.php?option=com_whp&controller=atvevohelyek&megye={$megye}&Itemid={$Itemid}";
			$a = "<a class=\"a_utvonal\" href=\"{$link}\">{$megye}</a>";
			$arr[] = $a;
		}
		if( $telepules_id ){ 
			
			$telepules = $this->getobj('#__wh_telepules',$telepules_id)->telepules;
			$link = "index.php?option=com_whp&controller=atvevohelyek&megye={$megye}&cond_telepules_id={$telepules_id}Itemid={$Itemid}";
			$a = "<a class=\"a_utvonal\" href=\"{$link}\">{$telepules}</a>";
			$arr[] = $a;
		}
		if (count($arr)){	
			$ret .= implode("<span class=\"utvonal_elvalaszto\"> / </span>", $arr);
			$ret .= "</div>";
			//echo $ret;
			//cond_kategoria_id
		}else{
			$ret = "<div class=\"utvonal\">".JTEXT::_('ATVEVOHELYEK')."</div>";
		}
		return $ret;
	
	}
	
	function getData()
	{
		// Lets load the data if it doesn't already exist
		
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query, $this->limitstart, $this->limit );
			//echo $this->_db->getquery().'<br /><br />';
			//echo $this->_db->geterrormsg();
			
			//array_map ( array($this, "setAtvhelyAdatok"), $this->_data) ;
			//array_map ( array($this, "setAr"), $this->_data);
			/*array_map ( array($this, "setKosar"), $this->_data );
			array_map ( array($this, "setSzerzo"), $this->_data);
			array_map ( array($this, "setListaNev"), $this->_data );			
			*/
		}
		//print_r($this->_data); die();
		return $this->_data;
	}//function
	
	function getTelepules(){
		ob_start();
		$telepules_id = jrequest::getVar("telepules_id");
		if ($telepules_id =='') {$telepules_id = $this->getSessionVar('telepules_id');
		}
		if($megye = urldecode(Jrequest::getVar("megye", "") )){
			$q = "select t.id as `value`, concat(t.telepules, '') as `option` 
			from #__wh_telepules as t
			inner join #__wh_atvhely as atv on atv.telepules_id = t.id			
			 where t.megye like '{$megye}' group by t.id order by t.telepules asc ";
			$this->_db->setQuery( $q );
			$rows = $this->_db->loadObjectList( );
			
			$o = '';
			$o->option = $o->value = '';
			array_unshift($rows,$o);
			
			$ret = "";
			$ret .= JHTML::_( 'Select.genericlist', $rows, "cond_telepules_id", array("class"=>"alapinput cim" ), "value", "option", $telepules_id )."<br />";
			
		}else{
			$ret = "&nbsp;";
		}
		echo $ret;
		$tmp= ob_get_contents();
		ob_end_clean();
		return $tmp;
	}
	
	function getAtvevohelyek(){
		ob_start();
		$atvhely_id = jrequest::getVar("atvhely_id", "");
		if ($atvhely_id =='') {$atvhely_id = $this->getSessionVar('atvhely_id');}
		if($telepules_id = jrequest::getVar("telepules_id", "")){
			$q = "select atv.id as `value`, concat(atv.nev,' - ',utca_hazszam) as `option` 
			from #__wh_atvhely as atv where atv.telepules_id = '{$telepules_id}' group by atv.id order by atv.nev asc ";
			$this->_db->setQuery( $q );
			$rows = $this->_db->loadObjectList( );
			$ret = "";
			$ret .= JHTML::_( 'Select.genericlist', $rows, "atvhely_id", array("class"=>"alapinput cim" ), "value", "option", $atvhely_id )."<br />";
			
		}else{
			$ret = "&nbsp;";
		}
		echo $ret;
		$tmp= ob_get_contents();
		ob_end_clean();
		return $tmp;
	}

	
	function getSearchArr(){
		$arr = array();
		$obj = "";
		$obj->atvhely = '<input name="nev" value="'.JRequest::getVar("nev") .'" />';
		$arr[] = $obj;
		$obj = "";		
		$kategoriafa = new kategoriafa( );
		$value = JRequest::getVar("kategoria_id", 0);
		$o="";
		$o->value = $o->option = "";
		array_unshift($kategoriafa ->catTree, $o);
		$obj->KATEGORIA = JHTML::_('Select.genericlist', $kategoriafa ->catTree, "kategoria_id", array( "class"=>"alapinput" ), "value", "option", $value);
		$arr[] = $obj;

		return 	$arr;
	}


	function _buildQuery()
	{
		$cond = $this->getCond();
		if($cond){
			$cond .= "and atvhely.aktiv = 'igen' ";
		}
		//die($cond);
		$query = "SELECT atvhely.*, telep.megye, telep.telepules FROM #__wh_atvhely as atvhely 
		inner join #__wh_telepules as telep on telep.id = atvhely.telepules_id
			{$cond} order by telep.megye, telep.telepules, atvhely.nev"; 
		//die( $query );
		return $query;
		
	}
	
	function getatvhelyek(){
		
		$rows=$this->getData();
		
		if(count($rows)>0){ // vannak sorok
			jimport("unitemplate.unitemplate");
			$uniparams->cols = 1;
			$uniparams->cellspacing = 0;
			$uniparams->templatePath = "components/com_whp/unitpl";
			$uniparams->pair = false;
			$ut = new unitemplate("atvevohelyek", $rows, "div", "atvevohely_lista", $uniparams);
			$ret = $ut -> getContents(); 
		}else{
			$ret = "<div align=center>".JText::_("NINCS TALALAT")."</div>";			
		}
		return $ret;
	}

	function getTotal()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);	
		}
		//echo $this->_total;
		return $this->_total;
	}//function
  
	function getPagination()
  {
 	// Load the content if it doesn't already exist
 	if (empty($this->_pagination))
 	{
 	    jimport('joomla.html.pagination');
 	    $this->_pagination = new JPagination($this->getTotal(), $this->limitstart, $this->limit );
 	}
 	return $this->_pagination;
  }//function


}// class
?>