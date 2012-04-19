<?php
defined('_JEXEC') or die('=;)');

class whModelhirlevel_lista extends modelbase {
	var $xmlFile = "hirlevel_lista.xml";
	var $uploaded = "components/com_wh/uploaded";
	var $tmpname = "";
	var $table = "#__wh_hirlevel_lista";
	//var $table ="wh_hirlevel_lista";

	function __construct() {
		parent::__construct();
		//die;
		$this -> value = JRequest::getVar("value", "");
		$this -> getData();
		$this -> xmlParser = new xmlhirlevel_lista($this -> xmlFile, $this -> _data);
		//$this->document->addScriptDeclaration("\$j(document).ready(function(){ initDateField()})");
	}//function

	function getDbCimek() {
		@$q = "select count(id) as db from #__wh_hirlevel_cim where lista_id = {$this->_data->id}";
		$this -> _db -> setQuery($q);
		return $this -> _db -> loadResult();
	}

	function truncateTables(){
		foreach (array('#__wh_hirlevel_cim', '#__wh_hirlevel_cim_lista_kapcs') as $t) {
			$q = "truncate table {$t}";
			$this->_db->setQuery($q);
			$this->_db->query();
		}
	}

	function feldolgozCsv($lista_id) {
		//die($lista_id." :lista_id ");
		$tmp_name = $_FILES["csv_upload"]["tmp_name"];
		$feldolgozott_sorok = 0;
		//$this->truncateTables();		
		if ($tmp_name) {
			// die($tmp_name);
			$filename = dirname(__FILE__) . "/import_csv.csv";
			move_uploaded_file($tmp_name, $filename);

			if (file_exists($filename)) {
				// die('ko');
				$handle = fopen($filename, "r");
				$i = 0;
				$csv_ok = 0;
				$firstrow = fgetcsv($handle, 1000, ";");

				while (($row = fgetcsv($handle, 1000, ";")) !== FALSE) {
					$object = "";
					$n = 0;

					foreach ($firstrow as $f) {
						$object -> $f = str_replace("", "", $row[$n]);
						$n++;
					}
					$object->aktiv = 'igen';
					//print_r($object);die;
					// $object->lista_id = $lista_id;
					if (!$this -> emailLetezikAdottListaban($object -> email, $lista_id)) {
						if ( $this->_db -> insertObject("#__wh_hirlevel_cim", $object, "id")) {
							$cim_id = $this->_db -> insertid();
							$o = '';
							$o -> lista_id = $lista_id;
							$o -> cim_id = $cim_id;
							$o -> datum = date('Y-m-d H:i:s');
							$this->_db -> insertobject('#__wh_hirlevel_cim_lista_kapcs', $o, "id" );
							echo $this->_db -> getErrorMsg();
							//print_r($o);
							//die();
							$feldolgozott_sorok++;
						} else {
							$this->_db -> getErrorMsg();
						}
					}
				} //mer_cim_lista_kapcs
			}
		}

		if ($feldolgozott_sorok) {
			$ret = $feldolgozott_sorok;
			//$this->result();
			unlink($filename);
		} else {
			$ret = 0;
		}
		return $ret;
	}

	function emailLetezik_($email, $lista_id) {
		$q = "select id from #__wh_hirlevel_cim where email = '" . trim($email) . "' and lista_id = {$lista_id} ";
		$this -> _db -> setQuery($q);
		return $this -> _db -> loadResult();
	}

	function emailLetezikAdottListaban($email, $lista_id) {
		$q = "select id from #__wh_hirlevel_cim as c inner join
		#__wh_hirlevel_cim_lista_kapcs as k on k.cim_id =  c.id 
		 where email = '" . trim($email) . "' and k.lista_id = {$lista_id} ";
		$this -> _db -> setQuery($q);
		return $this -> _db -> loadResult();
	}

	function store() {
		$row = &$this -> getTable("wh_hirlevel_lista");
		foreach ($this->getFormFieldArray() as $parName) {//ha tömböt kell menteni
			$val = JRequest::getVar($parName, "", "", 2, 2, 2);
			//echo $val."---<br />";
			if (is_array($val)) {
				$data[$parName] = "," . implode(",", $val) . ",";
				//echo $data[$parName]."<br />";
			} else {
				$data[$parName] = $val;
			}
		}
		//die;
		// Bind the form fields to the hello table
		if (!$row -> bind($data)) {
			$this -> setError($this -> _db -> stderr());
			return false;
		}

		// Make sure the record is valid
		if (!$row -> check()) {
			$this -> setError($this -> _db -> stderr());
			return false;
		}

		// Store the table to the database
		//print_r($row); exit;
		if (!$row -> store()) {
			$this -> setError($row -> getError());
			//die("hiba");
			return false;
		} else {
			//echo "--------------".;
			$id = $this -> _db -> insertId();
			if (!$id) {
				$id = $this -> getSessionVar("id");
			}

			$this -> feldolgozCsv($id);
		}
		//die("-{$id}");
		return $id;
	}

}// class
