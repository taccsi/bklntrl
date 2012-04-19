<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModelwebshop extends modelbase
{
	var $xmlFile = "webshop.xml";
	var $uploaded = "components/com_wh/uploaded";
	var $tmpname = "";
	var $table = "#__wh_webshop";
	//var $table ="wh_webshop";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");	
		$this->getData();
	 	$this->xmlParser = new xmlwebshop($this->xmlFile, $this->_data);
	}//function
//index.php?option=com_wh&controller=webshop&task=getSzallitasiDijtetelek&format=raw&webshop_id=1

	function mentSzallitasiDijtetel(){
		$table = "#__wh_szallitasi_tetel";
		$fields_ = $this->_db->getTableFields($table, 1);
		$o="";
		foreach($fields_[$table] as $f => $v){
			$v_ = jrequest::getVar($f);
			if( isset( $v_ ) ){
				$o->$f= $v_;
			}
		}
		$this->_db->updateObject( "#__wh_szallitasi_tetel", $o, "id" );
		$ret = "";
		$ret->html = "";
		$ret->debug = "";		
		return $this->getJsonRet( $ret );
	}
	
	function torolSzallitasiDijtetel(){
		$ret = "";
		$ret->html = "";
		$ret->debug = "";		
		$tetel_id = jrequest::getVar( "tetel_id", 0 );
		$q = "delete from #__wh_szallitasi_tetel where id = {$tetel_id} ";
		$this->_db->setQuery( $q );
		$this->_db->Query(  );		
		echo $this->_db->getErrorMsg(  );
		return $this->getJsonRet( $ret );
	}
	
	function hozzaadSzallitasiDijtetel(){
		$table = "#__wh_szallitasi_tetel";
		$fields_ = $this->_db->getTableFields($table, 1);
		$o="";
		foreach($fields_[$table] as $f => $v){
			$v_ = jrequest::getVar($f);
			if( isset( $v_ ) ){
				$o->$f= $v_;
			}
		}
		$this->_db->insertObject( "#__wh_szallitasi_tetel", $o, "id" );
		$ret = "";
		return $this->getJsonRet( $ret );
	}

	function getSzallitasiDijtetelek(){
		$ret = "";
		$ret->html = "";
		$ret->debug = "";		
		$webshop_id = jrequest::getVar( "webshop_id", 0 );
		$q = "select * from #__wh_szallitasi_tetel as tetel
		where webshop_id = {$webshop_id} ";
		$this->_db->setQuery( $q );
		$arr = $this->_db->loadObjectList();
		echo $this->_db->getErrorMsg(  );
		//echo  $this->_db->getQuery(  );
		foreach( $arr as $a ){
			$ret->html .= $this->getDijTetelInput( $a );
			//$ret->html .= $this->getDijTetelInput( $arr );				
		}
		//print_r($ret);
		//die;
		//$ret->html = "fésdfkslédkflésdfk";
		return $this->getJsonRet( $ret );
	}
	
   function getwebshop($id)
   {
   	$this->_db->setQuery("SELECT * FROM #__wh_webshop WHERE id = {$id}");
	return $this->_db->loadObject();
   }

	  
}// class
?>