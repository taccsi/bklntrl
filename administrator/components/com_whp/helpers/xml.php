<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlParser extends modelBase {
		
	function isPopup(){
		if( JRequest::getVar("tmpl") == "component" ){
			return 1;
		}else{
			return 0;
		}
	}
	function getTelepulesId( $node ){
		$controller = JRequest::getvar('controller');
		$name = $node->getAttribute('name');
		$value = $this->getAktVal($name);
		if ($megye = JRequest::getvar('megye')){} else {$megye = '';}
		//die($megye);
		//if (!$megye) $megye = jrequest::getVar("megye", "");
		$q = "select megye as `value`, megye as `option` from #__wh_atvhely as atv
		inner join #__wh_telepules as t on t.id = atv.telepules_id group by megye order by megye asc";
		$this->_db->setquery($q);
		$rows = $this->_db->loadObjectList();
		array_map ( array($this, "setUrlEncoding"), $rows );
		$o="";
		$o->option = $o->value = "";
		array_unshift( $rows, $o );
		$ret = "";
		$ret .= JHTML::_( 'Select.genericlist', $rows, "megye", array("class"=>"alapinput cim", "onchange"=>"getTelepules('{$controller}')" ), "value", "option", $megye );
		//$this->document->addscriptdeclaration("window.addEvent(\"domready\", function(){getTelepules('{$value}');})");
		$ret .= "<div id=\"ajaxContentTelepules\"></div>";		
		return $ret;
	}
	
	function setUrlEncoding($item){
		$item->value = urlencode($item->value);		
		return $item;
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


	function getAfaSelect( $node="", $nameSuffix = "", $value = ""){
		ob_start();
		if($node){
			$name = $node->getAttribute('name');
		}
		$q = "select id as `value`, ertek as `option` from #__whp_afa";
		$this->db->setQuery($q);
		$rows = $this->db->loadObjectList();
        echo JHTML::_('Select.genericlist', $rows, $name.$nameSuffix, array( "class"=>"multiple_search"), "value", "option", $value);
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret; 
	}
	 
	 function getMegrendelo(){
		$q = "select * from #__wh_felhasznalo where webshop_id = {$GLOBALS['whp_id']} and user_id = {$this->user->id}";
		$this->_db->setQuery($q);
		return $this->_db->loadObject();
	 }
	 
	function __construct($file="", $data=""){
		$this->db = JFactory::getDBO();
		$this->_db = JDatabase::getInstance( whpBeallitasok::getOption() );		
		$this->data = $data;
		
		$this->user=JFactory::getUser();
		$this->document=JFactory::getDocument();
		$this->dom = new DOMDocument();
		//echo $file; exit;
		$file = dirname(__FILE__)."/{$file}";
		//echo $file."<br />";
		if(!file_exists($file)){
			$file = dirname(__FILE__)."/xml.xml";
		}
		$this->dom->load($file);
		
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
	
	function getCsillagText($node){
		ob_start();
		echo Jtext::_('CSILLAGTEXT');
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret; 

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
				if( !in_array($group, array("session") ) ){
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
			//print_r( $group);
			if(is_a($group, "DOMElement")){
				$gName = $group->getAttribute('group');		
				//echo $gName."**************<br />";
				if(!in_array($gName, array("condFields", "ordFields")) ){
					$cont = htmlentities($this->getDataByGroup($gName));
					$aTxt.="\"{$gName}\" => \"{$cont}\", ";
				}
			}
		}
		$aTxt .= ");";
		//echo $aTxt;
		//die("*---");
		@eval ($aTxt);
		return $a;
	}

	function getDataByGroup($group){
		ob_start();
		$html="";
		$errorFields = JRequest::getVar("errorFields", array() );
		//echo $task; exit;
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
						$mandatory_sign = "";
						$class = "class=\"{$name}_ alapinput\"";
						if($type<>"hidden"){
							$html.="<tr><td valign=\"top\" class=\"key\">{$label}</td><td class=\"paramlist_value\">";}
	
						$value = $this->getAKtVal($name);
						switch($type){
							case "hidden" : break;
							case "calendar" : 
								$html.="{$value}<input type=\"hidden\" id=\"{$name}\" />";
								break;							
							case "spec" : 
								$function = $e_->getAttribute('function')."_d";
								$value = $this->$function($e_);
								$html.="{$value}";
								break;
							default:
								$html.="{$value}";								
						}
						if($type<>"hidden"){
							$html.="<td></tr>";
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
		function getFormByGroup($group){
		ob_start();
		$html="";
		$errorFields = JRequest::getVar("errorFields", array() );
		//echo $task; exit;
		foreach ($this->dom->getElementsByTagname('params') as $element ){
			$g_ = $element->getAttribute('group');
			if($g_ == $group){
				foreach($element->childNodes as $e_){
					if(is_a($e_, "DOMElement")){
						$name = $e_->getAttribute('name');
						$label = JText::_($e_->getAttribute('label'));
						$type = $e_->getAttribute('type');
						$mandatory = JText::_($e_->getAttribute('mandatory'));
						$js = $e_->getAttribute('js');
						$ajaxFunc = $e_->getAttribute('ajaxFunc');
						$ajaxEvent = $e_->getAttribute('ajaxEvent');
						$ajaxObj = "";
						if( $ajaxFunc ){
							($ajaxEvent) ? $ajaxEvent : $ajaxEvent = "onblur";
							$ajaxObj->ajaxJs = "{$ajaxEvent}=\"{$ajaxFunc}('{$name}', '{$mandatory}',this)\"";
							$ajaxObj->ajaxContent = "<span id=\"ajax_{$name}\" ></span>";
						}else{
							$ajaxObj->ajaxJs = "";
							$ajaxObj->ajaxContent = "";

						}
						$description = JText::_($e_->getAttribute('description'));
						$default = JText::_($e_->getAttribute('default'));
						($mandatory) ? $mandatory_sign = "*" : $mandatory_sign = "";
						if( $e_->getAttribute( 'popup' ) == -1 ){
							$popup = 0;
						}else{
							$popup = 1;
						}

						(in_array($name, $errorFields)) ? $mandatory_text = JText::_($e_->getAttribute('mandatory_text')) : $mandatory_text = "";
						$class = "class=\"{$name}_ alapinput\"";
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
								case "list" :
									$html.=$this->getSelect($name, $e_, $value, $ajaxObj).$description; break;
								case "multiple" :
									$html.=$this->getMultipleSelect($name, $e_, $value, $ajaxObj).$description; break;
	
								case "checkboxlist" :
	
									$html.=$this->getCheckboxList($name, $e_, $value, $ajaxObj).$description; break;
	
								case "textarea" :
									$class = "class=\"{$name}_ \"";
									$html.="<textarea {$class}  rows =\"10\" cols=\"30\" name=\"{$name}\" id=\"{$name}\" >{$value}</textarea>{$ajaxObj->ajaxContent}";								
									break;
								case "editor" :
									$editor =& JFactory::getEditor();
									$html.= $editor->display($name, $value, "500", 300, 300, 20, 0 ); 
									break;
									
								case "spec" :
											$func = $e_->getAttribute('function');						
											$html.= $this->$func($e_, $ajaxObj )."{$description}";
									break;
								case "calendar" :
									$html.= JHTML::_('calendar',$value, $name, $name, $format = '%Y-%m-%d',
									array("class"=>"{$name}_ alapinput", "size"=>'25',  'maxlength'=>'19')); break;
								case "submit":
									//$html.=$this->getSelect($name, $e_, $class);
									$class = "class=\"{$name}_ \"";
									$submitValue= jtext::_($e_->getAttribute("submitValue"));
									$html.="<input {$class} name=\"{$name}\" type=\"submit\" value=\"{$submitValue}\" /> {$description}"; break;						
								case "asubmit":
									//$html.=$this->getSelect($name, $e_, $class);
									$class = "class=\"{$name}_ asubmit\"";
									$submitValue= jtext::_($e_->getAttribute("submitValue"));
									$formid = ($e_->getAttribute('formid')) ? $e_->getAttribute('formid') : "adminForm";
									ob_start();
									?>
										<a id="<?php echo $name ?>" href="javascript:;" onclick="\$j('#<?php echo $formid ?>').submit();" <?php echo $class ?> ><?php echo $submitValue ?></a> <?php echo $description ?>
                                    <?php
									$html.= ob_get_contents(); ob_end_clean(); break;		
								case "file":
									//$html.=$this->getSelect($name, $e_, $class);
									$html.="<input {$js} {$class} name=\"{$name}\" type=\"file\"  /> {$description}"; break;						
								case "hidden":
									//$html.=$this->getSelect($name, $e_, $class);
									$html.="<input {$ajaxObj->ajaxJs} {$js} {$class} name=\"{$name}\" id=\"{$name}\" type=\"hidden\" value=\"{$value}\" />{$ajaxObj->ajaxContent} {$description}"; break;				
								case "checkbox":
									//$html.=$this->getSelect($name, $e_, $class);
									//echo $value;
									($value) ? $checked = "checked=\"checked\"" : $checked = "";
									$html.="<input {$js} {$class} name=\"{$name}\" type=\"checkbox\" value=\"igen\" {$checked} /> {$description}"; break;				
								case "radio":
									//$html.=$this->getSelect($name, $e_, $class);
									($value) ? $checked = "checked=\"checked\"" : $checked = "";
									$html.="<input {$js} {$class} name=\"{$name}\" type=\"checkbox\" value=\"1\" {$checked} /> {$description}"; break;				

									//$html.=$this->getSelect($name, $e_, $class);
								case "password":
									$html.="<div class=\"inputrow\" style=\"overflow:hidden; width: 100%;\"><input {$ajaxObj->ajaxJs} {$js} {$class} name=\"{$name}\" id=\"{$name}\" type=\"password\" onfocus=\"this.className='active_input'\" value='{$value}' /> {$ajaxObj->ajaxContent} {$description}</div>";
									break;	
								default:
									//$html.=$this->getSelect($name, $e_, $class);
									//die($js);
									
									$html.="<div class=\"inputrow\" style=\"overflow:hidden; width: 100%;\"><input {$ajaxObj->ajaxJs} {$js} {$class} name=\"{$name}\" id=\"{$name}\" type=\"text\" value='{$value}' onfocus=\"this.className='active_input'\" />{$ajaxObj->ajaxContent} {$description}</div>";								
							}
						}//megjelenhet vége
						if($mandatory) $html.= $this->getMandatoryHidden($name, $mandatory, $mandatory_text);
						if($type<>"hidden"){
							$html.="<span id=\"span_error_{$name}\" class=\"span_error\">{$mandatory_text}</span><td></tr>";
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

	function getFormByGroup__($group){
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
						$sw = $e_->getAttribute('sw');						
						$js = $e_->getAttribute('js');
						$ajaxFunc = $e_->getAttribute('ajaxFunc');
						$ajaxEvent = $e_->getAttribute('ajaxEvent');
						if( $ajaxFunc ){
							($ajaxEvent) ? $ajaxEvent : $ajaxEvent = "onblur";
							$ajaxObj->ajaxJs = "{$ajaxEvent}=\"{$ajaxFunc}('{$name}', '{$mandatory}',this)\"";
							$ajaxObj->ajaxContent = "<span id=\"ajax_{$name}\" ></span>";
						}else{
							$ajaxObj->ajaxJs = "";
							$ajaxObj->ajaxContent = "";

						}

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
						$class = "class=\"{$name}_ alapinput alapinput_{$type}\"";
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
						if($idKell && !$id){
							$html.=	jtext::_("A FUNKCIO ELERESEHEZ MENTES SZUKSEGES");
						}else{
							switch($type){
								case "password":
									$html.="<div class=\"inputrow\" style=\"overflow:hidden; width: 100%;\"><input {$ajaxObj->ajaxJs} {$js} {$class} name=\"{$name}\" id=\"{$name}\" type=\"password\" onfocus=\"this.className='active_input'\" value='{$value}' /> {$ajaxObj->ajaxContent} {$description}</div>"; break;
								case "dbCheckbox":
									$html.= $this->getDbCheckbox($name, $e_, $value, $ajaxObj).$description; break;
								case "dbListMultiple":
									$html.= $this->getDbListMultiple($name, $e_, $value, $ajaxObj).$description; break;
								case "dbList":

									$html.=$this->getDbList($name, $e_, $value, $sw).$description; break;									
								case "list" :
									$html.=$this->getSelect($name, $e_, $value).$description; break;
								case "sw" :
									$html.=$this->getSw($name, $e_, $value).$description; break;
								case "multiple" :
									$html.=$this->getMultipleSelect($name, $e_, $value).$description; break;
	
								case "checkboxlist" :
	
									$html.=$this->getCheckboxList($name, $e_, $value, $ajaxObj).$description; break;
	
								case "textarea" :
									$html.="<textarea {$class} rows =\"10\" cols=\"30\" id=\"{$name}\" name=\"{$name}\" >{$value}</textarea>{$ajaxObj->ajaxContent}";	 							
									break;
								case "editor" :
									$editor =& JFactory::getEditor();
									$html.= $editor->display($name, $value, "500", 300, 300, 20, 0 ); 
									break;
									
								case "spec" :
									$func = $e_->getAttribute('function');
									$html.= $this->$func($e_, $ajaxObj);
									break;
								case "calendar" :
									$html.= JHTML::_('calendar',$value, $name, $name, $format = '%Y-%m-%d',
									array("class"=>"{$name}_ alapinput", "size"=>'25',  'maxlength'=>'19')); break;
								case "submit":
									//$html.=$this->getSelect($name, $e_, $class);
									$submitValue= jtext::_($e_->getAttribute("submitValue"));
									$html.="<input {$class} name=\"{$name}\" type=\"submit\" value=\"{$submitValue}\" /> {$description}"; break;						
								case "file":
									$html.= $this->getFile($name, $e_, $value).$description; break;
								case "hidden":
									//$html.=$this->getSelect($name, $e_, $class);
									$html.="<input {$class} name=\"{$name}\" id=\"{$name}\" type=\"hidden\" value=\"{$value}\" /> {$ajaxObj->ajaxContent} {$description}"; break;				
								case "checkbox":
									//$html.=$this->getSelect($name, $e_, $class);
									($value) ? $checked = "checked=\"checked\"" : $checked = "";
									$html.="<input {$class} name=\"{$name}\" type=\"checkbox\" value=\"1\" {$checked} /> {$description}"; break;				
								case "radio":
									//$html.=$this->getSelect($name, $e_, $class);
									($value) ? $checked = "checked=\"checked\"" : $checked = "";
									$html.="<input {$class} name=\"{$name}\" type=\"checkbox\" value=\"1\" {$checked} /> {$description}"; break;				
								default:
									//$html.=$this->getSelect($name, $e_, $class);
									$html.="<div class=\"inputrow\" style=\"overflow:hidden; width: 100%;\"><input {$ajaxObj->ajaxJs} {$js} {$class} name=\"{$name}\" id=\"{$name}\" type=\"text\" value='{$value}' onfocus=\"this.className='active_input'\" />{$ajaxObj->ajaxContent} {$description}</div>";								
							}
						}//megjelenhet vége
							if($mandatory) $html.= $this->getMandatoryHidden($name, $mandatory, $mandatory_text);
							if($type<>"hidden"){
							$html.="<span class=\"span_error\">{$mandatory_text}</span><td></tr>";
						}
						}//echo $e_->tagName."<br />";
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
			$class = "class=\"alapinput {$name}\"";
			echo "<span {$class}><input {$class} id=\"{$idCheck}\" {$checked} type=\"checkbox\" {$js} value=\"{$r->$v_}\" />{$r->$o_}</span>";
			echo "<input type=\"hidden\" value=\"{$hiddenValue}\" name=\"{$name}[]\" id=\"{$idHidden}\"  />";
		}
		echo '<span style="clear:both"></span>';
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function getDbListMultiple($name, $node, $value, $sw=0 ){
		$name = $node->getAttribute("name");
		//echo $value."**";
		//if(!$value) $value = $this->getAktVal($name);
		( is_array($value) ) ? $value : $value = explode(",", $value );
		$table = $node->getAttribute("table");
		$v_ = $node->getAttribute("v_");
		$o_ = $node->getAttribute("o_");
		$cond = $node->getAttribute("cond");		
		$q = "select * from {$table} {$cond}";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();
		if($sw){
			$o="";
			$o->$v_ = $o->$o_= "";
			array_unshift($rows, $o); 
		}
		return JHTML::_('Select.genericlist', $rows, $name."[]", 
		array( "class"=>"{$name}_ alapselect", "multiple"=>"multiple"), $v_, $o_, $value);
	}

	function getDbList($name, $node, $value, $sw=0 ){
		$name = $node->getAttribute("name");
		//echo $value."**";
		if(!$value) $value = $this->getAktVal($name);
		$table = $node->getAttribute("table");
		$v_ = $node->getAttribute("v_");
		$o_ = $node->getAttribute("o_");
		$cond = $node->getAttribute("cond");		
		$q = "select * from {$table} {$cond}";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();
		if($sw){
			$o="";
			$o->$v_ = $o->$o_= "";
			array_unshift($rows, $o); 
		}
		return JHTML::_('Select.genericlist', $rows, $name, 
		array( "class"=>"{$name}_ alapselect"), $v_, $o_, $value);
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
                <input <?php echo $js; ?> <?php echo $checked ?> type="checkbox" value="<?php echo $value ?>" name="<?php echo $name ?>[]" /><?php echo Jtext::_($option); ?><br />
                <?php
			}
		}
		//array_unshift($arr, " ");
		$ret = ob_get_contents();
		ob_end_clean();
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
		return JHTML::_('Select.radiolist', $arr, $name, array( "class"=>"{$name}_ alapradio"), "value", "option", $value);
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
		array( "class"=>"{$name}_ alapselect"), "value", "option", $value);
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

	function getGroupElementNames( $group ){
		$node = $this->getGroup( $group );
		$arr = array();
		foreach($node->childNodes as $e_){
			if( is_a($e_, "DOMElement") ){
				$arr[] =$e_->getAttribute('name');
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
?>