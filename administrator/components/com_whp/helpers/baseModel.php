<?php

//die("adsasdasd");

defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.model');



class modelBase extends JModel

{

	var $tableFields =null;

	var $images = 3;  

	var $uploaded = "components/com_hr/oneletrajzok";

	var $tmpname = ""; 

  	var $limit = 20;

	var	$from = "";

	var	$fromname = "";

	var $mezoIdArr = array('7','8');

	var $csillagokSzama = 5;

	var $ertekelesArr = array("termek_minoseg"=>"", "kiszolgalas_minosege"=>"", "webaruhaz_kezelhetosege"=>"");	

	//var $xmlFields = array("used", "state", "area", "adCity", "shire", "adCityPart" );



	function __construct()

	{

		global $mainframe;

		

		parent::__construct();



		$this->db = &JFactory::getDBO(  );	

		$this->_db = &JDatabase::getInstance( whpBeallitasok::getOption() ); 

		//$this->_db = &JFactory::getDBO(  ); 
		$menu = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();
		if (isset($active->id)){
			$this->Itemid = $active->id;
			if ($this->Itemid){
				$this->setSessionVar('Itemid', $this->Itemid);
			}
			
		} else {
			$this->Itemid = 0;
		}
		
		//die();

		//$this->webContent = new webContent;

		$this->user=JFactory::getUser();

		$this->vasarlo = $this->getVasarlo();

		$this->doc = $this->document = JFactory::getDocument();

		$array = JRequest::getVar('cid',  0, '', 'array');

		//print_r($array[0]); exit;

		$this->setId((int)$array[0]);

		$this->setMandatoryFields();

		

		if($kategoria_id = jrequest::getVar("cond_kategoria_id", 0 )){

			//die("-----");

			$this->kategoria_ = $this->getObj("#__wh_kategoria", $kategoria_id );

		}else{

			$this->kategoria_ = "";

		}

	}//function

	function kerekit_suly($suly){
		$suly = number_format(round($suly,2),2);
		return $suly;
	}

	function getStarOptions( $name, $value = 0, $id = "" ){

		$ret ="";

		for( $i=1; $i<=$this->csillagokSzama; $i++ ){

			$checked = ($value == $i) ? $checked = "checked=\"checked\"" : "";

			$name = (!$id) ? $name : $id;

			$ret.= "<input name=\"{$name}\" type=\"radio\" class=\"hover-star\" value=\"{$i}\" {$checked} />";

		}

		return $ret;	

	}



	

	function getTabForm($tabArr){

		$ret = "";

		$ret .= "<ul class=\"tabs\">";

		foreach($tabArr as $t){

			$ret .= "<li type=\"{$t->type}\"><a href=\"#{$t->tabId}\">".JTEXT::_($t->title)."</a></li>";

		}

		$ret .= "</ul>";		



		$ret .= "<div class=\"tab_container\">";

		foreach($tabArr as $t){

			$ret .= "<div id=\"{$t->tabId}\" class=\"tab_content\" >{$t->tabContent}</a></div>";

		}

		$ret .= "</div>";		

		return $ret;

	

	}





	function getTabArr(){

		$g_ = $this->xmlParser->getAllFormGroups();	

		$ret = array();

		$o = new stdClass;

		$o->title = jtext::_( "MAINDATA" );

		$o->tabId = "tab1";

		$o->type = "normal";

		$o->tabContent = $this->getMainForm( html_entity_decode( $g_["maindata"] ) );

		

		$ret[]= $o;

		

		foreach( $this->getLanguagesArr() as $a ){

			if($a->code != $this->default_language ){

				$ind = array_search( $a, $this->getLanguagesArr() )+2;

				$o = new stdClass;

				$o->title = jtext::_($a->code);

				$o->type = $a->code;

				//print_r($a); die();

				$o->tabContent = $this->getLangform( $a->code );

				$ret[]= $o;

			}

		}

		$this->tabArr = $ret;

		array_map(array($this, "setTabId"), $ret);

		return $ret;

	}

	

	function setTabId($item){

		$ind = array_search($item, $this->tabArr);

		$item->tabId = "tab{$ind}";

		return $item;

	}	

	

	function getMainForm( $formData ){

		ob_start();

		?>

        <form method="post" enctype="multipart/form-data" id="adminForm" name="adminForm" >

          <fieldset>

            <legend><?php echo JText::_($this->controller); ?></legend>

            <?php echo $formData;?>

          </fieldset>

          <input type="hidden" name="option" value="com_xvs" />

          <input type="hidden" name="task" id="task" value="" />

          <input type="hidden" name="controller" value="<?php echo $this->controller ?>" />

        </form>

		<?php

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;

	}	



