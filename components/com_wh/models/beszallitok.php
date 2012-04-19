<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.model');

class whModelbeszallitok extends modelBase
{
	function __construct()
	{
	 	parent::__construct();
		global $mainframe, $option;
		// Get pagination request variables
		$limit = $this->limit;
		$this->limitstart = JRequest::getVar( "limitstart", 0 ); 
		$this->xmlParser = new xmlParser("beszallito.xml");	
	}//function
	
	function kivalaszt($termek_id, $beszallito_id){
		foreach($beszallito_id as $besz){
			if(!$this->letezikBeszallito($termek_id, $besz)){
				$o="";
				$o->termek_id = $termek_id;
				$o->beszallito_id = $besz;
				$this->_db->insertObject("#__wh_termek_beszallito_ar", $o, "id");
			}
		}
		return true;
	}
	
	function letezikBeszallito($termek_id, $besz){
		$query = "SELECT id FROM #__wh_termek_beszallito_ar WHERE termek_id = {$termek_id} AND beszallito_id = {$besz}";
		$this->_db->setQuery($query);
   		return $this->_db->loadResult();
	}
	
	function _buildQuery()
	{
		$cond = $this->getCond();	
		$query = "SELECT * FROM #__wh_beszallito as bsz {$cond} order by bsz.nev";
		//echo $query;
		return $query;
	}

	function getData()
	{
		
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			
			$query = $this->_buildQuery();
	
			$this->_data = $this->_getList( $query, $this->limitstart, $this->limit );
				
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