<?php
defined( '_JEXEC' ) or die( '=;)' );
class webContent extends whpAdmin{
	var $konkurenciaArr = array(
		"argep.hu" => "getArgepAr",
		
	);
	
	function __construct(){
		$this->_db = JFactory::getDBO();
	}
	
	function getKonkurenciaAr ( $termek_id, $konkurencia, $leker = 0) {
		if( $leker || !$this->getMentettar( $termek_id, $konkurencia ) ){
			$termek = $this->getObj("#__whp_termek", $termek_id);
			$obj="";
			$link = $this->getLink($termek->nev, $konkurencia);
			$obj->url = $link;
			$func = $this->konkurenciaArr[$konkurencia];
			$obj->ar = $this->$func( $link );
			$this->saveKonkurenciaAr($termek_id, $konkurencia, $obj->ar, $obj->url);
		}else{
			return $this->getMentettAr( $termek_id, $konkurencia );
		}
		return $obj;
	}
	
	function getMentettAr( $termek_id, $konkurencia ){
		$q= "select * from #__whp_konkurencia_ar where termek_id = {$termek_id} and konkurencia = '{$konkurencia}'";
		$this->_db->setQuery($q);
		return $this->_db->loadObject();
	}
	
	function saveKonkurenciaAr($termek_id, $konkurencia, $ar, $url){
		if($o = $this->getMentettar( $termek_id, $konkurencia )){
			$this->_db->updateObject("#__whp_konkurencia_ar", $o, "id" );			
		}else{
			$o="";
			$o->termek_id=$termek_id;
			$o->konkurencia=$konkurencia;
			$o->ar=$ar;
			$o->url = $url;
			$this->_db->insertObject("#__whp_konkurencia_ar", $o, "id" );
		}
	
	}
	
	function getArgepAr($link){
		if($link){
			$site = $this->getSite($link);
			$minta = '/<b>[0-9].*\&nbsp;€<\/b>/';
			preg_match_all($minta, $site, $matches, PREG_SET_ORDER);
			@$ar = strip_tags( $matches[0][0]);
			$ar = str_replace(array("&nbsp;", ".", "€"), "", $ar );
			//die($ar);	
			return $ar;
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