<?php

defined( '_JEXEC' ) or die( '=;)' );

class xmlRendeles extends xmlParser{

	function getFizetesiMod($node){

		$this->document->addScriptDeclaration('$(document).ready(function() {fizetesiMod(); getKosar()});');

		$name = $node->getAttribute('name');

		$value = $this->getAktVal($name);

		foreach( $node->childNodes as $e_ ){

			if( is_a( $e_, "DOMElement") ){

				$obj="";

				//print_r($e_);

				$obj->value = $e_->getAttribute('value');

				$obj->option = jtext::_($e_->textContent);

				$roic[]=$obj;

			}

		}

		//$f_=

		if($roic[0]->value){

			$o="";

			$o->value=$o->option="";

			//array_unshift($roic,$o);

		}

		if( strstr($this->user->name, "antika" ) || strstr($this->user->name, "teszt" ) ){

			$ret = JHTML::_('Select.genericlist', $roic, $name,	array( "class"=>"{$name}_ alapinput", "onchange"=>"fizetesiMod()" ), "value", "option", $value);

		}else{

			$ret = jtext::_("TESZTELES_ALATT");		

		}

		$ret .= ""; 

		//$this->document->addScript('http://ajax.microsoft.com/ajax/beta/0911/MicrosoftAjax.js');

		return $ret;

	}



		function getUserEmail( $node ){

		ob_start();

		$name = $node->getAttribute('name');

		$label = $node->getAttribute('label');
		$id = Jfactory::getuser()->id;
		$q = "select email from #__users where id = {$id}";
		$db = jfactory::getdbo();
		$db->setquery($q);
		$value = $db->loadresult();

		if ($value == ''){$value= $this->getAktVal($name);}

		echo "<input class=\"{$name} alapinput\" id=\"{$name}\" name=\"{$name}\" type=\"text\" value=\"{$value}\" >";

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;   			

	}


	

	function getOrderDbField($node){

		$name = $node->getAttribute('name');

		$value = $this->getAktVal($name);

		$label = $node->getAttribute('label');		

		$subtype = $node->getAttribute('subtype');

		if (!$subtype){$subtype = "text";}

		$user = Jfactory::getuser();

		if ($user->id){
			
			$q = "select * from #__wh_felhasznalo where user_id = {$user->id}";
			
			$this->_db->setquery($q);
			
			$felhasznalo = $this->_db->loadobject();
			
			
			
			$q = "select * from #__wh_rendeles where user_id = {$user->id} order by id desc limit 1";

			$this->_db->setquery($q);

			if ($r = $this->_db->loadobject()){

				parse_str($r->szallitasi_cim,$szallitasi_cim);

				parse_str($r->szamlazasi_cim,$szamlazasi_cim);

				switch ($name){

					case 'sz_nev':

					case 'sz_irszam':

					case 'sz_varos':

					case 'sz_utca':

						$value = $szallitasi_cim[$label];

						break;	

					

					case 'szamlazasi_nev':

					case 'irszam':

					case 'varos':

					case 'utca':

						$value = $szamlazasi_cim[$label];

						break;	

					case 'telefon':
						$value = $felhasznalo->telefon;
						break;
					default:

						$value = $r->$name;

				}

			} else {

				$q = "select * from #__wh_felhasznalo where user_id = {$user->id}";

				$this->_db->setquery($q);

				if ($u = $this->_db->loadobject()){

					//print_r($u);

					//die();

					switch ($name){

						case 'szamlazasi_nev':

						case 'sz_nev':

							@$value = $this->user->name;;

							break;

						case 'telefon':
							$value = $felhasznalo->telefon;
							break;

						default: @$value = $u->$name;

					}

				

				}



			}

		} 	

		ob_start();

		switch ($subtype){

			case 'textarea':

				?>

      			<textarea cols="30"  id="<?php echo $name ?>" rows="6" name="<?php echo $name ?>" class="alapinput"  ><?php echo $value ?></textarea>

     			<?php

	

				break;

			default:

				?>

      			<input id="<?php echo $name ?>" type="<?php echo $subtype ?>" class="alapinput" name="<?php echo $name ?>" value="<?php echo $value ?>" />

     			<?php

		}

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;	



	}





