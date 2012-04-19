<?php
defined( '_JEXEC' ) or die( '=;)' );
ini_set("display_errors", "1");
error_reporting(1);
//phpinfo();

class whModelmove extends modelbase{

	function expKategoriak(){
		$q = "select * from bikeline.bl_vm_category as c inner join bikeline.bl_vm_category_xref as xc
		on c.category_id = category_child_id";
		$this->_db->setQuery($q);
		foreach($this->_db->loadObjectList() as $k){
			$o=new stdClass;
			$o->id = $k->category_id;
			$o->nev = $k->category_name;
			$o->szulo = $k->category_parent_id;
			$o->aktiv = 'igen';
			$o->msablon_id = 1;
			$this->_db->insertObject("#__wh_kategoria", $o, "id");
			//print_r($o);
			//die;
		}

		$o=new stdClass;
		$o->nev = "admin";
		$o->szulo = 0;
		$o->aktiv = 'igen';
		$this->_db->insertObject( "#__wh_kategoria", $o, "id" );
		$admin_id = $this->_db->insertId( );
		$q = "update #__wh_kategoria set szulo = {$admin_id} where szulo is null or szulo = 0 and id <> {$admin_id} ";
		$this->_db->setQuery( $q );
		$this->_db->Query();
	}

	function import(){
		/*
		$this->nagyReset();
		$this->resetKategoria();	
		$this->expKategoriak();
		$this->expTermekek();
		*/
		die("import_vege");
	}

	function resetKategoria(){
		$q = "truncate table #__wh_kategoria ";
		$this->_db->setQuery($q);
		$this->_db->Query();
	}

