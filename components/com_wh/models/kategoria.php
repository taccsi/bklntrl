<?php
defined('_JEXEC') or die('=;)');

class whModelkategoria extends modelbase {
	var $xmlFile = "kategoria.xml";
	var $tmpname = "";
	var $table = "#__wh_kategoria";
	var $images = 1;
	var $uploaded = "media/wh/kategoriak/";

	//var $table ="wh_kategoria";

	function __construct() {
		parent::__construct();
		//die;
		$this -> value = JRequest::getVar("value", "");
		$this -> getData();
		$this -> xmlParser = new xmlkategoria($this -> xmlFile, $this -> _data);
	}//function

	function store() {
		//die(str_replace("#__", "", $this->table)." *********");
		$row = &$this -> getTable(str_replace("#__", "", $this -> table));
		//print_r($row);
		//die;
		foreach ($this->getFormFieldArray() as $parName) {//ha tömböt kell menteni
			$val = JRequest::getVar($parName, "", "", 2, 2, 2);
			if ($parName == "szulo") {
				$val = ($val) ? $val : $this -> getSessionVar("cond_kategoria_szulo");
			}
			if (is_array($val)) {
				$data[$parName] = "," . implode(",", $val) . ",";
			} else {
				$data[$parName] = $val;
			}
		}
		//print_r($data);
		//die;
		if (!$row -> bind($data)) {
			$this -> setError($this -> _db -> stderr());
			return false;
		}
		//print_r($data);
		//die;

		// Make sure the record is valid
		if (!$row -> check()) {
			$this -> setError($this -> _db -> stderr());
			return false;
		}

		// Store the table to the database
		//print_r($row); exit;
		if (!$row -> store()) {
			$this -> setError($row -> getError());
			return false;
		} else {
			//die($this->_db->getQuery());
			//echo "--------------".;
			$id = $this -> _db -> insertId();
			if (!$id) {
				$id = $this -> getSessionVar("id");
			}
		}
		//die("--{$id}");
		//$this->saveImages($id);
		$this -> mentFajlok($id);
		//die("{$id} - -");
		return $id;
	}
}