<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.model');

class whModelfelhasznalok extends modelBase
{
	function __construct()
	{
	 	parent::__construct();
		global $mainframe, $option;
		// Get pagination request variables
		$this->cond_webshop_id = jrequest::getVar("cond_webshop_id", "");
		$limit = $this->limit;
		$this->limitstart = JRequest::getVar( "limitstart", 0 ); 
		$this->xmlParser = new xmlParser("felhasznalo.xml");	
		$this->fcsoportok = $this->getfcsoportok();
		$v_ = "value";
		$o_ = "option";
		$o="";
		$o->$v_ = $o->$o_= "";
		array_unshift($this->fcsoportok, $o); 		
	}//function

	function getfcsoportok(){
		$q = "select id as `value`, nev as `option` from #__wh_fcsoport where aktiv = 'igen'";
		$this->_db->setQuery($q);
		//print_r($this->_db->loadObjectList());
		return $this->_db->loadObjectList();		
	}
	
	function mentfcsoport(){
		//die("********");
		$idArr = JREquest::getVaR("idArr", array(), "array");	
		$fcsoportIdArr = JREquest::getVaR("fcsoportIdArr", array(), "array");	
		foreach($idArr as $id){
			$ind = array_search($id, $idArr);
			//$afa_id = $afa_id_beszallito_ar[$ind];		
			$o = "";
			$o->id = $id;
			$o->fcsoport_id = $fcsoportIdArr[$ind];
			$this->_db->updateObject("#__wh_felhasznalo", $o, "id");
		}
	}
	
	function setfcsoport($item){
		$v_ = "value";
		$o_ = "option";
		$item->fcsoport = "<input type=\"hidden\" name=\"idArr[]\" value=\"{$item->id}\" >";
		$item->fcsoport .= JHTML::_('Select.genericlist', $this->fcsoportok, "fcsoportIdArr[]", array( "class"=>"fcsoport_ alapinput"), $v_, $o_, $item->fcsoport_id);
		return $item;
	}	

	function getSearchArr(){
		$arr = array();

		$obj = "";		
		$q = "select id as `value`, nev as `option` from #__wh_webshop order by nev";
		$this->_db->setQuery($q);
		$name = "cond_webshop_id";
		$value = JRequest::getVar($name);
		$rows = $this->_db->loadObjectList();		
		$o="";
		$o->value = $o->option = "";
		array_unshift($rows, $o);		
		$obj->WEBSHOP = JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"alapinput" ), "value", "option", $value);
		$arr[] = $obj;

		$obj = "";		
		$q = "select id as `value`, nev as `option` from #__wh_fcsoport order by nev";
		$this->_db->setQuery($q);
		$name = "cond_fcsoport_id";
		$value = JRequest::getVar($name);
		$rows = $this->_db->loadObjectList();		
		$o="";
		$o->value = $o->option = "";
		array_unshift($rows, $o);		
		$obj->FELHASZNALOI_CSOPORT = JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"alapinput" ), "value", "option", $value);
		$arr[] = $obj;

		$obj = "";
		$name = "cond_email";
		$value = JRequest::getVar($name);
		$obj->EMAIL = "<input name=\"{$name}\" id=\"{$name}\" value=\"{$value}\" />";
		$arr[] = $obj;

		$obj = "";
		$name = "cond_varos";
		$value = JRequest::getVar($name);
		$obj->VAROS = "<input name=\"{$name}\" id=\"{$name}\" value=\"{$value}\" />";
		$arr[] = $obj;

	return 	$arr;
	}
	
	function _buildQuery()
	{
		if( $this->cond_webshop_id ){
			$cond = $this->getCond();
			$w_ = $this->getObj("#__wh_webshop", $this->cond_webshop_id );
			$query = "SELECT felhasznalo.*, users.email, users.name, fcsoport.nev as fcsoport_nev, 
			fcsoport.kedvezmeny, fcsoport.kedvezmeny_tipus 
			FROM #__wh_felhasznalo as felhasznalo 
			inner join {$w_->database_}.{$w_->prefix_}users as users on felhasznalo.user_id = users.id 
			left join #__wh_fcsoport as fcsoport on felhasznalo.fcsoport_id = fcsoport.id 			
			{$cond} ";
			//echo "{$w_->database_} - {$w_->prefix_}<br /><br /><br />";
			//echo $query;
			//die;
			return $query;
		}else{
			return "";
		}
	}

	function getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ) && $this->cond_webshop_id )
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query, $this->limitstart, $this->limit );
			
				@array_map(array( $this, "setFCsoport"), $this->_data );
			
			//print_r($this->_data);
			//die("------");
		}else{
			$this->_data = array();
		}
		//$this->_data = array_map(array($this,"propValue"), $rows);
		//print_r($this->_data);exit;
		return $this->_data;
	}//function

	function getTotal()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);	
		}
		return $this->_total;
	}//function
  
	function getPagination()
  {
	if( $this->cond_webshop_id ){
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(),  $this->limitstart, $this->limit );
		}
 		return $this->_pagination;
	}else{
 		return "";	
	}
  }//function


}// class
?>