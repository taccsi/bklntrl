<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.model');
ini_set( 'max_execution_time', 50 );
//ini_set('memory_limit', '256' );
//ini_set("display_errors", 1);
//error_reporting(E_ALL);
//phpinfo();
//die;
class whModelimpex extends whAdmin{
	var $eles = true;
	var $limit = 30;
	var $uploaded = "media/wh/impex/"; 
	var $w = 55;
	var $h = 200;
	var $mode = "resize";
	var $katSeparator = "/";
	var $kategorianevLeirasbanCount =0;
	var $termekIdArr = array();
	var $delimiter = ";";
	var $enclosure = "~";	
	
	var $sablon = array(
		"kategoria" => "kategoria",	
		"cikkszam" => "cikkszam",
		"nev" => "termeknev",		
		"netto_ar"=>"brutto_ar",
		"leiras_rovid" => "rovidleiras",
		"leiras" => "leiras",
		"marka" => "marka",
		"aktiv" => "aktiv",
		"sorrend" => "sorrend",
	);

	function getExportRows( ){
		$q = "select termek.*, kategoria.lft, kategoria.rgt, gyarto.nev as marka from #__wh_termek as termek 
		inner join #__wh_kategoria as kategoria on termek.kategoria_id = kategoria.id
		left join #__wh_gyarto as gyarto on gyarto.id = termek.gyarto_id
		group by termek.id
		";
		$this->_db->setQuery( $q );
		$rows = $this->_db->loadObjectList( );
		//print_r($rows);
		//die;
		echo $this->_db->getErrorMsg( );
		$arrFej = array();
		$ret=array();
		//print_r($rows);
		foreach( $rows as $r ){
			$tmp = array();
			foreach($this->sablon as $k => $v ){
				switch( $k ){
					case "kategoria":
						$q = "select nev from #__wh_kategoria where lft<={$r->lft} and rgt >= {$r->rgt} order by lft";
						$this->_db->setQuery($q);
						$tmp[$v]= str_replace("admin/","", implode("/", $this->_db->loadResultArray() ) ); 
					break;
					case "netto_ar":
						$q = "select ( arT.ar * ( 1 + afaT.ertek/100 ) ) as brutto_ar from #__wh_ar as arT
						inner join #__wh_afa as afaT on arT.afa_id = afaT.id
						where arT.termek_id = {$r->id} 
						limit 1";
						$this->_db->setQuery( $q );
						$tmp[$v]=$this->_db->loadResult( );
						//$this->_db->loadResult( )						
						echo $this->_db->geterrorMsg( );
					break;
					/*
					case "leiras":
					case "leiras_rovid":
					break;
					*/
					default : $tmp[$v] = $r->$k;
				}
				//print_r( $tmp );
			}
			
			$q = "select * from #__wh_termekvariacio where termek_id = {$r->id} order by sorrend";
			$this->_db->setQuery( $q );
			$rowsTv = $this->_db->loadObjectList(  );
			echo  $this->_db->getErrorMsg(  );
			//echo  $this->_db->getQuery( );
			$opcio = "";
			if( count($rowsTv) ){
				parse_str( $rowsTv[0]->ertek );
				foreach( $this->osszesMsablonMezo as $a__ ){
					$vN_ = "mezoid_{$a__->id}"; 
					if( isset($$vN_) && $this->getObj( "#__wh_msablonmezo", $a__->id )->id ){
						$v = $a__->nev;
						$tmp[$v] = $$vN_;
					}
				}
			}else{
				//die("kkkkkk");
				foreach( $this->osszesMsablonMezo as $a__ ){
					$v = $a__->nev;
					//echo $v." ***************<br />";
					$tmp[$v] = "";
				}
			}
			
			if( $this->checkExportSor( $tmp, $r ) || 1 ){
				$ret[]=$tmp;
			}else{
				print_r($r);
				die ( "hiba!!! " );
			}
			
		}
		array_unshift($ret, array_keys( $ret[0] ) );
		$ret = $this->cleanTomb($ret);
		//print_r($ret);
		//die;
		return $ret;
	}

