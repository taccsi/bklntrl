<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModelimport extends modelbase
{
	var $uploaded = "";
	var $tmpname = "";
	//var $table ="wh_kategoria";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");
	}//function

	function toroldb(){

		$arr = array("#__wh_termek", "#__wh_ar");
		foreach($arr as $a){
			$q = "truncate table {$a}";
			$this->_db->setQuery($q);
			$this->_db->Query();			
		}

	/*
		$q = "select id from #__wh_termek where kategoria_id is null" ;
		$this->_db->setQuery($q);
		echo count( $this->_db->loadResultArray() );
		$arr = implode(",", $this->_db->loadResultArray() );
		$q = "delete from #__wh_termek where id in ({$arr})" ;
		echo $q."<br />";
		$this->_db->setQuery($q);
		//$this->_db->query();		
		
		$q = "delete from #__wh_ar where termek_id in ({$arr})" ;
	
		$this->_db->setQuery($q);
		echo $q."<br />";	
		//$this->_db->query();	
		die;
		*/
	}

	function getFields(){
		$db=JFactory::getDBO();
		$fields_ = $db->getTableFields("#__ingker", 1);
		$fields="";
		foreach($fields_["#__ingker"] as $f => $v){
			//echo $f;
			if($f<>"id") $fields[] = $f;
		}
		//print_r($fields);
		return $fields;
	}

	function feldolgoz_csv_csak_arak(){
		$database = JFactory::getDBO();
		$tmp_name = $_FILES["csvfile3"]["tmp_name"];
		$feldolgozott_sorok=0;				
		//echo $tmp_name.$_FILES["csvfile"]."-----";
		if($tmp_name){
			$filename = dirname(__FILE__)."/import_csv.csv";
			move_uploaded_file($tmp_name, $filename);
			//echo $filename;
			if(file_exists($filename)){
			$handle = fopen($filename, "r");
				$i=0;
				$csv_ok=0;
				$firstrow = fgetcsv($handle, 1000, ";");
				//print_r($firstrow);
				// print_r($this->fields);
				//$this->buildKedvezmenyTable();
				//$this->urit();
				while ( ($row = fgetcsv($handle, 1000, ";") ) !== FALSE) {
					$termek="";
					$ar = "";
					$n=0;
					//print_r($row);
					foreach($firstrow as $f){
						$f = trim($f);	
						$v = $row[$n];	
						switch($f){
							case "kategoria":
								$termek->kategoria_id = $this->getKategoriaId($v);
								break;
							case "brutto_ar" : /*$v/=1.25; */ $ar->ar = $v; $termek->$f = $v; break;
							case "meret" : 
							case "kod" : 
							case "netto_nagyker" : 
							case "brutto_nagyker" : 
							case "kep" : 
							case "meret" : 	
							case "mee"	:
							case "afa"	:																																
							case "keszlet"	:																																							
								break;							
							default:
								if($f){
									$termek->$f = $v;
								}
						}
						$n++;
					}
					//$database->insertObject( "#__wh_termek", $termek, "id"); 
					//$termek_id =$database->insertId();
					
					//print_r($termek);
					$q = "select id from #__wh_termek where nev like '%{$termek->nev}%' limit 1 ";
					$database -> setQuery($q);
					$termek_id = $database -> loadResult();
					//echo $database -> getQuery();
					//echo $database -> getErrorMsg();
					//echo $termek_id;
					//die;
					if($termek_id){
						$q = "select id, ar from #__wh_ar where termek_id = {$termek_id} limit 1 ";
						$database -> setQuery($q);
						$arO = $database -> loadObject();					
						$ar = "";
						$ar->id = $arO->id;
						$ar->ar = $termek->brutto_ar;
						//$ar->termek_id = $termek_id;
						//$ar->afa_id=1;
						//$ar->webshop_id = 62;
						print_r($ar);
						echo "orig ár: {$arO->ar}<br />";
						echo "<br /><br />";
					}
					$database -> updateObject( "#__wh_ar", $ar, "id");
					if($termek_id)	$feldolgozott_sorok++;
				}
			}
		}
		//die("csvfile3");
		return $feldolgozott_sorok;
	}


	function feldolgoz_csv2(){
		$database = JFactory::getDBO();
		$tmp_name = $_FILES["csvfile2"]["tmp_name"];
		$feldolgozott_sorok=0;				
		//echo $tmp_name.$_FILES["csvfile"]."-----";
		if($tmp_name){
			$filename = dirname(__FILE__)."/import_csv.csv";
			move_uploaded_file($tmp_name, $filename);
			//echo $filename;
			if(file_exists($filename)){
			$handle = fopen($filename, "r");
				$i=0;
				$csv_ok=0;
				$firstrow = fgetcsv($handle, 1000, ";");
				//print_r($firstrow);
				// print_r($this->fields);
				//$this->buildKedvezmenyTable();
				//$this->urit();
				while ( ($row = fgetcsv($handle, 1000, ";") ) !== FALSE) {
					$termek="";
					$ar = "";
					$n=0;
					//print_r($row);
					foreach($firstrow as $f){
						$f = trim($f);	
						$v = $row[$n];	
						switch($f){
							case "kategoria":
								$termek->kategoria_id = $this->getKategoriaId($v);
								break;
							case "brutto_ar" : $v/=1.25; $ar->ar = $v; break;
							case "meret" : 
							case "kod" : 
							case "netto_nagyker" : 
							case "brutto_nagyker" : 
							case "kep" : 
							case "meret" : 	
							case "mee"	:
							case "afa"	:																																
							case "keszlet"	:																																							
								break;							
							default:
								if($f){
									$termek->$f = $v;
								}
						}
						$n++;
					}
					$database->insertObject( "#__wh_termek", $termek, "id"); 
					$termek_id =$database->insertId(); 
					//print_r($termek);
					//die;	
					$ar->termek_id = $termek_id;
					$ar->afa_id=1;
					$ar->webshop_id = 62;
					$database -> insertObject( "#__wh_ar", $ar, "id");
					if($termek_id)	$feldolgozott_sorok++;
				}
			}
		}
		//die("csvfile2");
		return $feldolgozott_sorok;
	}

	function getKategoriaId($f){
		$kat = end(explode("/", $f));
		$q = "select id from #__wh_kategoria where nev like '%{$kat}%' ";
		$this->_db->setQuery($q);
		//echo $q."<br />";
		return $this->_db->loadResult();
	}

	function feldolgoz_csv(){
		$database = JFactory::getDBO();
		$tmp_name = $_FILES["csvfile"]["tmp_name"];
		$feldolgozott_sorok=0;				
		//echo $tmp_name.$_FILES["csvfile"]."-----";
		if($tmp_name){
			$filename = dirname(__FILE__)."/import_csv.csv";
			move_uploaded_file($tmp_name, $filename);
			//echo $filename;
			if(file_exists($filename)){
			$handle = fopen($filename, "r");
				$i=0;
				$csv_ok=0;
				$firstrow = fgetcsv($handle, 1000, ";");
				//print_r($firstrow);
				// print_r($this->fields);
				//$this->buildKedvezmenyTable();
				//$this->urit();
				while ( ($row = fgetcsv($handle, 1000, ";") ) !== FALSE) {
					$termek="";
					$ar = "";
					$n=0;
					//print_r($row);
					foreach($firstrow as $f){
						$f = trim($f);	
						$v = $row[$n];	
						switch($f){
							case "kategoria":
								$termek->kategoria_id = $this->getKategoriaId($v);
								break;
							case "brutto_ar" : $v/=1.25; $ar->ar = $v; break;
							case "meret" : 
							case "kod" : 
							case "netto_nagyker" : 
							case "brutto_nagyker" : 
							case "kep" : 
							case "meret" : 	
							case "mee"	:
							case "afa"	:																																
							case "keszlet"	:																																							
								break;							
							default:
								if($f){
									$termek->$f = $v;
								}
						}
						$n++;
					}
					$database->insertObject( "#__wh_termek", $termek, "id"); 
					$termek_id =$database->insertId(); 
					//print_r($termek);
					//die;	
					$ar->termek_id = $termek_id;
					$ar->afa_id=1;
					$ar->webshop_id = 62;
					$database -> insertObject( "#__wh_ar", $ar, "id");
					if($termek_id)	$feldolgozott_sorok++;
				}
			}
		}
		return $feldolgozott_sorok;
	}

/*
	function urit(){
		$database = JFactory::getDBO();
		$q="truncate table #__ingker";
		$database->setQuery($q);
		$database->query();
	}
*/
}// class
?>