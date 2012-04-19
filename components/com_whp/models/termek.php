<?php

defined( '_JEXEC' ) or die( '=;)' );



class whpModeltermek extends whpPublic{ 

	var $xmlFile = "termek.xml";

	var $tmpname = "";

	var $table = "#__whp_termek"; 

	var $w = 380;

	var $h = 200;

	var $w_egyeb = 70;

	var $h_egyeb = 100;

	var $mode_egyeb = "resize";	

	

	var $w_kapcsolodo = 150;

	var $h_kapcsolodo = 216;

	

	var $relatedProducts_w = 150;

	var $relatedProducts_h = 216;

	var $relatedProducts_mode = "crop";

	

	var $additionalProducts_w = 150;

	var $additionalProducts_h = 216;

	var $additionalProducts_mode = "crop";

	

	var $mode = "crop";

	

	

	

	//var $table ="whp_kategoria";

	function getCommentList(){

		$ret ="";		

		$termek_id=jrequest::getVar("termek_id", 0);

		$limit = jrequest::getVar("limit", 1000);

		$q = "select ertekeles.*, u.name from #__wh_ertekeles as ertekeles

		left join #__wh_termek as termek on ertekeles.termek_id = termek.id

		left join #__users as u on ertekeles.user_id = u.id		

		where termek_id = {$termek_id} and ertekeles.aktiv not like 'nem'

		order by ertekeles.datum desc limit {$limit}

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

			$ret .= "<div class=\"ert_row\"><div class=\"ert_bal\" >".jtext::_($k)."</div>";

			$ret .= "<div style=\"ert_jobb\" >".$this->getStarOptions( $k, $item->$k, "{$k}_{$item->id}" )."</div></div>";

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

		$o->aktiv = 'nem';

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

		$this->sendErtekelesErtesito($o);

		//print_r($o);

		//die(" -- -- - - - - -");		

		return $this->getJsonRet( $ret );

	}

	function sendErtekelesErtesito($o){

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

		$ret .= "<script>initajaxFormComment('commentForm')</script>";

		//die($ret);

		//$ret;

		//return $this->getJsonRet( $ret );

		return $ret;

	}

	



	function setComments($item){

		

		ob_start();

		$termek_id = jrequest::getVar( "termek_id", 0);

		$this->document->addscriptdeclaration("\$j(document).ready(function(){initCommentLink(); getCommentList('".$termek_id."',3) })");

		$link = "index.php?option=com_whp&controller=termek&task=getCommentForm&format=raw&termek_id=".$termek_id;

		echo "<a class=\"a_hozzaszolas\" href=\"{$link}\">" . jtext::_("HOZZASZOLOK") . "</a>";?>

        <div id="ajaxContentUzenetek"></div>

        <a class="a_osszes_hsz" style="cursor:pointer;" onclick="getCommentList('<?php echo $termek_id ?>','100');"><?php echo jtext::_("OSSZES_LISTAZASA") ?></a>

		<?php 

		

		

		$tmp = ob_get_contents();

		ob_end_clean();

		$item->comments = $tmp;

		return $item;

	}



	function getTermek(){

		$rows=$this->getData();
		
	
		//$this->document->addscriptdeclaration("\$j(document).ready(function(){initStarsLista()})");		

		//print_r($rows);

		if(count($rows)>0){ // vannak sorok

			jimport("unitemplate.unitemplate");

			$uniparams->cols = 1;

			$uniparams->cellspacing = 0;

			$uniparams->templatePath = "components/com_whp/unitpl";

			$uniparams->pair = false;

			$ut = new unitemplate("bontas", $rows, "table", "termek", $uniparams);

			$ret = $ut -> getContents(); 

		}else{

			$ret = "<div align=center>".JText::_("NINCS TALALAT")."</div>";			

		}

		return $ret;

	}

	function getfoglalas(){

		$rows=$this->getData();
		
		array_map ( array($this, "setFoglalasIdopont"), $rows);
		
		array_map ( array($this, "getFoglalasTovabblink"), $rows);
	
		
			//echo $this->_db->getErrorMsg();
	
		//$this->document->addscriptdeclaration("\$j(document).ready(function(){initStarsLista()})");		

		//print_r($rows);

		if(count($rows)>0){ // vannak sorok

			jimport("unitemplate.unitemplate");

			$uniparams->cols = 1;

			$uniparams->cellspacing = 0;

			$uniparams->templatePath = "components/com_whp/unitpl";

			$uniparams->pair = false;

			$ut = new unitemplate("bontas", $rows, "table", "foglalas", $uniparams);

			$ret = $ut -> getContents(); 

		}else{

			$ret = "<div align=center>".JText::_("NINCS TALALAT")."</div>";			

		}

		return $ret;

	}
	
	function getFoglalasTovabblink($item){
		$item->tovabblink = "<a href=\"index.php?option=com_whp&controller=rendeles&Itemid={$this->Itemid}\">".Jtext::_('TOVABB_A_FOGLALAS_OSSZESITOHOZ')."</a>";
		$item->tovabblink = "";
		return $item;
	}
	
	function setFoglalasIdopont($item){
		$arr_ = array();
		foreach(explode("\n", $item->idopontok) as $p){
			$o_="";
			$o_->value = trim(str_replace(array("\\", "\""),"",$p));
			$o_->option = trim(str_replace(array("\\"),"",$p));
			//echo "-".$o_->value." <br />";							
			$arr_[]=$o_;
		}
		$o__="";
		$o__->value=$o__->option="";
		array_unshift($arr_, $o__);
		$onchange = "setFoglalasIdopont()";
		$item->idopont_select = JHTML::_('Select.genericlist', $arr_, 'idopont', array('onchange'=>$onchange), "value", "option", '' );
			
			
		
		//print_r($item); die();
		
		return $item;
	}

	

	

	function setTabs($item){

		$tabArr= array();

		

		$o = "";

		$o->title = jtext::_( "LEIRAS" );

		$o->tabId = "tab1";

		$o->type = "normal";

		$o->tabContent = $item->leiras;

		$tabArr[0] = $o;

		

		$o = "";

		$o->title = jtext::_( "HOZZASZOLASOK" );

		$o->tabId = "tab2";

		$o->type = "normal";

		$o->tabContent = $item->comments;

		$tabArr[1] = $o;

		

		$item->tabs = $this->getTabForm($tabArr);

		return $item;

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

		global $mainframe;
		$ret = "";

		$termek = $this->getobj("#__wh_termek", jrequest::getVar("termek_id",0 ) );

		

		$from = jrequest::getVar("ajanlo_email","");

		$megjegyzes = jrequest::getVar("szoveg","");

		$fromname = jrequest::getVar("ajanlo_nev","");

		$subject= "Trifid - " . $fromname . " ajánlott Önnek egy terméket!";

		$body = "";

		$body .= "<h2>".jtext::_("Kedves")." ".jrequest::getVar("cimzett_nev","")."!</h2>";

		$body .= "<p>{$fromname} az alábbi terméket szeretné az Ön figyelmébe ajánlani, melyet megtekinthet erre a linkre kattintva:</p>";

		$a_ = "<a href=\"".$mainframe->getCfg('live_site')."/index.php?option=com_whp&controller=termek&termek_id={$termek->id}\" >{$termek->nev}</a>";

		$body .=  $a_."<br />";

		

		if($megjegyzes){

			$body.= "<br/>". $fromname . " ".jtext::_("üzenete").":<br/>{$megjegyzes}<br/>";

		}

		$body.= "<br/>---------------------------------------------<br/>";

		$body.= "Trifid Kft. - <a href=\"".$mainframe->getCfg('live_site')."\">www.trifid.hu</a>";

		

		$mode = 1;

		$recipient=array();

		$recipient[]= jrequest::getVar("cimzett_email","");

		$recipient[]="szabolcs@trifid.hu";

		JUtility::sendMail("noreply@trifid.hu", "Trifid", $recipient, $subject, $body, $mode);

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

		if (jrequest::getvar('layout') == 'foglalas'){
			//$this->document->addscriptdeclaration("\$j(document).ready(function() {setFoglalGomb({$this->termek_id})}); ");
		}
		

	}//function