   function validateForm(){

      $ret = "";

      $ret->error ="";  

      $ret->html ="";   

      $ret->id=0;

      $errorFields = $this->checkMandatoryFields();

      //print_r($errorFields);

      if( count( $errorFields ) ){

         $ret->error .= jtext::_("HIBASAN_KITOLTOTT_MEZOK")."\n"; 

         foreach($errorFields as $a){

            $e = $this->xmlParser->getNode("name", $a );

            if(is_a($e, "DOMElement")){ 

               $ret->error .= jtext::_( $e->getAttribute("label") ).": ".jtext::_($e->getAttribute("mandatory_text"))."\n";  

            }

         }

      }

      return $this->getJsonRet( $ret );

   }

	

	function getJsonRet( $obj ){

		$response=array();

		foreach($obj as $k=>$v){

			$response[$k]=$v;

		}

		$json = new Services_JSON();

		return $json->encode( $response );

	}	



	

	function getNettoBruttoInput($nettoName, $bruttoName, $netto_ar, $afa_id, $id, $name_ext = "[]" ){

		ob_start();

		@$afa = $this->getObj("#__whp_afa", $afa_id )->ertek;

		$N = "{$nettoName}{$name_ext}";

		$B = "{$bruttoName}{$name_ext}";

		$idN = "{$nettoName}{$id}";

		$idB = "{$bruttoName}{$id}";		

		$js = "onblur=\"arNettoBrutto('{$idN}', '{$idB}', {$afa}, 'nettoBol' )\"";

        echo "<input name=\"{$N}\" {$js} id=\"{$idN}\" type=\"text\" value=\"{$netto_ar}\" >".jtext::_("NETTO_AR")."&nbsp;&nbsp;&nbsp;";

		$js = "onblur=\"arNettoBrutto('{$idN}', '{$idB}', {$afa}, 'bruttoBol' )\"";

		$brutto_ar = $netto_ar*($afa /100 +1);

		echo "<input type=\"text\" id=\"{$idB}\" name=\"{$B}\" {$js} value=\"{$brutto_ar}\" />".jtext::_("BRUTTO_AR")."&nbsp;&nbsp;&nbsp;";

		$q = "select id as `value`, ertek as `option` from #__whp_afa";

		$this->db->setQuery($q);

		$rows = $this->db->loadObjectList();

		

		$name = "afa_id{$name_ext}";

        echo JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"multiple_search", "onchange"=>"setArMezok('{$name}', '{$idN}', '{$idB}',  'nettoBol')"), "value", "option", $afa_id ).jtext::_("AFA")."&nbsp;&nbsp;&nbsp;";

		

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;		

	}



	function cleanTomb($arr){

		$ret = array();

		foreach($arr as $a){

			if(trim($a)){

				$ret[]=$a;

			}

		}

		return $ret;

	}	



	

	

	function getVasarlo(){

		$q = "select felhasznalo.*, csoport.nev as csoport_nev from #__wh_felhasznalo as felhasznalo

		left join #__wh_fcsoport as csoport on felhasznalo.fcsoport_id = csoport.id

		where felhasznalo.user_id = {$this->user->id} 

		and felhasznalo.webshop_id = '".$GLOBALS["whp_id"]."' ";

		$this->_db->setQuery($q);

		$f = $this->_db->loadObject();

		return $f;		

	}

	

	function getVasarlo_(){

		$q = "select * from #__wh_felhasznalo where user_id = {$this->user->id} and webshop_id = '".$GLOBALS["whp_id"]."' ";

		$this->_db->setQuery($q);

		$f = $this->_db->loadObject();

		return $f;		

	}

	

	function setPageTitle($title){

		$doc = jfactory::getdocument();

		$doc->setTitle($title);

	}



	function getCond(){
		$cond = "";
		$group = $this->xmlParser->getGroup("condFields" );
		$mezok = array();
		$db = JFactory::getDBO();
		foreach ( $group->childNodes as $element ){
			if(is_a($element, "DOMElement") ){
				$field= $element->getAttribute('name');
				$q= $element->getAttribute('q');
				$val = JRequest::getVar($field, "");
				//echo $field."-----{$val}";
				//$val = $this->getSessionVar($field);
				//$val=$db->getEscaped($val);
				//print_r($val);
				//die("----");
				if( $val ){
					$v_ = JRequest::getVar( "cond_valaszto", "" );
					switch( $field ){
						case "kampany_id":
							if( is_array( $val ) ){
								$cond .= "kampany_kapcsolo.kampany_id in (".implode( ",", $val ).") and ";
								//echo $cond;
								//die( $cond );								
							}else{
								$cond .= "kampany_kapcsolo.kampany_id = '{$val}' and ";
							}

						break;						
						case "cond_cimke_varazslo":
							$val = $this->cleanTomb($val);
							if( !count( $val ) ) break;
							$val = $this->cleanTomb($val);
							$q = "select id, kategoria_id from #__wh_cimke as cimke
							where id in (".implode( ",", $val ).") order by kategoria_id";
							$this->_db->setQuery($q);
							$rows = $this->_db->loadObjectList( );		
							//echo $this->_db->getQuery( );
							//print_r($rows);
							echo $this->_db->getErrorMsg( );		
							$tmp = array();
							foreach($rows as $r){
								if(!isset($tmp[$r->kategoria_id]))$tmp[$r->kategoria_id] = array();
								$tmp[$r->kategoria_id][]=$r->id;
							}
							//print_r($tmp);
							$termekIdArr=array();
							foreach( $tmp as $k => $arr_ ){
								$q = "select kapcsolo.termek_id 
								from #__wh_cimke_kapcsolo as kapcsolo
								left join #__wh_cimke as cimke on kapcsolo.cimke_id = cimke.id
								where cimke.id in (" . implode(", ", $arr_) . ") ";
								$this->_db->setQuery($q);
								$termekIdArr[]= $this->_db->loadResultArray();
							}
							foreach($termekIdArr as $tArr){
								$cond .= "termek.id in( ".implode(", ", $tArr )." ) and "; 
							}
							//print_r($cond);
						break;
						case "cond_megvasarolhato" : 
							$val = $this->cleanTomb($val);
							if (count($val) < 2){
								foreach ($val as $v){
									if ($v == 'igen'){
										$cond .= "{$q} not like 'nem' and ";
									} else {
										$cond .= "{$q} like '{$v}' and ";
									}
								}
							}
							 break;
						case "cond_valaszto" :
						break;
						case "cond_nev" :
							$q = "select termek.id from #__wh_termek as termek 
							where termek.nev like '%{$val}%' 
							or termek.leiras like '%{$val}%'
							or termek.leiras_rovid like '%{$val}%'
							or termek.cikkszam like '%{$val}%' ";
							$this->_db->setQuery($q);
							$rows1 = $this->_db->loadResultArray( );
							echo $this->_db->getErrorMsg( );
							//die;							
							$q = "select termek.id from #__wh_termekvariacio as termekvariacio
							inner join #__wh_termek as termek on termekvariacio.termek_id = termek.id
							where termekvariacio.cikkszam like '%{$val}%'
							or ertek like '%mezoid_3={$val}&%'
							";
							$this->_db->setQuery($q);
							$rows2 = $this->_db->loadResultArray( );
							echo $this->_db->getErrorMsg( );
							$q = "select kapcsolo.termek_id
							from #__wh_cimke_kapcsolo as kapcsolo
							inner join #__wh_cimke as cimke on cimke.id = kapcsolo.cimke_id
							where cimke.nev like '%{$val}%'";
							$this->_db->setQuery($q);
							$rows3 = $this->_db->loadResultArray( );
							echo $this->_db->getErrorMsg( );
							$rows = array_merge( $rows1, $rows2, $rows3 );
							(array)$rows[]=0;
							$cond .= " termek.id in(".implode(",", $rows).") and "; 
							//die($cond);
						break;
						case "cond_cimke_id" :
							$idk = $this->getKategoriaIdkByCimkeId($val);
							$cond .= "({$q}  = {$val} or termek.id in ({$idk})) and "; 
							break;
						case "cond_gyarto_id" : $cond .= "{$q} = '{$val}' and "; break;
						case "cond_ar_tol": $cond .= "{$q} >= ".(int)$val." and "; break;
						case "cond_ar_ig": $cond .= "{$q} <= ".(int)$val." and "; break;
						case "cond_barmilyen_kifejezes" : 
							$cond .= "( termek.leiras like '%{$val}%' or ";
							$cond .= "termek.nev like '%{$val}%' or ";
							$cond .= "termek.isbn like '%{$val}%' or ";
							$cond .= "szerzo.nev like '%{$val}%' ";
							$cond .= " ) and ";
						break;
						case "cond_termekeim" : $cond .= "{$q} = {$this->user->id} and "; break;
						case "cond_kategoria_id" : 
							$idk = implode(",", $this->getlftrgtosszes($val, "#__wh_kategoria") );
							$termekIdArr = implode(",", $this->getCimkeTermekIdArr( $val ) );
							$cond .= "({$q} in ({$idk}) or pkk.kategoria_id in ({$idk})) and "; 
							//die($q);
							break;
						case "datum": $cond .= "DATE_FORMAT( datum, '%Y') = {$val} and "; break;
						case "megye": 
							$val = urldecode($val);
							$cond .= "{$q} like '%{$val}%' and ";
							break;
						default : $cond .= "{$q} like '%{$val}%' and ";
					}
				}
			} 
		}
		if($cond){
			$cond = "where ".substr($cond, 0, strlen($cond)-4); //ha adminfelület nem kell aktiv = 1
		}
	 	//echo $cond."<br /><br />";

      return $cond;

   }

   	

	

	function rogzitKereses(){

		if (Jrequest::getvar('cond_nev')){

			$o = '';

			$o->nev = Jrequest::getvar('cond_nev');

			$o->datum = date('Y-m-d H:i:s');

			$this->_db->insertobject('#__wh_kereses',$o);

		}

	}



   function getKategoriaIdkByCimkeId($val){

	   $q = "select k.id from #__wh_kategoria as k 

	   inner join #__wh_cimke as c on k.nev = c.nev

	   where c.id = {$val}";

	   $this->_db->setquery($q);

	   $k_idk = $this->_db->loadresultarray();

	   if ($k_idk){

		   $ossz_kateg_id = '';

		   foreach ($k_idk as $k){

				$idk = implode(",", $this->getlftrgtosszes($k, "#__wh_kategoria") );

				$ossz_kateg_id .= ','.$idk;

				$ossz_kateg_id = trim($ossz_kateg_id,',');

		   }

		   $q = "select id from #__wh_termek where kategoria_id in ({$ossz_kateg_id})";

		

		   $this->_db->setquery($q);

			//echo $this->_db->getquery();

		   $termekek = $this->_db->loadresultarray();

	  		//print_r($termekek); die();

		 return implode(',',$termekek);

	   } else {return '1';}

	}

	

   function getCimkeTermekIdArr( $val ){

	$katnev = $this->getObj( "#__wh_kategoria", $val )->nev;

	 

	 $q = "select kapcsolo.termek_id from #__wh_cimke_kapcsolo as kapcsolo 

	 inner join #__wh_cimke as cimke on kapcsolo.cimke_id = cimke.id

	 where cimke.nev = '{$katnev}'

	 ";

	 //echo $q;

	 $this->_db->setQuery($q);

	 (array)$ret = $this->_db->loadResultArray();	

	 $ret[]=0;

	 //die( $this->_db->geterrorMsg() );

	 return $ret;	 

   }

   

	function getLftRgtOsszes($id, $table, $idF="id"){

		$q = "select lft, rgt from {$table} where {$idF} = {$id} limit 1";

		$this->_db->setQuery($q);

		$obj = $this->_db->loadObject();

		@$q = "select {$idF} from {$table} where lft >= {$obj->lft} and rgt <= {$obj->rgt}";

		$this->_db->setQuery($q);

		return $this->_db->loadResultArray();	

	}



	function setSessionVar($var, $value){

		@$sess =& JSession::getInstance();

		@$sess->set( $var, $value );

	}



	

	function setElsokep($item){

		$src = "-";		

		if( file_exists($src) ){

			$item->elsokep = "<img class=\"listakep\" src=\"{$src}\" />";

		}else{

			$item->elsokep = "";

		}

		return $item;		

	}



	function getSearchArr(){

		$arr = array();

		$obj = "";

		$obj->NEV = '<input name="cond_nev" value="'.JRequest::getVar("cond_nev") .'" />';

		$arr[] = $obj;

		$obj = "";		

		return 	$arr;

	}



	function getSearch(){

		$arr = $this->getSearchArr();

		ob_start();

		?>

		<table class="table_search">

			<tr> 

       			<?php

				foreach($arr as $a){

					foreach($a as $oszlnev => $ertek){

						?>

						<td><span class="search_nev"><?php echo JText::_("$oszlnev") ?>: </span><?php echo $ertek ?></td>

						<?php

					}

				}

				?>

			<td>

            <input type="submit" value=" <?php echo JText::_("KERESES") ?>" />

            </td>

			</tr>

		</table>



        <?php

		echo $this->xmlParser->getOrderHiddenFields();	

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;

	}

	

	function delObj($table, $id, $pk ="id" ){

		$q = "delete from {$table} where {$pk} = $id limit 1";

		$this->_db->setQuery($q);

		return $this->_db->query();

	}

	

	function getObj($table, $id, $pk ="id" ){

		$q = "select * from {$table} where {$pk} = $id limit 1";

		$this->_db->setQuery($q);

		return $this->_db->loadObject();

	}

	

	function getJog(){

		$jog = "";

					//echo'dsddasda';

					$this->_db->setQuery("select * from #__wh_beallitas where id = 1");

					//echo $this->_db->getquery();

					//print_r($this->_db->loadObject()); 

					$webshop_kat = unserialize($this->_db->loadObject()->webshop_kat);

					$kategoriak = array();

					$termekek = array();

					

					foreach( explode(",", $webshop_kat[$GLOBALS['whp_id']]) as $kat_id ){

						//echo $kat_id."<br />";

						$q = "select lft, rgt from #__wh_kategoria where id = {$kat_id} limit 1";

						$this->_db->setQuery($q);

						$kat = $this->_db->loadObject();

						$q = "select id from #__wh_kategoria where lft >= {$kat->lft} and rgt <= {$kat->rgt}";

						$this->_db->setQuery($q);

						$kategoriak = array_merge( $kategoriak, $this->_db->loadResultArray() );

					}

					$jog->kategoriak = $kategoriak;

					$kategoria_idk = implode(',',$kategoriak);

					

					$q = "select id from #__wh_termek where kategoria_id in ({$kategoria_idk})";

					$this->_db->setQuery($q);

					$jog->termekek = $this->_db->loadResultArray();

					//@$sess = jfactory::getsession(); 

					//@$sess->set("kategoriak", $jog->kategoriak );

					//print_r($jog->kategoriak);

					//die;

					//echo $this->_db->getQuery($q);

					//print_r($jog);

					//die;

		

		return $jog;

	}

	

	/*function getJog(){

		$jog = "";

		switch( $this->user->usertype ){

			case "termekmenedzser" : 

					//echo $this->user->usertype;

					$felh_kat = unserialize($this->beallitas->felh_kat);

					$kategoriak = array();

					foreach( explode(",", $felh_kat[$this->user->id]) as $kat_id ){

						//echo $kat_id."<br />";

						$q = "select lft, rgt from #__whp_kategoria where id = {$kat_id} limit 1";

						$this->_db->setQuery($q);

						$kat = $this->_db->loadObject();

						$q = "select id from #__whp_kategoria where lft >= {$kat->lft} and rgt <= {$kat->rgt}";

						$this->_db->setQuery($q);

						$kategoriak = array_merge( $kategoriak, $this->_db->loadResultArray() );

					}

					$jog->kategoriak = $kategoriak;

				break;

			default :

				$q = "select id from #__whp_kategoria ";

				$this->_db->setQuery($q);

				$jog->kategoriak = $this->_db->loadResultArray() ;

		}

		return $jog;

	} */

	

	function kivalaszt($fName="", $table=""){

		$cid = JREquest::getVar("cid", array() );

		//print_r($cid);

		$kapcsolodo_id = JRequest::getVar("kapcsolodo_id", $this->getSessionVar("kapcsolodo_id") );

		$q = "select * from {$table} where id = {$kapcsolodo_id} ";

		$this->_db->setQuery( $q );

		$obj = $this->_db->loadObject();		

		//die($q);

		@$db_kapcsolodo_arr = explode(",", $obj->$fName);

		if( is_array( $db_kapcsolodo_arr ) ){

			$obj->$fName = array_merge( $cid , explode(",", $obj->$fName) );

		}else{

			$obj->$fName = $cid;

		}

		$obj->$fName = array_unique( $obj->$fName );

		$obj->$fName = ",".implode(",", $obj->$fName ).",";

		$obj->$fName = str_replace(",,", ",",$obj->$fName );

		

		$this->_db->updateObject($table, $obj, "id" );

		//print_r($obj);

		//die;

		//echo $sablon_id;

	}



	function getSearchSelect($name, $table, $o=""){

		$v = $this->getSessionVar($name);

		$this->_db->setQuery("select `name` as `option`, id as `value` from {$table} order by `name` asc");

		$rows = $this->_db->loadObjectList();

		if(!$o){

			$o="";

			$o->option="";

			$o->value="";

		}

		array_unshift($rows, $o);

		return JHTML::_('Select.genericlist', $rows, $name, array(), "value", "option", $v);

	}



	function saveImages($id){

		global $mainframe;	

		$db = JFactory::getDBO();

		if(!file_exists($this->uploaded)){

			mkdir($this->uploaded);

		}

		for($n=1; $n<=$this->images; $n++){

			$imgname ="{$this->uploaded}/{$id}_{$n}.jpg";

			//$imgname_th ="{$this->uploaded}/{$id}_{$n}.jpg";

			//echo $imgname; exit;

			if(JRequest::getVar("torol_img_{$n}")){

				unlink($imgname);

				//die($imgname);//exit;

				//$mainframe->redirect("index.php?option=com_ingatlan&task=edit_ingatlan&id={$id}");

			}else{

				$tmp_name = $_FILES["img_{$n}"]["tmp_name"];

				if($tmp_name){

					$filename = "{$this->uploaded}/{$id}_{$n}.jpg";

					move_uploaded_file($tmp_name, $filename);

					chmod($filename, 0777);

				}

			}

		}

	}	

	

	function getImages(){

		ob_start();

		//die($this->images."----");

		echo '<table>';

		for($n=1; $n<=$this->images; $n++){

			(!@$this->_data->id) ? $id= $this->getSessionVar("kapcsolodo_id") : $id = $this->_data->id;

			$imgname ="{$this->uploaded}/{$id}_{$n}.jpg";

			//echo file_exists($imgname)." -- - - - -<br />";

			if(file_exists($imgname)){

				?>



<tr>

  <td class="key"><?php echo "{$n}. ".JText::_("image"); ?></td>

  <td><img src="<?php echo $imgname ?>" style="width:80px"  />

    <input type="checkbox" name="<?php echo "torol_img_{$n}" ?>" value="1" />

    <?php echo JText::_("delete") ?></td>

</tr>

<?php

			}else{

				?>

<tr>

  <td class="key"><?php echo "{$n}. ".JText::_("image"); ?></td>

  <td><input type="file" name="<?php echo "img_{$n}" ?>" /></td>

</tr>

<?php

			}

		}

		echo '</table>';	

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;	

	}



	function store()

	   {

	   //die(str_replace("#__", "", $this->table)." *********");

		$row =& $this->getTable( str_replace("#__", "", $this->table) );

		//print_r($row);

		//die;

		foreach($this->getFormFieldArray() as $parName){//ha tömböt kell menteni

			$val = JRequest::getVar($parName,"", "",2,2,2);

			//echo $parName."---<br />";

			if(is_array($val)){

				$data[$parName] = ",".implode(",", $val).",";

			}else{

				$data[$parName] = $val;

			}

		}



		  if (!$row->bind($data)) {

			 $this->setError($this->_db->stderr());

			 return false;

		  }

			//print_r($data);

			//die;

			

		  // Make sure the record is valid

		  if (!$row->check()) {

			 $this->setError($this->_db->stderr());

			 return false;

		  }

	

		  // Store the table to the database

		  //print_r($row); exit;

		  if (!$row->store()) {

			 $this->setError( $row->getError() );

		   return false;

		  }else{

			//die($this->_db->getQuery()); 

			//echo "--------------".;

		   		$id = $this->_db->insertId();

			 if(!$id){

			 $id = $this->getSessionVar("id");

		   }

		  }

			//die("--{$id}");

			if( method_exists( $this->xmlParser, "saveImages") ){

				$this->xmlParser->saveImages($id);

			}

			if( method_exists( $this->xmlParser, "saveFiles") ){

				$this->xmlParser->saveFiles($id);

			}

			if( method_exists( $this->xmlParser, "saveSpecifikacio") ){

				$this->xmlParser->saveSpecifikacio($id);

			}

			if( method_exists( $this->xmlParser, "saveGoogleKoord") ){

				$this->xmlParser->saveGoogleKoord($id);

			}



 			//die("{$id} - -");	

		  return $id;

	  }   	



	function setId($id)

	{

		// Set id and wipe data

		$this->_id		= $id;

		$this->_data	= null;

	}//function

	

	function deleteSession(){

		$this->xmlParser->deleteSession();

	}

	

	function getData()

	{

		// Load the datadie;

		//exit;

		if (empty( $this->_data )) {

			$query = "SELECT * FROM {$this->table} WHERE id = {$this->_id}";

			

			$this->_db->setQuery( $query );

			$this->_data = $this->_db->loadObject();

			//die($this->table);

		}

		if (!$this->_data) {

			$this->_data = new stdClass();



			$data = JRequest::get( 'post' );

			foreach($data  as $v => $n){

				$this->_data->$v = $n;

			}

			//print_r($this->_data);

		}

		

		$this->setMandatoryFields();

		//print_r($_REQUEST);exit;

		//print_r($this->_data);exit;

		return $this->_data;

	}//function



	function getFormByGroup($group){

		return $this->xmlParser->getFormByGroup($group);

	}

	

	function getAllFormGroups(){

		return  $this->xmlParser->getAllFormGroups();

	}

	

	function rendelesAktiv($rendeles_id){

		//$rendeles_id = JRequest::getInt("rendeles_id", 0);

		$db = JFactory::getDBO();

		$q = "select id from #__st_rendeles where id = {$rendeles_id} and datum >= now()";

		$db->setQuery($q);

		if($db->loadResult()){

			return true;

		}else{

			return false;

		}

	}

	

	

	function getNyelvCond($cond, $val){

		//foreach(array("nyelv1","nyelv2","nyelv3") as $ny){

			$cond.="(";

			foreach($val as $v){

				if($v){

					if(array_search($v,$val)==(count($val)-1)){

						"(u.nyelv1 like '%{$v}%' or u.nyelv2 like '%{$v}%' or u.nyelv3 like '%{$v}%' ) and ";

					}else{

						//$cond.="$q like '%{$v}%' or ";

						"(u.nyelv1 like '%{$v}%' or u.nyelv2 like '%{$v}%' or u.nyelv3 like '%{$v}%' ) and ";

					}

				}

			}

			$cond.=") and ";

		//}

		return $cond;

		

	}



	function getTermekIds($id){

		$q="select termek_id from #__st_rendeles where id = {$id}";

		$this->_db->setQuery($q);

		$str = $this->_db->loadResult();

		$str = substr($str,1,strlen($str)-2);

		return $str;

	}

	  

	function getOrderHtml($field, $arr){

      ob_start();

      $val = Jrequest::getInt($field."_order",1);

      //echo $val;

      $orderField = Jrequest::getWord("orderField");

      if($orderField == $field."_order"){

         if($val == 1){

            $val=2;

            $title = $this->order_fields[$field]["title"][1];

            $img="<img src=\"components/com_whp/images/nyilak2.gif\" >";

         }else{

            $val = 1;

            $title = $this->order_fields[$field]["title"][0];  

            $img="<img src=\"components/com_whp/images/nyilak1.gif\" >";

         }

      }else{

         $title = $this->order_fields[$field]["title"][2];

         $img="<img src=\"components/com_whp/images/nyilak3.gif\" >";

      }

      //echo $title." ********";

      $java =

         "javascript:document.getElementById(\"keresoForm\").{$field}_order.value={$val};    document.getElementById(\"keresoForm\").orderField.value=\"{$field}_order\"; document.getElementById(\"keresoForm\").submit()";

      ?>

<a title="<?php echo $title ?>" href='<?php echo $java; ?>'><?php echo $img ?> <?php echo $arr["nev"] ?></a>

<?php

      $ret = ob_get_contents();

      ob_end_clean();

      return $ret;

   }

  

   function getOrderBlock(){

      ob_start();

      ?>

<table class="table_rendezo" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <?php

            foreach($this->order_fields as $f =>$arr){

                ?>

    <td><?php echo $rendMezo = $this->getOrderHtml($f, $arr); ?></td>

    <?php

            }

            ?>

  </tr>

</table>

<?php

      $ret = ob_get_contents();

      ob_end_clean();   

      return $ret;

   }



	function getFormFieldArray(){

		$formFieldArray=array();

		foreach ($this->xmlParser->dom->getElementsByTagname('param') as $element ){

			if(is_a($element, "DOMElement")){

				$name = $element->getAttribute('name');

				//echo $name."<br />";

				$formFieldArray[]=$name;

			}

		}

		//print_r($formFieldArray); exit;

		return $formFieldArray;

	}	

	

	function getItemObjectFromXml( &$item, $a, $var){

		//print_r($item);

		//exit;

		foreach($var as $v){

			$obj="";

			$node = $this->getNode("name", $v );

			$obj ->label = $node->getAttribute("label");

			$obj ->short = $item->$v;

			$obj ->description = $node->getAttribute("description");

			$obj ->name = $v;



			//echo $node->getAttribute("type");

			switch($node->getAttribute("type")){

				case "list" :

					foreach($node->childNodes as $e_){

						if(is_a($e_, "DOMElement")){

							if($e_->getAttribute('value') == $item->$v){

								$obj ->textContent = $e_->textContent;

								$short = $e_->getAttribute("short");

								($short) ? $obj ->short = $short : $obj ->short = " ----";

							}

						}

					}

					break;

				default:

				$obj ->textContent = $item->$v;

			}

			$item->$v=$obj;

		}

	}



	function getNode($attribute, $value ){

		foreach ($this->dom->getElementsByTagname('param') as $element ){

			//echo $element->getAttribute($attribute);

			if($element->getAttribute($attribute)==$value){

				return $element;

			}

		}

	}

	

	function cron(){

		$cronfile = dirname(__FILE__)."/cron.php";

		if (file_exists( $cronfile ))

			include_once( $cronfile );

		if ($last_cron_date != date("Ymd")  /* || 1 */ ){

			//echo "hello cronfile";

			$this->manageRecallMails();

			$last_cron_date = date("Ymd");

		}

		$Fnm = $cronfile;

		$inF = fopen($Fnm,"w"); 

		fwrite($inF,'<?php $last_cron_date='.$last_cron_date.';?>');

		fclose($inF); 

	}

	

	function getUserByAdId($id){

		$q="select u.* from #__pad as p inner join #__users as u on p.user_id = u.id 

		where p.id = {$id} ";

		$this->_db->setQuery($q);

		$row = $this->_db->loadObject();

		return $row;

	}



	function getPropsById($o_){

		$q="select x.name as value, prop.name, prop.extension from #__pad_prop_xref as x 

		inner join #__pad as p on x.pad_id = p.id

		inner join #__pad_prop as prop on x.prop_id = prop.id 

		where p.id = {$o_->id} ";

		$this->_db->setQuery($q);

		$o_->props = $this->_db->loadObjectList();

		//echo $this->_db->getQuery();

		//echo $this->_db->getErrorMsg();

		//print_r($o_->props); exit;

		return $o_;

	}



	function getFileName($id, $n){

		return "{$this->uploaded}/{$id}_{$n}.jpg";

	}



	function getFileName2($id, $n){

		return "administrator/components/com_pad/images/{$id}_{$n}.jpg";

	}



	function checkMandatoryFields(){

		$errors = array();

		//print_r($this->_data->mandatory_fields);

		//exit;

		foreach($this->_data->mandatory_fields as $f){

			$index = array_search($f,$this->_data->mandatory_fields);

			$func = $this->_data->mandatory_functions[$index];

			if( !$this->$func($f) ){

				$errors[]=$f;

			}

		}

		//print_r($errors); exit;

		return $errors;

	}

	

	function mandatoryCheck($name, $ajaxVal="" ){

		//exit;

		if(!$ajaxVal){

			$val =JRequest::getVar($name, 0);

		}else{

			$val = $ajaxVal;

		}

		if(is_array($val)) return $val[0]; else return $val;

	}

	

	function mandatoryCond($name){

		if(JRequest::getVar($name, 0)<>1) return 0;

		return 1;

	}

	

	function getMandatoryHidden($name, $mandatory_function, $mandatory_text){

		ob_start();

		?>

<input type="hidden" name="mandatory_fields[]" value="<?php echo $name ?>" />

<input type="hidden" name="mandatory_functions[]" value="<?php echo $mandatory_function ?>" />

<input type="hidden" name="mandatory_texts[]" value="<?php echo $mandatory_text ?>" />

<?php

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;

	}



	function setMandatoryFields(){

		$arr=array("mandatory_fields","mandatory_functions","mandatory_texts");

		$mandatory_fields = JRequest::getVar("mandatory_fields");

		$mandatory_functions = JRequest::getVar("mandatory_functions", "");	

		$mandatory_texts = JRequest::getVar("mandatory_texts", "");			

		if(is_array($mandatory_fields)){

			foreach($arr as $a ){

				foreach($$a as $v){

					//print_r($$a);

					$this->_data->$a = $$a;

				}

			}

		}

		//print_r($this->_data);//exit;

	}



	function delete(&$jTable)

	{

		//die($jTable);

		$cids = JRequest::getVar( 'cid', array(0), '', 'array' );

		$row =& $this->getTable($jTable);

		//print_r($cids);

		//die();

		//print_r($row);exit;

		if (count( $cids ))

		{

			foreach($cids as $cid) {

				if (!$row->delete( $cid )) {

					$this->setError( $row->getError() );

					return false;

				}

			}//foreach

		}

		return true;

	}//function



	function getCatSelect(){

		$this->catTree(0);

		$this->_data->kategoria_id = explode(",", $this->_data->kategoria_id);

		return JHTML::_('Select.genericlist', $this->catTree, "kategoria_id[]", 

		array("multiple"=>"multiple", "class"=>"button"), "kategoria_id", "kategoria_name", $this->_data->kategoria_id);

	}

	

	function catDepth($kategoria_child_id){

		$q = "select kategoria_parent_id from #__vm_kategoria_xref where kategoria_child_id = {$kategoria_child_id}";

		$this->_db->setQuery($q);

		$res = $this->_db->loadResult();

		if($res){

			$this->depth++;

			$this->margo.="&nbsp;&nbsp;&nbsp;&nbsp;";

			$this->catDepth($res);

		}

	}

	

	function catTree($parent){

		$q = "select * from #__vm_kategoria as c inner join #__vm_kategoria_xref as x 

		on c.kategoria_id=x.kategoria_child_id

		where x.kategoria_parent_id = {$parent} and c.kategoria_publish = 'Y' order by c.list_order";

		$this->_db->setQuery($q);

		$roic = $this->_db->loadObjectList();

		if(count($roic)){

			foreach($roic as $r){

				$this->depth=1;

				$this->margo="";

				$this->catDepth($r->kategoria_child_id);

				//echo "{$this->margo}[{$this->depth}]&nbsp;{$r->kategoria_name}<br />";

				$o_="";

				$o_->kategoria_name = "{$this->margo}[{$this->depth}]&nbsp;{$r->kategoria_name}";

				$o_->kategoria_id = $r->kategoria_child_id;

				$this->catTree[]=$o_;

				$this->catTree($r->kategoria_child_id);

			}

		}

	}



	function setFields($table){

		$db=JFactory::getDBO();

		$fields_ = $db->getTableFields($table, 1);

		$fields="";

		foreach($fields_[$table] as $f => $v){

			//echo $f."<br />";

			$this->tableFields->$f=null;

		}

	}

	

	function getSessionVar($var){

		@$sess =& JSession::getInstance();

		//$o_ = $sess->get("padData");

		return $sess->get($var);

		//print_r($o_); exit;

		//return $o_->$var;

	}

}

?>

