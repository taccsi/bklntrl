<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlParser extends modelBase{
	function isPopup(){
		if( JRequest::getVar("tmpl") == "component" ){
			return 1;
		}else{
			return 0;
		}
	}  

	function getDbCim($item){
		$q = "select count(cim_id) as db from #__mer_cim_lista_kapcs where lista_id = '{$item->value}' ";
		$this->_db->setQuery($q); 
		$res = $this->_db->loadResult();
		$item->option .=" ({$res} db email)";
		return $item;
	}	

	function getListaIdk($node){ 
		ob_start();
		$cid = jrequest::getvar('cid');
		$id = $cid[0];
		$name = $node->getAttribute('name');
		//$value=$this->getAktVal($name);
				
		$q = "select id as `value`, nev as `option` from #__wh_hirlevel_lista order by nev";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();
		//print_r($rows);
		//array_map(array($this, "getDbCim"), $rows);
		
		$q = "select id from #__wh_hirlevel_lista as l
		inner join #__wh_hirlevel_cim_lista_kapcs as k on l.id = k.lista_id where k.cim_id = {$id}";
		$this->_db->setquery($q);
		$value = $this->_db->loadresultarray();
		//print_r($value);
		//echo $this->_db->getQuery();
		//die; 
		$o="";
		$o->value = $o->option = "";
		array_unshift($rows,$o);
		
		echo JHTML::_('Select.genericlist', $rows, $name.'[]', array("multiple"=>"multiple","class"=>"alapinput multiple"), "value", "option", $value);		
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function getAfaSelect2( $name, $value = 1, $event = "onchange", $js = "javascript:;" ){
		ob_start();
		//die("{$name}");
		$q = "select id as `value`, ertek as `option` from #__wh_afa";
		$this->db->setQuery($q);
		$rows = $this->db->loadObjectList();
        echo JHTML::_( 'Select.genericlist', $rows, $name, array( "class"=>"multiple_search", $event=>$js ), "value", "option", $value );
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function getUploadify($node) {
		$name = $node->getAttribute('name');
		$value=$this->getAktVal($name);
		ob_start();
		?>
		<div class="demo-box">
        	<div id="status-message">Select some files to upload:</div>
			<div id="custom-queue"></div>
			<input id="custom_file_upload" type="file" name="Filedata" />        
		</div>

		<?
		$ret = ob_get_contents();
		ob_end_clean();
		
		return $ret;
		// die($readonly);
	}

	function getKapcs_kategoria_id($node) {
		$name = $node->getAttribute('name');
		$value=$this->getAktVal($name);
		(is_array($value)) ? $value : $value = explode(",", $value);
		$arr = array();
		ob_start();
		$kategoriafa = new kategoriafa(array(0), 5000, $this->getSzuloKategoria()->id);
		$o="";
		$o->option = $o-> value = "";
		array_unshift($kategoriafa ->catTree, $o);
		echo JHTML::_('Select.genericlist', $kategoriafa ->catTree, $name."[]", 
		array("class"=>"alapinput", "readonly" => "readonly",
		"multiple"=>"multiple"), "value", "option", $value);
		$ret = ob_get_contents();	  
		ob_end_clean();
		return $ret;
		// die($readonly);
	}
	
	function getFormByGroup($group){
		ob_start();
		$html="";
		$errorFields = JRequest::getVar("errorFields", array() );
		//echo $task; exit;
		$id = $this->getAktVal("id");	
		//die("{$id} -----");
		foreach ($this->dom->getElementsByTagname('params') as $element ){
			$g_ = $element->getAttribute('group');
			if($g_ == $group){
				foreach($element->childNodes as $e_){
					if(is_a($e_, "DOMElement")){
						$name = $e_->getAttribute('name');
						$label = JText::_($e_->getAttribute('label'));
						$type = $e_->getAttribute('type');
						$mandatory = JText::_($e_->getAttribute('mandatory'));
						$description = JText::_($e_->getAttribute('description'));
						$default = JText::_($e_->getAttribute('default'));
						$idKell = $e_->getAttribute('idKell');
						($mandatory) ? $mandatory_sign = "*" : $mandatory_sign = "";
						if( $e_->getAttribute( 'popup' ) == -1 ){
							$popup = 0;
						}else{
							$popup = 1;
						}

						(in_array($name, $errorFields)) ? $mandatory_text = JText::_($e_->getAttribute('mandatory_text')) : $mandatory_text = "";
						$class = "class=\"{$name}\"";
						if($type != "hidden"){
							if( $this->megjelenhet($type, $popup) ){
								$html.="<tr><td valign=\"top\" class=\"key\">{$label}{$mandatory_sign}</td><td class=\"paramlist_value\">";
							}
						}	
						$value = $this->getAktVal($name);
						//die($value."*------");
						//($this->data->$name) ? : $value = $this->getSessionVar($name);
						(!$value) ? $value= $default : $value = $value;
						//echo $value."<br />";
						if($this->megjelenhet($type, $popup) ){
							switch($type){
								case "sw" : $html.=$this->getSw($name, $e_, $value).$description; break;								
								case "dbCheckbox":
									$html.= $this->getDbCheckbox($name, $e_, $value).$description; break;								
									
								case "dbListMultiple":
									$html.=$this->getDbListMultiple($name, $e_, $value).$description; break;									
									
								case "dbList":
									$html.=$this->getDbList($name, $e_, $value).$description; break;									
								case "list" :
									$html.=$this->getSelect($name, $e_, $value).$description; break;
								case "multiple" :
									$html.=$this->getMultipleSelect($name, $e_, $value).$description; break;
	
								case "checkboxlist" :
	
									$html.=$this->getCheckboxList($name, $e_, $value).$description; break;
	
								case "textarea" :
									
									$html.="<textarea {$class} rows =\"10\" cols=\"30\" name=\"{$name}\" >{$value}</textarea>";								
									break;
								case "editor" :
									$editor =& JFactory::getEditor();
									$html.= $editor->display($name, $value, "500", 300, 300, 20, 0 ); 
									break;
									
								case "spec" :
											if($idKell && !$id){
												$html.=	jtext::_("A FUNKCIO ELERESEHEZ MENTES SZUKSEGES");
											}else{
												$func = $e_->getAttribute('function');
												$html.= $this->$func($e_);
											}
									break;
								case "calendar" :
									$html.= JHTML::_('calendar',$value, $name, $name, $format = '%Y-%m-%d',
									array("class"=>"{$name}_ alapinput", "size"=>'25',  'maxlength'=>'19')); break;
								case "submit":
									//$html.=$this->getSelect($name, $e_, $class); 
									$html.="<input {$class} name=\"{$name}\" type=\"submit\" value=\"{$label}\" /> {$description}"; break;						
								case "file":
									$html.= $this->getFile( $e_).$description; break;
								case "hidden":
									//$html.=$this->getSelect($name, $e_, $class);
									$html.="<input {$class} name=\"{$name}\" id=\"{$name}\" type=\"hidden\" value=\"{$value}\" /> {$description}"; break;				
								case "checkbox":
									$html.=$this->getCheckbox($name, $e_, $value).$description; break;

								case "radio":
									//$html.=$this->getSelect($name, $e_, $class);
									($value) ? $checked = "checked=\"checked\"" : $checked = "";
									
									$html.="<input {$class} name=\"{$name}\" type=\"checkbox\" value=\"1\" {$checked} /> {$description}"; break;				
								default:
									//$html.=$this->getSelect($name, $e_, $class);
									$class = "class=\"{$name} alapinput\"";
									$html.="<input {$class} name=\"{$name}\" id=\"{$name}\" type=\"text\" value='{$value}' /> {$description}";								
							}
						}//megjelenhet vége
						if($mandatory) $html.= $this->getMandatoryHidden($name, $mandatory, $mandatory_text);
						if($type<>"hidden"){
						$html.="<span class=\"span_error\">{$mandatory_text}</span><td></tr>";
						}
						//echo $e_->tagName."<br />";
					}
				}
			}
		}
		if($html) $html="<table class=\"paramlist admintable\">{$html}</table>";
		echo $html;
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function getFile( $node ){
		$name = $node->getAttribute( "name" );
		$kapcsoloNev = $node->getAttribute( "kapcsoloNev" );
		$dir = $node->getAttribute( "dir" );
		$megengedettFajlok = $node->getAttribute( "megengedettFajlok" );
		( file_exists("administrator") ) ? $x_ ="" : $x_ = "../" ;
		$dir = $x_."media/".$node->getAttribute( "dir" );
		//die($x_);
		$ret = "";
		$kapcsolo_id = $this->getAktVal("id");
		$ret.="<input type=\"file\" id=\"{$name}\" name=\"{$name}\" class=\"{$name}_\"> ";
		if ($o_ = $this->letezikFajl( $kapcsolo_id, $kapcsoloNev, $name ) ){
			$link = "{$dir}/{$o_->fajlnev}.{$o_->ext}";
			$ret.="<a target=\"_blank\" href=\"{$link}\">{$o_->eredetiNev}.{$o_->ext}</a>";
			$ret.="<input name=\"torolFajlokArr[]\" type=\"checkbox\" value=\"{$o_->id}\" >".jtext::_("TORLES");
		}
		return $ret;
	}

	function getSw($name, $node, $value="" ){
		$value = $this->getaktval($name);
		$arr = array();
		$obj="";
		$obj->value = "igen";
		$obj->option = jtext::_("IGEN");
		$arr[]=$obj;
		$obj="";
		$obj->value = "nem";
		$obj->option = jtext::_("NEM");
		$arr[]=$obj;
		($value) ? $value : $value = $node->getAttribute('default');
		return JHTML::_('Select.radiolist', $arr, $name, array( "class"=>"{$name}_"), "value", "option", $value);
	}
	 
	function __construct($file="", $data=""){
		$this->webContent = new webContent;
		$this->data = $data;
		$this->document=JFactory::getDocument();
		$this->user = jfactory::getUser();
		$this->dom = new DOMDocument();
		//echo $file; exit;
		$file = dirname(__FILE__)."/{$file}";
		//echo $file."<br />";
		if(!file_exists($file)){
			$file = dirname(__FILE__)."/xml.xml";
		}
		$this->dom->load($file);
		$this->db =JFactory::getDBO();
		$this->_db =JFactory::getDBO();		
		/*
		if(JRequest::getInt("sessSw", 0) == 2){
			$this->setFormFieldArray();
			$this->session();					
		}else{
		
		}
		*/
		//echo $this->getSessionVar("nev");
	}

	function delObj($table, $id, $pk ="id" ){
		$q = "delete from {$table} where {$pk} = $id limit 1";
		$this->db->setQuery($q);
		return $this->db->query();
	}
	
	function getObj($table, $id, $pk ="id" ){
		$q = "select * from {$table} where {$pk} = $id limit 1";
		$this->db->setQuery($q);
		return $this->db->loadObject();
	}	
	
	function getAfaSelect( $node="", $nameSuffix = "", $value = ""){
		ob_start();
		if($node){
			//print_r($node);
			//die;
			$name = $node->getAttribute('name');
		} else {$name = 'besz_afa';}
		if(!$value){
			if($this->getaktVal("id")){
				$id= $this->getaktval("id");
				$q = "select afa_id from #__wh_ar where termek_id = {$id} group by termek_id";
				$this->db->setQuery($q);
				$rows = $this->db->loadObject();
				if($rows){
					foreach($rows as $row){
					$value = $row;}
				}
			}
		}
		$q = "select id as `value`, ertek as `option` from #__wh_afa";
		$this->db->setQuery($q);
		$rows = $this->db->loadObjectList();
        echo JHTML::_('Select.genericlist', $rows, $name.$nameSuffix, array( "class"=>"multiple_search"), "value", "option", $value);
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret; 
	}
	
	function kapcsListaz($arr){
		//print_r($arr);
		//die;ß
		ob_start();
		?>
        <div class="table_padding">
		<table class="table_sablon">
			<tr>
       			<?php
				foreach($arr[0] as $oszlnev => $ertek){
					echo '<th class="th_sablon">',JText::_($oszlnev),"</th>";
				}
				?>
			</tr>
       			<?php
				$db=0;
				foreach($arr as $obj){
					if($db%2 == 0) $class = "class=\"td_sablon1\"";
					else $class = "class=\"td_sablon2\"";
					echo '<tr>';
					foreach($obj as $nev => $v){
						?><td <?php echo $class; ?>><?php echo $v;?> </td><?php
					}
					$db++;
					echo '</tr>';
				}
				?>
			</tr>
		</table>
        <input type="hidden" name="torol_id" id="torol_id" value="" />
        <input type="hidden" name="sorrendId" id="sorrendId" value="" />
        <input type="hidden" name="irany" id="irany" value="" />
        <input type="hidden" name="tablanev" id="tablanev" value="" />
        <input type="hidden" name="mezo_idk" id="mezo_idk" value="" />
        <input type="hidden" name="tabla_id" id="tabla_id" value="" />
        <input type="hidden" name="mezonev" id="mezonev" value="" />
        </div>
        <?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	function sorrendNyilak($id,$mezo_id,$curent_table,$mezo){
		ob_start();
		global $Itemid;
		$this->db->setQuery("SELECT * FROM {$curent_table} WHERE id = {$id}");
		$rows = $this->db->loadObjectList();
		foreach ($rows as $row){
			$nincsnyil_fel = false;
			$nincsnyil_le = false;
			$mezo_idk = array();
			$kapcs_mezok = "0".$row -> $mezo."0";
			$mezo_idk = explode(",", $kapcs_mezok);
			$elemszam = count($mezo_idk);
			if($mezo_idk[$elemszam-2] == $mezo_id){
				$nincsnyil_le = true;
			}
			if(!$nincsnyil_le){
			$link= "javascript:void(0);";
			$js = "javascript:sorrend2('".$mezo_id."', 'le','".$curent_table."','".$row -> $mezo."','".$id."','".$mezo."');";
			?>
			<a href="<?php echo $link ?>" onclick="<?php echo $js; ?>" ><img src="components/com_wh/assets/images/downarrow.png" /></a>
			<?php
            }
			
			if($mezo_idk[1] == $mezo_id){
				$nincsnyil_fel = true;
			}
			
			if(!$nincsnyil_fel){
			$link= "javascript:void(0);";
			$js = "javascript:sorrend2('".$mezo_id."', 'fel','".$curent_table."','".$row -> $mezo."','".$id."','".$mezo."');";
			
			?>
			<a href="<?php echo $link ?>" onclick="<?php echo $js; ?>" ><img src="components/com_wh/assets/images/uparrow.png" /></a>
			<?php
     		}
		}
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	function sorrend(){
		$irany = JRequest::getVar("irany");		
		$sorrendId = JRequest::getVar("sorrendId");	
		$tablanev = JRequest::getVar("tablanev");
		$tabla_id = JRequest::getVar("tabla_id");	
		$mezo_idk = JRequest::getVar("mezo_idk");
		$mezonev = JRequest::getVar("mezonev");
		$sorrend = array();
		$sorrend = explode(",", $mezo_idk);
		$ind = array_search($sorrendId, $sorrend);			
		if($irany=="le"){
			if($sorrend[$ind+1]!=0){
				$temp = $sorrend[$ind+1];
				$sorrend[$ind+1] = $sorrendId;
				$sorrend[$ind] = $temp;
			}
		}else{
			if($sorrend[$ind-1]!=0){
				$temp = $sorrend[$ind-1];
				$sorrend[$ind-1] = $sorrendId;
				$sorrend[$ind] = $temp;
			}
		}
		$sorrend = implode(",", $sorrend);
		$q = "update {$tablanev} set {$mezonev} = '{$sorrend}' where id = {$tabla_id} ";
		$this->db->setQuery($q);
		$this->db->Query();
	}
	
	function getKapcsolodoId($node){
		ob_start();
		$name = $node->getAttribute('name');
		$value=$this->getSessionVar("kapcsolodo_id");
		?>
        <input name="<?php echo $name ?>" id="<?php echo $name ?>" value="<?php echo $value ?>" type="hidden"  />
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;		
	}
	
	function getAktVal($name){
		if( JRequest::getVar( "fromlist", "" ) ){
			@$value = $this->data->$name;
		}else{
			$value = $this->getSessionVar($name);
		}		
		//echo $name."<br />";
		//die("--".$value);
		return $value;
	}
	
	function deleteSession(){
		@$sess = JSession::getInstance();
		foreach ($this->dom->getElementsByTagname('params') as $ps ){
			if(is_a($ps, "DOMElement")){
				$group = $ps->getAttribute('group');
				//echo "group: ".$group."<br />";
				if( !in_array($group, array("session", "condFields", "ordFields") ) ){
					foreach($ps->childNodes as $e){
						if(is_a($e, "DOMElement")){
							$name = $e->getAttribute('name');
							//echo $name."<br />";
							$sess->set($name, "");	
						}
					}
				}
			}
		}
	}



	function getOrderHiddenFields(){
		ob_start();
		$group = $this->getGroup("ordFields");
		foreach ($group->childNodes as $element ){
			if(is_a($element, "DOMElement")){ 
				$f = $element->getAttribute('name');
				$v = JRequest::getVar($f);
				echo "<input type=\"hidden\" id=\"{$f}_order\" name=\"{$f}_order\" value=\"{$v}\" />";
			}
		}	  
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
   }
	
	function session()
	{
		$data = JRequest::get( 'post' );
		//$data = $_REQUEST;
		@$sess =& JSession::getInstance();
		foreach($this->formFieldArray as $f){
			//echo $f."<br /><br /><br /><br /><br />";
			$value = JRequest::getVar($f);
			//echo $value." -- <br />";
			$sess->set($f, $value);
		}
		//echo $this->getSessionVar("nev")."********";
	}

	function getAllDataGroups(){
		$aTxt="\$a=array(";
		foreach ($this->dom->getElementsByTagname('params') as $group ){
			if(is_a($group, "DOMElement")){
				$gName = $group->getAttribute('group');				
				if(!in_array($gName, array("condFields", "ordFields")) ){
					//$form = htmlentities($this->getFormByGroup($gName));
					$cont = "";
					//echo $form;
					$aTxt.="\"{$gName}\" => \"{$cont}\", ";
				}
			}
		}
		$aTxt .= ");";
		//echo $aTxt;
		@eval ($aTxt);
		return $a;
	}
	
	function getAllFormGroups(){
		$aTxt="\$a=array(";
		foreach ($this->dom->getElementsByTagname('params') as $group ){
			if(is_a($group, "DOMElement")){
				$gName = $group->getAttribute('group');				
				if(!in_array($gName, array("condFields", "ordFields")) ){
					$form = htmlentities($this->getFormByGroup($gName));
					//echo $form;
					$aTxt.="\"{$gName}\" => \"{$form}\", ";
				}
			}
		}
		$aTxt .= ");";
		//echo $aTxt;
		@eval ($aTxt);
		return $a;
	}

	function megjelenhet($type, $popup){
		if( $this->isPopup() ){
			if($popup){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 1;
		}
	}

	function getDbCheckbox($name, $node, $value, $sw=0 ){
		$name = $node->getAttribute("name");
		//echo $value."**";
		if(!$value) $value = $this->getAktVal($name);
		
		if( !is_array($value) ){
			$value = explode(",", $value);
		}
		
		$table = $node->getAttribute("table");
		$v_ = $node->getAttribute("v_");
		$o_ = $node->getAttribute("o_");

		$cond = $node->getAttribute("cond");
		$q = "select * from {$table} {$cond} order by {$o_}";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();
		ob_start();
		foreach($rows as $r){
			if(in_array( $r->$v_, $value ) ){
				$checked = "checked=\"checked\"";
				$hiddenValue = $r->$v_;
			}else{
				$checked="";
				$hiddenValue = "";				
			}
				
			$idHidden = "{$name}_{$r->$v_}";
			$idCheck = "check_{$name}_{$r->$v_}";			
			$js = "onclick=\"kapcsolHiddenByCheck({$idCheck},{$idHidden})\"";
			$class = "class=\"{$name}\"";
			echo "<span {$class}><input {$class} id=\"{$idCheck}\" {$checked} type=\"checkbox\" {$js} value=\"{$r->$v_}\" />{$r->$o_}</span>";
			echo "<input type=\"hidden\" value=\"{$hiddenValue}\" name=\"{$name}[]\" id=\"{$idHidden}\"  />";
		}
		echo '<span style="clear:both"></span>';
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function getCheckbox($name, $node, $value ){
		//$name = $node->getAttribute();
		ob_start();
		$idHidden = "{$name}_";
		$idCheck = "check_{$name}_";			
		$js = "onclick=\"kapcsolHiddenByCheck( '{$idCheck}', '{$idHidden}' )\"";
		$class = "class=\"{$name}\"";
		//echo $value." ----";
		//$label = $node->getAttribute("label");
		($value == 'igen') ? $checked = "checked=\"checked\"" : $checked = "";
		echo "<span {$class} ><input {$class} id=\"{$idCheck}\" {$checked} type=\"checkbox\" {$js} value=\"igen\" /></span>";
		echo "<input type=\"hidden\" value=\"{$value}\" name=\"{$name}\" id=\"{$idHidden}\"  />";
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	function getCheckboxList($name, $node, $value ){
		ob_start();
		if(!is_array($value)){
			$valueArr = explode(",", $value);
		}else $valueArr=$value;
		//print_r($value);
		//die;
		foreach($node->childNodes as $e_){
			if(is_a($e_, "DOMElement")){
				$obj="";
				//print_r($e_);
				$value = $e_->getAttribute('value');
				$option = $e_->textContent;
				//echo $option;
				if(in_array(@$option, @$valueArr)){
					$checked ="checked=\"checked\"" ;
				}else{
					$checked="";
				}
				$js ="";
				?>
                <input <?php echo $js; ?> <?php echo $checked ?> type="checkbox" value="<?php echo $value ?>" name="<?php echo $name ?>[]" /><?php echo $option ?><br />
                <?php
			}
		}
		//array_unshift($arr, " ");
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function getDbListMultiple($name, $node, $value, $sw=0 ){
		$name = $node->getAttribute("name");
		//echo $value."**";
		if(!$value) $value = $this->getAktVal($name);
		$table = $node->getAttribute("table");
		$v_ = $node->getAttribute("v_");
		$o_ = $node->getAttribute("o_");
		$cond = $node->getAttribute("cond");		
		$sw = $node->getAttribute("sw");
		$q = "select * from {$table} {$cond}";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();
		$js = $node->getAttribute("js");
		$jsEvent = $node->getAttribute("jsEvent");
		if(!is_array($value))$value = explode(",", $value);		
		if($sw){
			$o="";
			$o->$v_ = $o->$o_= "";
			array_unshift($rows, $o); 
		}
		return JHTML::_('Select.genericlist', $rows, $name."[]", array( "class"=>"{$name}_", "multiple"=>"multiple", $jsEvent => $js ), $v_, $o_, $value);
	}
	
	function getDbList($name, $node, $value, $sw=0 ){
		$name = $node->getAttribute("name");
		//echo $value."**";
		if(!$value) $value = $this->getAktVal($name);
		$table = $node->getAttribute("table");
		$v_ = $node->getAttribute("v_");
		$o_ = $node->getAttribute("o_");
		$cond = $node->getAttribute("cond");		
		$sw = $node->getAttribute("sw");
		$q = "select * from {$table} {$cond}";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();
		$js = $node->getAttribute("js");
		$jsEvent = $node->getAttribute("jsEvent");
		
		if($sw){
			$o="";
			$o->$v_ = $o->$o_= "";
			array_unshift($rows, $o); 
		}
		return JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"{$name}_", $jsEvent => $js ), $v_, $o_, $value);
	}

	function getSelect($name, $node, $value ){
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
		
		return JHTML::_('Select.genericlist', $roic, $name, 
		array( "class"=>"{$name}_"), "value", "option", $value);
	}

	function getMultipleSelect($name, $node, $value) {
		//print_r($value);exit;
		foreach($node->childNodes as $e_){
			if(is_a($e_, "DOMElement")){
				$obj="";
				$obj->value = $e_->getAttribute('value');
				$obj->option = $e_->textContent;
				$roic[]=$obj;
			}
		}
		if(!is_array($value))$value = explode(",", $value);
		$arr=array();
		foreach($value as $v_){
			$o_="";
			$o_->value = $o_->option = $v_; 
			$arr[]=$o_;
		}
		return JHTML::_('Select.genericlist', $roic, $name."[]", array("multiple"=>"multiple", "class"=>"button"), "value", "option", $arr);
	}

	function getTextInput($name, $node, $value ){
		ob_start();
		?>
        <input type="text" name="<?php echo $name ?>" class="alapinput <?php echo "{$name}_" ?>" value="<?php echo $value ?>" />
        <?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	function getItemObjectFromXml( &$item, $a, $var){
		foreach($var as $v){
			$obj="";
			$node = $this->getNode("name", $v );
			$obj ->label = $node->getAttribute("label");
			$obj ->description = $node->getAttribute("description");
			$obj ->name = $v;

			//echo $node->getAttribute("type");
			switch($node->getAttribute("type")){
				case "list" :
					foreach($node->childNodes as $e_){
						if(is_a($e_, "DOMElement")){
							if($e_->getAttribute('value') == $item->$v){
								$obj ->textContent = $e_->textContent;
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
	
	function getXmlArr($name){
		$node = $this->getNode( "name", $name );
		//print_r( $node->childNodes );
		$arr = array();
		foreach( $node->childNodes as $e_){
			if(is_a($e_, "DOMElement")){
				if( $v = trim( $e_->textContent ) ){
					$o="";
					$o->value = $e_->getattribute("value");
					$o->option = jtext::_($e_->textContent);
					$arr[]=$o;
				}
			}
		}
		
		return $arr;
	}

	function getGroup( $value ){
		foreach ($this->dom->getElementsByTagname("params") as $element ){
			if($element->getAttribute("group")==$value){
				if(is_a($element, "DOMElement")){
					return $element; 
				}
			}
		}
		return false;
	}
 
	function getNode($attribute, $value ){
		foreach ($this->dom->getElementsByTagname("param") as $element ){
			if($element->getAttribute($attribute)==$value){
				return $element;
			}
		}
		return false;
	}

	function getSessionVar($var){
		@$sess =& JSession::getInstance();
		//$o_ = $sess->get("padData");
		return $sess->get($var);
		//print_r($o_); exit;
		//return $o_->$var;
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

	function session_admin_orig()
	{
		//exit;
		$data = JRequest::get( 'post' );
		//$data = $_REQUEST;
		@$sess =& JSession::getInstance();
		$task = JRequest::getVar("task");
		//echo $task;

		foreach($data as $varname => $v_){
			//echo $varname."<br />";
			$value = JRequest::getVar($varname, "");
			$sess->set($varname, $v_);
		}

		if($task != "add" && $task != "save" && $task != "cancel" /*&& $task != "" */){
			//print_r($this->tableFields);
		}else{
			//exit;
		}
	}

	function deleteProps($id){
		$q="delete from #__pad_prop_xref where pad_id = {$id}";
		$this->db->setQuery($q);
		$this->db->query();
	}
	
	function saveProps($id){
		$q="select id from #__pad_prop ";
		$this->db->setQuery($q);
		$roic = $this->db->loadResultArray();
		foreach($roic as $r){
			$val = JRequest::getVar("prop_{$r}", "");
			if($val){
				$o_ = "";
				$o_->name = $val;
				$o_->pad_id = $id;
				$o_->prop_id = $r;
				$this->db->insertObject("#__pad_prop_xref", $o_, "id");
				//echo $val."<br />";
			}
		}		
		//exit;
	}
}