	function cleanTomb($arr){
		$ret = array();
		foreach( $arr as $arr_){
			$ret_ = array();
			foreach($arr_ as $k => $a){
				$a = trim($a);
				$a = str_replace( array("\n", "\r"),"", $a );
				$ret_[$k]=$a;
			}
			$ret[]=$ret_;
		}
		//print_r($ret);
		//die;
		return $ret;
	}
	
	function checkExportSor($arr, $debugObj = ""){
		if( !isset( $this->fejArr ) ){
			//print_r($arr);
			//die("----");
			$this->fejArr = $arr;
			//print_r($arr);
			//die;
			return true;
		}else{
			if( array_keys( $this->fejArr ) != array_keys( $arr ) ){
				return false;
			}
		}
		return true;
	}

	function export(){
		$user_id = jrequest::getVar("user_id", 0);
		$rows = $this->getExportRows(  );
		if( count($rows) ){
			$dir = "export_734573457345";
			$filename="{$dir}/termek_export_".date( "Ymd_His",time() ).".csv";
			//die ( $filename." ---") ;
			$fp = fopen( $filename, "w" );
			foreach($rows as $r){
				//print_r($rows);
				//die;
				fputcsv( $fp, $r, $this->delimiter, $this->enclosure );				
			}
			fclose($fp);
			$this->setsessionvar("filename_", $filename);
			//return true;
		}else{
			$this->setsessionvar("filename_", "" );
			//return false;
		}
		//die;
	}

	function __construct(){
	 	parent::__construct();
		global $mainframe, $option;
		$this->adminKategoria = $this->getSzuloKategoria();
		$q = "select id from #__wh_msablonmezo as mezo ";
		$this->_db->setQuery($q);
		$this->osszesMezoIdArr = $this->_db->loadResultArray();
		//$this->modellOpciok = $this->getObj("#__wh_msablonmezo", "Modell opciók", "nev" );
		$q = "select * from #__wh_msablonmezo";
		$this->_db->setQuery( $q );
		$this->osszesMsablonMezo = $this->_db->loadObjectList( );
		//print_r( $this->osszesMsablonMezo );
		//die;
		echo  $this->_db->getErrorMsg(  );
		@$this->altalanosSablonId = $this->getObj( "#__wh_msablon", "Általános", "nev" )->id;
	}//function
	
	function getForrasKep( $cikkszam ){
		$dir = "importkepek";
		$scan = glob(rtrim($dir,'/').'/*');
		foreach( $scan as $index=>$path ){
			if( is_file($path) && strstr($path, $cikkszam ) ){
				return $path;
			}
		}
		return false;
	}
	
