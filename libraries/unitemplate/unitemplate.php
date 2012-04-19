<?php
/**
 * @version 0.2-rc1
 * @package uniTemplate
 * @copyright (C) 2009 Farkas Zolt�n
 * @author Farkas Zolt�n - zoli@trifid.hu
 * @license Creative Commons Attribution-Share Alike 3.0
 *          http://creativecommons.org/licenses/by-sa/3.0/
 * 
 * You are free to share: to copy, distribute, and transmit the work
 * You are free to remix: to adapt the work
 *
 * Under the following conditions:
 * Attribution: you must attribute the work in the manner specified by the
 * author or licensor (but not in any way that suggests that they endorse
 * you or your use of the work).
 * Share alike: If you alter transform, or build upon this work, you may
 * distribute the resulting work only under the same, similar or a
 * compatible license.
 *
*/

// for Joomla
defined( '_JEXEC' ) or die( '=;)' );
//defined( '_VALID_MOS' ) or die( 'Restricted access' );

class uniTemplate{

	var $version = "0.2-rc3";
	var $uniTplDefaultPath = "libraries/unitemplate/templates/";
	
	var $tpl;
	var $tplName;
	var $templateFile;
	
	function uniTemplate($id, $data = array(), $htmlType = "div", $template = "default", $params = array()){
		$this->params = $params;
		$this->params->id = $id;
		$this->params->htmlType = $htmlType;
		$this->params->tpl = $template;
		
		if (empty($data) || empty($id)) die($this->uniHelp());
		$this->data = $data;
		
		$this->ret = "";
		
		// template file from params
		if (isset($params->templatePath)){
			if (substr($params->templatePath,-1,1) == '/')
				$params->templatePath = substr($params->templatePath,-1,1);
			$templateFile = $params->templatePath . '/' . $template . ".php";
		} else {
			// default template path
			$templateFile = $this->uniTplDefaultPath.$template.".php";
		}
		$this->tplName = $template;
		(!empty($template) && file_exists($templateFile)) ? $this->templateFile = $templateFile : die($this->uniHelp("template"));
		
		// id
		(!empty($id)) ? $this->id = $id : die($this->uniHelp("id"));
		
		// itemClass
		$this->itemClass = (!empty($params->itemClass)) ? $params->itemClass : "";
		
		// pair
		$this->pair = (!empty($params->pair) && $params->pair) ? true : false;
		

		// htmlType
		switch($htmlType){
			case "table":
				(!empty($params->cols) && $params->cols>0) ? $this->cols = $params->cols : die($this->uniHelp("cols"));
				$this->cellspacing = (!empty($params->cellspacing) && $params->cellspacing>0) ? $params->cellspacing : 0;
				$this->tpl = $this->uniTableList();
				break;
			case "div":
				(!empty($params->cols) && $params->cols>0) ? $this->cols = $params->cols : $this->cols = 9999999;
				$this->tpl = $this->uniDivList();
				break;
			case "clear":
				$this->tpl = $this->uniClearList();
				break;
			default: die($this->uniHelp("htmlType"));
		}

		// wrapperClass open
		if (!empty($params->wrapperClass)) $this->ret .= "<div class=\"{$params->wrapperClass}\">";
				
		// template output
		$this->ret .= $this->tpl;
		
		// wrapperClass close
		if (!empty($params->wrapperClass)) $this->ret .= "</div>";

	}
	
	
	function getContents(){
		return $this->ret;
	}
	
	
	function uniTableList(){
		require_once($this->templateFile);
		$tpl = new $this->tplName;
		$ret = "<table cellpadding=\"0\" cellspacing=\"{$this->cellspacing}\" border=\"0\"
				class=\"unitable_{$this->id}\" id=\"unitable_{$this->id}\">";
		for ($i=0;$i<intval(ceil(count($this->data)/$this->cols));$i++){
			if (count($this->data) == 1) $row = $this->data;
			else $row = array_slice($this->data,$i*$this->cols,$this->cols);
			if ($this->pair && (($i % 2) == 0)) $ret .= "<tr class=\"evenRow\">";
			elseif ($this->pair &&(($i % 2) == 1)) $ret .= "<tr class=\"oddRow\">";
			else $ret .= "<tr>";
			foreach ($row as $cell){
				if (!empty($this->itemClass)) $tdClass = $this->itemClass. " ";
				if (!empty($cell->itemClass)) $tdClass .= $cell->itemClass . " ";
				$tdClass = (isset($tdClass)) ? trim($tdClass) : '';
				if (!empty($tdClass)) $ret .= "<td class=\"{$tdClass}\">"; else $ret .= "<td>";
				$tpl->cell = $cell;
				$uCell = $tpl->getTpl();
				preg_match_all("@\{([^}]+)\}@i",$uCell,$out);
				$replaceable = $out[1];
				foreach($replaceable as $repl){
					if (method_exists($tpl,$repl))
						$uCell = str_replace("{{$repl}}",$tpl->$repl(),$uCell);
					elseif (isset($cell->$repl)) $uCell = str_replace("{{$repl}}",$cell->$repl,$uCell);
				}
				$ret .= $uCell;
				$ret .= "</td>";
			}
			$ret .= "</tr>";
		}
		$ret .= "</table>";
		return $ret;
	}
	
	
	function uniDivList(){
		require_once($this->templateFile);
		$tpl = new $this->tplName;
		$ret = "<div class=\"unidiv_{$this->id}\" id=\"unidiv_{$this->id}\">\n";
		for ($i=0;$i<intval(ceil(count($this->data)/$this->cols));$i++){
			if (count($this->data) == 1) $row = $this->data;
			else $row = array_slice($this->data,$i*$this->cols,$this->cols);
			
			if ($this->pair && (($i % 2) == 0) && ($this->cols != 9999999)) $ret .= "<div class=\"evenRow\">\n";
			elseif ($this->pair &&(($i % 2) == 1) && ($this->cols != 9999999)) $ret .= "<div class=\"oddRow\">\n";
			elseif ($this->cols != 9999999) $ret .= "<div class=\"row\">\n";
			
			foreach ($row as $cell){
				
				$last = ($cell == end($row)) ? "last" : "";
				if ($this->itemClass != "")
					$ret .= "<div class=\"{$this->itemClass}\">\n";
				elseif (count($this->data) > 0) $ret .= "<div class=\"item {$last}\">\n";
				
				$tpl->cell = $cell;
				$uCell = $tpl->getTpl();
				preg_match_all("@\{([^}]+)\}@i",$uCell,$out);
				$replaceable = $out[1];
				
				foreach($replaceable as $repl){
					if (method_exists($tpl,$repl))
						$uCell = str_replace("{{$repl}}",$tpl->$repl(),$uCell);
					elseif (isset($cell->$repl)) $uCell = str_replace("{{$repl}}",$cell->$repl,$uCell);
				}
				$ret .= $uCell;
				
				if (count($this->data) != 1 || $this->itemClass != "") $ret .= "</div>\n";
			}
			
			if ($this->cols != 9999999) $ret .= "</div>";
		}
		$ret .= "</div>\n";
		return $ret;
	}
	
	
	function uniClearList(){
		require_once($this->templateFile);
		$tpl = new $this->tplName;
		$ret = "";

		foreach ($this->data as $cell){
			if ($this->itemClass != "")
				$ret .= "<div class=\"{$this->itemClass}\">";
			
			$tpl->cell = $cell;
			$uCell = $tpl->getTpl();
			preg_match_all("@\{([^}]+)\}@i",$uCell,$out);
			$replaceable = $out[1];
			
			foreach($replaceable as $repl){
				if (method_exists($tpl,$repl))
					$uCell = str_replace("{{$repl}}",$tpl->$repl(),$uCell);
				elseif (isset($cell->$repl)) $uCell = str_replace("{{$repl}}",$cell->$repl,$uCell);
			}
			$ret .= $uCell;
			
			if ($this->itemClass != "")
				$ret .= "</div>"; // item v�ge
		}

		return $ret;
	}
	