	function expTermekek(){
		//die("**");
		$q = "select * from bikeline.bl_vm_product as p 
		left join bikeline.bl_vm_product_category_xref as xp on p.product_id = xp.product_id
		left join bikeline.bl_vm_product_price as price on p.product_id = price.product_id
		group by p.product_id
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
			$o->aktiv = ( $p->product_publish == 'Y' ) ? 'igen' : 'nem' ;
			$o->cikkszam = $p->product_sku;

			$o->leiras = $p->product_desc;
			$o->leiras_rovid = $p->product_s_desc;
			$o->kategoria_id = $p->category_id;
			

			$this->_db->insertObject("#__wh_termek", $o, "id");

			$termek_id = $this->_db->insertId();
			$ar = "";
			$ar->termek_id = $p->product_id;
			$ar->afa_id = 1;
			$ar->ar = $p->product_price;
			$ar->webshop_id = 1;
			$this->_db->insertObject("#__wh_ar", $ar, "id");

			echo $this->_db->geterrorMsg( );			
			$this->letrehozKep($o->id, $p, "termek", $p->product_name);
			//$this->letrehozTermvariaciok( $o->id, $p );
			//$this->letrehozLencsek( $o->id, $p );			
			//$this->letrehozTulajdonsagok( $o->id, $p );
		
			//print_r($o);
		}
		//die("import end");
	}

	function letrehozKep( $termek_id, $p="", $csoport="", $nev = "", $leiras = "" ){
		
		$arr = array();
		$arr[]="../components/com_virtuemart/shop_image/product/{$p->product_full_image}";
		$q = "select concat('..', file_name ) from bikeline.bl_vm_product_files where file_product_id = {$termek_id} and file_mimetype = 'image/jpeg' ";
		$this->_db->setQuery($q);
		$arr = array_merge($arr, $this->_db->loadResultArray() );
		if($termek_id == 370){
			//print_r($arr);
			//die("---");
		}
		//$forrasKep = ;
		foreach($arr as $forrasKep ){
			$forrasKep = trim( $forrasKep );
			if( file_exists($forrasKep) && is_file($forrasKep) ){
				if($termek_id == 370){
					//echo $forrasKep." *********************<br />";
					//die("---");
				}
				//echo $forrasKep." ******<br />";
				$k= new stdClass;
				$k->termek_id = $termek_id;
				$k->aktiv = "igen";
				//$k->csoport = $csoport;
				$k->nev = $nev;			
				//$k->leiras = $leiras;						
				$this->_db->insertObject("#__wh_kep", $k, "id");
				$kepId = $this->_db->insertID();
				$celkep = "media/termekek/{$kepId}.jpg";
				copy($forrasKep, $celkep);
			}
		}
		//die;
	}

	function letrehozLencsek( $termek_id, $vm_termek ){
		$szinCikkszamArr = explode(",", $vm_termek->lencse ); 
		//print_r( $szinCikkszamArr );
		//$p->product_full_image
		foreach( $szinCikkszamArr as $c ){
			$p = $this->getObj( "bikeline.bl_vm_product", $c, "product_sku" );
			$this->letrehozKep($termek_id, $p, "lencse", $p->product_name, $p->product_desc."<br />".$p->product_s_desc);			
		}
		//die;
	}

	function letrehozTermvariaciok( $termek_id, $vm_termek ){
		$szinCikkszamArr = explode(",", $vm_termek->szin ); 
		//print_r( $szinCikkszamArr );
		//$p->product_full_image
		foreach( $szinCikkszamArr as $c ){
			//
			$p = $this->getObj( "bikeline.bl_vm_product", $c, "product_sku" );
			$tv = "";
			$tv->termek_id = $termek_id;
			$tv->ertek = "&mezoid_5={$p->product_name}&";			
			$this->_db->insertObject( "#__wh_termekvariacio", $tv, "id" );	
			$this->letrehozKep($termek_id, $p, "szin", $p->product_name);			
			//print_r( $p );
			//echo "dáéklsd<br />";
		}
		//die;
	}
	
	function nagyReset(){
		$arr = array("#__wh_kategoria", "#__wh_termek", "#__wh_gyarto", "#__wh_kep", "#__wh_termekvariacio", "#__wh_fajl", "#__wh_ar" );
		foreach($arr as $a ){
			$q = "truncate table {$a}" ;
			$this->_db->setQuery($q);
			$this->_db->Query($q);			
		}
		foreach( array("images/resized", "media/termekek") as $dir ){
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

	function letrehozTulajdonsagok( $termek_id, $vm_termek ){
		$idArr = explode(",", $vm_termek->features ); 
		//print_r( $szinCikkszamArr );
		//$p->product_full_image
		$idArr = $this->cleanTomb( $idArr );
		foreach( $idArr as $c ){
			if(trim($c)){
				$t_ = $this->getObj( "#__wh_tulajdonsag", $c, "old_id");
				$tulajdonsag_id = $t_->id;
				if( !$t_ ){
					$p = $this->getObj( "bikeline.bl_vm_product", $c, "product_sku" );
					print_r($p);
					$t_ = "";			
					$t_ -> nev = $p->product_name;
					$t_ -> leiras = $p->product_s_desc.$p->product_desc;
					$t_ -> old_id = $c;
					$this->_db->insertObject("#__wh_tulajdonsag", $t_, "id" );
					$tulajdonsag_id = $this->_db->insertId();
					$forrasKep = "../components/com_virtuemart/shop_image/product/{$p->product_full_image}";
					if( file_exists($forrasKep) ){
						$k= "";
						$k->kapcsolo_id  = $tulajdonsag_id;
						$k->kapcsoloNev  = "tulajdonsag_id";
						$k->xmlNev  = "tulajdonsag_kep";
						$fArr = explode(".", $p->product_full_image);
						$k->fajlnev  = $fArr[0];
						$k->eredetiNev  = $fArr[0];
						$k->ext = end($fArr);
						$this->_db->insertObject("#__wh_fajl", $k, "id");
						$kepId = $this->_db->insertID();
						$celkep = "../media/tulajdonsagok/{$k->fajlnev}.{$k->ext}";
						copy($forrasKep, $celkep);
					}
				}
				$kapcs = "";
				$kapcs ->termek_id = $termek_id;
				$kapcs ->tulajdonsag_id = $tulajdonsag_id;			
				$this->_db->insertObject("#__wh_termek_tul_kapcsolo", $kapcs, "id" );	
			}
			//$this->letrehozKep($termek_id, $p, "lencse", $p->product_name, $p->product_desc."<br />".$p->product_s_desc);			
		}
		//print_r($idArr);
		//die;
		//die;
	}
		
}// class
?>