	function importTermekekEgyseges( ){
		//die("importTermekekEgyseges");
		//$sablon = $this->lanchalozatSablon;
		$feldolgozott_sorok = 0;
		$szamlalo=0;
		foreach( (array)$this->fileArr as $a){
			if( $cikkszam = $this->getSorErtek( $a, $this->sablon[ "cikkszam" ] ) ){
				$kategoria = $this->getSorErtek( $a, $this->sablon[ "kategoria" ] );
				$t_ = "";//echo $str."<br />";
				$t_->cikkszam = $cikkszam;
				$t_->nev = $termeknev = $this->getSorErtek( $a, $this->sablon[ "nev" ] ); 
				$t_->sorrend = $this->getSorErtek( $a, $this->sablon[ "sorrend" ] );
				$brutto_ar =$this->getSorErtek( $a, $this->sablon[ "netto_ar" ] );
				$brutto_ar = str_replace(array(","),"", $brutto_ar);
				//echo $brutto_ar. " ".$cikkszam."<br />";
				$netto_ar = (int)$brutto_ar / 1.25 ;
				$afa_id = 1;
				//echo $netto_ar."<br />";
				$t_->leiras = $this->getSorErtek( $a, $this->sablon[ "leiras" ] );
				$t_->leiras_rovid = $this->getSorErtek( $a, $this->sablon[ "leiras_rovid" ] );
				$aktiv = $this->getSorErtek( $a, $this->sablon[ "aktiv" ] );
				$aktiv = ( $aktiv == "nem" ) ? "nem" : "igen";
				
				$t_->aktiv = ( in_array($aktiv, array("igen", "1", "Y") ) ) ? "igen" : "nem";
				@$t_ ->gyarto_id = $this->getGyartoId( $termek_id, "_/".$this->getSorErtek( $a, $this->sablon[ "marka" ] ) );
				//echo $kategoria."<br />";
				//die;
				if( $kategoria_id = $this->getKategoriaId( $kategoria ) ){
					$t_->kategoria_id = $kategoria_id;
				}
				//echo $t_->kategoria_id."<br />";
				if( $t__ = $this->getObj( "#__wh_termek", $cikkszam, "cikkszam" ) ){
					$t_->id = $t__->id;
					//print_r($t__);
					if( $this->eles ) $this->_db->updateObject("#__wh_termek", $t_, "id" );
					//echo "fentvan <br />";
					$termek_id = $t__->id;
				}else{
					//$t_->kategoria_id = $this->getKategoriaIdBringaland( $kategoria );
					//echo "kategoria_id: {$t_->kategoria_id}<br />";
					if(!$t_->kategoria_id){
						$szamlalo++;
					}
					
					if( $this->eles ) $this->_db->insertObject( "#__wh_termek", $t_, "id" );
					$termek_id = $this->_db->insertId( );
					//print_r( $t_ );
					echo "utjermek <br />";					
					//die;
					//echo $this->_db->getErrorMsg()." *********<br />";
				}
				if( $this->eles ) {
					$this->setAr($termek_id, $netto_ar, $afa_id);
					$this->setTermekvariacioEgyseges( $termek_id, $a );				
					$this->setKep( $termek_id, $cikkszam );
				}
				$feldolgozott_sorok++;
			}
		}
		//echo "kategorianevLeirasbanCount: ".$this->kategorianevLeirasbanCount."<br />";
		//echo $szamlalo;
		//die( "****************{$feldolgozott_sorok}" );
		return $feldolgozott_sorok;
	}