	function uniHelp($e = ""){
		$ret = "<div style=\"color:red;\">uniTemplate " . $this->version;
		
		if (!empty($e)) $ret .= "\n\nHiba a param�terben: {$e}\n";
		else $ret .= "\n\nHiba: rossz vagy hi�nyz� param�terek.\n";
		
		$ret .= "Param�terek:\n";
		
		foreach($this->params as $k => $v){
			$ret .= $k . ": " . $v . "\n";
		}
		
		$ret .= "\nHaszn�lat: new uniTemplate(\$id, \$data, \$htmlType, \$template, \$params);\n\n";
		$ret .= "\$id: azonos�t�\n";
		$ret .= "\$data: adatokat tartalmaz� objektum, vagy objektumok t�mbje\n";
		$ret .= "\$htmlType: milyen szabv�ny szerint list�zzon. div vagy table\n";
		$ret .= "\$template: haszn�lni k�v�nt template neve.\n";
		$ret .= "\$params: param�tereket tartalmaz� objektum\n\n";
		$ret .= "Param�terek:\n<ul>";
		$ret .= "<li>cols: h�ny oszlopba list�zzon.<ul><li>DIV htmlType eset�n burkol� DIV-et tesz ki. Ha nem adjuk meg, folyamatosan rakja egym�s ut�n a DIV-eket.</li><li>TABLE htmlType eset�n megad�sa k�telezo.</li></ul>�rt�kek: <i>int</i></li>";
		$ret .= "<li>wrapperClass: megad�sakor ilyen CSS oszt�ly� DIV-be foglalja a template-et. �rt�kek: <i>string</i></li>";
		$ret .= "<li>itemClass: megad�sakor ilyen CSS oszt�lyt ad az elemeknek (DIV vagy TD) �rt�kek: <i>string</i></li>";
		$ret .= "<li>pair: jel�li a p�ros �s p�ratlan sorokat <i>evenRow</i> �s <i>oddRow</i> CSS oszt�lyokkal. �rt�kek: <i>bool</i></li>";
		$ret .= "<li>cellspacing: TABLE htmlType eset�n a t�bl�zat cellspacing-je. �rt�kek: <i>int</i></li>";
		$ret .= "</ul></div>";
		return nl2br($ret);
	}
}
?>