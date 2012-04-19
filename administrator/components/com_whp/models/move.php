<?php
defined( '_JEXEC' ) or die( '=;)' );
ini_set("display_errors", "1");
error_reporting(1);
//phpinfo();

class whpModelmove extends modelbase
{

	function expTermekek(){
		//die("**");
		$q = "select * from #__vm_product as p 
		inner join #__vm_product_category_xref as xp on p.product_id = xp.product_id
		inner join #__vm_product_price as price on p.product_id = price.product_id
		";
		$this->_db->setQuery($q);
		$this->_db->loadObjectList();
		echo $this->_db->getErrorMsg();
		//die;
		foreach($this->_db->loadObjectList() as $p){
			$o=new stdClass;
			$o->id = $p->product_id;
			$o->nev = $p->product_name;
			$o->kategoria_id = $p->category_id;
			$o->aktiv = 'igen';
			$o->cikkszam = $p->product_sku;
			$o->ar = $p->product_price;
			$o->afa_id = 1;
			$o->leiras = $p->product_desc;
			$o->leiras_rovid = $p->product_s_desc;
			$o->kategoria_id = $p->category_id;
			$this->_db->insertObject("#__whp_termek", $o, "id");
			$forrasKep = "../components/com_virtuemart/shop_image/product/{$p->product_full_image}";
			if(file_exists($forrasKep)){
				$k= new stdClass;
				$k->termek_id = $o->id;
				$k->aktiv = "igen";
				$this->_db->insertObject("#__whp_kep", $k, "id");
				$kepId = $this->_db->insertID();
				$celkep = "../media/termekek/{$kepId}.jpg";
				copy($forrasKep, $celkep);
			}
			//print_r($o);
			//die;
		}
	}

	function expKategoriak(){
		//die("**");
		$q = "select * from #__vm_category as c inner join #__vm_category_xref as xc
		on c.category_id = category_child_id";
		$this->_db->setQuery($q);
		foreach($this->_db->loadObjectList() as $k){
			$o=new stdClass;
			$o->id = $k->category_id;
			$o->nev = $k->category_name;
			$o->szulo = $k->category_parent_id;
			$o->aktiv = 'igen';
			$this->_db->insertObject("#__whp_kategoria", $o, "id");
			//print_r($o);
			//die;
		}
	}
	
	function nagyReset(){
		$arr = array("#__whp_kategoria", "#__whp_termek", "#__whp_gyarto", "#__whp_felhasznalo", "#__whp_rendeles","#__whp_tetel","#__whp_kep" );
		foreach($arr as $a ){
			$q = "truncate table {$a}" ;
			$this->_db->setQuery($q);
			$this->_db->Query($q);			
		}
		foreach(array("images/resized", "media/wh/termekek") as $dir ){
			$this->recursiveDelete($dir);
		}
	
	}
	
	function delMediaDir(){
		$this->recursiveDelete("media/termekek");
		$this->recursiveDelete("images/resized");		
	}

    function recursiveDelete($str){
        if(is_file($str)){
            return @unlink($str);
        }
        elseif(is_dir($str)){
            $scan = glob(rtrim($str,'/').'/*');
            foreach($scan as $index=>$path){
                $this->recursiveDelete($path);
            }
            //return @rmdir($str);
        }
    }
	

}// class
?>
