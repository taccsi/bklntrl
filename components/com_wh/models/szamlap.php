<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.model');

class whModelszamlap extends whAdmin
{
	var $limit = 30;
	var $uploaded = "media/wh/szamlap/";
	var $w = 55;
	var $h = 200;
	var $mode = "resize";
	var $katSeparator = " > ";
	
   function __construct()
   {
      parent::__construct();
      global $mainframe, $option;
	  $this->datum = date("Y-m-d H:i:s", time() );
      // Get pagination request variables
      $this->xmlParser = new xmlszamlap("szamlap.xml"); 
      // In case limit has been changed, adjust it
      $this->limitstart = jrequest::getVar("limitstart",0);
      //$this->minta();die;

   }//function
	
	function csvImportNagykerAr(){
		/*		
		require_once("components/com_wh/models/move.php");
		$m_ = new whModelmove;
		$m_ -> kategoriaDelete();
		*/
		//die("dfékadsékaédlkds");
		//$arr_ = array("isbn"=>"cikkszam", "mennyiseg"=>"quantity", "");
		$tmp_name = $_FILES["nagyker_ar_csv"]["tmp_name"];		
		  $feldolgozott_sorok=0;           
		  //echo $tmp_name.$_FILES["csvfile"]."-----";
		  if( $tmp_name ){
				$fajlArr = array();
			 $filename = dirname(__FILE__)."/import_csv.csv";
			 move_uploaded_file($tmp_name, $filename);
			 if(file_exists($filename)){
				$handle = fopen($filename, "r");
				$i=0;
				$csv_ok=0;
				$elsosor = fgetcsv( $handle, 1000, ";" );
				//print_r($elsosor);
				while ( ( $row = fgetcsv( $handle, 1000, ";" ) ) !== FALSE) {
					//$fajlArr[]= $row;
					//print_r($elsosor);
					//die;
					if( $this->ellenorizSor( $row, $elsosor) ){
						//print_r($row);
						$ar_nagyker = str_replace(",", "", $row[array_search("netto_atadasi_ar", $elsosor)] );
						$isbn = trim($row[array_search("isbn", $elsosor)]);
						//echo array_search("Nettó átadási ár", $elsosor).$ar_nagyker." --<br />";
						//echo $ar_nagyker." ***** *******<br />";						
						//echo $isbn."<br />";
						//print_r($elsosor);
						//die;
						$this->setAr( $isbn, $ar_nagyker );
					}
				}
				fclose($handle);
				
				//echo $filename;
				//die;
			 }
			//die("--------");
			return true;		 
		  }else{
			return false;
		  }
	}
	
	function setAr( $isbn, $ar_nagyker ){
		$q = "select arT.id as ar_id, termek.*, arT.ar as netto_ar, afaT.ertek as afa_ertek from #__wh_termek as termek 
		inner join #__wh_ar as arT on termek.id = arT.termek_id
		inner join #__wh_afa as afaT on arT.afa_id = afaT.id
		where replace( termek.isbn, '-', '' ) = replace( '{$isbn}', '-', '' ) ";
		$this->_db->setQuery($q);
		$t_ = $this->_db->loadObject();
		//print_r($t_);
		//echo str_replace("-", "", $isbn )." {$ar_nagyker} ***************<br />";
		if($isbn == str_replace("-", "", "9789632990064" ) ){
			//die($ar_nagyker."--------------");
		}
		
		if( $t_->ar_id && $ar_nagyker ){
			$ar_ = "";
			$ar_ ->id = $t_->ar_id;
			$ar_->ar_nagyker = $ar_nagyker;
			$ar_->afa_id_nagyker = 1;//ezt lehet hogy módosítani kell

			$ar_->ar_atadasi = $ar_nagyker;
			$ar_->afa_id_atadasi = 1;//ezt lehet hogy módosítani kell
			
			$this->_db->updateObject( "#__wh_ar", $ar_, "id" );
		}
	}

	function ellenorizSor( $row, $elsosor ){
		//print_r($elsosor);
		//print_r($row);

		foreach($elsosor as $e){
			if(trim($e)){
				$v_ = trim($row[array_search($e, $elsosor)]);
				//echo $v_."<br /><br />"; 
				//echo $e." - ".$v_."<br />";
				switch($e){
					case "isbn" : 
						//$isbn = $this->getIsbn( $v_ );
						$q = "select id from #__wh_termek where replace(isbn, '-', '' ) = replace('{$v_}', '-', '' ) ";
						$this->_db->setQuery($q);
						if ( !$this->_db->loadResult() ) return false;
					break;
					case "felhasznaloi_azonosito" :
						//die( $user_id ."sdfoéjkslédfjksélfkdlséfkdfsélkl" );
						if ( !$this->getFelhasznalo($v_, 1 )->id ) return false;
					break;
					/*
					default :
						//echo "***************** {$v_} <br />";
						if (!$v_){
							//echo "kilép {$v_}<br />";
							return false;
						};
					*/
				}
			}
		}
		//echo "minden ok ?????<br />";
		return true;
	}
	
