<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModeluzlet extends modelbase
{
	var $xmlFile = "uzlet.xml";
	var $uploaded = "";
	var $tmpname = "";
	var $table = "#__wh_uzlet";
	var $negyedevekVissza = array("1,2,3" =>"1", "4,5,6"=>"2", "7,8,9"=>"3", "10,11,12"=>"4" );
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");
		$this->getData();
	 	$this->xmlParser = new xmluzlet($this->xmlFile, $this->_data);
		
	}//function

	function csvExport(){
		$dir = "components/com_wh/exports";
		$filename="{$dir}/export_".date("Ymd_His",time()).".csv";
		$elv = "\"";
		$h =";";
		$fp = fopen($filename, "w");
		$q = "select * from #__wh_uzlet as uzlet order by nev ";
		$this->_db->setQuery( $q );
		
		$ret = "";
		$fej1 = array("nev", "orszag", "irszam", "varos", "utca_hazszam", "email", "telefon" );
		//$fej2 array();
		foreach( $this->_db->loadObjectList() as $sz){
			if( $arr = $this->getuzletKimutatasExport(0, $sz->id ) ){
				$line = "";
				foreach($fej1 as $f1){
					$line .= "{$elv}".$sz->$f1."{$elv};";
				}
				$line .= "\n";
				foreach($arr as $a){
					foreach($a as $k => $v){
						$line .= "{$elv}".$v."{$elv};";
					}
					$line .= "\n";
				}
				$line .= "\n";
				fputs($fp, $line);
			}
		}
		fclose($fp);
		//die;
		$this->setsessionvar("filename_", $filename);
		//return $filename;
	//die; 
	}

	function getuzletKimutatasExport($cellspacing = 0, $uzletajdonos_id = 0 ){
		ob_start();
		//$link = "index.php?option=com_wh&controller=uzlet&task=jutalekNyomtatas&fromlist=1&uzletajdonos_id={$this->_data->id}&cid[]={$this->_data->id}&cond_ev";
		echo $this->_db->getErrorMsg();
		$cond_ev = $this->getValtozok()->cond_ev;
		$cond_honap = $this->getValtozok()->cond_honap;
		( $uzletajdonos_id ) ? $uzletajdonos_id : $uzletajdonos_id = $this->getValtozok()->uzletajdonos_id;
		//die("{$uzletajdonos_id}");
		$q = "select replace(termek.isbn, '-', '') as isbn from #__wh_termek as termek
		where termek.szerzo_id in (select szerzo_id from #__wh_uzlet_kapcsolo where uzletajdonos_id = {$uzletajdonos_id} ) ";
		$q = "select replace(termek.isbn, '-', '') as isbn from #__wh_termek as termek
		inner join #__wh_szerzo as szerzo on termek.szerzo_id = szerzo.id
		where szerzo.jogdij_jejarat_datum >= now()
		and termek.szerzo_id in 
		(select szerzo_id from #__wh_uzlet_kapcsolo where uzletajdonos_id = {$uzletajdonos_id} ) ";		
		
		
		$this->_db->setQuery($q);
		$termekIsbnArr = "'".implode("','" , $this->_db->loadResultArray() )."'";
		$termekIsbnArr2 = "'".implode("','" ,$this->getKotojeles($this->_db->loadResultArray()) )."'";
		//echo $termekIsbnArr."<br /><br /><br />";
		
		
		$q = "select 
		sum( termek.jogdij * tetel.quantity ) as osszeg,
		sum( tetel.quantity ) as mennyiseg,
		termek.jogdij,
		termek.nev as konyvnev,
		termek.szerzo_id,
		tetel.cikkszam
		
		from #__wh_rendeles as rendeles
		right join #__wh_tetel as tetel on rendeles.id = tetel.rendeles_id
		inner join #__wh_termek as termek on replace(tetel.cikkszam,'-','') = replace(termek.isbn, '-', '')
		where year(rendeles.datum) = {$cond_ev}
		and month(rendeles.datum) in ( {$cond_honap} ) 
		and ( tetel.cikkszam in ({$termekIsbnArr}) or tetel.cikkszam in ({$termekIsbnArr2}) )
		and termek.jogdij <> 0		
		group by tetel.cikkszam
		order by termek.nev
		 ";
		 
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();
		//print_r($rows);
		$arr = array();
		$osszJutalek = 0;
		foreach($rows as $r){
			$tulhanyad = $this->getTulhanyad($r->szerzo_id, $uzletajdonos_id);
			$jutalek = $r->osszeg * $tulhanyad/100;
			$o = "";
			//$o->KONYVNEV = $r->konyvnev." (".$this->getKotojelesCikkszam($r->cikkszam).")";
			$o->KONYVNEV = $r->konyvnev." (".$r->cikkszam.")";
			$o->JOGDIJ = $r->jogdij;
			$o->MENNYISEG = $r->mennyiseg;
			$o->OSSZEG = $r->osszeg;
			$o->TULAJDONRESZ_SZAZALEK = "{$tulhanyad} %";
			$o->JUTALEK = $jutalek;
			$osszJutalek += $jutalek;
			$arr[]=$o;
		}
		
		if(count($arr)){		
			$arr_ =array();
			foreach($o as $k => $v){
				$arr_[]=$k;
			}
			$o_="";
			$uIndex = array_search( end( $arr_ ), $arr_);
			foreach($arr_ as $k){
				$ind = array_search( $k, $arr_ );
				if( $arr_[$ind] == $arr_[ $uIndex-1 ] ){
					$o_->$k = jtext::_("MINDOSSZESEN");
				}elseif( $arr_[$ind] == $arr_[ $uIndex ] ){
					$o_->$k = $osszJutalek;
				}else{
					$o_->$k = " ";
				}
			}
			$arr[]=$o_;
		}

		$ret = ob_get_contents();
		ob_end_clean();
		return $arr;
	}

	function getuzletKimutatas($cellspacing = 0, $uzletajdonos_id = 0 ){
		ob_start();
		//$link = "index.php?option=com_wh&controller=uzlet&task=jutalekNyomtatas&fromlist=1&uzletajdonos_id={$this->_data->id}&cid[]={$this->_data->id}&cond_ev";
		echo $this->_db->getErrorMsg();
		$cond_ev = $this->getValtozok()->cond_ev;
		$cond_honap = $this->getValtozok()->cond_honap;
		( $uzletajdonos_id ) ? $uzletajdonos_id : $uzletajdonos_id = $this->getValtozok()->uzletajdonos_id;
		//die("{$uzletajdonos_id}");
		$q = "select replace(termek.isbn, '-', '') as isbn 
		from #__wh_termek as termek inner join #__wh_szerzo as szerzo on termek.szerzo_id = szerzo.id
		where szerzo.jogdij_jejarat_datum >= now()
		and termek.szerzo_id in 
		(select szerzo_id from #__wh_uzlet_kapcsolo where uzletajdonos_id = {$uzletajdonos_id} ) ";		
		$this->_db->setQuery($q);
		//echo $this->_db->getQuery();		
		$termekIsbnArr = "'".implode("','" , $this->_db->loadResultArray() )."'";
		$termekIsbnArr2 = "'".implode("','" ,$this->getKotojeles($this->_db->loadResultArray()) )."'";
		//echo $termekIsbnArr."<br /><br /><br />";
		
		
		$q = "select 
		sum( termek.jogdij * tetel.quantity ) as osszeg,
		sum( tetel.quantity ) as mennyiseg,
		termek.jogdij,
		termek.nev as konyvnev,
		termek.szerzo_id,
		tetel.cikkszam
		
		from #__wh_rendeles as rendeles
		right join #__wh_tetel as tetel on rendeles.id = tetel.rendeles_id
		inner join #__wh_termek as termek on replace(tetel.cikkszam,'-','') = replace(termek.isbn, '-', '')
		where year(rendeles.datum) = {$cond_ev}
		and month(rendeles.datum) in ( {$cond_honap} ) 
		and tetel.cikkszam in ( {$termekIsbnArr} )
		and termek.jogdij <> 0
		group by tetel.cikkszam
		order by termek.nev
		 ";
		 
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();
		//print_r($rows);
		$arr = array();
		$osszJutalek = 0;
		foreach($rows as $r){
			$tulhanyad = $this->getTulhanyad($r->szerzo_id, $uzletajdonos_id);
			$jutalek = $r->osszeg * $tulhanyad/100;
			$o = "";
			//$o->KONYVNEV = $r->konyvnev." (".$this->getKotojelesCikkszam($r->cikkszam).")";
			$o->KONYVNEV = $r->konyvnev." (".$r->cikkszam.")";
			$o->JOGDIJ = ar::_($r->jogdij);
			$o->MENNYISEG = $r->mennyiseg;
			$o->OSSZEG = ar::_($r->osszeg);
			$o->TULAJDONRESZ_SZAZALEK = "{$tulhanyad} %";
			$o->JUTALEK = ar::_($jutalek);
			$osszJutalek += $jutalek;
			$arr[]=$o;
		}
		if(count($arr)){
			$o="";
			$o->EXTRA_HTML = "<tr><td class=\"td_mindosszesen\" colspan=\"6\">".jtext::_("MINDOSSZESEN").": ".ar::_($osszJutalek)." </td></tr>";
			$arr[]=$o;		
			$listazo = new listazo($arr, "table_kimutatas", "", "", "", "", $cellspacing);
			echo $listazo->getLista();
		}else{
			echo "&nbsp;";
		}
		$ret = ob_get_contents();
		ob_end_clean();
		return "{$ret}";
	}

	function tomegesEmailKuldes(){
		//print_r( $this->getValtozok() );
		$q = "select id from #__wh_uzlet as uzlet 
		where email <> '' order by nev ";
		$this->_db->setQuery( $q );
		$ret = 0;
		foreach( $this->_db->loadResultArray() as $id){
			$this->kuldErtesitoEmail( $id );
			$ret ++;
		}
		return $ret;		
	}

	function kuldErtesitoEmail( $uzletajdonos_id = 0 ){
		ob_start();
		//echo $this->_db->getErrorMsg();
		if($kimutatas = str_replace("&nbsp;", "", $this->getuzletKimutatas( 10, $uzletajdonos_id ) ) ){
			$cond_ev = $this->getValtozok()->cond_ev;
			$cond_honap = $this->getValtozok()->cond_honap;
			( $uzletajdonos_id ) ? $uzletajdonos_id : $uzletajdonos_id = $this->getValtozok()->uzletajdonos_id;		
			$q = "update #__wh_uzlet set datum = now() where id = {$uzletajdonos_id}";
			$this->_db->setQuery($q);
			$this->_db->Query();
			$em_ = new xCemail;
			$from = "info@dsffds.hu";
			$fromname = "fdsfsf.hu";		
			(array)$recipient[]=$this->getObj("#__wh_uzlet", $uzletajdonos_id )->email;
			//$recipient[]="tamas@trifid.hu";
			$subject = jtext::_("ELSZAMOLAS");
			$footer = jtext::_("ELSZAMOLAS_FOOTER");
			$header = "<h1>".jtext::_("KEDVES")." ".$this->getObj("#__wh_uzlet", $uzletajdonos_id )->nev."!</h1>";
			$mode =1;
			
			$body = "";
			$body .= jtext::_("ELSZAMOLAS_HEAD") . "<br /><br />";
			//$body .= jtext::_("ELSZAMOLASI_IDOSZAK").": {$cond_ev}-{$this->negyedevekVissza[$cond_honap]}<br /><br />";
			$body .= jtext::_("ELSZAMOLASI_IDOSZAK").": {$cond_ev} {$this->negyedevekVissza[$cond_honap]}. ".jtext::_("NEGYEDEV");				
			$body .= $kimutatas;
			$em_ -> kuldLevel($from, $fromname, $recipient, $subject, $body, $footer, $header, $mode );
			echo $this->_db->getErrorMsg();		
			$ret = ob_get_contents();
			ob_end_clean();
			return $ret.$this->getEmailElkuldve();
		}else{
			return "&nbsp;";
		}
	}

	function getEmailElkuldve(){
		ob_start();
		$uzletajdonos_id = $this->getValtozok()->uzletajdonos_id;		
		$q = "select datum from #__wh_uzlet
		where id = {$uzletajdonos_id}";
		$this->_db->setQuery($q);
		echo $this->_db->loadResult();		
		echo $this->_db->getErrorMsg();		
		$ret = ob_get_contents();
		ob_end_clean();
		return "{$ret}&nbsp;";
	}
	
	function getuzletszemelyesAdatok( $uzletajdonos_id ){
		//$nev = $this->getObj(,  )->nev;	
		$o = $this->getObj("#__wh_uzlet", $uzletajdonos_id );
		$ret = "";
		@$ret .= "<h2>{$o->nev}</h2>";
		@$ret .= "<h3>".jtext::_("CIM").": {$o->orszag} {$o->irszam} {$o->varos}, {$o->utca_hazszam}</h3>";		
		@$ret .= "<h3>".jtext::_("KAPCSOLAT").": {$o->telefon} {$o->email}</h3>";		
		return $ret;
	}
	
	function getNyomtatasCim(){
		@$cond_ev = $this->getValtozok()->cond_ev;
		@$cond_honap = $this->getValtozok()->cond_honap;		
		$ret = "<h1>".jtext::_("KIMUTATAS")."</h1>";
		@$ret .= $this->getuzletszemelyesAdatok( $this->getValtozok()->uzletajdonos_id);
		@$ret .="<h3>".jtext::_("ELSZAMOLASI_IDOSZAK").": {$cond_ev} {$this->negyedevekVissza[$cond_honap]}. ".jtext::_("NEGYEDEV")."</h3>";		
		return $ret;
	}
	
	function getuzletKimutatasTomeges(){
		//print_r( $this->getValtozok() );
		$q = "select id from #__wh_uzlet as uzlet 
		where email = '' order by nev ";
		$this->_db->setQuery( $q );
		$ret = "";
		@$cond_ev = $this->getValtozok()->cond_ev;
		@$cond_honap = $this->getValtozok()->cond_honap;		
		foreach( $this->_db->loadResultArray() as $id){
			if( $kimutatas = str_replace("&nbsp;", "", $this->getuzletKimutatas( 10, $id ) ) ){
				$ret .= $this->getuzletszemelyesAdatok( $id );
				$ret .=jtext::_("ELSZAMOLASI_IDOSZAK").": {$cond_ev} {$this->negyedevekVissza[$cond_honap]}. ".jtext::_("NEGYEDEV");						
				$ret .= $kimutatas;
				$ret.="<p style=\"page-break-before: always\">";
			}
		}
		return $ret;
	}
	
	function teszt(){
		ob_start();
		$q = "select tetel.netto_ar, afa from #__wh_tetel as tetel 
		left join #__wh_rendeles as rendeles on tetel.rendeles_id = rendeles.id
		where month( rendeles.datum ) = 11 and tetel.cikkszam = replace('978-963-299-612-7', '-', '') ";
		$this->_db->setQuery($q);
		print_r( $this->_db->loadObjectList() );
		echo $this->_db->getErrorMsg()."-----";		
		$ret = ob_get_contents();
		ob_end_clean();
		return "{$ret}&nbsp;";
	}
	
	function getKotojeles($arr){
		$ret = array();
		foreach($arr as $a){
			$ret []= $this->getKotojelesCikkszam($a);
		}
		return $ret;
	}
	
	function getKotojelesCikkszam($cikkszam){
		$cikkszam = trim( stripslashes( $cikkszam ) );
		$str = "";
		$str .= substr($cikkszam, 0, 3)."-";
		$str .= substr($cikkszam, 3, 3)."-";
		$str .= substr($cikkszam, 6, 3)."-";
		$str .= substr($cikkszam, 9, 3)."-";
		$str .= substr($cikkszam, 12, 1);
		return $str;
	}
	
	function getTulhanyad($szerzo_id, $uzletajdonos_id){
		$q = "select tulhanyad from #__wh_uzlet_kapcsolo where szerzo_id = {$szerzo_id} and uzletajdonos_id = {$uzletajdonos_id}";
		$this->_db->setQuery($q);
		return $this->_db->loadResult();
	}
	
	function getValtozok(){
		$arr = array("cond_ev", "cond_honap", "uzletajdonos_id");
		$ret ="";
		foreach($arr as $a){
			if($v = jrequest::getVar( $a, "" ) ){
				$ret->$a = $v;
			}
		}
		return $ret;
	}
	
	function getTelepules(){
		ob_start();
		$f_ = $this->xmlParser->getAllFormGroups();
		$id = jrequest::getVar("uzlet_id", 0);	
		//echo $id." -------------";
		if( $id ){	
			$obj = $this->getObj( "#__wh_uzlet", $id );
			//print_r($obj);
			$telepules_id = $obj->telepules_id;
			$irszam = $obj->irszam;
			$utca_hazszam = $obj->utca_hazszam;
		}else{
			foreach(array("telepules_id", "irszam", "utca_hazszam") as $a){
				$$a = $this->xmlParser->getAktVal($a);
			}
		}
		//$telepules_id = jrequest::getVar("telepules_id", "");
		if($megye = jrequest::getVar("megye", "") ){
			$megye = $this->getObj("#__wh_telepules", $megye, "megye" )->megye;
			$q = "select id as `value`, concat(telepules, '') as `option` 
			from #__wh_telepules where megye = '{$megye}' and ( lelekszam > 1000 or lelekszam = 0 ) order by telepules asc ";
			$this->_db->setQuery( $q );
			$rows = $this->_db->loadObjectList( );
			echo "<span class=\"span_cim\">".jtext::_("TELEPULES")."</span>".JHTML::_( 'Select.genericlist', $rows, "telepules_id", array("class"=>"alapinput cim" ), "value", "option", $telepules_id )."<br />";
			echo  "<span class=\"span_cim\">".jtext::_("IRANYITOSZAM")."</span>"."<input class=\"alapinput cim\" id=\"irszam\" name=\"irszam\" value=\"".$irszam."\" >"."<br />";
			echo"<span class=\"span_cim\">".jtext::_("UTCA_HAZSZAM")."</span>"."<input class=\"alapinput cim\" id=\"utca_hazszam\" name=\"utca_hazszam\" value=\"".$utca_hazszam."\" >"."<br />";
		}else{
			echo "&nbsp;";
		}
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
}// class
?>