	function getEgyezesGomb($node){

		ob_start();

		$name = $node->getAttribute('name');

		$label = $node->getAttribute('label');

		$value = $this->getAktVal($name);

		//echo "<input name=\"{$name}\" class=\"alapinput_\" type=\"button\" onclick=\"MasolSzallitasiAdatok();\" value=\"".jtext::_("MASOL")."\" />";

		echo "<a href=\"javascript:;\" class=\"btn_szallitasimasol\" onclick=\"MasolSzallitasiAdatok();\">".jtext::_("MASOL")."</a>";

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;

	}

	function getKupon( $node ){
		$name = $node->getAttribute('name');
		$value = $this->getAktVal($name);
		$ret  = "";
			$ret .= "<table><tr><td>";
		$ret .= "<input class=\"{$name}\" id=\"{$name}\" name=\"{$name}\" type=\"text\" value=\"{$value}\" >";
			$ret .= "</td><td>";
		//$ret .= "<input value=\"".jtext::_( "ELLENORIZ_AZONOSITO_KOD" )."\" type=\"button\" onclick=\"ellenorizKupon()\" >";	
		$ret .= "<a class=\"btn_kupon\" href=\"javascript:;\" onclick=\"ellenorizKupon()\">".jtext::_( "ELLENORIZ_AZONOSITO_KOD" )."</a>";		
			$ret .= "</td></tr></table>";
		$ret .= "<span id=\"ajaxContentKupon\" ></span>";
		$ret .=jtext::_("KUPON_INFO");
		return $ret;
	}

/*	function getTelepulesId( $node ){

		

		$name = $node->getAttribute('name');

		$value = $this->getAktVal($name);

		$megye = $this->getAktVal('megye');

		$q = "select megye as `value`, megye as `option` from #__wh_atvhely as atv

		inner join #__wh_telepules as t on t.id = atv.telepules_id group by megye order by megye asc";

		$this->_db->setquery($q);

		$rows = $this->_db->loadObjectList();

		$o="";

		$o->option = jtext::_("KEREM_VALSSZON_MEGYET");

		$o->value = "";

		array_unshift( $rows, $o );

		$ret = "";

		$ret .= JHTML::_( 'Select.genericlist', $rows, "megye", array("class"=>"alapinput cim", "onchange"=>"getTelepules( )" ), "value", "option", $megye );

		//$this->document->addscriptdeclaration("window.addEvent(\"domready\", function(){getTelepules('{$value}');})");

		$ret .= "<div id=\"ajaxContentTelepules\"></div>";		

		return $ret;

	} */





	function getHiddenDatum($node){

		ob_start();

		$name = $node->getAttribute('name');

		$value = $this->getAktVal($name);		

		?>

        <input type="hidden" name="<?php echo $name ?>" value="<?php echo date("Y-m-d H:i:m", time() ) ?>" />

        <?php

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;		

	}



	function getUserIdHidden(){

		ob_start();

		$user = JFactory::getUser();

		?><input type="hidden" name="user_id" value="<?php echo $user->id ?>" />

        <?php

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;

	}



	

	function getFizetes($node){

		ob_start();

		$name = $node->getAttribute('name');

		$value = $this->getAktVal($name);

		echo JText::_("UTALAS_VAGY_PENZTARI_BEFIZETES");

		?>

        <input name="<?php echo $name ?>" value="<?php echo JText::_("ELORE_UTALAS") ?>" type="hidden" />

		<?php

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;

	}



	function getSzallitasiMod($node ){

		$this->document->addScriptDeclaration("\$j(document).ready(function() { getKosar() });");



		$name = $node->getAttribute('name');

		//$value = $this->getAktVal($name);

		

		@$value = $this->getSessionVar("szallitas");

		
		//$value = $this->getSessionVar($name);		

		//echo "{$value}----";

		foreach($node->childNodes as $e_){

			if(is_a($e_, "DOMElement")){

				$obj="";

				//print_r($e_);

				$obj->value = $e_->getAttribute('value');

				$obj->option = jtext::_($e_->textContent);

				$roic[]=$obj;

			}

		}

		//$f_=

		if($roic[0]->value){

			$o="";

			$o->value=$o->option="";

			//array_unshift($roic,$o);

		}

		$ret = JHTML::_('Select.genericlist', $roic, $name,	array( "class"=>"{$name}_ alapselect", "onchange"=>"getAjaxMezok()" ), "value", "option", $value);

		$ret .= ""; 

		//$this->document->addScript('http://ajax.microsoft.com/ajax/beta/0911/MicrosoftAjax.js');

		return $ret;

	}