	function setTermekvariacioEgyseges( $termek_id, $sor ){
		if( $termek_id ){
			$arr_=array();
			foreach($this->sablon as $a){
				$arr_[] = $a;
			}
			$parameterNevek = array();
			foreach( $this->elsosor as $a ){
				$ind = array_search($a, $this->elsosor );
				if(!in_array( $a, $arr_ ) ){
					$parameterNevek[]=$a;
				}
			}
			$ind = 0;
			foreach( $parameterNevek as $a ){
				$a = trim($a);
				if( !$this->getObj( "#__wh_msablonmezo", $a, "nev" )->id ){
					$o="";
					$o->nev = $a;
					$o->aktiv = "igen";
					$o->tipus = "textrow";
					$o->kereso = "nem";
					$o->sorrend = $ind;					
					$this->_db->insertObject( "#__wh_msablonmezo", $o, "id" );
					echo $this->_db->getErrorMsg(  );
					$o="";
					$o->msablonmezo_id = $this->_db->insertId( );
					$o->msablon_id = $this->altalanosSablonId;
					$this->_db->insertObject( "#__wh_msablonmezo_kapcsolo", $o, "id" );
					echo $this->_db->getErrorMsg(  );					
					$ind++;					
				}
			}
			//print_r( $parameterNevek );
			//die("--");
			$q = "delete from #__wh_termekvariacio where termek_id = {$termek_id} ";
			$this->_db->setQuery($q);
			$this->_db->Query();
			// ,kapcsolo
			$q = "select * from #__wh_msablonmezo as mezo ";
			$this->_db->setQuery( $q );
			$rows = $this->_db->loadObjectList(  );
			echo  $this->_db->getErrorMsg(  );
			//echo  $this->_db->getQuery(  );
			$str="";
			$modellOpciok = "";
			foreach( $rows as $r ){
				//echo $r->nev."<br />";
				if($r->nev == "Modell opciók"){
					$modellOpciok = $this->getSorErtek( $sor, $r->nev );
					$modellOpciokId = $r->id;
				}else{
					if($v_ = $this->getSorErtek( $sor, $r->nev )){
						$str .= "&mezoid_{$r->id}={$v_}&";
					}
				}
			}			
			$tv = "";
			$tv->termek_id = $termek_id;
			$tv->ertek = $str;
			$this->_db->insertObject( "#__wh_termekvariacio", $tv, "id" );
			
			if( $modellOpciok ){
				$tvO = $this->getObj("#__wh_termekvariacio", $this->_db->insertId() );
				$ind = 0;
				$delimiter = (strstr($modellOpciok,"**")) ? "**" : "\n";
				foreach( explode($delimiter, $modellOpciok ) as $a_ ){
					$tmp_ = explode(":", $a_ );
					$ar = str_replace(array(".", " ", "Ft"),"", end($tmp_) );
					$ar =  (int)$ar/1.25 ;
					$tvO->sorrend = $ind;
					if( $ind==0 ){
						$tvO->ar = $ar;
						$v_ = trim( $tmp_[0] );
						$tvO->ertek .= "mezoid_{$modellOpciokId}={$v_}&";
						$this->_db->updateObject( "#__wh_termekvariacio", $tvO, "id" );
					}else{
						unset($tvO->id);
						$tvO->ar = $ar;
						$this->_db->insertObject( "#__wh_termekvariacio", $tvO, "id" );
					}
					$ind++;
					//echo $ar."<br />";
				}
				//print_r( $tvO );
				//print_r( $modellOpciok );
				//die("");
			}
		}
		//print_r($tv);
		//die;
	}

	function getSorErtek( $sor, $str ){
		if(in_array( $str, $this->elsosor) ){
			$ret = trim( $sor[array_search( $str, $this->elsosor)] );
		}else{
			$ret = "";
		}
		return ($ret) ? $ret : false;
	}

	function setKep( $termek_id, $cikkszam ){
		//$this->delThommeyKepek();
		//die($termek_id." ***");
		if( $termek_id  ){
			$t = $this->getObj("#__wh_termek", $termek_id );
			$q = "select * from #__wh_kep where termek_id = {$t->id} order by id asc";
			$this->_db->setQuery($q);
			$kepek = $this->_db->loadObjectList();
			$forras_kep = $this->getForrasKep( $cikkszam );
			echo "$forras_kep <br />";
			if( file_exists( $forras_kep ) && is_file( $forras_kep ) ){
				$this->delKepek( $termek_id );				
				//echo $forras_kep."<br />";
				$k  = "";
				$k->termek_id = $termek_id;
				$k->aktiv = "igen";
				$k->site_kapcsolo = ""; 
				$this->_db->insertObject("#__wh_kep", $k, "id");
				$id = $this->_db->insertId();
				$celkep = "media/termekek/{$id}.jpg";
				copy( $forras_kep, $celkep );						
				//print_r($k);											
			}
		}
	}
	