	function csvJavito(){
		require_once("components/com_wh/models/move.php");
		$m_ = new whModelmove;
		$m_ -> kategoriaDelete();
		//die("dfékadsékaédlkds");
		//$arr_ = array("isbn"=>"cikkszam", "mennyiseg"=>"quantity", "");
		$tmp_name = $_FILES["javitofajl_csv"]["tmp_name"];		
		  $feldolgozott_sorok=0;           
		  //echo $tmp_name.$_FILES["csvfile"]."-----";
		  if( $tmp_name ){
			$sszesKatArr = array();
			 $filename = dirname(__FILE__)."/import_csv.csv";
			 move_uploaded_file($tmp_name, $filename);
			 $fajlArr = array();
			 
			 if(file_exists($filename)){
				$handle = fopen($filename, "r");
				$i=0;
				$csv_ok=0;
				$elsosor = fgetcsv( $handle, 1000, ";" );
				//print_r($elsosor);
				while ( ( $row = fgetcsv( $handle, 1000, ";" ) ) !== FALSE) {
					$fajlArr[]= $row;
					$kat = trim($row[array_search("Műfaj", $elsosor)]);
					if($kat && !in_array ($kat, $sszesKatArr ) ) $sszesKatArr[] = $kat;
				}
				$this->letrehozKategoriak( $sszesKatArr );
				$q = "update #__wh_kategoria set szulo = '' where nev = 'Szórakoztató irodalom' ";
				$this->_db->setQuery( $q );				
				$this->_db->Query( );								
				/*
				$k_ ="";
				$k_->id = 2;
				$k_->szulo = 0;
				$this->_db->updateObject( "#__wh_kategoria", $k_, "id" );
				*/
				//die;
				$q = "update #__wh_termek set kategoria_id = ''";
				$this->_db->setQuery( $q );				
				$this->_db->Query( );								
				
				foreach($fajlArr as $row){
					if( $isbn = trim($row[array_search("ISBN", $elsosor)] ) ){
						$kat = trim( $row[array_search("Műfaj", $elsosor)] );
						$termek = $this->getObj("#__wh_termek", $isbn, "isbn" );
						if($isbn == '978-963-299-995-1' ){ 
							/*
							echo $kat."<br />";
							print_r($termek);
							die("{$isbn}");
							*/
						}
						if ( $kategoria_id = $this->getKategoriaId( $kat ) ){
							$termek->kategoria_id = $kategoria_id;
							$this->_db->updateObject("#__wh_termek", $termek, "id" );
						}
					}
				}
				
				fclose($handle);
				
				//echo $filename;
				//die;
			 }
			//die("--------");
			return true;		 
		  }else{
			return false;
		  }
	}

	function letrehozKategoriak( $sszesKatArr ){
		//return ;
		//print_r($sszesKatArr); 
		//die;
		//echo count($this->items['Books']); die();
		foreach ( $sszesKatArr as $str ){
			$kategoriak = explode($this->katSeparator, $str);
			foreach( $kategoriak as $kategoriaNev ){
				$ind = array_search( $kategoriaNev, $kategoriak );
				$q = "select * from #__wh_kategoria where nev = '{$kategoriaNev}' ";
				$this->_db->setQuery($q);
				if( $rows = $this->_db->loadObjectList() ){//létezik de az ágat vizsgálni kell
					$letezik = false;
					$arr_ = array_chunk( $kategoriak, $ind+1 );				
					foreach( $rows as $r ){
						$q = "select nev from #__wh_kategoria where lft<= {$r->lft} and rgt >= {$r->rgt} order by lft asc ";
						$this->_db->setQuery($q);
						if( $this->_db->loadResultArray() == $arr_[0] ){//létezik!!!
							$letezik = true;
							break;
						}
					}
					if($letezik){
						$kategoria_id = end($rows)->id;
					}else{
						$kategoria_id = $this->letrehozKategoria($kategoriak, $ind, $kategoriaNev);
					}
				}else{//biztos, hogy nem létezik a kategória, létrehozzuk
					$kategoria_id = $this->letrehozKategoria($kategoriak, $ind, $kategoriaNev);
				}
			}
		}
		$kategoriafa = new kategoriafa();
		//die("adkdsékadsék");
	}

