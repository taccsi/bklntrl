<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlTermek extends kep{
	var $images = 3;
	var $uploaded = "media/termekek";
	var $table = "#__wh_termek";	
	var $kepPrefix = "";
	var $nopic = "components/com_wh/assets/images/nopic.jpg";

	function getLightboxLink($link, $txt){
		return "<a href=\"{$link}\" rel=\"lightbox[x]\" rev=\"width: 800px height:500px\">{$txt}</a>";
	}

	function getTermekTipus( $node){
		$name = $node->getAttribute( "name" );
		$value = $this->getAktVal($name);
		$ret = "";
		foreach( array("", "DARABARU", "CSOMAGOLT_ARU", "TEKERCSES_ARU") as $a ){
			$o="";
			$o->value = $a;
			$o->option = jtext::_($a);			
			$arr[]=$o;
		}
		$ret .= JHTML::_('Select.genericlist', $arr, $name, array( "class"=>"", "onchange"=> "getTermekTipus()" ), "value", "option", $value );
		//$this->document->addScriptDeclaration( "\$j( document ).ready( function(){ getKalkulatorMezokFotermek(); } )" );
		$ret .= "<div id=\"ajaxContentTermekTipus\" ></div>";
		return $ret;		
	}

	function getNettoNagykerAr______________( $node ){
		$name = $node->getAttribute( "name" );
		$value = $this->getAktVal($name);
		$ret = "";
		$ret .= "<input type=\"text\" value >";
	}

	function getProductPriceList( $node ){
		$product_id = $this->getAktVal( "id" );
		//echo $product_id."- -----";
		$this->document->addScriptDeclaration("\$j(document).ready(function(){ getProductPriceList( '{$product_id}' ); })");
		$ret = "" ;
		$ret .= "<div id=\"ajaxContentProductPriceList\"></div>";
		return $ret;
	}
   
	function getTolIgMezo( $node ){
		ob_start();
		$name = $node->getAttribute( "name" );
		$fTol = str_replace("ig", "", $name);
		$fIg = str_replace("tol", "", $name);
		$tol = $this->getSessionVar( $fTol );
		$ig = $this->getSessionVar( $fIg );
		$description = $node->getAttribute("description");
		if($tol && $ig){
			$value = "{$tol}-{$ig}";
		}else{
			$value = $this->getAktVal( $name );
			$arr = explode("-", $value);
			@$tol = $arr[0];
			@$ig = $arr[1];
		}
		echo "<input name=\"{$name}\" value=\"{$value}\" id=\"{$name}\" type=\"hidden\"  class=\"\" />";
		echo "<input class=\"input_tolig\" name=\"{$fTol}\" id=\"{$fTol}\" value=\"{$tol}\" type=\"text\" />".jtext::_("TOL")."&nbsp;&nbsp;";
		echo "<input class=\"input_tolig\" name=\"{$fIg}\" id=\"{$fIg}\" value=\"{$ig}\" type=\"text\"  />".jtext::_("IG")."&nbsp;&nbsp;".$description;
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
	}

	function getTolIgAlapterulet( $node ){
		ob_start();
		$name = $node->getAttribute("name");
		$tol = $this->getSessionVar( "alapterulet_tol" );
		$ig = $this->getSessionVar( "alapterulet_ig" );
		if($tol && $ig){
			$value = "{$tol}-{$ig}";
		}else{
			$value = $this->getAktVal( $name );
			$arr = explode("-", $value);
			$tol = $arr[0];
			$ig = $arr[1];
		}
		//die($tol." ---------------");	
		echo "<input name=\"{$name}\" value=\"{$value}\" id=\"{$name}\" type=\"hidden\"  class=\"\" />";
		echo "<input name=\"alapterulet_tol\" id=\"alapterulet_tol\" value=\"{$tol}\" type=\"text\" />".jtext::_("TOL")."&nbsp;&nbsp;";
		echo "<input name=\"alapterulet_ig\" id=\"alapterulet_ig\" value=\"{$ig}\" type=\"text\"  />".jtext::_("IG");
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
	}

	function getTermekVariaciok( $node ){
		ob_start();
		$document = jfactory::getDocument();
		$id = $this->getAktVal("id");
		$document->addScriptDeclaration("\$j(document).ready(function(){ getTermekVariaciok() })");
		$name = $node->getAttribute('name');
		$value=$this->getaktVal($name);
		?>
        <div id="ajaxContentParameterLista" ></div>
        <?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
   }

	function getMsablonErtek( $node ){
		ob_start();
		$document = jfactory::getDocument();
		$id = $this->getAktVal("id");
		$document->addScriptDeclaration("window.addEvent(\"domready\", function(){getParameterek('{$id}');});");
		$name = $node->getAttribute('name');
		$value=$this->getaktVal($name);
		$q = "select *";
		?>
        <div id="ajaxContentParameterLista" ></div>
        <?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
   }

	function isTermekId($link){
	
		$id=$this->getaktVal("id");
		if(!$id){
			$teljes_hivatk =" href=\"#\" onclick=\"alert('".JText::_("VAN TERMEK ID")."');\"";
		}
		else {
			$teljes_hivatk = " href=\"".$link."\" rel=\"lightbox[x]\" rev=\"width: 800px height:500px\"";
		}
		return $teljes_hivatk;
	}

	function getKonkurenciaArak($node){
		ob_start();
		$id = $this->getAktVal("id");
		$leker_konkurencia = $this->getAktVal("leker_konkurencia");
		$arr = array();
		foreach($this->webContent->konkurenciaArr as $k => $x){
			($leker_konkurencia == $k) ? $leker=1 : $leker=0;
			$kObj = $this->webContent->getKonkurenciaAr ( $id, $k, $leker );
			//printf("%s: Ár: %s url: %s <br />", $k, $kObj->ar, $kObj->url );
			$o ="";
			$o->KONKURENCIA = $this->getKonkurenciaArakBuborek($k, $kObj);
			@$o->AR = number_format($kObj->ar,0,""," ")." Ft";
			@$o->URL = "<a target=\"_blank\" href=\"$kObj->url\">{$kObj->url}</a>";
			$o->AR_LEKERDEZES = "<input type=\"submit\" onclick=\"$('task').value='keep'; $('leker_konkurencia').value='{$k}'\" value=\"".JText::_("LEKERDEZ AR")."\" />";
			$arr[]=$o;
		}
		$listazo = new listazo( $arr );
		echo $listazo->getLista();

		?>
        <script>$('leker_konkurencia').value=''</script>
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;   					
	}
	function getArArr($node){
		ob_start();
		$name = $node->getAttribute('name');
		$value = (array)$this->getSessionVar($name);
		foreach($value as $v){
			?>
			<input name="<?php echo $name ?>[]" value="<?php echo $v ?>" type="hidden_" />
			<?php
		}
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;   			
	}
	
	function sorrendKep($sorrend, $termek_id, $elso_kep, $utolso_kep){
		ob_start();
		global $Itemid;	
			if($sorrend != $utolso_kep){
			$link= "javascript:void(0);";
			$js = "document.getElementById('task').value='sorrend'; tabEllenoriz(); javascript:kepSorrendezo('le','".$sorrend."','".$termek_id."');";

			?>
			<a href="<?php echo $link ?>" onclick="<?php echo $js; ?>" ><img src="components/com_wh/assets/images/downarrow.png" /></a>
			<?php
            }
			
			if($sorrend != $elso_kep){
			$link= "javascript:void(0);";
			$js = "document.getElementById('task').value='sorrend'; tabEllenoriz(); javascript:kepSorrendezo('fel','".$sorrend."','".$termek_id."');";
			
			?>
			<a href="<?php echo $link ?>" onclick="<?php echo $js; ?>" ><img src="components/com_wh/assets/images/uparrow.png" /></a>
			<?php
     		}
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
	}
	
	function sorrendNyilak( $obj ){
		ob_start();
		global $Itemid;
		$q = "select count(id) as osszes from #__wh_kep where termek_id = {$obj->termek_id} and id <> {$obj->id}";
		$this->db->setQuery($q);
		$osszes = $this->db->loadResult();

		if($obj->sorrend < ($osszes) ){
			$link= "javascript:void(0);";
			$js = "javascript:sorrend('".$obj->id."', 'le');";
			?>
			<a href="<?php echo $link ?>" onclick="<?php echo $js ?>" ><img src="components/com_wh/assets/images/downarrow.png" /></a>
			<?php
		}
		if($obj->sorrend > 0 ){
			$link= "javascript:void(0);";
			$js = "javascript:sorrend('".$obj->id."', 'fel');";
			?>
			<a href="<?php echo $link ?>" onclick="<?php echo $js ?>" ><img src="components/com_wh/assets/images/uparrow.png" /></a>
			<?php
		}		

		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	function getMarka($node){
		ob_start();
		$name = $node->getAttribute('name');
		$value = $this->getaktVal($name);
		$q = "select id as `value`, nev as `option` FROM #__wh_gyarto";
		$this->db->setQuery($q);
		$rows = $this->db->loadObjectList();
		echo JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"multiple_search"), "value", "option", $value);
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
	}
	
	function getBeszallitoQuery($termek_id){
		$q = "select a.*, b.nev FROM #__wh_termek_beszallito_ar as a inner join #__wh_beszallito as b
		ON a.beszallito_id = b.id
		WHERE a.termek_id = {$termek_id} 
		ORDER BY a.netto_ar
		";
		$this->db->setQuery($q);
		return $this->db->loadObjectList();
	}
	
	function getBeszListaz($node){
		ob_start();
		if($this->getaktVal("id")){
		$termek_id=$this->getaktVal("id");
		/*$q = "select a.*, b.nev FROM #__wh_termek_beszallito_ar as a inner join #__wh_beszallito as b
		ON a.beszallito_id = b.id
		WHERE a.termek_id = {$termek_id} 
		ORDER BY a.netto_ar
		";
		$this->db->setQuery($q);*/
		$rows = $this->getBeszallitoQuery($termek_id);
		$arr = array();

		if($rows){
			foreach( $rows as $r ){
				$torol_link = "if(confirm('".JText::_("ARE YOU SURE")."')){ $('torol_id_').value={$r->id}; $('task').value='torolBeszallitoAr'; tabEllenoriz(); $('adminForm').submit() }";
				$obj = "";
				$obj -> NEV = $r->nev;
				$obj -> TOROL = "<input type=\"button\" onclick=\"{$torol_link}\" value=\"".JText::_("TORLES")."\" >";	
				$node = $this->getNode("name", "afa_id");
				$obj-> AFA = $this->getAfaSelect($node, "_beszallito_ar[]", $r->afa_id );
				$obj -> BESZALLITOI_AR = $this->getNettoBruttoInput
				("beszallito_netto_ar", "beszallito_bruttoAr",  $r->netto_ar, 25, "beszArId{$r->id}", $name_ext = "[]", "</br>" )."<input type=\"hidden\" name=\"beszallito_ar_id[]\" value=\"{$r->id}\">";
//				$obj -> BESZALLITO_NETTO_AR = "<input type=\"text\" name=\"beszallito_netto_ar[]\" value=\"{$row->netto_ar}\" ><input type=\"hidden\" name=\"beszallito_ar_id[]\" value=\"{$row->id}\">";
				$arr[] = $obj;
			}
		}else{
			//$obj = "";
			//$arr[] = $obj;
		}
		if( count($arr) ){
			echo $this->kapcsListaz($arr);
			if($rows){
				?>
				<input type="hidden" id="torol_id_" name="torol_id_" value="" >
				<?php
			}
		}
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
		}
		else{
		$ret = "";
		return $ret;
		}
	}

	function getBeszallito( $node ){
		ob_start();
		$name = $node->getAttribute('name');
		$value=$this->getaktVal($name);
		$id=$this->getaktVal("id");
		$link="index.php?option=com_wh&controller=beszallitok&kapcsolodo_id={$id}&tmpl=component";
		?>
<input name="<?php echo $name ?>" id="<?php echo $name ?>" value="<?php echo $value ?>" type="hidden"  />
<a <?php echo $this->isTermekId($link); ?>><?php echo  JText::_("BESZALLITO HOZZADASA") ;  ?></a>
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
   }

	function getJavasoltAr(){
		ob_start();
		$termek_id = $this->getaktVal( "id" );
		$haszonObj = $this->getHaszonObj($termek_id);
		echo ar::_(@$haszonObj->koltseg);
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	function setSzazalekos($ws_szam,$db){
		$termek_id = $this->getaktVal( "id" );
		$haszonObj = $this->getHaszonObj($termek_id);
		if(@$haszonObj->koltseg){
			return $valtas_szazalekka = "valtasSzazalekka({$db});";
		}
		else{
			return $valtas_szazalekka = "";
		}
	}
	
	function setAras($ws_szam,$db){
		$termek_id = $this->getaktVal( "id" );
		$haszonObj = $this->getHaszonObj($termek_id);
		@$besz=$this->getBeszallitoAdatok($termek_id);
		if(@$haszonObj->koltseg){
			return $valtas_szazalekka = "valtasArra({$db},{$haszonObj->koltseg});";
		}
		else if(@!$haszonObj->koltseg && $besz){
			$besz = $this->getBeszallitoAdatok($termek_id);
			@$besz_netto_ar = $besz->netto_ar;	
			return $valtas_szazalekka = "valtasArra({$db},{$besz_netto_ar});";
		}

	}
	
	function getBeszallitoAdatok($termek_id){
		$q = "select * from 
		#__wh_termek_beszallito_ar 
		where termek_id = {$termek_id}
		group by netto_ar having min(netto_ar)
		";
		$this->_db->setQuery($q);
		return $this->_db->loadObject();
	}

   	function getArak( $node ){
		ob_start();

		$name = $node->getAttribute('name');
		$value=$this->getaktVal($name);
		$termek_id=$this->getaktVal("id");
		$this->beallitas=$this->getBeallitas();
		$kategoria_id=$this->getaktVal("kategoria_id");	
		$webshopId = implode(",", $this->getWebshopIdArrByKategiriaId($kategoria_id));
		//die($webshopId."{$kategoria_id}-----------");
		$q = "select count(*) from #__wh_webshop where id in ({$webshopId})";
		$this->db->setQuery($q);
		$ws_szam = $this->db->loadResult();
	
		$q = "select * from #__wh_webshop where id in ({$webshopId}) order by id";
		$this->db->setQuery($q);
		$rows = $this->db->loadObjectList();
		$arr = array();
		
		$termek_id = $this->getaktVal( "id" );
		$db=0;
		foreach($rows as $r){
			$q = "select arT.*, afaT.ertek as afaErtek from #__wh_ar as arT 
			inner join #__wh_afa as afaT on arT.afa_id = afaT.id
			where arT.termek_id = {$termek_id} and arT.webshop_id = {$r->id} ";
			$this->db->setQuery($q);
			$arO = $this->db->loadObject();
			echo $this->db->getErrorMsg();
			$valtas_szazalekka = $this->setSzazalekos($ws_szam,$db);
			$valtas_arra = $this->setAras($ws_szam,$db);
			$o="";
			$o->WEBSHOP = $r->nev;
			//print_r($arO);
			@$o->AR = $this->getNettoBruttoInput("ar", "bruttoAr", $arO->ar, $arO->afaErtek, "ar{$r->id}", $name_ext = "[]", "</br>" )."<input type=\"hidden\" name=\"webshopId[]\" value=\"{$r->id}\" />";
			//@$o->AR = "<input type=\"hidden\" name=\"webshopId[]\" value=\"{$r->id}\" /><input name=\"ar[]\" id=\"ar".$db."\" onblur=\"{$valtas_szazalekka}\" value=\"{$arO->ar}\" />Ft";
			//$o->AR_SZAZALEK = "<input name=\"ar_szazalek[]\" id=\"ar_szazalek".$db."\" onblur=\"{$valtas_arra}\" value=\"\" />%
			//<script>{$valtas_szazalekka}<script>";
            
			@$o->AFA = $this->getAfaSelect($node, "afa_id[]", $arO->afa_id );
			$arr[]=$o;
			$db++;
		}
		if( count($arr) ){
			$listazo = new listazo( $arr );
			echo $listazo->getLista();
		}else{
			echo jtext::_("A TERMEKKATEGORIA NINCS WEBSHOPHOZ RENDELVE");
		}
		
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function getWebShopKategoria( $node ){
		$termek_id = $this->getAktVal("id");
		$q = "select webshop.*, kategoria.id as kategoria_id 
		from #__wh_webshop as webshop inner join #__wh_kategoria as kategoria on webshop.id = kategoria.webshop_id
		order by webshop.id, webshop.nev";
		$this->_db->setQuery($q);
		$arr = $this->_db->loadObjectList();
		echo $this->_db->geterrorMsg();
		$r = "";
		$ret = array();
		foreach( $arr as $a ){
			$o = "";
			$o->WEBSHOP = $a->nev;
			$kategoriafa = new kategoriafa( array(), 5000, $a->kategoria_id );
			$o_ ="";
			$o_->option = $o_-> value = "";
			array_unshift( $kategoriafa->catTree, $o_ );
			$q = "select kategoria_id from #__wh_kategoria_kapcsolo where termek_id = {$termek_id} and webshop_id = {$a->id}";
			$this->_db->setQuery($q);
			(int)$value = $this->_db->loadResult();
			//echo $value."------------------";
			//echo $this->_db->getQuery();
			$o->KATEGORIA = "";
			$o->KATEGORIA .= JHTML::_('Select.genericlist', $kategoriafa ->catTree, "kategoria_id_webshop[]", array("class"=>"kategoria_id_webshop", "readonly" => "readonly"), "value", "option", $value);
			$o->KATEGORIA .= "<input type=\"hidden\" name=\"webshop_id[]\" value=\"{$a->id}\" >";						
			$ret[]=$o;		
		}
		if( count( $ret ) ){
			$listazo = new listazo($ret, "webshop_lista");
			$r .= $listazo->getLista();
		}
		return $r;
	}
	
	function getKategoriaSelect($node) {
		$name = $node->getAttribute('name');
		$value=$this->getAktVal($name);
		$arr = array();
		ob_start();
		if( $this->isPopup() ){
			$obj = $this->getObj("#__wh_kategoria", $value);
			?>
			<input name="<?php echo $name ?>" type="hidden" value="<?php echo $value ?>" /> 
<?php echo $obj->nev ?>
			<?php	
		}else{
			$kategoriafa = new kategoriafa( array(), 5000, $this->getSzuloKategoria()->id);
			$o="";
			$o->option = $o-> value = "";
			array_unshift($kategoriafa ->catTree, $o);
			echo JHTML::_('Select.genericlist', $kategoriafa ->catTree, $name, array("class"=>"", "readonly" => "readonly"), "value", "option", $value);
		}
		$ret = ob_get_contents();	  
		ob_end_clean();
		return $ret;
		// die($readonly);
	}
	
	function getParhKategoriaSelect($node) {
		$cid = Jrequest::getvar('cid');
		$termek_id = $cid[0];
		$name = $node->getAttribute('name');
		$description = $node->getAttribute('description');
		$value=$this->getAktVal($name);
		if (!count($value)){
			
			$q = "select kategoria_id from #__wh_parh_kat_kapcs where termek_id = '{$termek_id}'";
			$this->_db->setquery($q);
			//die($this->_db->getquery());
			$value = $this->_db->loadresultarray();
			//print_r($value); die();
		}
		$arr = array();
		//print_r($value); die();
		ob_start();
		$kategoriafa = new kategoriafa( array(), 5000, $this->getSzuloKategoria()->id);
		$o="";
		$o->option = $o-> value = "";
		array_unshift($kategoriafa ->catTree, $o);
		echo JHTML::_('Select.genericlist', $kategoriafa ->catTree, $name."[]", array("class"=>"", "readonly" => "readonly","multiple"=>"multiple","size"=>"8"), "value", "option", $value).Jtext::_($description);
		
		$ret = ob_get_contents();	  
		ob_end_clean();
		return $ret;
		// die($readonly);
	}

	function getKiegTermekek($node){
		$name = $node->getAttribute('name');
		$value = $this->getAktVal($name);
		$ajaxContentId = "ajaxContentKiegTermekek";
		$document = jfactory::getDocument();
		$document->addScriptDeclaration("\$j(document).ready(function(){ getKiegTermekek( ) });");
		$ret ="";
		
		$q ="select id as `value`, nev as `option` from #__wh_termek as termek order by nev";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList( );
		$arrName = "kiegTermekekArr";		
		$str = "var {$arrName} = [ ";
		foreach( $rows as $r ){
			$str .= $this->getJsonRet( $r ).", ";
		}
		//$str .= "{\"-\"}";
		$str .=" ];";
		$this->document->addScriptDeclaration( $str ); 		
		//$this->setJsAutoCompleteArr( $rows, $jsArr );
		$autoCName = "{$name}";
		$this->document->addScriptDeclaration( "\$j(document).ready(function(){ initAutoComplete( '{$autoCName}', '{$arrName}', '{$name}' ) })" ); 
		$o="";
		$o->option = $o->value = "";
		array_unshift( $rows, $o );
		$ret = "";
		$ret .= "<input type=\"text\" id=\"{$autoCName}\" name=\"{$autoCName}\" />";
		//$ret .= "<input type=\"hidden\" id=\"{$name}\" name=\"{$name}\" value=\"\" />";		
		$ret .= "<input type=\"button\" onclick=\"hozzaadKiegTermek( '{$ajaxContentId}'); \" value=\"".jtext::_("HOZZAAD")."\" /> ";
		$ret .= "<div id=\"{$ajaxContentId}\"></div>";
		return $ret;
	}
   
	function getKapcsolodoTermekek( $node ){
		$name = $node->getAttribute('name');
		$value = $this->getAktVal($name);
		$ajaxContentId = "ajaxContentKapcsolodTermekek";
		$document = jfactory::getDocument();
		$document->addScriptDeclaration("\$j(document).ready(function(){ getKapcsolodoTermekek( ) });");
		$ret ="";
		
		$q ="select id as `value`, nev as `option` from #__wh_termek as termek order by nev";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList( );
		$arrName = "kapcsolodoTermekekArr";		
		$str = "var {$arrName} = [ ";
		foreach( $rows as $r ){
			$str .= $this->getJsonRet( $r ).", ";
		}
		//$str .= "{\"-\"}";
		$str .=" ];";
		$this->document->addScriptDeclaration( $str ); 		
		//$this->setJsAutoCompleteArr( $rows, $jsArr );
		$autoCName = "{$name}";
		$this->document->addScriptDeclaration( "\$j(document).ready(function(){ initAutoComplete( '{$autoCName}', '{$arrName}', '{$name}' ) })" ); 
		$o="";
		$o->option = $o->value = "";
		array_unshift( $rows, $o );
		$ret = "";
		$ret .= "<input type=\"text\" id=\"{$autoCName}\" name=\"{$autoCName}\" />";
		//$ret .= "<input type=\"hidden\" id=\"{$name}\" name=\"{$name}\" value=\"\" />";		
		$ret .= "<input type=\"button\" onclick=\"hozzaadKapcsolodoTermek( '{$ajaxContentId}'); \" value=\"".jtext::_("HOZZAAD")."\" /> ";
		$ret .= "<div id=\"{$ajaxContentId}\"></div>";
		return $ret;
	}

   function getKapcsolodKategoriaSelect($node) {
	
      	$name = $node->getAttribute('name');
		$value = $this->getAktVal($name);
     	if( !is_array( $value ) && $value ){
			$value = explode(",", $value );
		 }else{
		 	
		 }
	  $kategoriafa = new kategoriafa( );
      return JHTML::_('Select.genericlist', $kategoriafa ->catTree, $name."[]", array("multiple"=>"multiple", "class"=>""), "value", "option", $value);
   }
   
}
?>
