<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModelKampany extends modelbase
{
	var $xmlFile = "kampany.xml";
	var $uploaded = "";
	var $tmpname = "";
	var $table = "#__wh_kampany";
	//var $table ="wh_kategoria";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");
		$this->getData();
	 	$this->xmlParser = new xmlkampany($this->xmlFile, $this->_data);
	}//function

	function getKedvezmeny( ){
		$kampany_id = jrequest::getVar( "kampany_id", "" );
		$k = $this->getObj("#__wh_kampany", $kampany_id );
		//print_r($k);
		$name = "kedvezmeny";
		$value = $k->$name;
		$kedvezmeny = jrequest::getVar( "kedvezmeny", "" );
		$kedvezmeny = (!$kedvezmeny) ? $value : $kedvezmeny;
		//$kedvezmeny = ( $kedvezmeny == "undefined" ) ? "" : $kedvezmeny;

		$name = "kedvezmeny_tipus";
		$value = $k->$name;
		
		$kedvezmeny_tipus = jrequest::getVar( "kedvezmeny_tipus", "" );
		$kedvezmeny_tipus = (!$kedvezmeny_tipus) ? $value : $kedvezmeny_tipus;
				
		//$kedvezmeny_brutto = jrequest::getVar( "kedvezmeny_brutto", "" );		
		$kedvezmeny_brutto = $kedvezmeny*1.25;
		
		$ret = "";
		$name = "kedvezmeny";
		$title = ( $kedvezmeny_tipus == "OSSZEG" ) ? jtext::_("KEDVEZMENY_NETTO").": " : "";
		$js = ( $kedvezmeny_tipus == "OSSZEG" ) ? "onblur=\"setKedvezmeny('netto', this.value)\"" : "";
		$ret .= "{$title}<input {$js} name=\"{$name}\" id=\"{$name}\" value=\"{$kedvezmeny}\" type=\"text\" > ";
		
		if( $kedvezmeny_tipus == "OSSZEG" ){
			$name = "kedvezmeny_brutto";
			$js = "onblur=\"setKedvezmeny('brutto', this.value )\"";
			$ret .= jtext::_("KEDVEZMENY_BRUTTO").": <input {$js} name=\"{$name}\" id=\"{$name}\" value=\"{$kedvezmeny_brutto}\" type=\"text\" >";
		}else{
		}
		$arr = array();
		
		foreach( array(/*"",*/ "%", "OSSZEG") as $a ){
			$o="";
			$o->value=$a;
			$o->option=jtext::_($a);
			$arr[]=$o;
		}
		$name = "kedvezmeny_tipus";
		$ret .= JHTML::_( 'Select.genericlist', $arr, $name, array( "class"=>"{$name}_ alapinput", "onchange"=>"getKedvezmeny()" ), "value", "option", $kedvezmeny_tipus );
		$r= "";
		$r->html = $ret;
		$r->error = "";
		return $this->getJsonRet( $r );
	}

	function store()
	   {
	   //die(str_replace("#__", "", $this->table)." *********");
		$row =& $this->getTable( str_replace("#__", "", $this->table) );
		//print_r($row);
		//die;
		foreach($this->getFormFieldArray() as $parName){//ha tömböt kell menteni
			$val = JRequest::getVar($parName,"", "",2,2,2);
			//echo $parName."---<br />";
			if(is_array($val)){
				$data[$parName] = ",".implode(",", $val).",";
			}else{
				$data[$parName] = $val;
			}
		}

		  if (!$row->bind($data)) {
			 $this->setError($this->_db->stderr());
			 return false;
		  }
			//print_r($data);
			//die;
			
		  // Make sure the record is valid
		  if (!$row->check()) {
			 $this->setError($this->_db->stderr());
			 return false;
		  }
	
		  // Store the table to the database
		  //print_r($row); exit;
		  if (!$row->store()) {
			 $this->setError( $row->getError() );
		   return false;
		  }else{
			//die($this->_db->getQuery()); 
			//echo "--------------".;
		   		$id = $this->_db->insertId();
			 if(!$id){
			 $id = $this->getSessionVar("id");
		   }
		  }
			//die("--{$id}");
         $this->saveImages($id);
 		$this->updateTermekekKampany($id);
			//die("{$id} - -");
		  return $id;
	  }   	
	
	function updateTermekekKampany( $kampany_id ){
		$k_ = $this->getObj("#__wh_kampany", $kampany_id );
		$kezi_torles = jrequest::getVar( "kezi_torles" );
		$kRequest = jrequest::getVar( "kategoria_id" );
		( is_array( $kRequest ) ) ? $kategoriak = $kRequest : $kategoriak = explode(",", $kRequest );
		$kategoriak = $this->cleanTomb( $kategoriak );
		$kArr = $kategoriak;
		//echo jrequest::getVar( "kategoria_id" );
		//print_r(jrequest::getVar( "kategoria_id" ));
		//print_r( $kategoriak );
		$arr = array();
		foreach( $kArr as $kategoria_id ){
			$kategoriak = array_merge($kategoriak, $this->getlftrgtosszes($kategoria_id, "#__wh_kategoria") );
		}
		//print_r($kategoriak);
		if( jrequest::getVar( "aktiv", "" ) == "nem" ){
			$q = "delete from #__wh_kampany_kapcsolo where kampany_id = {$kampany_id}";
			$this->_db->setQuery($q);
			$this->_db->Query();
		}else{
			$kategoriak = implode(",", $kategoriak );
			//die($kategoria_sw." ---------");
			( $kezi_torles =="igen" ) ? $q = "delete from #__wh_kampany_kapcsolo where kampany_id = {$kampany_id}" : $q = "delete from #__wh_kampany_kapcsolo where kampany_id = {$kampany_id} and direkt = '' ";
			$this->_db->setQuery($q);
			$this->_db->Query();
		
			$q = "select * from #__wh_termek as termek where termek.kategoria_id in( {$kategoriak} ) ";
			$this->_db->setQuery($q);
			foreach( $this->_db->loadObjectList() as $t){
				$o="";
				$o->termek_id = $t->id;
				$o->kampany_id = $k_->id;
				$o->kampany_prioritas = $k_->kampany_prioritas;
				$this->_db->insertObject("#__wh_kampany_kapcsolo", $o, "" );
			}
		}
	}
}// class
?>