<?php
defined( '_JEXEC' ) or die( '=;)' );
//ini_set("display_errors", 1);
class kategoriafa /*extends modelBase*/{
	var $catTreeArr = array();
	function __construct( $lehetsegesKategoriak='', $limitDepth = 50000, $szulo=0, $table = "#__wh_kategoria" ){
		$this->_db = &JDatabase::getInstance( whpBeallitasok::getOption() );		
		$this->table = $table;
		$this->lehetsegesKategoriak = $lehetsegesKategoriak; //= $this->getMegengedettKategoriak( $kivetelKategoriak );
		$this->catTree=array();
		$this->depth=1;
		$this->limitDepth = $limitDepth;
		$this->catTree( $szulo );
		//$this->setCat();
		$o_="";
		$o_->option = " - ".JText::_("LEGFELSO SZINT")." - ";
		$o_->value = 0;	
		//array_unshift($this->catTree, $o_);
	}

   function catTree($szulo){
      $q = "select * from {$this->table} where szulo = {$szulo} order by sorrend ";
      $this->_db->setQuery($q);
      $rows = $this->_db->loadObjectList();
		echo $this->_db->getErrorMsg();
	  //print_r($rows); die();
	  if(count($rows)){
         foreach($rows as $r){
            $this->depth = 1;
            $this->margo ="";
            $this->catDepth($r->id);
            //echo "{$this->margo}[{$this->depth}] {$r->nev}<br />";
         $margo="";
		 //echo $this->depth;
         for($i=0; $i<$this->depth; $i++){
            $margo.=$this->margo;
         }
            $o_="";
			($r->melyseg > 1) ? $o_->option = "{$margo}|_{$r->nev}" : $o_->option = "{$margo}{$r->nev}";
            $o_->value = $r->id;
            $o_->melyseg = $r->melyseg;
            $o_->szulo = $r->szulo;			
            $o_->sorrend = $r->sorrend;
            $this->catTreeArr[]=$o_;
            
			$o_="";
			$o_->nev = "{$r->nev}";
            $o_->id = $r->id;
            $o_->melyseg = $r->melyseg;
            $o_->szulo = $r->szulo;			
            $o_->sorrend = $r->sorrend;
			$this->catTreeArr2[]=$o_;
            //print_r($this->catTree);
			if($this->depth <= $this->limitDepth ){
				$this->catTree($r->id);
			}
         }
         //print_r($rows); die();
      }
   }

	function setCat(){
		//print_r($this->lehetsegesKategoriak);
		//die;
		foreach($this->catTree as $c){
			$ind = array_search($c, $this->catTree);
			if( !in_array($c->value, $this->lehetsegesKategoriak ) ) {
				unset($this->catTree[$ind]);
			}			
		}
	}

   function catDepth($id){
   	//print $id."<br/>";
      $q = "select szulo from {$this->table} where id = {$id}";
      $this->_db->setQuery($q);
      $res = $this->_db->loadResult();
      //echo $res."<br />";
	  if($res){
         $this->depth++;
         $this->margo .="&nbsp;";
         $this->catDepth($res);
      }
   }
   
   function getStartCat(){
   		$lehetsegesKategoriak = $this->getMegengedettKategoriak( $this->kivetelKategoriak );			
		$q = "select szulo from {$this->table} where id in( {$lehetsegesKategoriak} ) order by melyseg asc limit 1 ";	
		$this->_db->setQuery($q);
		//die($this->_db->loadResult(). "-");
		return $this->_db->loadResult();
   }
   
   function getMegengedettKategoriak( $kivetelKategoriak ){
		$o = new modelBase;
		//$megengedettKategoriak = (array)$o->user->jog->kategoriak ;
		$megengedettKategoriak = array();
		if( !count( $megengedettKategoriak )  ){
			$q = "select id from {$this->table} ";	
			$this->_db->setQuery($q);	
			$megengedettKategoriak = $this->_db->loadResultArray();
		}
		$megengedettKategoriak = array_diff( $megengedettKategoriak, $kivetelKategoriak );
	   	return $megengedettKategoriak  ;
	}
   
   function rebuild_tree($szulo, $left) {
      $right = $left+1;
      $q="SELECT id FROM {$this->table} WHERE szulo ='{$szulo}'";
      $this->_db->setQuery($q);
	  //echo $q."<br />";
      $rows = $this->_db->loadObjectList();
	 //echo $this->_db->geterrorMsg()."<br />";
	  foreach($rows as $row){
         //print_r($row);
         $right = $this->rebuild_tree($row->id, $right);    
      }
      $o="";
      $o->id=$szulo;
      $o->lft = $left;
      $o->rgt = $right;
      $this->depth = 1;
      $this->catDepth($o->id);
	  $o->melyseg = $this->depth;
      $this->_db->updateObject("{$this->table}", $o, "id");
      return $right+1;
	}

}

?>