	function setKepek__( $termek_id, $sor ){
		//$this->delThommeyKepek();
		//die($termek_id." ***");
		if( $termek_id  ){
			$t = $this->getObj("#__wh_termek", $termek_id );
			$q = "select * from #__wh_kep where termek_id = {$t->id} order by id asc";
			$this->_db->setQuery($q);
			$kepek = $this->_db->loadObjectList();
			//echo $forras_kep."<br />";
			if( count($kepek) ){
				//$this->delKepek( $termek_id );
				foreach($kepek as $k_){
					$ind = array_search($k_, $kepek) + 1;
					$kep = $this->getSorErtek( $sor, "KÉP{$ind}" );
					$forras_kep = "impkepek/{$kep}.jpg";
					if( file_exists( $forras_kep ) && is_file( $forras_kep ) ){
						$celkep = "media/termekek/{$ind}.jpg";
						copy( $forras_kep, $celkep );
					}
				}
			}else{//uj képek
				for( $i=1; $i<=5; $i++ ){
					if( $kep = $this->getSorErtek( $sor, "KÉP{$i}" )){
						//echo $kep." ********* KÉP{$i}<br />";
						$forras_kep = "impkepek/{$kep}.jpg";
						if( file_exists( $forras_kep ) && is_file( $forras_kep ) ){
							//echo $forras_kep."<br />";
							$k  = "";
							$k->termek_id = $t->id;
							$k->aktiv = "igen";
							$k->site_kapcsolo = ""; 
							$this->_db->insertObject("#__wh_kep", $k, "id");
							print_r($k);
							$id = $this->_db->insertId();
							$celkep = "media/termekek/{$id}.jpg";
							copy( $forras_kep, $celkep );						
							//print_r($k);											
						}
						//echo $forras_kep."<br />";
						//echo $celkep."<br />";					
					}
				}
			}
		}else{
			echo "nincs termek_id setKepek <br />";
		}
		//die;
	}

	function delKepek( $termek_id ){
		$q = "select id from #__wh_kep where termek_id = {$termek_id} ";
		$this->_db->setQuery($q);
		$arr = $this->_db->loadResultArray();
		foreach( $arr as $a ){
			$file_ = "media/termekek/{$a}.jpg";
			unlink($file_);
			$q = "delete from #__wh_kep where id = {$a} ";
			$this->_db->setQuery($q);
			$this->_db->query();
			//echo $this->_db->getQuery()."<br />";
			//echo $this->_db->geterrorMsg( )."<br />";			
		}
	}

	function setAr( $termek_id = 0, $netto_ar, $afa_id){
		if( $termek_id ){
			$arO = $this->getObj( "#__wh_ar", $termek_id, "termek_id" );
			$q = "select id from #__wh_ar where termek_id = {$termek_id}";
			$this->_db->setQuery($q);
			//echo $q."<br />";
			$arr = $this->_db->loadResultArray();
			if( count($arr) ){
				foreach($arr as $a){
					$o="";
					$o->id = $a;
					$o->ar = $netto_ar;
					$o->afa_id = $afa_id;
					//$o->webshop_id = 1;
					$this->_db->updateObject("#__wh_ar", $o, "id" );
				}
			}else{
				$q = "select id from #__wh_webshop ";
				$this->_db->setQuery($q);
				$arr = $this->_db->loadResultArray();
				foreach($arr as $a){
					$o="";
					$o->ar = $netto_ar;
					$o->afa_id = $afa_id;
					$o->afa_id = $afa_id;				
					$o->termek_id = $termek_id;	
					$o->webshop_id = $a;
					$this->_db->insertObject("#__wh_ar", $o, "id" );			
				}
			}
		}else{
			echo "nincs termek_id <br />";
		}
	}

	function getKategoriaId( $kategoria, $elv = "/" ){
		$kategoria = "admin/".trim( $kategoria );
		//$adminKategoria = $this->adminKategoria;
		( $kategoria[strlen( $kategoria ) - 1 ] == $elv ) ? $kategoria = substr( $kategoria, 0, strlen($kategoria)-1 ): $kategoria;
		//echo $kategoria."<br />";
		$q = "select id, lft, rgt, nev from #__wh_kategoria as kategoria ";
		$this->_db->setQuery($q);
		foreach( $this->_db->loadObjectList() as $o ){
			$q = "select * from #__wh_kategoria as kategoria where lft <= {$o->lft} and rgt >= {$o->rgt} order by lft ";
			$this->_db->setQuery($q);
			$ag = $this->_db->loadObjectList();
			$agTxt = "";
			$i_ = 0;
			$ready = false;
			foreach($ag as $ag_){
				if(!$ready){
					if( $agTxt != $kategoria.$elv ){
						$agTxt .= $ag_->nev.$elv;
					}else{
						//echo $agTxt." *********<br />";
						$ready = true;
					}
				}
			}
			
			$agTxt = substr( $agTxt,0, strlen( $agTxt )-1);
			if( strpos( $agTxt, $kategoria ) === 0 ){
				//echo $agTxt." :: ".$kategoria.": <br />";
				//echo strpos($agTxt, $kategoria )." *** <br />";
				
				//echo $kategoria."<br />";
				echo $agTxt."<br />";
				$ind = count( explode($elv, $agTxt) ) - 1;
				//echo $ind."<br />";
				echo $ag[$ind]->id."<br />";
				return $ag[$ind]->id;
			}
		}
		//echo "false<br />";
		return false;
	}