	function getKategoriaId( $str ){
		$kategoriak = explode( $this->katSeparator, $str );	
		$katNev = trim(end($kategoriak));
		$q = "select * from #__wh_kategoria where nev = '{$katNev}' ";
		$this ->_db->setQuery($q);
		//print_r( $this->_db->loadObjectList() );
		$kategoria_id = 0;		
		foreach( $this ->_db->loadObjectList() as $k_ ){
			$q = "select nev from #__wh_kategoria where lft <= {$k_->lft} and rgt >= {$k_->rgt} order by lft ";
			$this ->_db->setQuery($q);
			$str_ = implode( $this->katSeparator, $this ->_db->loadResultArray() );
			if( strstr( trim($str), trim($str_) ) ){				
				$q = "select id from #__wh_kategoria where lft >= {$k_->lft} and rgt <= {$k_->rgt} order by lft ";
				$this ->_db->setQuery($q);
				$arr_ = $this ->_db->loadResultArray();
				$kategoria_id = end($arr_);
				//print_r(end($arr_));
				//die("-----------------");
			}
			//if($kategoria_id) break;
		}
		return $kategoria_id;
	}

	function letrehozKategoria($kategoriak, $ind, $kategoriaNev){
		$o = "";
		$o->szulo = ($ind) ? $this->getObj("#__wh_kategoria", $kategoriak[($ind-1)], "nev")->id : 0;
		$o->nev = $kategoriaNev;
		$o->aktiv = 'igen';
		$this->_db->insertObject( "#__wh_kategoria", $o, "id" );
		$kategoria_id = $this->_db->insertID();				
		$kategoriafa = new kategoriafa();
		return $kategoria_id;
	}