	function getOsszesen( $node ){

		ob_start();

		$id = $this->getAktVal("id");

		$rendeles = $this->getObj("#__whp_rendeles", $id);

		parse_str($rendeles->osszesen_adatok);

		$arr = array();

		foreach($this->getGroupElementNames( "osszesen_valtozok" ) as $v ){

			$o="";

			$o->PARAM = jtext::_(strtoupper($v));

			$o->ERTEK = $$v;

			$arr[]=$o;

		}

		$listazo = new listazo($arr, "");

		echo $listazo->getLista();

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;   			

	}

	

	function getVasarloAdatok( $node ){

		ob_start();

		$id = $this->getAktVal("id");

		$rendeles = $this->getObj("#__whp_rendeles", $id);

		parse_str($rendeles->felhasznaloi_adatok);

		$arr = array();

		foreach($this->getGroupElementNames( "felhasznalo_valtozok" ) as $v ){

			$o="";

			$o->PARAM = jtext::_(strtoupper($v));

			$o->ERTEK = $$v;

			$arr[]=$o;

		}

		$listazo = new listazo($arr, "");

		echo $listazo->getLista();

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;   			

	}

	

	function getRendelesiAdatok( $node ){

		ob_start();

		$id = $this->getAktVal("id");

		$rendeles = $this->getObj("#__whp_rendeles", $id);

		echo "<h2>".jtext::_("SZAMLAZASI_ADATOK")."</h2>";

		echo $this->getDecodeXmlString("data3", $rendeles->szamlazasi_cim);

		echo "<h2>".jtext::_("SZALLITASI_ADATOK")."</h2>";		

		echo $this->getDecodeXmlString("data5", $rendeles->szallitasi_cim);

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;   			

	}



	function getDecodeXmlString($group, $adat){

		$group = $this->getGroup($group);

		$ret = "";

		parse_str($adat);

		foreach ($group->childNodes as $element ){

			if(is_a($element, "DOMElement")){ 

				$v = $element->getAttribute('label');

				if(!in_array($v, array("SZAMLAZASI_SZALLITASI_CIM_EGYEZIK"))){

					$l = jtext::_( $v );

					$v = $$v;

					if($v)$ret.= "{$l}: {$v}<br />";

				}

			}

		}	  

		return $ret;

	}



	function getTetelek( $node ){

		ob_start();

		$id = $this->getAktVal("id");

		$q = "select * from #__whp_tetel where rendeles_id = {$id}";

		$this->_db->setQuery($q);

		$tetelek = $this->_db->loadObjectList();

		$arr = array();

		foreach($tetelek as $t ){

			$o="";

			foreach($this->getGroupElementNames( "tetel_valtozok" ) as $v ){

				$$v="";

			}

			foreach($this->getGroupElementNames( "tetel_valtozok" ) as $v ){

				//echo $v."<br />";

				parse_str($t->tetel_adatok);

				

				$fej = jtext::_(strtoupper($v));

				@$o->$fej = $$v;

			}

			$arr[]=$o;

		}

		$listazo = new listazo($arr, "");

		echo $listazo->getLista();

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;   			

	}

	

	function getKiszallitasAr( $node ){

		ob_start();

		$name = $node->getAttribute('name');

		$value = $this->getAktVal($name);

		if(!$value || 1){

			$kalkulaltAr = $this->getKalkulaltAr($this->getOsszesTomeg());

			$value = $kalkulaltAr;

		}

		?>

		<input name="<?php echo $name ?>" value="<?php echo $value ?>" type="text" />

		<?php echo jtext::_("OSSZES_TOMEG").": ".$this->getOsszesTomeg() ?> kg

		<?php

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;   			

	}

	

}

?>