	function getGyartoId( $termek_id, $str ){
		$arr = explode( "/", $str );
		if( count($arr) > 1 ){
			$nev = trim(end($arr));
			$nev = $this->_db->getEscaped($nev);
			if($nev == "KELLY\'S"){
				@$q = "select * from #__wh_gyarto where `nev` like '%KELLY%' limit 1";
			}else{
				@$q = "select * from #__wh_gyarto where `nev` = '{$nev}' limit 1";
			}
			$this->_db->setQuery($q);
			$o = $this->_db->loadObject();
			if($o){
				return $o->id;
			}elseif($nev){
				$o = ""; 
				//$o->nev = str_replace("'", "&#039;", $nev ); 
				//$o->nev = str_replace("'", "\'", $nev ); 
				$o->nev = $nev; 
				$o->aktiv = "igen";
				$this->_db->insertObject("#__wh_gyarto", $o, "id");
				return $this->_db->insertId();
			}
		}
	}
	
	function dbReset( $site_kapcsolo = "", $truncate = 0 ){
		if( $site_kapcsolo && !$truncate ){
			$arr = array( "#__wh_termek", "#__wh_ar", /*"#__wh_kategoria",*/ "#__wh_kategoria_kapcsolo", "#__wh_kep" );			
			foreach( $arr as $t){
				$q = "delete from {$t} where site_kapcsolo = '{$site_kapcsolo}' ";
				$this->_db->setQuery($q);
				$this->_db->Query();
			}
		}else{
			$arr = array( "#__wh_termek", "#__wh_ar", /*"#__wh_kategoria",*/ "#__wh_kategoria_kapcsolo", "#__wh_kep", "#__wh_gyarto" );			
			foreach( $arr as $t ){
				$q = "truncate table {$t}";
				$this->_db->setQuery($q);
				$this->_db->Query();
			}
		}
	}

	function import(){
		//require_once("components/com_wh/models/move.php");
		//$m_  = new whModelmove;
		switch(jrequest::getVar('importTipus') ){
			case "IMPORT" :
				$this->setFileArr();
				$feldolgozott_sorok = $this->importTermekekEgyseges();
				//$this->setSorrendKapcsolo();
				//$this->setSorrendArak();
			break;				
		}

		$this->setSessionVar( "jelentes", $jelentes );
		return $feldolgozott_sorok;
	}

	function setFileArr(){
		$database = JFactory::getDBO();
		//echo "feldolgoz_csv";
		$tmp_name = $_FILES["importfile"]["tmp_name"];
		$feldolgozott_sorok=0;				
		//echo $tmp_name.$_FILES["csvfile"]."-----";
		//print_r($_FILES["importfile"]);
		//die;
		if($tmp_name){
			$filename = dirname(__FILE__)."/import_csv.csv";
			move_uploaded_file($tmp_name, $filename);
			//fclose($handle);
			//die( $filename );
			if(file_exists($filename)){
				$handle = fopen($filename, "r");
				//echo $handle;
				$i=0;
				$csv_ok=0;
				$this->elsosor = fgetcsv($handle, 0, $this->delimiter, $this->enclosure );
				//print_r($this->elsosor);
				//die;
				$jelentes = "";
				while ( ($row = fgetcsv( $handle, 0, $this->delimiter, $this->enclosure ) ) !== FALSE) {
					//print_r($row);
					$termek = "";
					(array)$this->fileArr[] = $row;
					//echo $feldolgozott_sorok."<br />";
					$feldolgozott_sorok++;
				}
			}
		}
		fclose($handle);
		//print_r($this->fileArr);
		//die;
		unlink($filename);
	}

