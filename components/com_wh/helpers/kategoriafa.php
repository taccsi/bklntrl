<?php
defined('_JEXEC') or die('=;)');
class kategoriafa extends kep {
	function __construct($kivetelKategoriak = array(0), $limitDepth = 50000, $szulo = 0, $table = "#__wh_kategoria") {
		$this -> table = $table;
		$this -> db = JFactory::getDBO();
		//$this->setLehetsegesKatok( $kivetelKategoriak );
		$this -> lehetsegesKategoriak = $this -> getMegengedettKategoriak($kivetelKategoriak);
		$this -> rebuild_tree(0, 0);
		$this -> catTree = array();
		$this -> depth = 1;
		$this -> limitDepth = $limitDepth;
		$this -> catTree($szulo);
		//$this->catTree = array_map(array($this, "setCatDisabled"), $this->catTree);
		$this -> setCat();
		//print_r($this->catTree);
		//die;
		$o_ = "";
		$o_ -> option = " - " . JText::_("LEGFELSO SZINT") . " - ";
		$o_ -> value = 0;
		//array_unshift($this->catTree, $o_);
	}

	function setCat() {
		//print_r($this->lehetsegesKategoriak);
		//die;
		foreach ($this->catTree as $c) {
			$ind = array_search($c, $this -> catTree);
			if (!in_array($c -> value, $this -> lehetsegesKategoriak)) {
				unset($this -> catTree[$ind]);
			}
		}
	}

	function catDepth($id) {
		//print $id."<br/>";
		$q = "select szulo from {$this->table} where id = {$id}";
		$this -> db -> setQuery($q);
		$res = $this -> db -> loadResult();
		//echo $res."<br />";
		if ($res) {
			$this -> depth++;
			$this -> margo .= "&nbsp;";
			$this -> catDepth($res);
		}
	}

	function getStartCat() {
		$lehetsegesKategoriak = $this -> getMegengedettKategoriak($this -> kivetelKategoriak);
		$q = "select szulo from {$this->table} where id in( {$lehetsegesKategoriak} ) order by melyseg asc limit 1 ";
		$this -> db -> setQuery($q);
		//die($this->db->loadResult(). "-");
		return $this -> db -> loadResult();
	}

	function getMegengedettKategoriak($kivetelKategoriak) {
		$o = new modelBase;
		$megengedettKategoriak = (array)$o -> user -> jog -> kategoriak;
		if (!count($megengedettKategoriak)) {
			$q = "select id from {$this->table} ";
			$this -> db -> setQuery($q);
			$megengedettKategoriak = $this -> db -> loadResultArray();
		}
		$megengedettKategoriak = array_diff($megengedettKategoriak, $kivetelKategoriak);
		return $megengedettKategoriak;
	}

	function catTree($szulo) {
		$q = "select * from {$this->table} where szulo = {$szulo} order by sorrend ";
		$this -> db -> setQuery($q);
		$rows = $this -> db -> loadObjectList();
		//echo $q;
		//print_r($rows);
		if (count($rows)) {
			foreach ($rows as $r) {
				$this -> depth = 1;
				$this -> margo = "";
				$this -> catDepth($r -> id);
				//echo "{$this->margo}[{$this->depth}] {$r->category_name}<br />";
				$margo = "";
				for ($i = 0; $i < $this -> depth; $i++) {
					$margo .= $this -> margo;
				}
				$o_ = "";
				($r -> melyseg > 1) ? $o_ -> option = "{$margo}|_{$r->nev}" : $o_ -> option = "{$margo}{$r->nev}";
				$o_ -> value = $r -> id;
				$o_ -> melyseg = $r -> melyseg;
				$o_ -> szulo = $r -> szulo;
				$o_ -> sorrend = $r -> sorrend;
				$this -> catTree[] = $o_;
				if ($this -> depth <= $this -> limitDepth) {
					$this -> catTree($r -> id);
				}
			}
		}
	}

	function rebuild_tree($szulo, $left) {
		$right = $left + 1;
		$q = "SELECT id FROM {$this->table} WHERE szulo ='{$szulo}'";
		$this -> db -> setQuery($q);
		//echo $q."<br />";
		$rows = $this -> db -> loadObjectList();
		//echo $this->db->geterrorMsg()."<br />";
		foreach ($rows as $row) {
			//print_r($row);
			$right = $this -> rebuild_tree( $row -> id, $right );
		}
		$o = "";
		$o -> id = $szulo;
		$o -> lft = $left;
		$o -> rgt = $right;
		$this -> depth = 1;
		$this -> catDepth($o -> id);
		$o -> melyseg = $this -> depth;
		$this -> db -> updateObject("{$this->table}", $o, "id");
		return $right + 1;
	}
}