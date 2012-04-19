<?php
defined('_JEXEC') or die('Restricted access'); ?>
<?php

class google{
	var $gkey ="ABQIAAAAoQO6gAw58QQwfz5vpnkVqxSesqwx32hnkGUznHC63mULgQpq7xQpEE_h5rxUgcuehW1Wcg-aPL2WUQ";
	var $iso ="utf8";
	function __construct(){
		
	}

	function getGoogleMap($szelesseg, $magassag, $vercseppek, $center_zoom, $obj){
		ob_start();
			//print_r($obj);		
		?>
		<div id="gmap" style="width: <?php echo $szelesseg ?>px; height: <?php echo $magassag ?>px;">&nbsp;</div>
		<script src="http://maps.google.com/maps?file=api&v=2&key=<?php echo $this->gkey ?>&oe=utf8"
		  type="text/javascript"></script><script type="text/javascript">
			load();
		  function load() {
		  if (GBrowserIsCompatible()) {
			var map = new GMap2(document.getElementById("gmap"));
			map.addControl(new GSmallMapControl());
			map.setCenter(new GLatLng(<?php echo $obj->coord ?>), <?php echo $center_zoom->zoom ?>);
					function createMarker(point, cucc) {
					  var marker = new GMarker(point);
					  GEvent.addListener(marker, "click", function() {
						marker.openInfoWindowHtml(cucc);
					  });
					  return marker;
					}
			kozpont_ = createMarker(new GLatLng(<?php echo $obj->coord ?>),"<?php echo $obj->telepules." ".$obj->utca ?>");
			map.addOverlay( kozpont_);
			kozpont_.setImage("http://ntsi.hu/ertekbecsles/images/icons/house.png");
			<?php
			//print_r( $vercseppek );
			if(is_array($vercseppek)){
				foreach($vercseppek as $v){
					if($v->coord){
						$title = $this->getTitle($v);
						?>
						var title = '<?php echo $title ?>';
						m_ = createMarker(new GLatLng(<?php echo $v->coord ?>), title);
						map.addOverlay(m_);
						//m_.setImage("http://ntsi.hu/ertekbecsles/images/jel.jpg");
						<?php
					}
				}
			}
			?>
		  }
		}
		</script>
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function getTitle($obj){
		$Itemid = $this->Itemid;
		$link="index.php?tavolsag=&Itemid={$Itemid}&option=com_ingker&view=bontas&id={$obj->id}";
		ob_start();
		?><strong><?php echo $obj->telepules ?></strong><br /><div class="div_cimke"><?php echo "{$obj->irszam}<br />{$obj->utca}" ?></div><div style="text-align:center" ><a href="<?php echo $link ?>" >ugrás ide</a></div><?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;		
	}
	
		
	function setGoogleCoord($rows){
		$db = JFactory::getDBO();
		//print_r($rows);
		foreach($rows as $r){
			if($r->coord =="" /* || 1*/ ){
				$obj="";
				$obj->id = $r->id;
				$coord = $this->get_geo("Magyarország {$r->telepules} {$r->utca}", $this->gkey, "utf8");
				$c_ = explode(",","{$coord}");//echo $coord;
				//print_r( $coord->coordinates);
				$obj->coord = "{$coord}";				
				$obj->c1 = $c_[0];
				$obj->c2 = $c_[1];
				//print_r($obj);
				$db->updateObject("#__ingker", $obj, "id");
			}			
		}
	}

	function get_geo($address)
	{
		$this->debug_log("get_geo(".$address.")");
		//$address = iconv('UTF-8', 'ISO-8859-2//IGNORE', $address);
		//echo $address."<br />";
		$coords = '';
		$getpage='';
		$replace = array("\n", "\r", "&lt;br/&gt;", "&lt;br /&gt;", "&lt;br&gt;", "<br>", "<br />", "<br/>");
		$address = str_replace($replace, '', $address);
	
		$this->debug_log("Address: ".$address);
		
		$uri = "http://maps.google.com/maps/geo?q=".urlencode($address)."&output=xml&key=".$this->gkey;
		$this->debug_log("get_geo(".$uri.")");
		
		if ( !class_exists('SimpleXMLElement') )
		{
			// PHP4
			$ok = false;
			$this->debug_log("SimpleXMLElement doesn't exists so probably PHP 4.x");
			if (ini_get('allow_url_fopen'))
				if (($getpage = file_get_contents($uri)))
					$ok = true;

			if (!$ok) {
				$this->debug_log("URI couldn't be opened probably ALLOW_URL_FOPEN off");
				if (function_exists('curl_init')) {
					$this->debug_log("curl_init does exists");
					$ch = curl_init();
					$timeout = 5; // set to zero for no timeout
					curl_setopt ($ch, CURLOPT_URL, $uri);
					curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
					$getpage = curl_exec($ch);
					curl_close($ch);
				} else
					$this->debug_log("curl_init doesn't exists");
			}
	
			$this->debug_log("Returned page: ".$getpage);
	
			if (function_exists('mb_detect_encoding')) {
				$enc = mb_detect_encoding($getpage);
				if (!empty($enc))
					$getpage = mb_convert_encoding($getpage, $iso, $enc);
			}
				
			if (function_exists('domxml_open_mem')&&($getpage<>'')) {
				$responsedoc = domxml_open_mem($getpage);
				if ($responsedoc !=null) {				
					$response = $responsedoc->get_elements_by_tagname("Response");
					if ($response!=null) {
						$placemark = $response[0]->get_elements_by_tagname("Placemark");
						if ($placemark!=null) {
							$point = $placemark[0]->get_elements_by_tagname("Point");
							if ($point!=null) {
								$coords = $point[0]->get_content();
								$this->debug_log("Coordinates: ".join(", ", explode(",", $coords)));
								return $coords;
							}
						}
					}
				}
			}
			$this->debug_log("Coordinates: null");
			return null;
		}
		else
		{
			// PHP5
			$this->debug_log("SimpleXMLElement does exists so probably PHP 5.x");
			//echo "SimpleXMLElement does exists so probably PHP 5.x";
			$ok = false;
			if (ini_get('allow_url_fopen')) { 
				if (file_exists($uri) || 1) {//antineni
					$getpage = file_get_contents($uri);
					$ok = true;
				}
			}
			
			if (!$ok) { 
				$this->debug_log("URI couldn't be opened probably ALLOW_URL_FOPEN off");
				if (function_exists('curl_init')) {
					$this->debug_log("curl_init does exists");
					$ch = curl_init();
					$timeout = 5; // set to zero for no timeout
					curl_setopt ($ch, CURLOPT_URL, $uri);
					curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
					$getpage = curl_exec($ch);
					curl_close($ch);
				} else
					$this->debug_log("curl_init doesn't exists");
			}
	
			$this->debug_log("Returned page: ".$getpage);
			if (function_exists('mb_detect_encoding')) {
				$enc = mb_detect_encoding($getpage);
				if (!empty($enc))
					$getpage = mb_convert_encoding($getpage, $this->iso, $enc);
			}
	
			if ($getpage <>'') {
				$expr = '/xmlns/';
				$getpage = preg_replace($expr, 'id', $getpage);
				//die($getpage);
				$xml = new SimpleXMLElement($getpage);
				foreach($xml->xpath('//coordinates') as $coordinates) {
					$coords = $coordinates;
					break;
				}
				if ($coords=='') {
					$this->debug_log("Coordinates: null");
					return null;
				}
				//$this->debug_log("Coordinates: ".join(", ", explode(",", $coords)));
				//$tmp = 

				$tmp = explode(",",$coords);
				$coords = "{$tmp[1]},{$tmp[0]}";
				return $coords;
			}
		}
		$this->debug_log("get_geo totally wrong end!");
	}

	function debug_log($text)
	{
		if ($this->debug_plugin =='1' /* || 1*/ )
			$this->debug_text .= "\n// ".$text." (".round($this->memory_get_usage()/1024)." KB)";
	
		return;
	}

    function memory_get_usage()
    {
		if ( function_exists( 'memory_get_usage' ) )
			return memory_get_usage(); 
		else
			return 0;
    }

	function getDistance($c1, $c2)
	{
	 $R = 6371; // Föld sugara km-ben
	 //$c1="47.519983,19.033813";
	 //$c2="47.686192,17.635117";	 
	 $c1 = explode(',',$c1);
	 $c2 = explode(',',$c2);
	 
	 $lat1 = $c1[0];
	 $lon1 = $c1[1];
	 $lat2 = $c2[0];
	 $lon2 = $c2[1];
	 
	 $dLat = deg2rad($lat2-$lat1); //RADIANS( {$this->lat2}-c1 )
	 $dLon = deg2rad($lon2-$lon1); //RADIANS( {$this->lon2}-c2 )

	 //sin($dLat/2) ----> sin(radians({$this->lat2}-c1 )/2)
	 //cos(deg2rad($lat1)) ----> cos(radians(c1))
	 //cos(deg2rad($lat2)) ----> cos(radians({$this->lat2}))
	 //sin($dLon/2) ---> sin( RADIANS( {$this->lon2}-c2 )/2 )

	 $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
	 $c = 2 * atan2(sqrt($a),sqrt(1-$a));
	 $d = $R * $c;
	 return $d;
	}	

}
?>