	function importKepek(){
		foreach((array)$this->fileArr as $a){
			if( $cikkszam = $this->getSorErtek( $a, "Cikkszám" ) ){
				$t_ = $this->getObj("#__wh_termek", $cikkszam, "cikkszam");
				if( !in_array( $t_->id, $this->termekIdArr ) ){
					$this->letrehozKep( $t_->id, $cikkszam, $csoport="", $nev = "", $leiras = "" );
					$this->termekIdArr[]= $t_->id;					
				}
			}
		}
		//die;
	}
	
	function importKategoriak(){
		$feldolgozott_sorok = 0;
		foreach((array)$this->fileArr as $a){
			$str = $a[array_search("nev", $this->elsosor)];
			$nev_ = $a[array_search("nev_", $this->elsosor)];			
			
			$asszoc = implode(",", $this->cleanTomb(explode(",", $a[array_search("Asszoc", $this->elsosor)])));
			//echo $asszoc."<br />";
			//echo $item['Category']." <br />";
			$kategoriak = explode($this->katSeparator, $str);
			$kategoriak_ = explode($this->katSeparator, $nev_);			
			$i_=1;
			$szulo =0;
			foreach( $kategoriak as $kategoriaNev ){
				$ind = array_search($kategoriaNev, $kategoriak);
				$kategoriaNev_ = $kategoriak_[$ind];
				$q = "select * from #__wh_kategoria where nev = '{$kategoriaNev}' and melyseg = {$i_} ";
				$this->_db->setQuery($q);
				if ( $k_ = $this->_db->loadObject() ){
					$szulo = $k_->id;
				}else{
					$k_->nev = $kategoriaNev;
					$k_->nev_ = $kategoriaNev_;
					$k_->szulo = $szulo;
					$k_->asszoc = (end($kategoriak) == $kategoriak[$i_-1] ) ? $asszoc : "";
					$k_->aktiv = "igen";					
					$this->_db->insertObject("#__wh_kategoria", $k_, "id");
					$szulo = $this->_db->insertId();
					$kategoriafa = new kategoriafa(); 
					$feldolgozott_sorok++;
				}
				$i_++;
			}
		}
		//die;
		return $feldolgozott_sorok;
	}

	function getJelentes(){
		if(jrequest::getVar("import")){
			$ret = "<h2>".jtext::_("JELENTES")."</h2>";
			$ret.="<strong>".jrequest::getVar("feldolgozott_sorok")." ".jtext::_("DB_SOR_FELDOLGOZVA")."</strong><br /><br />";
			$ret.=$this->getSessionVar("jelentes");
			return $ret;
		}else{
			return "";
		}
	}
	
	function getJelentesSor($elsosor, $termek, $mod){
		$arr = array();
		foreach($elsosor as $f){
			$arr[] = $termek->$f;
		}
		$sor = implode(" - ", $arr)." : ".$mod."<br />";
		return $sor;
	}

   function rebuild_tree($szulo, $left) {
      $right = $left+1;
      $q="SELECT id FROM #__wh_kategoria WHERE szulo ='{$szulo}'";
      $this->_db->setQuery($q);
	  //echo $q."<br />";
      $rows = $this->_db->loadObjectList();
	 //echo $this->db->geterrorMsg()."<br />";
	  foreach($rows as $row){
         //print_r($row);
         $right = $this->rebuild_tree($row->id, $right);    
      }
      $o="";
      $o->id=$szulo;
      $o->lft = $left;
      $o->rgt = $right;
      $this->depth = 1;
      //$this->catDepth($o->id);
	  //$o->melyseg = $this->depth;
      $this->_db->updateObject("#__wh_kategoria", $o, "id");
      return $right+1;
	}
}// class
?>