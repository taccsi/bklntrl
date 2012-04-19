<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.model');

class whModelKepek extends modelBase
{

var $uploaded = "media/wh/termekek/";

	function __construct()
	{
	 	parent::__construct();
		global $mainframe, $option;
		// Get pagination request variables
		$limit = $this->limit;
		$this->limitstart = JRequest::getVar( "limitstart", 0 ); 
		$this->xmlParser = new xmlParser("kep.xml");	
	}//function

	function _buildQuery()
	{
		
		$cond = $this->getCond();	
		$kapcsolodo_id = $this->xmlParser->getAktVal("kapcsolodo_id");
		$query = "SELECT * FROM #__wh_kep where termek_id = {$kapcsolodo_id} order by sorrend";
		//echo $query;
		return $query;
	}

	function delete(&$jTable)
	{
		//die($jTable);
		$cids = JRequest::getVar( 'cid', array(0), '', 'array' );
		$row =& $this->getTable($jTable);
		//print_r($cids);
		//die();
		//print_r($row);exit;
		if (count( $cids ))
		{
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getError() );
					return false;
				}
			}//foreach
		}
		
		foreach($cids as $c){
			$filename = "{$this->uploaded}{$c}_1.jpg";
			unlink($filename);
		}
		return true;
	}//function

	function getData()
	{
		
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			
			$query = $this->_buildQuery();
	
			$this->_data = $this->_getList( $query, $this->getState('limitstart'), $this->getState('limit') );
				
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