	function getFoglalGomb(){
		ob_start();	
		$Itemid = $this->getsessionvar('Itemid');
			 	$ok = 0;
			 	foreach ($this->getsessionvar('kosar') as $kulcs => $k){
			 		if ($kulcs == 'FOTERMEK'){$ok = 1;}
			 	}
			 	$link = jroute::_("index.php?option=com_whp&controller=rendeles&Itemid={$Itemid}"); 
				 if (@$ok){echo "<a class=\"button UGRAS_A_KOSARHOZ\" href=\"{$link}\">".jtext::_("MEGRENDELEM")."</a>"; } else {
				 	echo jtext::_('KEREM_ADJA_MEG_A_RESZTVEVOK_LETSZAMAT');
				 }
		
		$html = ob_get_contents();
		ob_end_clean();
		$ret ="";
		$ret->html = $html;
		$ret->error = "";
		return $this->getJsonRet($ret);	
	}

	function setKosarMatrix( $item ){

		$q = "select * from #__wh_termekvariacio where termek_id = {$item->id} order by sorrend, id ";

		$this->_db->setQuery($q);

		$variaciok = $this->_db->loadObjectList();

		//$arO = $this->getArObject();

		$arr = array();

		if( count( $variaciok ) > 1 && 0){

			foreach( $variaciok as $v ){

				$ind = array_search($v, $variaciok );

				$vArr = $this->getVariacioArr( $v->id );

				if( !$ind ){//első sor

					$o = $o_select = "";

					//$o->CIKKSZAM_TV = "<span class=\"span_tvnev\">".jtext::_( "CIKKSZAM" )."</span>";

					foreach( $vArr as $v_ ){

						if( in_array($v_->mezo_id, $this->valtozoTvIDArr ) ){						

							$vN = "MEZOID_{$v_->mezo_id}";

							$o_select->option = $o->$vN = "<span class=\"span_tvnev\">{$v_->nev}</span>";

							 

						}

					}

					$o->AR_TV = "<span class=\"span_tvnev\">".jtext::_( "AR" )."</span>";

					$o->KOSAR = "&nbsp;";

					//$o->KESZLET = "&nbsp;";

					//$arr[]=$o;

				}

				$o = $o_select = "";

				//$o->CIKKSZAM_TV = "(".$v->cikkszam.")";

				foreach( $vArr as $v_ ){

					if( in_array($v_->mezo_id, $this->valtozoTvIDArr ) ){

						$vN = "MEZOID_{$v_->mezo_id}";

						$o_select->option = $o->$vN = $v_->ertek;

					}

				}

				($this->user->id) ? $ar = $v->netto_nagyker_ar : @$v->a;

				//$o->AR_TV = ar::_( ar::getBrutto( @$ar, $item->afaErtek ) ); 

				$tvArO = $this->getTvAr( $v->id, $item->afaErtek, $item->kampany);

				$o->AR_TV =  $tvArO->arHTML;

				//print_r( $tvArO );

				//print_r($v); die();



				//$o_select->value = $v->id;

				//$o->KOSAR = $this->getKeszlet( $item, $v->id );

				if($tvArO->netto_ar ) {

					$arr[]=$o;

					//if ($item->megvasarolhato != 'nem' and $v->keszlet > 0){}

					$arr_select[] = $o_select;

				};

			}

			

			$listazo = new listazo($arr, "kosarmatrix");

			$ret = $listazo->getLista();

			@$item->kosarMatrix = @$this->getTVKosar();

			$item->kosarMatrix .= $ret;

			

			$item->kosar = '';

		}else{

			$item->kosarMatrix = "";

			if ($item->megvasarolhato != 'nem'){

			$item->kosar = ( $item->ar ) ? $this->getKosar( $item->id ) : "";

			} else {$item->kosar ='';}

		}

		

		return $item;

	}



	function setKosarMezok(){

		ob_start();

		$tv_id = jrequest::getvar('tv_id');

		$termek_id = jrequest::getvar('termek_id');

		$this->termek_id = $termek_id;

		$d_ = $this->getData();

		$termek = $d_[0];

		$ret = "";

		$ret ->error = "";

		$ret ->html = "";

		$arO = $this->getTvAr( $tv_id, $termek->afaErtek, $termek->kampany );

		if($arO->arHTML_me){

			$ret ->html .= '<table class="table_ar_"><tr><td class="szoveg td_ar_felirat">'.jtext::_('AR').': </td><td>'.$arO->arHTML_me.'</td></tr></table>';



			$ret ->html .= "".jtext::_("AFAT_TARTALMAZZA");

		}

		//$ret ->html .= "";	

		$name = "egysegar_kal";

		$value = $arO->netto_ar;

		$ret ->html .= "<input type=\"hidden\" name=\"{$name}\" id=\"{$name}\" value=\"{$value}\" >";

		$ret ->html .= ( (int)$arO->netto_ar ) ? "" : jtext::_("KERESSE_ARUHAZAINKBAN");

		//$ret ->html .= "(egysegar)";



		$name = "mennyisegi_egyseg_kal";

		$value = $termek->me;

		$ret ->html .= "<input type=\"hidden\" name=\"{$name}\" id=\"{$name}\" value=\"{$value}\" >";



		$name = "afa_kal";

		$value = $termek->afaErtek;

		$ret ->html .= "<input type=\"hidden\" name=\"{$name}\" id=\"{$name}\" value=\"{$value}\" >";

		$xxx = ob_get_contents();

		ob_end_clean();

		return $this->getJsonRet( $ret );

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

			//print_r($this->_data); die();

			array_map ( array($this, "setTermekvariaciok"), $this->_data);			

			array_map ( array($this, "setLeiras"), $this->_data) ;

			array_map ( array($this, "setUtvonal"), $this->_data) ;

			array_map ( array($this, "setBontaskep"), $this->_data) ;

			array_map ( array($this, "setKampany"), $this->_data );			

			array_map ( array($this, "setAr"), $this->_data );	

			array_map ( array($this, "setCsomagar"), $this->_data );			

			array_map ( array($this, "setKosarMatrix"), $this->_data) ;	
			//array_map ( array($this, "setHolVasarolhato"), $this->_data);

			array_map ( array($this, "setBontasEgyebKepek"), $this->_data);			

			//array_map ( array($this, "setTermVarList"), $this->_data ); 

			//array_map ( array($this, "setListaNev"), $this->_data );			

			array_map(array($this, "setRelatedProducts"), $this->_data);

			array_map(array($this, "setAdditionalProducts"), $this->_data);

			array_map ( array($this, "setFoglaloLink"), $this->_data );
			
			
			

			array_map(array($this, "setRecommender"), $this->_data);

			array_map ( array($this, "setComments"), $this->_data );			

			array_map ( array($this, "setKeszlet"), $this->_data );	

			array_map ( array( $this, "setShareLinks" ), $this->_data );

			array_map ( array( $this, "setLetolthetoFile" ), $this->_data );

			//array_map ( array($this, "setLegkisebbAr"), $this->_data );	

			array_map ( array($this, "setTabs"), $this->_data );	

			array_map ( array($this, "setKategoriaLink"), $this->_data );	

			array_map ( array($this, "setTermvarSelect"), $this->_data );			

			array_map ( array($this, "setKalulator"), $this->_data );				

			//array_map ( array($this, "setLinkToRelated"), $this->_data );	

			

			//echo $this->_db->getErrorMsg();

		}

