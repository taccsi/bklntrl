<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpModeltermek extends whpPublic{
	var $xmlFile = "termek.xml";
	var $tmpname = "";
	var $table = "#__whp_termek";
	var $w = 232;
	var $h = 200;
	var $w_egyeb = 72;
	var $h_egyeb = 50;	
	
	var $w_kapcsolodo = 140;
	var $h_kapcsolodo = 80;
	
	var $relatedProducts_w = 140;
	var $relatedProducts_h = 80;
	var $relatedProducts_mode = "crop";
	
	var $additionalProducts_w = 140;
	var $additionalProducts_h = 80;
	var $additionalProducts_mode = "crop";
	
	var $mode = "resize";
	
	//var $table ="whp_kategoria";
	function getCommentList(){
		$ret ="";		
		$termek_id=jrequest::getVar("termek_id", 0);
		$q = "select ertekeles.*, u.name from #__wh_ertekeles as ertekeles
		left join #__wh_termek as termek on ertekeles.termek_id = termek.id
		left join #__users as u on ertekeles.user_id = u.id		
		where termek_id = {$termek_id}
		order by ertekeles.datum desc
		";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList( );
		
		//echo $this->_db->getQuery( );				
		//echo $this->_db->getErrorMsg( );		

		if(count($rows)>0){ // vannak sorok
			array_map(array($this, "setCsillagok"), $rows );
			jimport("unitemplate.unitemplate");
			$uniparams->cols = 1;
			$uniparams->cellspacing = 0;
			$uniparams->templatePath = "components/com_whp/unitpl";
			$uniparams->pair = false;
			$ut = new unitemplate("termek_ertekeles", $rows, "div", "termek_ertekeles", $uniparams);
			$ret->html = $ut->getContents(); 
		}else{

		}
		$ret->error="";
		return $this->getJsonRet( $ret );
	}
	
	function setCsillagok( $item ){
		$ret ="";
		foreach( $this->ertekelesArr as $k=>$v ){
			$ret .= "<div style=\"clear:both;\" >".jtext::_($k)."</div>";
			$ret .= "<div style=\"clear:both;\" >".$this->getStarOptions( $k, $item->$k, "{$k}_{$item->id}" )."</div>";
		}
		$item->csillagok = $ret;
		return $item;
	}
	
	function saveComment(){
		$ret ="";		
		$ret->html="";
		$ret->error="";
		$o="";
		$table = "#__wh_ertekeles";
		$fields_ = $this->_db->getTableFields($table, 1);
		//print_r();
		foreach($fields_[$table] as $f => $v){
			$v = urldecode(JRequest::getVar($f, "", "", 2, 2, 2));
			$o->$f=$v;
		}
		if( isset( $o->id ) && $o->id && $this->getObj( $table, $o->id) ){
			$this->_db->updateObject( $table, $o, "id" );
			$ret->id = $o->id;
			echo $this->_db->getErrorMsg( );
			//$ret->html = jtext::_("SIKERES_MENTES");
		}else{
			$this->_db->insertObject($table, $o, "id" );
			$ret->id = $this->_db->insertId();		
			echo $this->_db->getErrorMsg( );				
			//$ret->html = jtext::_("SIKERES_MENTES");				
		}
		//print_r($o);
		//die(" -- -- - - - - -");		
		return $this->getJsonRet( $ret );
	}

	function getCommentForm(){
		$ret ="";
		$f = new xmlTermek( "comment.xml", "" /*$this->_data*/ );
		$o_ = $f->getAllFormGroups();
		$ret .= "<form enctype=\"multipart/form-data\" id=\"commentForm\" >";
		$ret .= html_entity_decode( $o_["maindata"] );
		$ret .= "<input name=\"webshop_id\" value=\"".$GLOBALS["whp_id"]."\" type=\"hidden\" >";				
		$ret .= "<input name=\"termek_id\" id=\"termek_id\" value=\"".jrequest::getVar( "termek_id", 0)."\" type=\"hidden\" >";		
		$ret .= "<input name=\"datum\" value=\"".date("Y-m-d H:i:s")."\" type=\"hidden\" >";
		$ret .= "<input name=\"ip\" value=\"".$_SERVER['REMOTE_ADDR']."\" type=\"hidden\" >";				
		$ret .= "<input name=\"controller\" value=\"termek\" type=\"hidden\" >";
		$ret .= "<input name=\"option\" value=\"com_whp\" type=\"hidden\" >";
		$ret .= "<input name=\"task\" value=\"saveComment\" type=\"hidden\" >";
		$ret .= "<input name=\"format\" value=\"raw\" type=\"hidden\" >";
		$ret .= "</form>";
		$ret .= "<script>initajaxForm('commentForm')</script>";
		//die($ret);
		//$ret;
		//return $this->getJsonRet( $ret );
		return $ret;
	}
	

	function setComments($item){
		ob_start();
		$this->document->addscriptdeclaration("\$j(document).ready(function(){initCommentLink(); getCommentList('".jrequest::getVar( "termek_id", 0)."',3) })");
		$link = "index.php?option=com_whp&controller=termek&task=getCommentForm&format=raw&termek_id=".jrequest::getVar( "termek_id", 0);
		echo "<a class=\"a_hozzaszolas\" href=\"{$link}\">" . jtext::_("HOZZASZOLOK") . "</a>";?>
        <div id="ajaxContentUzenetek"></div>
		<?php 
		$tmp = ob_get_contents();
		ob_end_clean();
		$item->comments = $tmp;
		return $item;
	}

	function getTermek(){
		$rows=$this->getData();
		$this->document->addscriptdeclaration("\$j(document).ready(function(){initStarsLista()})");		
		//print_r($rows);
		if(count($rows)>0){ // vannak sorok
			jimport("unitemplate.unitemplate");
			$uniparams->cols = 1;
			$uniparams->cellspacing = 0;
			$uniparams->templatePath = "components/com_whp/unitpl";
			$uniparams->pair = false;
			$ut = new unitemplate("bontas", $rows, "div", "termek", $uniparams);
			$ret = $ut -> getContents(); 
		}else{
			$ret = "<div align=center>".JText::_("NINCS TALALAT")."</div>";			
		}
		return $ret;
	}

	function setRecommender($item){
		ob_start();
		$this->document->addscriptdeclaration("\$j(document).ready(function(){initCommentLink()})");
		$link = "index.php?option=com_whp&controller=termek&task=getRecommendForm&format=raw&termek_id=".jrequest::getVar( "termek_id", 0);
		echo "<a class=\"a_hozzaszolas\" href=\"{$link}\">" . jtext::_("AJANLJA_ISMEROSENEK") . "</a>";
		$tmp = ob_get_contents();
		ob_end_clean();
		$item->recommender = $tmp;
		return $item;
	}

	function sendRecommend(){
		$ret = "";
		$termek = $this->getobj("#__wh_termek", jrequest::getVar("termek_id",0 ) );
		
		$from = jrequest::getVar("ajanlo_email","");
		$fromname = jrequest::getVar("ajanlo_nev","");
		$subject= jtext::_("TERMEK_AJANLAS_DRPADLO");
		$body = "";
		$body .= "<h1>".jtext::_("KEDVES")." ".jrequest::getVar("cimzett_nev","")."<h1>";
		$body .= "<p>".jtext::_("TERMEK_AJANLAS_SZOVEG")."</p>";
		$a_ = "<a href=\"http://192.168.0.66/drpadlo/index.php?option=com_whp&controller=termek&termek_id={$termek->id}\" >{$termek->nev}</a>";
		$body .= jtext::_("TERMEK_MEGNEVEZESE").": ". $a_."<br />";
		$mode = 1;
		$recipient=array();
		$recipient[]= jrequest::getVar("cimzett_email","");
		$recipient[]="szabolcs@trifid.hu";
		JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode);
		//die( "$from, $fromname, $recipient, $subject, $body, $mode" );
		//print_r($ret);
		$this->getJsonRet($ret);
	}

	function getRecommendForm(){
		$f = new xmlTermek( "recommend.xml", '' );
		$o_ = $f->getAllFormGroups();
		$ret ="";
		$ret .= "<form enctype=\"multipart/form-data\" id=\"recommendForm\" >";
		$ret .= html_entity_decode( $o_["maindata"] );
		$ret .= "<input name=\"webshop_id\" value=\"".$GLOBALS["whp_id"]."\" type=\"hidden\" >";				
		$ret .= "<input name=\"termek_id\" value=\"".jrequest::getVar( "termek_id", 0)."\" type=\"hidden\" >";		
		$ret .= "<input name=\"datum\" value=\"".date("Y-m-d H:i:s")."\" type=\"hidden\" >";
		$ret .= "<input name=\"ip\" value=\"".$_SERVER['REMOTE_ADDR']."\" type=\"hidden\" >";				
		$ret .= "<input name=\"controller\" value=\"termek\" type=\"hidden\" >";
		$ret .= "<input name=\"option\" value=\"com_whp\" type=\"hidden\" >";
		$ret .= "<input name=\"task\" value=\"sendRecommend\" type=\"hidden\" >";
		$ret .= "<input name=\"format\" value=\"raw\" type=\"hidden\" >";
		$ret .= "</form>";
		$ret .= "<script>initajaxFormReccomend('recommendForm')</script>";
		return $ret;
	}

   function validateForm(){
      $ret = "";
      $ret->error ="";  
      $ret->html ="";   
      $ret->id=0;
      $errorFields = $this->checkMandatoryFields();
		$f = new xmlTermek( "comment.xml", "" /*$this->_data*/ );
      if( count( $errorFields ) ){
         $ret->error .= jtext::_("HIBASAN_KITOLTOTT_MEZOK")."\n"; 
         foreach($errorFields as $a){
            $e = $f->getNode("name", $a );
            if( is_a($e, "DOMElement") ){ 
               $ret->error .= jtext::_( $e->getAttribute("label") ).": ".jtext::_($e->getAttribute("mandatory_text"))."\n";  
            }
         }
      }
      return $this->getJsonRet( $ret );
   }
	
	function __construct(){
		parent::__construct(); 
		$this->value = JRequest::getVar("value", "");
		$this->termek_id = JRequest::getVar('termek_id',0);
		$this->limitstart=0;
		$this->limit=1;
		//$this->gettermek();
	 	$this->xmlParser = new xmltermek($this->xmlFile, "" /*$this->_data*/);
		$this->document->addscriptdeclaration("\$j(document).ready(function() {listazUzenetek('termek')}); ");
	}//function

	function setKosarMatrix( $item ){
		$q = "select * from #__wh_termekvariacio where termek_id = {$item->id} order by sorrend, id ";
		$this->_db->setQuery($q);
		$variaciok = $this->_db->loadObjectList();
		//$arO = $this->getArObject();
		$arr = array();
		if( count( $variaciok ) >1 ){
			foreach( $variaciok as $v ){
				$ind = array_search($v, $variaciok );
				$vArr = $this->getVariacioArr( $v->id );
				if( !$ind ){//első sor
					$o = "";
					$o->CIKKSZAM_TV = "<span class=\"span_tvnev\">".jtext::_( "CIKKSZAM" )."</span>";
					foreach( $vArr as $v_ ){
						if( in_array($v_->mezo_id, $this->valtozoTvIDArr ) ){						
							$vN = "MEZOID_{$v_->mezo_id}";
							$o->$vN = "<span class=\"span_tvnev\">{$v_->nev}</span>";
						}
					}
					$o->AR_TV = "<span class=\"span_tvnev\">".jtext::_( "AR" )."</span>";
					$o->KOSAR = "&nbsp;";
					$o->KESZLET = "&nbsp;";
					//$arr[]=$o;
				}
				$o="";
				//$o->CIKKSZAM_TV = $v->cikkszam;
				
				foreach( $vArr as $v_ ){
					if( in_array($v_->mezo_id, $this->valtozoTvIDArr ) ){
						$vN = "MEZOID_{$v_->mezo_id}";
						$o->$vN = $v_->ertek;
					}
				}
				($this->user->id) ? $ar = $v->netto_nagyker_ar : @$v->a;
				//$o->AR_TV = ar::_( ar::getBrutto( @$ar, $item->afaErtek ) ); 
				$o->AR_TV = $this->getTvAr( $v->id, $item->afaErtek, $item->kampany)->arHTML;
				$o->KOSAR = $this->getTVKosar( $v->id, $item->id );
				$o->KESZLET = $this->getKeszlet( $item, $v->id );
				$arr[]=$o;					
			}
			$listazo = new listazo($arr, "kosarmatrix");
			$ret = $listazo->getLista();
			$item->kosarMatrix = $ret;
			$item->kosar = '';
		}else{
			$item->kosarMatrix = "";
			$item->kosar = $this->getKosar($item->id).'xx';
		}
		
		return $item;
	}


	function getTVKosar( $tvId, $termek_id ){
		$formId = "TVkosar{$tvId}";
		
		$ret = "";
       	$ret .= "<form method=\"post\" id=\"{$formId}\" >";
    	$ret .= "<input type=\"hidden\" name=\"termVarId\" value=\"{$tvId}\" />";
    	$ret .= "<input type=\"hidden\" name=\"kosarba_id\" value=\"{$termek_id}\" />";
    	$ret .= "<input type=\"hidden\" name=\"option\" value=\"com_whp\" />";		
    	$ret .= "<input type=\"hidden\" name=\"controller\" value=\"kosar\" />";
    	$ret .= "<input type=\"hidden\" name=\"task\" value=\"add\" />";						
    	$ret .= "<input type=\"text\" name=\"mennyiseg_kosarba\"  class=\"mennyiseg_kosarba\" value=\"1\" maxlength=\"2\" />".jtext::_("DB");
    	$ret .= "<input type=\"button\" onclick=\"\$j('#{$formId}').submit()\" class=\"kosarbagomb\" value=\"".jtext::_("KOSARBA")."\" />";
        $ret .= "</form>";
		
		return $ret;
	}

	function setTermekvariaciok($item){
		$q = "select * from #__wh_termekvariacio where termek_id = {$item->id} order by sorrend, id ";
		$this->_db->setQuery($q);
		$variaciok = $this->_db->loadObjectList();
		//print_r($variaciok);
		$this->valtozoTvIDArr=array();
		$arr = array( );
		if( count( $variaciok ) ){
			$ret = "";
			$tmpArr = array();
			foreach( $variaciok as $v ){
				$vArr = $this->getVariacioArr( $v->id );
				//print_r($vArr);
				foreach($vArr as $obj){
					$vN = "nev_{$obj->mezo_id}";
					$vE = "ertek_{$obj->mezo_id}";
					if( isset( $ret->$vN ) ){
						if(!in_array( trim( $obj->ertek ), $tmpArr) ){
							$ret->$vE .= ", " . $obj->ertek;
							$this->valtozoTvIDArr[]= $obj->mezo_id;
						}
					}else{
						$ret->$vN = $obj->nev;
						$ret->$vE = $obj->ertek;
					}
					$tmpArr[]=$obj->ertek;
				}
			}
			$arr[]=$ret;
		}
		//print_r($tmpArr);
		$nevek = array();
		foreach($arr as $a){
			$o = "";
			foreach( (array)$a as $k => $a__ ){
				if(strstr($k, "nev")){
					$nevek[]= $a__;
				}
			}
			foreach( (array)$a as $k => $a__ ){
				if(strstr($k, "ertek")){
					$ertekek[]= $a__;
				}
			}
		}
		$arr_ = array();
		foreach($nevek as $n){
			$ind = array_search($n, $nevek);
			$o = "";
			$o->VARIACIO_NEV = $n.": ";
			$o->VARIACIO_ERTEK = str_replace('\\','',$ertekek[$ind]);
			$arr_[]=$o;
		}
		$listazo = new listazo($arr_, "variacio_table");
		//print_r($listazo->getLista( ));
		$item -> termekvariaciok = $listazo->getLista( );
	}

	function getVariacioArr( $termekVariacio_id ){
		$termekvvariacio = $this->getObj("#__wh_termekvariacio", $termekVariacio_id);
		$q = "select msablonmezo.* from #__wh_msablonmezo as msablonmezo order by sorrend asc ";
		$this->_db->setQuery($q);
		$msablonmezoArr = $this->_db->loadObjectList();
		parse_str( $termekvvariacio->ertek );
		//echo $termekvvariacio->ertek;
		$ret = array();
		foreach( $msablonmezoArr as $m ){
			$mezoNev = "mezoid_{$m->id}";
			//echo $$mezoNev." ***<br />";
			if(@$value = $$mezoNev){
				$o="";
				$o->nev = "{$m->nev}";
				$o->ertek = ( $value ) ? $value : "";
				$o->mezo_id = $m->id;
				$ret[]=$o;
			}else{
			}
		}
		return $ret;
	}
	
	function setLeiras( $item ){
		$ret = "";
		if($item->site_kapcsolo == "bringaland"){
			$arr = explode("<br>", $item->leiras);
			foreach($arr as $a){
				$arr_ = explode(":", $a);
				$ret .= "<div class=\"div_leiras\" >";
				$ret .="<span class=\"span_cim\">{$arr_[0]}: </span>";
				$ret .="<span class=\"span_ertek\">".end($arr_)."</span>";				
				$ret .= "</div>";				
			}
			//die(htmlentities($item->leiras));
			$item->leiras = $ret;
		}
		return $item; 
	}
	
	function _buildQuery(){
		$cond = "where termek.id = {$this->termek_id} and termek.aktiv = 'igen' ";
		$q = "SELECT termek.*, 
		ar.ar, kategoria.nev as kategorianev, 
		afa.ertek as afaErtek, gyarto.id as gyarto_id, gyarto.nev as gyarto, gyarto.url as gyarto_url,
		kampany_kapcsolo.kampany_prioritas, 
		kampany.id as kampany_id_
		FROM #__wh_termek as termek 
		inner join #__wh_kategoria as kategoria on termek.kategoria_id = kategoria.id
		inner join #__wh_ar as ar on ar.termek_id = termek.id
		inner join #__wh_afa as afa on ar.afa_id = afa.id
		left join #__wh_kampany_kapcsolo as kampany_kapcsolo on kampany_kapcsolo.termek_id = termek.id
		left join #__wh_kampany as kampany on kampany_kapcsolo.kampany_id = kampany.id	
		left join #__wh_gyarto as gyarto on gyarto.id = termek.gyarto_id	
		{$cond} "; 
		return $q;
	}

	function setUtvonal( $item ){
		$item->utvonal = $this->getUtvonal( $item->kategoria_id );
		return $item;
	}

	function getData(){
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query, $this->limitstart, $this->limit );
			//print_r($this->_data);
			echo $this->_db->getErrorMsg();
			//array_map ( array($this, "setKosar"), $this->_data) ;			
			array_map ( array($this, "setTermekvariaciok"), $this->_data);			
			array_map ( array($this, "setLeiras"), $this->_data) ;
			array_map ( array($this, "setUtvonal"), $this->_data) ;
			array_map ( array($this, "setBontaskep"), $this->_data) ;
			array_map ( array($this, "setKampany"), $this->_data );			
			array_map ( array($this, "setKosarMatrix"), $this->_data) ;			
			array_map ( array($this, "setAr"), $this->_data);
			
			//array_map ( array($this, "setHolVasarolhato"), $this->_data);
			array_map ( array($this, "setBontasEgyebKepek"), $this->_data);			
			//array_map ( array($this, "setTermVarList"), $this->_data ); 
			array_map ( array($this, "setListaNev"), $this->_data );			
			array_map(array($this, "setRelatedProducts"), $this->_data);
			array_map(array($this, "setAdditionalProducts"), $this->_data);
			
			array_map(array($this, "setRecommender"), $this->_data);
			array_map ( array($this, "setComments"), $this->_data );			
			array_map ( array($this, "setKeszlet"), $this->_data );	
			
			array_map ( array($this, "setLegkisebbAr"), $this->_data );	
			//echo $this->_db->getErrorMsg();
		}
		//$this->_data = array_map(array($this,"propValue"), $rows);
		//print_r($this->_data);exit;
		return $this->_data;
	}//function
	
	function setKeszlet($item) {
		if( !$item->kosarMatrix){
			$item->keszlet = $this->getKeszlet( $item );
		}else{
			$item->keszlet = "";
		}
		return $item;
	}
	
	function getKeszlet( $item, $tvId =0 ){
		if($tvId){
			$tvO = $this->getObj( "#__wh_termekvariacio", $tvId );
			$keszlet = $tvO->keszlet;
		}else{
			$keszlet = $item->keszlet;			
		}
		if($keszlet) {
			$ret = sprintf( Jtext::_("KESZLETEN"),$keszlet );
		} else {
			$ret = Jtext::_( "NINCS_KESZLETEN" );
		}
		return $ret;
	}
	
	function setRelatedProducts($item){
	
		$q = "select kapcsolodo_termek_id from #__wh_ktermek_kapcsolo where termek_id = {$item->id}";
		$this->_db->setquery($q);
		$idk = implode(',',@$this->_db->loadresultarray()); 
		if ($idk) {
		//echo ($idk); 
		$jkategoriak = implode(",", $this->getjog()->kategoriak );
		$q = "SELECT termek.id, termek.netto_nagyker_ar, termek.cikkszam,
			termek.nev, termek.leiras_rovid,
			termek.kategoria_id, 
			ar.ar, kategoria.nev as kategorianev, 
			afa.ertek as afaErtek, 
			kampany_kapcsolo.kampany_prioritas, 
			gy.nev as gyarto,
			gy.id as gyarto_id,
			kampany.id as kampany_id_
		FROM #__wh_termek as termek 
			inner join #__wh_kategoria as kategoria on termek.kategoria_id = kategoria.id
			inner join #__wh_ar as ar on ar.termek_id = termek.id
			inner join #__wh_afa as afa on ar.afa_id = afa.id
			left join #__wh_gyarto as gy on gy.id = termek.gyarto_id
			left join #__wh_kampany_kapcsolo as kampany_kapcsolo on kampany_kapcsolo.termek_id = termek.id
			left join #__wh_kampany as kampany on kampany_kapcsolo.kampany_id = kampany.id	
			left join #__wh_cimke_kapcsolo as ck on termek.id = ck.termek_id
			left join #__wh_termekvariacio as tv on termek.id = tv.termek_id 
		WHERE termek.id in ({$idk}) 
			and kategoria.id in ({$jkategoriak}) 
			and ar.webshop_id = {$GLOBALS['whp_id']} 
			and termek.aktiv='igen' 
			and termek.besorolatlan != 'igen'
		GROUP by termek.id order by rand() ";
			$this->_db->setQuery($q);
			//echo ($this->_db->getquery());
			$rows = $this->_db->loadObjectList();
					
			//print_r($rows); die();
			if (count($rows)) {
			array_map ( array( $this, "setListaKep_relatedProducts" ), $rows);
			array_map ( array( $this, "setKampany" ), $rows );
			array_map ( array( $this, "setAr" ), $rows);
			array_map ( array( $this, "setListaNev" ), $rows );
			array_map ( array( $this, "setListLink" ), $rows );
			jimport("unitemplate.unitemplate");
			$ret ='<h3>'.Jtext::_('KAPCSOLODO_TERMEKEK').'</h3>';
			$uniparams->cols = 3;
			$uniparams->cellspacing = 0;
			$uniparams->templatePath = "components/com_whp/unitpl";
			$uniparams->pair = true;
			$ut = new unitemplate("related_products", $rows, "div", "related_products", $uniparams);
			$ret .= $ut -> getContents(); 
			$item->related_products = $ret;
			} else {$item->related_products = '';}
	
		} else {$item->related_products = '';}
	return $item;
	
	}
	
	function setListaKep_relatedProducts($item){
		$Itemid = $this->Itemid;
		$q = "select id from #__wh_kep where termek_id = {$item->id} order by id limit 1";
		$this->_db->setquery($q);
		$kep_id = $this->_db->loadresult();
		if (isset($kep_id)){
			$forras_kep = "admin/media/termekek/{$kep_id}.jpg";
		}
		$class="zoom";
		$buborek_kep="";
		@$alt=$item->nev;
		@$cel_kep=$this->xmlParser->getCelKepNev( $kep_id, $this->relatedProducts_w, $this->relatedProducts_h, $this->relatedProducts_mode );
		$link = jroute::_("index.php?option=com_whp&controller=termek&cond_kategoria_id={$item->kategoria_id}&Itemid={$Itemid}&termek_id={$item->id}");		
		@$item->listaKep = $this->xmlParser->image((string)$forras_kep, $cel_kep, $link, $this->relatedProducts_w, $this->relatedProducts_h, $this->relatedProducts_mode, "", "{$alt}", "{$alt}");
		return $item;
	}
	
	
	
	function setAdditionalProducts($item){
		
		$q = "select kieg_termek_id from #__wh_kiegtermek_kapcsolo where termek_id = {$item->id}";
		$this->_db->setquery($q);
		//echo $this->_db->getquery();
		$idk = implode(',',@$this->_db->loadresultarray()); 
		
		if ($idk) {
		//echo ($idk); 
		$jkategoriak = implode(",", $this->getjog()->kategoriak );
		$q = "SELECT termek.id, termek.netto_nagyker_ar, termek.cikkszam,
			termek.nev, termek.leiras_rovid,
			termek.kategoria_id, 
			ar.ar, kategoria.nev as kategorianev, 
			afa.ertek as afaErtek, 
			kampany_kapcsolo.kampany_prioritas, 
			gy.nev as gyarto,
			gy.id as gyarto_id,
			kampany.id as kampany_id_
		FROM #__wh_termek as termek 
			inner join #__wh_kategoria as kategoria on termek.kategoria_id = kategoria.id
			inner join #__wh_ar as ar on ar.termek_id = termek.id
			inner join #__wh_afa as afa on ar.afa_id = afa.id
			left join #__wh_gyarto as gy on gy.id = termek.gyarto_id
			left join #__wh_kampany_kapcsolo as kampany_kapcsolo on kampany_kapcsolo.termek_id = termek.id
			left join #__wh_kampany as kampany on kampany_kapcsolo.kampany_id = kampany.id	
			left join #__wh_cimke_kapcsolo as ck on termek.id = ck.termek_id
			left join #__wh_termekvariacio as tv on termek.id = tv.termek_id 
		WHERE termek.id in ({$idk}) 
			and kategoria.id in ({$jkategoriak}) 
			and ar.webshop_id = {$GLOBALS['whp_id']} 
			and termek.aktiv='igen' 
			and termek.besorolatlan != 'igen'
		GROUP by termek.id order by rand() ";
			$this->_db->setQuery($q);
			//echo ($this->_db->getquery());
			$rows = $this->_db->loadObjectList();
					
			//print_r($rows); die();
			if (count($rows)) {
			array_map ( array( $this, "setListaKep_additionalProducts" ), $rows);
			array_map ( array( $this, "setKampany" ), $rows );
			array_map ( array( $this, "setAr" ), $rows);
			array_map ( array( $this, "setListaNev" ), $rows );
			array_map ( array( $this, "setListLink" ), $rows );
			jimport("unitemplate.unitemplate");
			$ret ='<h3>'.Jtext::_('KIEGESZITO_TERMEKEK').'</h3>';
			$uniparams->cols = 3;
			$uniparams->cellspacing = 0;
			$uniparams->templatePath = "components/com_whp/unitpl";
			$uniparams->pair = true;
			$ut = new unitemplate("related_products", $rows, "div", "related_products", $uniparams);
			$ret .= $ut -> getContents(); 
			
			
			
			
			$item->additional_products = $ret;
			} else {$item->additional_products = '';}
	
		} else {$item->additional_products = '';}
	return $item;
	
	}
	
	function setListaKep_additionalProducts($item){
		$Itemid = $this->Itemid;
		$q = "select id from #__wh_kep where termek_id = {$item->id} order by id limit 1";
		$this->_db->setquery($q);
		$kep_id = $this->_db->loadresult();
		if (isset($kep_id)){
			$forras_kep = "admin/media/termekek/{$kep_id}.jpg";
		}
		$class="zoom";
		$buborek_kep="";
		@$alt=$item->nev;
		@$cel_kep=$this->xmlParser->getCelKepNev( $kep_id, $this->additionalProducts_w, $this->additionalProducts_h, $this->additionalProducts_mode );
		$link = jroute::_("index.php?option=com_whp&controller=termek&cond_kategoria_id={$item->kategoria_id}&Itemid={$Itemid}&termek_id={$item->id}");		
		@$item->listaKep = $this->xmlParser->image((string)$forras_kep, $cel_kep, $link, $this->additionalProducts_w, $this->additionalProducts_h, $this->additionalProducts_mode, "", "{$alt}", "{$alt}");
		return $item;
	}
	
	function setTermVarList( $item ){
		//print_r($item);
		$q = "select * from #__wh_termekvariacio where termek_id = {$item->id} order by sorrend, id"; 
		$this->_db->setQuery($q);
		$arr = array();
		$variaciok = $this->_db->loadObjectList();
		if(count($variaciok)){
			foreach( $variaciok as $v ){
				$o= "";
				/*
				$o->MERET = $this->getVariacioGumiMeret($v->id);
				$o->LI = $this->getVariacioParamValue( $v->id, 10);
				$o->SS = $this->getVariacioParamValue( $v->id, 11);
				$o->CIKKSZAM = $this->getVariacioParamCikkszam($v->id);
				$o->AR = $this->getVariacioArHTML($v->id);
				$o->KOSARBA = $this->getKosar($item->id, $v->id);
				*/
				$o->XXXXXX = "vhdhlsjgjl";
				$arr[]=$o;
			}
			sort($arr);
			$listazo = new listazo($arr, "termVarList");
			$item->termVarLista = $listazo->getLista();
		}else{
			$item->termVarLista = "";
		}
		return $item;
	}
	
	function setHolVasarolhato($item){
		//print_r($item);
		$q = "select u.* from #__wh_uzlet as u inner join #__wh_uzlet_kapcsolo as kapcs on u.id = kapcs.uzlet_id
		where termek_id = '{$item->id}' limit 1";
		$this->_db->setquery($q);
		$rows = $this->_db->loadobject();
		//print_r($rows);
		@$item->uzlet_url = $rows->url;
		@$item->uzlet_nev = $rows->nev;
		return $item;
	}

	function addUzenet(){
		
		ob_start();
		
		$o="";
		$o->termek_id = jrequest::getVar("termek_id","");
		$o->datum = jrequest::getVar("datum","0");
		$o->szoveg = urldecode(JRequest::getVar('szoveg',"", "",2,2,2));
		$o->nev = jrequest::getVar("nev","0");
		$o->user_nev = jrequest::getVar("user_nev","0");
		$o->user_email = jrequest::getVar("user_email","0");
		$o->aktiv = 'igen';
	
		
		//die($o->text
		
		
		//print_r($o); die();
		/*if( $obj = $this->getObj("#__wh_komment", $o->id, "id" ) ){
			$o->id = $obj->id;
			$this->_db->updateObject("#__wh_komment", $o, "id");
		}else{
			$this->_db->insertObject("#__wh_komment", $o, "id");
		}*/
		$this->_db->insertObject("#__wh_komment", $o, "id");
		//echo $this->_db->geterrormsg();
		$ret = ob_get_contents();
		ob_end_clean();
		//return $ret;
		return $this->listazUzenet();
	}
	
	function listazUzenet(){
		
		ob_start();
		$termek_id = jrequest::getvar("termek_id",0);
		//echo 'hello';
		//$webshop_id = $this->getSessionVar("webshop_id");		
		@$q = "select *  from #__wh_komment where termek_id = {$termek_id} and aktiv like 'igen' order by datum";
		
		$this->_db->setQuery($q);
		$obj = $this->_db->loadobjectList();
		//echo $this->_db->getErrorMsg();
		//echo $this->_db->getquery();
		//die();
		
		$arr = array();
		
		if (count($obj)){
			//print_r($obj); die();
			foreach($obj as $a ){
				$o="";
				$o->NEV = $a->nev;
				$o->SZOVEG = $a->szoveg;
				$o->USER_NEV = $a->user_nev;
				$o->USER_EMAIL = $a->user_email;
				$o->DATUM = $a->datum;
				$controller = Jrequest::getvar('controller');																			
				$js = "torolUzenet( '{$a->id}','{$termek_id}', '{$controller}' )";
				if ($this->user->usertype == 'Super Administrator') {
					$o->TORLES = "<input onclick=\"{$js}\" type=\"button\" value=\"".jtext::_("TORLES")."\" >";
				} else {
					$o->TORLES = "";
				}
				
				$arr[] = $o;
			}
		} 
		
		//print_r($arr); 
		//echo 'fdsafasd';
		//UNITEMPLATE
		if (count($arr)) {
			
			
			jimport("unitemplate.unitemplate");
			$uniparams->cols = 1;
			$uniparams->cellspacing = 0;
			$uniparams->templatePath = "components/com_whp/unitpl";
			$uniparams->pair = true;
			$ut = new unitemplate("hozzaszolas", $arr, "div", "hozzaszolas", $uniparams);
			echo $ut -> getContents(); 
			//$ret = "";
			
		
		//LISTAZO
		//$listazo = new listazo($arr);$ret = $listazo->getLista();
		} else {
			echo "<div class=\"NINCS_HOZZASZOLAS\">"."</div>";
		}
		
		//
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
		//return "------";
	}

}// class
?>
