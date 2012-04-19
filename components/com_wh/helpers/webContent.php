<?php
defined( '_JEXEC' ) or die( '=;)' );
class webContent extends modelBase{
	var $konkurenciaArr = array(
		"argep.hu" => "getArgepAr",
		"olcsobbat.hu" => "getOlcsobbatAr",		
		
	);
	
	function __construct(){
		$this->_db = JFactory::getDBO();
	}
	
	function getKonkurenciaAr ( $termek_id, $konkurencia, $leker = 0) {
		//die($konkurencia);
		//die($leker."-----<br />");
		if( $leker /* || !$this->getMentettar( $termek_id, $konkurencia )*/  ){
			$termek = $this->getObj("#__wh_termek", $termek_id);
			$obj="";
			$link = $this->getLink($termek->kereso_szo, $konkurencia);
			$obj->url = $link;
			$func = $this->konkurenciaArr[$konkurencia];
			$arak = $this->$func( $link );
			@$obj->ar = $arak[0];
			
			$this->saveKonkurenciaAr($termek_id, $konkurencia, $arak, $obj->url);
		}else{
			return $this->getMentettAr( $termek_id, $konkurencia );
		}
		return $this->getMentettAr( $termek_id, $konkurencia );
	}
	
	function getMentettAr( $termek_id, $konkurencia ){
		$q= "select * from #__wh_konkurencia_ar where termek_id = {$termek_id} and konkurencia = '{$konkurencia}'";
		$this->_db->setQuery($q);
		//die($this->_db->getQuery());
		$obj = $this->_db->loadObject();
		//print_r($obj);
		//die("{$q}--");
		return $this->_db->loadObject();
	}
	
	function saveKonkurenciaAr($termek_id, $konkurencia, $arak, $url){
		//echo $termek_id."<br />".$konkurencia."<br />";
		//die;
		if($o = $this->getMentettar( $termek_id, $konkurencia )){
			$o->ar = $arak[0];
			$o->arak = serialize($arak);
			$o->url = $url;
			$this->_db->updateObject("#__wh_konkurencia_ar", $o, "id" );
			//print_r($o);
			//die("-----");
		}else{
			$o="";
			$o->termek_id=$termek_id;
			$o->konkurencia=$konkurencia;
			@$o->ar = $arak[0];
			$o->arak = serialize($arak);
			$o->url = $url;
			$this->_db->insertObject("#__wh_konkurencia_ar", $o, "id" );
		}
	
	}

	//<a title="Olympus FE-46 - árak" href="/szorakoztato_elektronika/digitalis_fenykepezok/olympus_fe_46/">19 900 Ft</a>

	function getOlcsobbatAr($link){
		if($link){
			$site = $this->getSite($link);
			$minta = '/">[0-9]+.*Ft<\/a>/';
			preg_match_all($minta, $site, $matches, PREG_SET_ORDER);
			$arak=array();
			//print_r($matches);
			//die;
			foreach($matches as $a){
				$ar = str_replace(array(" ", "\">", "</a>.", "Ft"), "", $a[0] );
				@$ar = strip_tags( $ar );
				$arak[] = $ar;
			}
			return $arak;
		}else{
			return "0";
		}
	}
	
	function getArgepAr($link){
		if($link){
			$site = $this->getSite($link);
			$minta = '/<b>[0-9]+.*\&nbsp;Ft<\/b>/';
			preg_match_all($minta, $site, $matches, PREG_SET_ORDER);
			$arak=array();
			foreach($matches as $a){
				$ar = str_replace(array("&nbsp;", ".", "Ft"), "", $a[0] );
				@$ar = strip_tags( $ar );
				$arak[] = $ar;
			}
			//print_r($matches);
			//die;
			return $arak;
		}else{
			return "0";
		}
	}
	
	function getLink($termekNev, $site){
		$searchText = urlencode("{$termekNev} site:{$site}");
		sleep(1);
		$homepage = file_get_contents("http://google.hu/search?&q={$searchText}");
		preg_match_all('/<h3 class=r>.*?<\/h3>/', $homepage, $matches, PREG_SET_ORDER);
		foreach($matches as $m){
			preg_match('/href=\".*?\"/', $m[0], $m2);			
			$link=str_replace(array("\"", "href="),"",$m2[0]);
			break;
		}
		//echo $homepage;
		@preg_match('/^http:\/\/.*/', $link, $linkS);
		//print_r($linkS[0]);exit;
		return @$linkS[0];
	}
	
	function getSite($site){
		preg_match("@^(?:http://)([^/]+)(.*)$@i",$site,$out);
		$host=$out[1];
		$page=$out[2];
		if (strlen($page)==0) $page = "/";
		$errno = 0;
		$errstr ="";
		$timeout = 100;
		$file = fsockopen($host, 80, $errno, $errstr, $timeout);
		$out = "GET {$page} HTTP/1.1\r\n";
		$out .= "Host: {$host}\r\n";
		$out .= "Connection: Close\r\n\r\n";
		fputs($file, $out);
		while(!feof($file)){
		$data[]=fgets($file);
		}
		$data = implode("", $data);
		//$data = substr($data, strpos($data, "<!DOCTYPE"), strpos($data, "</html>")-strpos($data, "<!DOCTYPE")+7 );
		return $data;
	}
}
?>