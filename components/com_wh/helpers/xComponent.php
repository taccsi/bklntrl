<?php
defined( '_JEXEC' ) or die( '=;)' );
class whAdmin extends modelBase{ 
	function __construct(){
		$this->params = &JComponentHelper::getParams( 'com_wh' );
		parent::__construct();	
	}

	function setSorrendArak(){
		$q = "select id from #__wh_termek as termek";
		$this->_db->setQuery( $q );
		$rows = $this->_db->loadResultArray();
		echo  $this->_db->getErrorMsg(  );
		//echo  $this->_db->getQuery(  );
		foreach($rows as $r){
			$this->setSorrendAr( $r );
		}	
	}
	
	function setSorrendAr( $termek_id ){
		$arr = array();
		$q = "select min( arT.ar )
		from #__wh_ar as arT
		inner join #__wh_termek as termek on termek.id = arT.termek_id
		inner join #__wh_afa as afaT on arT.afa_id = afaT.id
		where termek.id = {$termek_id}
		";
		$this->_db->setQuery( $q );
		$minar = $this->_db->loadResult();
		if($minar)$arr[]=$minar;
		echo  $this->_db->getErrorMsg(  );		
		//print_r( $arO );
		//die;
		$q = "select termek.netto_akcios_ar_bringacentrum, termek.netto_akcios_ar_bringa from #__wh_termek as termek
		where termek.id = {$termek_id}
		";
		$this->_db->setQuery( $q );
		$o_ = $this->_db->loadObject();
		echo $this->_db->getErrorMsg(  );
		if($o_->netto_akcios_ar_bringacentrum)$arr[]=$o_->netto_akcios_ar_bringacentrum;
		if($o_->netto_akcios_ar_bringa)$arr[]=$o_->netto_akcios_ar_bringa;
		sort($arr);
		$sorrend_ar = ( @$arr[0] ) ? $arr[0] : 9999999;
		$o_->sorrend_ar = $sorrend_ar;
		$o_->id = $termek_id;
		$this->_db->updateObject( "#__wh_termek", $o_, "id" );
		//die("{$o_->id} --");
	}
	
	function setListaKep($item){
		//die("--");
		$q = "select id from #__wh_kep where termek_id = {$item->id} order by sorrend limit 1 ";
		$this->_db->setQuery($q);
		$item->listaKep = $this->getListakep($this->_db->loadResult());
		return $item;
	}

	function getListaKep( $id ){
		//die("***");
		$kO = $this->getObj("#__wh_kep", $id);
		@$termek = $this->getObj("#__wh_termek", $kO->termek_id);
		$class="zoom";
		$buborek_kep="";
		@$alt=$termek->nev;
		$forras_kep= $this->xmlParser->getKepNev( $id );
		$cel_kep=$this->xmlParser->getCelKepNev( $id, $this->w, $this->h, $this->mode );
		//die($cel_kep);
		$link = $forras_kep;		
		//image($forras_kep, $cel_kep, $link="", $w="", $h="", $mode="", $class="", $buborek_kep="", $alt="")
		$img = $this->xmlParser->image($forras_kep, $cel_kep, $link, $this->w, $this->h, $this->mode, "class=\"zoom\" rel=\"group\"", "{$alt}", "{$alt}");
		//$ret ="<a rel=\"group\" class=\"zoom\" href=\"{$forras_kep}\">{$img}</a>";
		//die($ret);
		return $img;

	}
}