		//$this->_data = array_map(array($this,"propValue"), $rows);

		//print_r($this->_data);exit;

		return $this->_data;

	}//function

	function setFoglaloLink($item){
		$Itemid = $this->Itemid;

	
		$link = jroute::_("index.php?option=com_whp&controller=termek&cond_kategoria_id={$item->kategoria_id}&Itemid={$Itemid}&termek_id={$item->id}&layout=foglalas");
		
		//$item->nev = "<a href=\"{$link}\"><span>".stripcslashes( $item->gyarto)."</span> - <strong> ".stripcslashes( $item->nev )."</strong></a>";
		
		@$kosar = $this->getSessionVar("kosar");
		$ok = 1;
		
		foreach ($kosar as $kulcs => $k){
			if ($kulcs == 'FOTERMEK'){
				if ($k->id != $item->id){$ok = 0;}
			} else {if (isset($k->szulo_termek_id)){
				if ($k->szulo_termek_id != $item->id){$ok = 0;}
			}
					
				
			}
			
		}
		//if (@$kosar){print_r($kosar); die();}
		if ($ok){
			$item->foglalolink = "<a href=\"{$link}\">".jtext::_('LEFOGLALOM')."</strong></a>";
		} else {
			
			$item->foglalolink = '<strong>'.jtext::_('ON_MAR_OSSZEALLITOTT_FOGLALAST_ADJA_FEL_RENDELESET_VAGY_URITSE_KOSARAT').'</strong>';
		}
		
		
		 
		return $item;
	}

	function setLinkToRelated($item){

	//print_r($item); die();

	if ($item->additional_products){

		$item->linktoRelated = "<a id=\"horgony_kapcs\" class=\"\" href=\"#related\">".Jtext::_('KIEGESZITO_TERMEKEK')."</a>";

	} else {

		$item->linktoRelated = JTEXT::_('NINCS_KIEGESZITO_TERMEK');

	}
	return $item;
	}

	

	function setTermvarSelect( $item ){
		$ret = "";
		$termVarId = jrequest::getVar( "termVarId", "" );
		$q = "select * from #__wh_termekvariacio where termek_id = {$item->id} order by sorrend, id ";
		$this->_db->setQuery($q);
		$variaciok = $this->_db->loadObjectList();
		//$arO = $this->getArObject();
		//print_r( $variaciok );
		//termVarSelect
		$arr = array();
		$arr_select = array();
		if( count( $variaciok ) > 1 ){
			foreach( $variaciok as $v ){
				$ind = array_search($v, $variaciok );
				$vArr = $this->getVariacioArr( $v->id );
				//print_r( $vArr );
				if( !$ind ){//első sor
					$o = $o_select = "";
					foreach( $vArr as $v_ ){
						if( in_array($v_->mezo_id, $this->valtozoTvIDArr ) ){						
							$vN = "MEZOID_{$v_->mezo_id}";
							$o_select->option = $o->$vN = "<span class=\"span_tvnev\">{$v_->nev}</span>";
						}
					}
					$o->AR_TV = "<span class=\"span_tvnev\">".jtext::_( "AR" )."</span>";
					$o->KOSAR = "&nbsp;";
				}
				$o = $o_select = "";
				foreach( $vArr as $v_ ){
					$vN = "MEZOID_{$v_->mezo_id}";
					$o_select->option = $o->$vN = $v_->ertek;
				}
				($this->user->id) ? $ar = $v->netto_nagyker_ar : @$v->a;
				//$o->AR_TV = ar::_( ar::getBrutto( @$ar, $item->afaErtek ) ); 
				$tvArO = $this->getTvAr( $v->id, $item->afaErtek, $item->kampany);
				$o->AR_TV =  $tvArO->arHTML;
				//print_r( $tvArO );
				//print_r($v); die();
				$o_select->value = $v->id;
				$o->KOSAR = $this->getKeszlet( $item, $v->id );
				$arr[]=$o;
				//if($item->megvasarolhato != 'nem' and $v->keszlet > 0){$arr_select[] = $o_select;}
				$arr_select[] = $o_select;
			}
			$termVarId = jrequest::getVar( "termVarId", "" );
			$mennyiseg = jrequest::getVar( "mennyiseg", "" );
			//print_r( $arr_select );
			if( count($arr_select)==1 /*&& 0*/ ){
				$ret .= "<input type=\"hidden\" name=\"termVarSelect\" id=\"termVarSelect\" value=\"{$arr_select[0]->value}\" >";
				$ret .= $arr_select[0]->option;
			}else{
				$ret .= Jtext::_('KEREM_VALASSZON').JHTML::_( 'Select.genericlist', $arr_select, 'termVarSelect', array("onchange"=>"setKosarMezok(); getKalkulator('{$mennyiseg}')","class"=>"alapinput variacioSelect" ), "value", "option", $termVarId );
			}
		}else{
			$ret .= "";
		}
		$ret = "<div class=\"div_termvarselect\">".$ret."</div>";
		$item->termvarSelect = $ret;
		return $item;
	}

	function setCsomagar($item){

		//print_r($item); die();

		if ($item->m2csomag != 0){

			$item->csomagar = Jtext::_('CSOMAGAR').': '.ar::_(ar::getbrutto($item->ar,$item->afaErtek)*$item->m2csomag,'€');} else {

			$item->csomagar ='';

		}

		return $item;

	}



	function setKalulator( $item ){ 

		$ret = "";

		//$ret .= $this->getKalkulator( $item->id );

		$mennyiseg = jrequest::getVar( "mennyiseg", "" );

		$this->document->addScriptDeclaration( "\$j( document ).ready( function(){ setKosarMezok(); getKalkulator('{$mennyiseg}'); } )" );

		$ret .= "<span id=\"ajaxContentKalkulator\" ></span>";

		$ret .= "<span id=\"ajaxContentKalkulatorEredmeny\" ></span>";		

		

		$ret .= "<input type=\"hidden\" id=\"termek_id_\" value=\"{$item->id}\" >";

		$ret .= "<input type=\"hidden\" id=\"termek_tipus_\" value=\"{$item->termek_tipus}\" >";

		$kosar_index = jrequest::getVar( "kosar_index", "" );

		$ret .= "<input type=\"hidden\" id=\"kosar_index\" value=\"{$kosar_index}\" >";				

		$item->kalkulator = $ret;

		return $item;

	}

	

	function kalkulalCsomagoltTermek(){	//MÓDOSÍTOTT 
		$ret = "";
		$ret->html = "";		
		$ret->error = "";
		foreach( array("termVarId", "termek_id", "value", "input_id", "csomagolasi_egyseg", "ea", "mennyisegi_egyseg_kal", "afa_kal",  "inputArrName", "inputArrValue","inputArrMe" ) as $a){
			$$a = jrequest::getVar( $a, "" );
		}
		$ea = ar::getKerekitettAr( $ea * ( $afa_kal / 100 + 1 ) );
		//$obj->CSOMAG_AR = ar::_( $o->csomagolasi_egyseg * $ar , "€ / csomag" );
		if( $input_id == "mennyiseg_kal" ){
			$arr = array();
			$csomag = ceil( $value / $csomagolasi_egyseg );
			$ret->csomag = $csomag;
			$szamolt_mennyiseg =  $csomagolasi_egyseg * $csomag;			
			if ( !is_int( $szamolt_mennyiseg ) ){ $szamolt_mennyiseg = number_format($szamolt_mennyiseg, 4 );} 
			$szamolt_ar = round( $szamolt_mennyiseg * $ea );			
			$o="";
			$o->HIDDEN1 = jtext::_( "CSOMAG" );
			$o->HIDDEN2 = $csomag;			
			$arr[]=$o;
			$o="";
			$o->HIDDEN1 = jtext::_("SZAMOLT_MENNYISEG");
			$o->HIDDEN2 = $szamolt_mennyiseg." ".$mennyisegi_egyseg_kal;			
			$arr[]=$o;
			/*
			$o="";
			$o->HIDDEN1 = jtext::_("SZAMOLT_AR");
			$o->HIDDEN2 = ar::_($szamolt_ar);			
			$arr[]=$o;
			*/
			$ret->csomag = $csomag;
		}else{
			$arr = array();	
			$value = ceil( $value );	
			$ret->csomag = $value;					
			$szamolt_mennyiseg =  $csomagolasi_egyseg * $value;			
			$szamolt_ar = ceil ( $szamolt_mennyiseg * $ea   );			
			$o="";
			$o->HIDDEN1 = jtext::_("CSOMAG");
			$o->HIDDEN2 = $value;			
			$arr[]=$o;
			$o="";
			$o->HIDDEN1 = jtext::_("SZAMOLT_MENNYISEG");
			$o->HIDDEN2 = $szamolt_mennyiseg." ".$mennyisegi_egyseg_kal;
			$arr[]=$o;
			/*
			$o="";
			$o->HIDDEN1 = jtext::_("SZAMOLT_AR");
			$o->HIDDEN2 = ar::_($szamolt_ar);
			$arr[]=$o;
			*/
			$ret->szamolt_mennyiseg = $szamolt_mennyiseg;	
		}
		if($szamolt_mennyiseg){
			$o = ""; 
			$draga = ( $szamolt_ar > 100000 ) ? "draga" : "";
			$o->HIDDEN1 = "<span class=\"span_szamitott_ar {$draga}\">" . ar::_($szamolt_ar) . "</span>";
			$o->HIDDEN2 = $this->getTVKosar( $termVarId, $termek_id, $szamolt_mennyiseg, $this->getKalkulaciosInformaciok($inputArrName, $inputArrValue, $inputArrMe )  );	
			array_unshift($arr, $o);
		}

		$l = new listazo($arr, "table_kalkulator_eredmeny",'','','');

		$ret->html .= $l->getLista();

		//$ret->csomag = $value;

		return $this->getJsonRet( $ret );		

	}

	function getKalkulator( ){
		$this->termek_id = $termek_id = jrequest::getVar( "termek_id", 0 );
		$tv_id = jrequest::getVar( "tv_id", "" );
		$mennyiseg = jrequest::getVar( "mennyiseg", "" );
		$tvO = $this->getObj("#__wh_termekvariacio", $tv_id );
		$rows = $this->getData();
		$termek = $rows[0];
		$ret = "";
		$f_ = $termek->termek_tipus;
		$o = unserialize( $tvO->termek_tipus_arr );
		$o->mennyiseg = $mennyiseg;
		$o->me = $termek->me;
		$o->termek = $termek;
		$o->tv_id = $tv_id;
		$ret .= $this->$f_( $o );
		//$ret .= $f_;
		$r = "";
		$r->html= $ret;
		$r->error = "";
		return $this->getJsonRet( $r );
	}

	function DARABARU( $o = "" ){
		$mennyiseg = trim( jrequest::getVar("mennyiseg", "1" ) );
		$mennyiseg = ( $mennyiseg ) ? $mennyiseg : 1;		
		ob_start();
		$ret = "";
		//$js
		//$ret .= "<input {$js} class=\"kosarbagomb\" type=\"button\" value=\"".jtext::_("KALKULAL")."\" >";
		//print_r($o);
		if( $o->tv_id ){

			$tvO = $this->getObj( "#__wh_termekvariacio", $o->tv_id );
			//parse_str($tvO->ertek, $arr_);
			//$kalkulaciosInformaciok = $arr_["mezoid_3"];

			if ((int)$tvO->ar > 0){
				if( $o->termek->megvasarolhato == "igen" && $tvO->keszlet ){
					$ret .= $this->getTVKosar( $o->tv_id, $o->termek->id, $mennyiseg, "", "text", 'db' );
				}elseif($o->termek->megvasarolhato == "nem"){
					$ret .= '<span class="piros">'.jtext::_("KERESSE_ARUHAZAINKBAN").'</span>';				
				}elseif( (int)$tvO->keszlet == 0 ){
					$ret .= '<span class="piros">'.jtext::_("JELENLEG_NINCS_KESZLETEN").'</span>';					
				}
			}
		}elseif( $o->termek->megvasarolhato != "igen" ){
			$ret .= '<span class="piros">'.jtext::_("KERESSE_ARUHAZAINKBAN").'</span>';
		}
		$ob = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function TEKERCSES_ARU( $o = "" ){
		$ret = "";
		$tvO = $this->getObj( "#__wh_termekvariacio", $o->tv_id );
		if ((int)$tvO->ar > 0){
			//$mennyiseg = jrequest::getVar( "mennyiseg", "1" );
			//$ret .= $this->getTVKosar( $o->tv_id, $o->termek->id, $mennyiseg, "text" );		
			$name = "szelesseg";
			$value = (isset($o->$name) ) ? $o->$name : "";
			//$ret->brutto_ar = ar::getBrutto( $ar, $afaErtek );
			$egysegar_kal = jrequest::getVar( "egysegar_kal" );
			
			$obj ='';
			$obj->SZELESSEG =$value.' m'."<input type=\"hidden\" class=\"termek_tipus_input\" me=\"m\" value=\"{$value}\" id=\"{$name}\" name=\"{$name}\" >";
			$tmp__ = $egysegar_kal;
			//$egysegar_kal = ar::getKerekitettAr( $egysegar_kal );
			$brutto_ar = ar::getBrutto ( $egysegar_kal, $o->termek->afaErtek );
			//$folyometer_ar = ar::getKerekitettAr( $brutto_ar ) * $value;
			//$folyometer_ar = ar::getKerekitettAr( $brutto_ar ) * $value;
			//$folyometer_ar = ceil( $brutto_ar * $value );
			$folyometer_ar = round( $brutto_ar * $value );
			$obj->FOLYOMETER_AR = ar::_( $folyometer_ar, "€ / fm" );
		//	$ret .= jtext::_( "SZELESSEG" ).": ".$value." m<br />";
		//	$ret .= "<input type=\"hidden\" value=\"{$value}\" id=\"{$name}\" name=\"{$name}\" >";
			/*
			$name = "egysegar";
			$value = (isset($o->$name) ) ? $o->$name : "";
			//$js = "onblur =\"mentTermekTipusTv( '{$name}', this );\" ";
			$ret .= "<input {$js} type=\"text\" value=\"{$value}\" class=\"termek_tipus_input\" name=\"{$name}\" ><br />" . jtext::_($name).jtext::_("netto_");

*/
			$name = "mennyiseg_kal";
			$value = ( isset( $o->$name ) ) ? $o->$name : "";
			$szukseges_hosszusag = number_format( $o->mennyiseg / $o->szelesseg, 2 );
			$szukseges_hosszusag = 0;
			$szukseges_hosszusag = ( (int)$szukseges_hosszusag > 0) ? $szukseges_hosszusag : "";
			//$szukseges_hosszusag = "***";
			$js = "onblur =\"kalkulalTekercsesTermek( this );\" onclick=\"torolKosarAjax(this)\" ";		
			$obj->szukseges_hosszusag="<input {$js} type=\"text\" value=\"{$szukseges_hosszusag}\" me=\"m\" class=\"termek_tipus_input\" id=\"{$name}\" name=\"{$name}\" > fm";
			//$obj->HIDDEN="<input class=\"kosarbagomb btn_kalkulal\" type=\"button\" value=\"".jtext::_("KALKULAL")."\" >";
			$obj->HIDDEN="<a href=\"javascript:;\" class=\"kosarbagomb btn_kalkulal\" >".jtext::_("KALKULAL")."</a>";
			//$ret .= "<input {$js} type=\"text\" value=\"{$o->mennyiseg}\" class=\"termek_tipus_input\" id=\"{$name}\" name=\"{$name}\" ><br />" . jtext::_($name);
			//$ret .= "<br />";
			//$ret .= "<input class=\"kosarbagomb\" type=\"button\" value=\"".jtext::_("KALKULAL")."\" >";
			$arr = array();
			$arr[] = $obj;
			$listazo = new listazo($arr, "termekadatok_tekercs",'','','',"1");
			$ret = $listazo->getlista();
		}
		$termek_id = jrequest::getVar( "termek_id", "" );
		$termek = $this->getObj("#__wh_termek", $termek_id );
		$ret .= $this->getMegvasarolhatoInfo( $tvO, $termek );
		
		return $ret;
	}

	function METERES_TERMEKEK( $o = "" ){
		$ret = "";
		$tvO = $this->getObj( "#__wh_termekvariacio", $o->tv_id );
		if ((int)$tvO->ar > 0){
			$mennyiseg = jrequest::getVar( "mennyiseg", "" );
			//$ret .= $this->getTVKosar( $o->tv_id, $o->termek->id, $mennyiseg, "text" );		
			$name = "szelesseg";
			$value = (isset($o->$name) ) ? $o->$name : "";
			//$ret->brutto_ar = ar::getBrutto( $ar, $afaErtek );
			$egysegar_kal = jrequest::getVar( "egysegar_kal", 0 );
			$index = jrequest::getVar( "index", 0 );
			$obj ='';
			$name = "mennyiseg_kal";
			$value = ( isset( $o->$name ) ) ? $o->$name : "";
			//$mennyiseg
			//$szukseges_hosszusag = number_format( $o->mennyiseg / $o->szelesseg, 2 );
			$szukseges_hosszusag = ( (int)$szukseges_hosszusag > 0) ? $szukseges_hosszusag : $mennyiseg;
			//$szukseges_hosszusag = "***";
			$js = "onblur =\"kalkulalMeteresTermek( this );\" onclick=\"torolKosarAjax(this)\" ";		
			$obj->szukseges_hosszusag="<input {$js} type=\"text\" value=\"{$szukseges_hosszusag}\" me=\"m\" class=\"termek_tipus_input\" id=\"{$name}\" name=\"{$name}\" > fm";
			//$obj->HIDDEN="<input class=\"kosarbagomb btn_kalkulal\" type=\"button\" value=\"".jtext::_("KALKULAL")."\" >";
			$obj->HIDDEN="<a href=\"javascript:;\" class=\"kosarbagomb btn_kalkulal\" >".jtext::_("KALKULAL")."</a>";
			//$ret .= "<input {$js} type=\"text\" value=\"{$o->mennyiseg}\" class=\"termek_tipus_input\" id=\"{$name}\" name=\"{$name}\" ><br />" . jtext::_($name);
			//$ret .= "<br />";
			//$ret .= "<input class=\"kosarbagomb\" type=\"button\" value=\"".jtext::_("KALKULAL")."\" >";
			$arr = array();
			$arr[] = $obj;
			$listazo = new listazo($arr, "termekadatok_tekercs",'','','',"1");
			$ret = $listazo->getlista();
		}

		$termek_id = jrequest::getVar( "termek_id", "" );
		$termek = $this->getObj("#__wh_termek", $termek_id );
		$ret .= $this->getMegvasarolhatoInfo( $tvO, $termek );
		return $ret;
	}

	function kalkulalMeteresTermek(){ //MÓDOSÍTOTT

		ob_start();

		$ret = "";

		$ret->html = "";		

		$ret->error = "";

		foreach( array("termVarId", "termek_id", "value", "input_id", /*"szelesseg",*/ "ea", "afa_kal", "inputArrName", "inputArrValue", "inputArrMe" ) as $a){

			$$a = jrequest::getVar( $a, "" );

		}

		$arr = array();

		$value = number_format( $value, 3 ); // ez a szukseges hosszusag

		//echo round( $value, 2 ) . " +++ ";

		$v_ = 100 * ( $value - floor( $value ) ) ;

		$v__ = $v_ - floor( $v_ );

		//echo $v__." ****" ; 

		if( (string)$v__ != '1' && round($v__,1) > 0 ){

			$value = floor( $value ) + floor( $v_ ) / 100 + 0.01;

		}else{

			$value = number_format( $value, 2 );

		}

		$kalkulaciosInformaciok = $this->getKalkulaciosInformaciok( $inputArrName, $inputArrValue, $inputArrMe );

		//$szamolt_ar = ar::getKerekitettAr( $ea * ( $afa_kal / 100 + 1 ) );

		//$szamolt_ar *= $value;
		
		$szamolt_ar =  $ea * ( $afa_kal / 100 + 1 );
		$szamolt_ar = ar::getKerekitettAr($value *$szamolt_ar);
		if( $szamolt_ar ){
			/*
			$tvO = $this->getObj("#__wh_termekvariacio", $termVarId );
			parse_str($tvO->ertek, $arr_);
			$kalkulaciosInformaciok = $arr_["mezoid_3"]."<br />".$kalkulaciosInformaciok;
			*/
			$o = "";
			$draga = ( $szamolt_ar > 100000 ) ? "draga" : "";
			$o->HIDDEN1 = "<span class=\"span_szamitott_ar {$draga}\">" . ar::_($szamolt_ar) . "</span>";
			$o->HIDDEN2 = $this->getTVKosar( $termVarId, $termek_id, $value, $kalkulaciosInformaciok );
			array_unshift( $arr, $o );
		}
		$ob = ob_get_contents();

		ob_end_clean();

		

		$l = new listazo($arr, "table_kalkulator_eredmeny");

		$ret->html .= $l->getLista();

		$ret->szukseges_hosszusag = ( (int)$value ) ? $value : "";
		$ret->szukseges_hosszusag = $value;

		return $this->getJsonRet( $ret );		

	}



	function kalkulalTekercsesTermek(){ //MÓDOSÍTOTT
		ob_start();
		$ret = "";
		$ret->html = "";		
		$ret->error = "";
		foreach( array("termVarId", "termek_id", "value", "input_id", "szelesseg", "ea", "afa_kal", "inputArrName", "inputArrValue", "inputArrMe" ) as $a){
			$$a = jrequest::getVar( $a, "" );
		}
		$arr = array();
		$value = number_format( $value, 2 ); // ez a szukseges hosszusag
		
		$v_ = 100 * ( $value - floor( $value ) ) ;
		$v__ = $v_ - floor( $v_ );
		//echo $v__." ****" ; 
		if( (string)$v__ != '1' && round($v__,1) > 0 ){
			//echo number_format( $value+0.01, 2 )." !!!!!!!!!!!!!!!!!!!";
			//echo floor($v_)/100 . "<br />" ;
			//echo $v_ + 0.01 . " <br />";
			
			//$value = floor( $value ) + floor( $v_ ) / 100 + 0.01;
		}else{
			//$value = number_format( $value, 2 );
		}
		$szamolt_terulet = $szelesseg * $value;		
		if (!is_int($szamolt_terulet)){ $szamolt_terulet = number_format( $szamolt_terulet , 3 );} 
		$ea = ar::getKerekitettAr( $ea * ( $afa_kal / 100 + 1 ) );
		$szamolt_ar = $szamolt_terulet * $ea;
		//echo $szamolt_terulet."<br />";
		//echo $ea."<br />";		
		$o="";
		$o->HIDDEN1 = jtext::_( "SZAMITOTT_TERULET" );
		$o->HIDDEN2 = number_format($szamolt_terulet,3,',',' ') ." m2";	
		$arr[]=$o;
		/*$o="";
		$o->HIDDEN1 = jtext::_("SZAMOLT_AR");
		$o->HIDDEN2 = ar::_($szamolt_ar);			
		$arr[]=$o;*/
		//print_r($inputArrValue);
		$kalkulaciosInformaciok = $this->getKalkulaciosInformaciok( $inputArrName, $inputArrValue, $inputArrMe );
		//print_r();
		if( $szamolt_terulet ){
			/*
			$tvO = $this->getObj("#__wh_termekvariacio", $termVarId );
			parse_str($tvO->ertek, $arr_);
			$kalkulaciosInformaciok = $arr_["mezoid_3"]."<br />".$kalkulaciosInformaciok;
			*/
			$o = "";
			$draga = ( $szamolt_ar > 1000000 ) ? "draga" : "";
			$o->HIDDEN1 = "<span class=\"span_szamitott_ar {$draga}\">" . ar::_($szamolt_ar) . "</span>";

			$o->HIDDEN2 = $this->getTVKosar( $termVarId, $termek_id, $szamolt_terulet, $kalkulaciosInformaciok );
			array_unshift( $arr, $o );
		}
		$ob = ob_get_contents();
		ob_end_clean();
		$l = new listazo($arr, "table_kalkulator_eredmeny");
		$ret->html .= $l->getLista()."<br />";
		$ret->szukseges_hosszusag = ( (int)$value ) ? number_format($value,2,',',' ') : "";
		
		return $this->getJsonRet( $ret );		
	}

	function getKalkulaciosInformaciok( $inputArrName, $inputArrValue, $inputArrMe ){
		$arr = array();
		foreach( $inputArrValue as $v ){
			$ind = array_search( $v, $inputArrValue );
			$n = $inputArrName[$ind];
			$me = $inputArrMe[$ind];
			if( $v ){
				$arr[]=jtext::_($n).": {$v} {$me}";
			}
		}
		$ret = count( $arr) ? implode(", ", $arr) : "";
		//die($ret);
		return $ret;
	}

	function CSOMAGOLT_ARU( $o = "" ){	//MÓDOSÍTOTT
		$ret = "";
		$tvO = $this->getObj( "#__wh_termekvariacio", $o->tv_id );
		if ((int)$tvO->ar > 0){
			$name = "csomagolasi_egyseg";
			$value = (isset($o->$name) ) ? $o->$name : "";
			//felső csomagolós táblázat
			$obj->CSOMAGOLASI_EGYSEG = $value . " m2 / csomag<input type=\"hidden\" value=\"{$value}\" id=\"{$name}\" name=\"{$name}\" >";
			$egysegar_kal = jrequest::getVar( "egysegar_kal", "" );
			//$ar = ar::getKerekitettAr( $egysegar_kal * ( $o->termek->afaErtek / 100 + 1) );
			$ar = ceil( $egysegar_kal * ( $o->termek->afaErtek / 100 + 1) );
			
			//$obj->CSOMAG_AR = ar::_( $o->csomagolasi_egyseg * $ar , "€ / csomag" );
			$obj->CSOMAG_AR = ceil($o->csomagolasi_egyseg * $ar) . "€ / csomag" ;   
			$arr = array();
			$arr[] = $obj;
			$listazo = new listazo($arr, "csomagolasi_adatok",'','','');
			$ret = $listazo->getlista();
			$obj = "";
			$name = "mennyiseg_kal";
			$value = ( isset( $o->$name ) ) ? $o->$name : "";
			$js = "onblur =\"kalkulalCsomagoltTermek( this );\" onclick=\"torolKosarAjax(this)\" ";		
			$obj->$name="<input {$js} type=\"text\" value=\"{$o->mennyiseg}\" me=\"{$o->me}\" class=\"termek_tipus_input\" id=\"{$name}\" name=\"{$name}\" > ".$o->me;
			$arr = array();
			$arr[] = $obj;
			$listazo = new listazo( $arr, "termekadatok",'','','',"1" );
			$ret .= $listazo->getlista();
			$obj = "";
			$name = "csomagszam_kal";
			$value = (isset($o->$name) ) ? $o->$name : "";
			$js = "onblur =\"kalkulalCsomagoltTermek( this );\" onclick=\"torolKosarAjax(this)\" ";
			$obj->$name = "<table><tr><td>";
			$obj->$name .= "<input {$js} type=\"text\" me=\"db\" value=\"{$value}\" class=\"termek_tipus_input\" id=\"{$name}\" name=\"{$name}\" > db";
			$obj->$name .= "</td><td>";
			$obj->$name .= "<a href=\"javascript:;\" class=\"kosarbagomb btn_kalkulal\" >".jtext::_("KALKULAL")."</a>";
			$obj->$name .= "</td></tr></table>";
			$arr = array();
			$arr[] = $obj;
			$listazo = new listazo($arr, "termekadatok_kalkulal", '', '', '', "1");
			$ret .= $listazo->getlista();
		} else {
			$ret = "";
		}
		$termek_id = jrequest::getVar( "termek_id", "" );
		$termek = $this->getObj("#__wh_termek", $termek_id );
		$ret .= $this->getMegvasarolhatoInfo( $tvO, $termek );
		return $ret;
	}

	function getTVKosar( $termVarId, $termek_id, $szamolt_mennyiseg = 1, $kalkulaciosInformaciok = "", $mennyKosarbaInputType = "hidden", $me ='' ){
		$formId = "TVkosar{$termVarId}";
		$ret = "";
       	$ret .= "<form method=\"post\" id=\"{$formId}\" >";
    	$ret .= "<input type=\"hidden\" name=\"termVarId\" value=\"{$termVarId}\" />";
    	$ret .= "<input type=\"hidden\" name=\"kosarba_id\" value=\"{$termek_id}\" />";
    	$ret .= "<input type=\"hidden\" name=\"option\" value=\"com_whp\" />";		
    	$ret .= "<input type=\"hidden\" name=\"controller\" value=\"kosar\" />";
    	$ret .= "<input type=\"hidden\" name=\"task\" value=\"add\" />";		
    	$ret .= "<input type=\"hidden\" id=\"kalkulaciosInformaciok\" name=\"kalkulaciosInformaciok\" value=\"{$kalkulaciosInformaciok}\" />";
		$ret .= "<table class=\"table_kalkulator_kosarba\"><tr><td class=\"HIDDEN1\">";	
		$ret .= "<input type=\"{$mennyKosarbaInputType}\" name=\"mennyiseg_kosarba\" class=\"mennyiseg_kosarba\" value=\"{$szamolt_mennyiseg}\" maxlength=\"2\" />";
   		$ret .="<span class=\"me\">{$me}</span>";
		$ret .= "</td><td class=\"HIDDEN2\">";
		$tv = $this->getObj("#__wh_termekvariacio", $termVarId );
		$termek = $this->getObj( "#__wh_termek", $termek_id );
		
			$ret .= "<a href=\"javascript:;\" onclick=\"\$j('#{$formId}').submit()\" class=\"kosarbagomb\" >".jtext::_("KOSARBA")."</a>";
		
		$ret .= "</td></tr></table>";
		$ret .= "</form>";
		return $ret;
	}

	function getMegvasarolhatoInfo( $tv, $termek ){
		$ret = "";
		if( $tv->keszlet && $termek->megvasarolhato == "igen" ){ 
			//$ret .= "<a href=\"javascript:;\" onclick=\"\$j('#{$formId}').submit()\" class=\"kosarbagomb\" >".jtext::_("KOSARBA")."</a>";
		}else{

			/*if($termek->megvasarolhato != "igen"){$ret .= '<span class="piros">'.jtext::_("KERESSE_ARUHAZAINKBAN").'</span>';		}else {$ret .= '<span class="piros">'.jtext::_("JELENLEG_NINCS_KESZLETEN").'</span>';
		} taccsi */ 

		}
		return $ret;
	}

	function setLetolthetoFile($item){

		//print_r($item);

		$this->_db->setquery("select * from #__wh_fajl as fajl where kapcsolo_id = '{$item->id}' and kapcsoloNev like 'termek_id' ");

		$kapcs_fajl = $this->_db->loadobject();

		//print_r($kapcs_fajl); 

		//die();

		//$item->letoltheto_pdf = "<a href=\"media/pdf/{$item->ingyenes_oldalak}\">".JTEXT::_('OLVASSON_BELE')."</a>";

		//$pdfFile = "media/pdf/".urlencode($item->ingyenes_oldalak);

		$filename = urlencode($item->nev);

		if ($kapcs_fajl !=''){$pdfFile = urlencode( $kapcs_fajl->fajlnev.'.'.$kapcs_fajl->ext);} else {$pdfFile = 'nincs.pdf';}

		

		//echo($pdfFile);

		@$link = "index.php?option=com_whp&controller=termek&task=pdfFile&pdfFile={$pdfFile}&id={$item->id}&filename={$kapcs_fajl->eredetiNev}";

		//$link = "/media/pdf/".urlencode( $item->ingyenes_oldalak );		

		if( file_exists("admin/media/termekfajlok/{$pdfFile}") ){

			$item->letoltheto_file = "<a target=\"_blank\" href=\"admin/media/termekfajlok/{$pdfFile}\">" . $kapcs_fajl->eredetiNev.'.'.$kapcs_fajl->ext."</a>";

		}else{

			$item->letoltheto_file = "";			

		}

		//$item->letoltheto_pdf = "";

		return $item;

	}

	function setKategoriaLink($item){

		$Itemid = $this->Itemid;

		$link = "index.php?option=com_whp&controller=termekek&cond_kategoria_id={$item->kategoria_id}&Itemid={$Itemid}";

		$item->kategoriaLink = "<a href=\"{$link}\">".Jtext::_("TOVABBI_TERMEKEK_A_KATEGORIABAN")."</a>";

		return $item;

	}

	

	function setListaNev___($item){

		$Itemid = $this->Itemid;

		$nev = stripcslashes( $item->nev );

		$nev = preg_replace( '/kerékpár/i', '', $nev);

		$link = jroute::_("index.php?option=com_whp&controller=termek&cond_kategoria_id={$item->kategoria_id}&Itemid={$Itemid}&termek_id={$item->id}");

		//$item->nev = "<a href=\"{$link}\"><span>".stripcslashes( $item->gyarto)."</span> - <strong> ".stripcslashes( $item->nev )."</strong></a>";

		$item->nev = "<a href=\"{$link}\">";

		$item->nev .= $nev;

		//if ($item->cikkszam) $item->nev .= " (".$item->cikkszam.")";

		$item->nev .= "</a>";

		

		return $item;

	}

	

	

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

	

		$q = "select termek_id from #__wh_ktermek_kapcsolo where kapcsolodo_termek_id = {$item->id}";

		$this->_db->setquery($q);

		$idk = implode(',',@$this->_db->loadresultarray()); 

		if ($idk) {

		//echo ($idk); 

		$jkategoriak = implode(",", $this->getjog()->kategoriak );

		$q = "SELECT termek.*, ar.ar as ar, kategoria.nev as kategorianev, afa.ertek as afaErtek, kampany_kapcsolo.kampany_prioritas, kampany.id as kampany_id_ FROM #__wh_termek as termek 

			inner join #__wh_ktermek_kapcsolo as ktermek_kapcsolo on termek.id = ktermek_kapcsolo.termek_id
						
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

		GROUP by termek.id order by ktermek_kapcsolo.sorrend ";

			$this->_db->setQuery($q);

			//echo ($this->_db->getquery());

			$rows = $this->_db->loadObjectList();

					

			//print_r($rows); die();

			if (count($rows)) {
					
			array_map ( array($this, "setTermekvariaciok"), $rows);			

			array_map ( array($this, "setLeiras"), $rows) ;

			array_map ( array($this, "setUtvonal"), $rows) ;

			array_map ( array($this, "setBontaskep"), $rows) ;

			array_map ( array($this, "setKampany"), $rows );			

			array_map ( array($this, "setAr"), $rows );	

			array_map ( array($this, "setCsomagar"), $rows );			

			array_map ( array($this, "setKosarMatrix"), $rows) ;	
			//array_map ( array($this, "setHolVasarolhato"), $this->_data);

			array_map ( array($this, "setBontasEgyebKepek"), $rows);			

			//array_map ( array($this, "setTermVarList"), $this->_data ); 

			//array_map ( array($this, "setListaNev"), $this->_data );			

			array_map(array($this, "setRelatedProducts"), $rows);

			array_map(array($this, "setAdditionalProducts"), $rows);

			

			array_map(array($this, "setRecommender"), $this->_data);

			array_map ( array($this, "setComments"), $this->_data );			

			array_map ( array($this, "setKeszlet"), $this->_data );	

			array_map ( array( $this, "setShareLinks" ), $this->_data );

			array_map ( array( $this, "setLetolthetoFile" ), $this->_data );

			//array_map ( array($this, "setLegkisebbAr"), $this->_data );	

			array_map ( array($this, "setTabs"), $this->_data );	

			array_map ( array($this, "setKategoriaLink"), $this->_data );	

			array_map ( array($this, "setTermvarSelect"), $this->_data );			

			array_map ( array($this, "setKalulator"), $this->_data );				

			//array_map ( array($this, "setLinkToRelated"), $this->_data );	

			

			
			//print_r($rows); die();
			jimport("unitemplate.unitemplate");

			

			$uniparams->cols = 3;

			$uniparams->cellspacing = 0;

			$uniparams->templatePath = "components/com_whp/unitpl";

			$uniparams->pair = true;

			$ut = new unitemplate("rel_list", $rows, "div", "related_products", $uniparams);

			$ret = $ut -> getContents(); 

			$item->related_products = $ret;

			} else {$item->related_products = '';}

	

		} else {$item->related_products = '';}

	return $item;

	

	}

	

	function setListaKep_relatedProducts($item){

		$Itemid = $this->Itemid;

		$q = "select id from #__wh_kep where termek_id = {$item->id} and listakep = 'igen'  order by id limit 1";

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

		$q = "SELECT termek.id,termek.me, termek.netto_nagyker_ar, termek.cikkszam,

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

			//array_map ( array($this, "setLegkisebbAr"), $rows );

			

			jimport("unitemplate.unitemplate");

			$ret ='<h3>'.Jtext::_('KIEGESZITO_TERMEKEK').'</h3>';

			$uniparams->cols = 3;

			$uniparams->cellspacing = 0;

			$uniparams->templatePath = "components/com_whp/unitpl";

			$uniparams->pair = true;

			$ut = new unitemplate("list", $rows, "div", "termek_lista", $uniparams);

			$ret .= $ut -> getContents(); 

			

			

			

			

			$item->additional_products = $ret;

			} else {$item->additional_products = '';}

	

		} else {$item->additional_products = '';}

	return $item;

	

	}

	

	function setListaKep_additionalProducts($item){

		$Itemid = $this->Itemid;

		$q = "select id from #__wh_kep where termek_id = {$item->id} and listakep = 'igen' order by id limit 1";

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

		$o->aktiv = 'nem';

		

	

		

		//die($o->text

		

		

		//print_r($o); die();

		/*if( $obj = $this->getObj("#__wh_komment", $o->id, "id" ) ){

			$o->id = $obj->id;

			$this->_db->updateObject("#__wh_komment", $o, "id");

		}else{

			$this->_db->insertObject("#__wh_komment", $o, "id");

		}*/

		$this->_db->insertObject("#__wh_komment", $o, "id");

		$this->sendKommentErtesito($o);

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

		$r = "";

		$r->html = $ret;

		$r->error = "";		

		return $this->getJsonRet($r);

		//return "------";

	}

	

	function setShareLinks($item){

		ob_start();

		?>

			<div class="facebook">

              <table><tr><td>
               <div class="div_share">

                    

                    

                    <a title="<?php echo Jtext::_("Hozzáadás az iWiW-hez") ?>" onclick="window.open('http://iwiw.hu/pages/share/share.jsp?u='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(''), '', 'toolbar=1, menubar=1, resizable=1, location=1, status=1, scrollbars=1, width=800, height=600');return false;" href="http://www.iwiw.hu/" target="_blank"><img src="components/com_whp/assets/images/logo_iwiw_a.jpg" /></a>

                    <a title="<?php echo Jtext::_("Hozzáadás a Facebook-hoz") ?>" onclick="window.open('http://www.facebook.com/share.php?u='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(''), '', 'toolbar=1, menubar=1, resizable=1, location=1, status=1, scrollbars=1, width=800, height=600');return false;" href="http://www.facebook.com/" target="_blank"><img src="components/com_whp/assets/images/logo_facebook_a.jpg" /></a>

                    <a title="<?php echo Jtext::_("Hozzáadás a Twitter-hez") ?>" onclick="window.open('http://twitter.com/home?status='+encodeURIComponent('')+' '+encodeURIComponent(location.href), '', 'toolbar=1, menubar=1, resizable=1, location=1, status=1, scrollbars=1, width=800, height=600');return false;" href="http://www.twitter.com/" target="_blank"></a>

                    <a title="<?php echo Jtext::_("Hozzáadás a MySpace-hez") ?>" onclick="window.open('http://www.myspace.com/index.cfm?fuseaction=postto&amp;' + 't=' + encodeURIComponent(document.title) + '&amp;c=&amp;u=' + encodeURIComponent(location.href) + '&amp;l=', '', 'toolbar=1, menubar=1, resizable=1, location=1, status=1, scrollbars=1, width=800, height=600');return false;" href="http://www.myspace.com/" target="_blank"><img src="components/com_whp/assets/images/logo_myspace_a.jpg" /></a>

                    <a title="<?php echo Jtext::_("Hozzáadás a Startlap-hoz") ?>" onclick="window.open('http://www.startlap.hu/sajat_linkek/addlink.php?url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(''), '', 'toolbar=1, menubar=1, resizable=1, location=1, status=1, scrollbars=1, width=800, height=600');return false;" href="http://www.startlap.hu/" target="_blank"><img src="components/com_whp/assets/images/logo_startlap_a.jpg" /></a>

                <a title="<?php echo Jtext::_("Hozzáadás a Google Könyvjelzőkhöz") ?>" onclick="window.open('http://www.google.com/bookmarks/mark?op=add&amp;bkmk='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent('')+'&amp;annotation=', '', 'toolbar=1, menubar=1, resizable=1, location=1, status=1, scrollbars=1, width=800, height=600'); return false;" href="http://www.google.com/" target="_blank"><img src="components/com_whp/assets/images/logo_google_a.jpg" /></a>

                </div>
              </td><td><div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="fb-like" data-href="www.trifid.hu" data-send="true" data-layout="button_count" data-width="150" data-show-faces="true" data-font="tahoma"></div>
</td></tr></table>
               

    		</div>
		<?php

		$ret = ob_get_contents();

		ob_end_clean();

		$item->shareLinks = $ret;

		return $item;

	}



}// class

?>