	function kontirImport___(){
		$tmp_name = $_FILES["importfile_kontir"]["tmp_name"];		
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
				$elsosor = fgetcsv($handle, 1000, ";");
				//print_r($elsosor);
				//die;
				$ret = "";
				$ret.="^programname  Kontir 2000 Win32" . PHP_EOL;
				$ret.="^version      2.0" . PHP_EOL;
				$ret.="^codepage     D" . PHP_EOL;
				$ret.="^date         2010.09.20." . PHP_EOL;
				$ret.="^time         14:28" . PHP_EOL;
				$ret.="^firmname     CENTRO" . PHP_EOL;
				$ret.="^user         C" . PHP_EOL;
		
				while ( ( $row = fgetcsv($handle, 1000, ";" ) ) !== FALSE) {
				   $szamlap = "";
					$ret.="^startrecord" . PHP_EOL;
					$ret.="^type  1" . PHP_EOL;
					$ret.="^field s05       09" . PHP_EOL;
					$ret.="^field s10       13" . PHP_EOL;
					$ret.="^field s20       K" . PHP_EOL;
					$ret.="^field s25       3812" . PHP_EOL;
					$ret.="^field s27       911" . PHP_EOL;
					$ret.="^field s30       467" . PHP_EOL;
					$datum = date("Y-m-d", strtotime($row[array_search("Dátum", $elsosor)]) );
					$ret.="^field afadat    {$datum}" . PHP_EOL;
					$v = $row[array_search("Azonosító", $elsosor)];
					$ret.="^field s35       {$v}" . PHP_EOL;
					$ret.="^field s45" . PHP_EOL;				
					$fizetendo = $row[array_search("Fizetendő", $elsosor)];
					$ret.="^field s60       {$fizetendo}" . PHP_EOL;
					$alaposszeg = $row[array_search("Alapösszeg", $elsosor)];
					$afaOsszeg = $row[array_search("ÁFA", $elsosor)];				
					$afaKulcs =  floor(($fizetendo/$alaposszeg-1)*100);
					$ret.="^field s55       {$afaKulcs},00" . PHP_EOL;
					$ret.="^field s65       {$alaposszeg},00" . PHP_EOL;
					$ret.="^field s70       {$afaOsszeg},00" . PHP_EOL;
					$v = $row[array_search("Vevő név", $elsosor)];
					$ret.="^field s75       {$v}" . PHP_EOL;
					$ret.="^field s85       {$datum}" . PHP_EOL;
					$ret.="^endrecord" . PHP_EOL;
				}
			 }
			//die($filename);
			unlink($filename);		
			$ret.=" ".PHP_EOL;
			$filename= "components/com_wh/models/konverzio.txt";
			$fp = fopen($filename, "w");
			fputs($fp, $ret);
			fclose($fp);
			return $filename;		 
		  }else{
		  	return false;
		  }
	}

	function csvImport(){
		//die("dfékadsékaédlkds");
		//$arr_ = array("isbn"=>"cikkszam", "mennyiseg"=>"quantity", "");
		$tmp_name = $_FILES["importfile_csv"]["tmp_name"];		
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
				$elsosor = fgetcsv($handle, 1000, ";");
				//print_r($elsosor);
				while ( ( $row = fgetcsv($handle, 1000, ";" ) ) !== FALSE) {
					//echo " kdfhskjsdfhkdsfh<br />";
					//echo $this->ellenorizSor($row, $elsosor)." **<br />"; 
					if( $this->ellenorizSor($row, $elsosor) ){
						$rendeles_id = $this->letrehozRendeles($row, $elsosor);
						$this->letrehozTetel( $rendeles_id, $row, $elsosor );						
						//echo $rendeles_id."<br />";
						//echo ("ok a sor<br />");
					}
				}
			 }
			//die("--------");
			return true;		 
		  }else{
		  	return false;
		  }
	}

	function letrehozTetel($rendeles_id, $row, $elsosor ){
		$isbn = trim( $row[array_search("isbn", $elsosor)]);
		//echo "isbn: {$isbn}<br />";
		$mennyiseg = trim($row[array_search("mennyiseg", $elsosor)]);
		$q = "select termek.*, arT.ar as netto_ar, arT.ar_nagyker, arT.afa_id_nagyker, afaT.ertek as afa_ertek from #__wh_termek as termek 
		inner join #__wh_ar as arT on termek.id = arT.termek_id
		inner join #__wh_afa as afaT on arT.afa_id_nagyker = afaT.id
		where replace(isbn , '-', '') = replace('{$isbn}', '-', '' )";		
		$this->_db->setQuery($q);
		$termek = $this->_db->loadObject();
		//print_r($termek);
		//die("{$isbn} {$mennyiseg}");
		$rendeles = $this->getObj("#__wh_rendeles", $rendeles_id);
		$t = "";
		$t->rendeles_id = $rendeles_id;
		$t->nev = $termek->nev;
		$t->cikkszam = str_replace("-", "", $isbn);
		$t->quantity = $mennyiseg;
		$t->netto_ar = $termek->ar_nagyker;
		$t->afa = $termek->afa_ertek;
		$t->termek_id = $termek->id;
		
		
		//$t->netto_ar = $this->getFelhCsoportAr( $this->getFelhasznalo($rendeles->user_id, 1 )->id, $termek->netto_ar, $termek->id );
		//
		//$t->afa = $termek->afa_ertek;
		$this->_db->insertObject("#__wh_tetel", $t, "id");
	}

	function getFelhCsoportAr( $felhasznalo_id, $ar, $termek_id ){
		//echo "$felhasznalo_id, $ar, $termek_id <br />";
		$q = "select fcsoport.* from #__wh_felhasznalo as felhasznalo
		inner join #__wh_fcsoport as fcsoport on felhasznalo.fcsoport_id = fcsoport.id
		where felhasznalo.id = {$felhasznalo_id}";
		$this->_db->setQuery($q);
		$fcsop = $this->_db->loadObject();
		//echo $ar."<br />";
		if($fcsop){
			if($fcsop->kedvezmeny_tipus == "OSSZEG"){
				$ar -= $fcsop->kedvezmeny;
			}else{
				$ar -=  ($ar * $fcsop->kedvezmeny/100);
			}
		}
		//echo $ar."<br />";
		//die;
		return $ar;
	}

	function letrehozRendeles($row, $elsosor){
		$user_id = trim($row[array_search("felhasznaloi_azonosito", $elsosor)]);		
		$q = "select * from #__wh_felhasznalo where user_id = {$user_id} and webshop_id = 1 ";
		$this->_db->setQuery($q);
		$f_ = $this->_db->loadObject();

		$q = "select id from #__wh_rendeles where user_id = {$user_id} and webshop_id = 1 and datum = '{$this->datum}' ";
		$this->_db->setQuery($q);
		$rendeles_id = $this->_db->loadresult();		
		echo $q."<br />";
		if(!$rendeles_id){
			$r = "";
			$r->user_id = $user_id;
			$r->webshop_id = 1;
			$r->datum = $this->datum;
			$r->szallitasi_cim = $f_->szallitasi_cim;
			$r->szamlazasi_cim = $f_->szamlazasi_cim;
			$r->b2b = "igen";
			$this->_db->insertObject("#__wh_rendeles", $r, "id" );
			$rendeles_id = $this->_db->insertid();					
		}else{
			//die("rrrrrrrrr");
		}
		
		return $rendeles_id;
	}

	function getFelhasznalo($user_id, $webshop_id = 1){
		$q = "select * from #__wh_felhasznalo where user_id = {$user_id} and webshop_id = {$webshop_id} ";
		$this->_db->setQuery($q);
		return $this->_db->loadObject();
	}

	function import(){ 
		$database = JFactory::getDBO();
      //echo "feldolgoz_csv";
		$f_=jrequest::getVar("kapcsolo");
		return $this->$f_();
	}
	

}// class

?>