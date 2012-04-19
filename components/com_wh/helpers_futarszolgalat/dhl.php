<?php
defined( '_JEXEC' ) or die( '=;)' );
class dhl extends modelBase /*xmlParser*/{
	
	function __construct(){
		//$this->base_template = new base_template; 
		//$this->webContent = new webContent;
		$this->_db = JFactory::getDBO();
		$this->db = JFactory::getDBO();		
		$this->rendeles_id = $this->getSessionVar("id");
		//$this->export();
		//$this->feltoltFajl();		
	}
	
	function feltoltFajl( $host, $user, $pw, $local_file, $remote_file ){ 
		/*
		$host = "ftp8.maxer.hu";
		$user = "visimpex_hu";
		$pw = "qpftgbah";
		*/
		$conn = ftp_connect($host ) or die ( "Cannot initiate connection to host" );
		ftp_login($conn, $user, $pw) or die("Cannot login");
		ftp_pasv( $conn, true );	
		echo "$host<br />$user<br />$pw<br />$local_file<br />$remote_file";
		$upload = ftp_put( $conn, $remote_file,  $local_file, FTP_BINARY);
		
		if (!$upload) {
		  echo "Cannot upload";
		  $ret = false;
		} else {
		  echo "Upload complete";
		  $ret = true;
		}
		ftp_close( $conn );
		//die("--------------------");		
		//die("  feltoltes eredmenye: {$ret}--------------");
		//unlink( $local_file );
		return $ret;
	}

	function export(){
		$rArr=array();
		$rArr[] = $this->getObj( "#__wh_rendeles", $this->rendeles_id );
		array_map(array($this, "setOsszertek"), $rArr);
		$rendeles = $rArr[0];
		parse_str($rendeles->sz_cim);
		//die($rendeles->sz_cim);
		$rendeles->osszertek += $rendeles->kiszallitas_ar;
		$webshop = $this->getObj( "#__wh_webshop", $rendeles -> webshop_id );
		$vasarlo = $this->getVasarlo( $rendeles->user_id, $rendeles->webshop_id );		
		//print_r($vasarlo);
		//die;
		$q = "select * from #__wh_tetel where rendeles_id = {$this->rendeles_id}";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();
		//print_r($rows);
		
		$remote_file = "export_{$this->rendeles_id}.csv";
		$filename = dirname(__FILE__)."/{$remote_file}";
		$elv = "";
		//$h ="";
		$mezohatarolo = "|";
		$fp = fopen($filename, "w");

		foreach($rows as $r){
			$ind = array_search($r, $rows);
			if( $ind == 0 ){
				$line = "";		
				foreach( $this->arrElsosor as $a){
					//echo "{$a}  ***********<br />";
					switch( $a ){
						case "LOGIN" :
						case "PW" :
						case "FELEADOWEBSHOP_EMAIL" :
							$v = $this->webshopArr[$webshop->nev][$a];
							//echo $a." - ".$v."<br />";
							break;
						default : $v = $a;
					}
					$line .= "{$elv}".$v."{$elv}{$mezohatarolo}";
				}
				$line .= $this->getToldalek();				
				//$line = substr($line, 0, strlen($line)-1);	
				//die($line." ******");			
				$line .= "\n";
				//die($line." --------------------");
				fputs($fp, $line);
			}
			for($i= 0; $i<4; $i++){
				$line = "";
				$arr = "arr{$i}";
				foreach($this->$arr as $a ){
					switch( $a ){
						case "RENDELES_ID" : 
							$v = $this->rendeles_id;
						break;
						case "DATUM" : 
							$v = date("Ymd", time() );
						break;
						case "CSOMAGAR" : 
							$v = $rendeles->osszertek;
						break;//SHIPMENT
						
						case "PARTNERKOD" :
							if( $rendeles->webshop_id == 3 ){
								$v = $rendeles->dhl_partnerkod_myparfum;
							}else{
								$v = $this->webshopArr[$webshop->nev][$a];
							}
						break;
							
						case "CEGNEV" :
						case "KAPCSOLATTARTO":
						case "UTCA":
						case "IRANYITOSZAM":
						case "VAROS":
						case "FELEADOWEBSHOP_EMAIL":						
						case "TELEFON":		
						case "AZONOSITO":				
							$v = $this->webshopArr[$webshop->nev][$a];
						break;//sender
						
						//&SZALLITASI_NEV=Szabó Dávid&IRANYITOSZAM=8200&VAROS=Veszprém&UTCA=Kádártai u. 52.
						case "CIMZETT_NEV":
							$v = $SZALLITASI_NEV;
						break;
						
						case "CIMZETT_UTCA":
							$v = $UTCA;
						break;						
						case "CIMZETT_IRANYITOSZAM":
							$v = $IRANYITOSZAM;
						break;												
						case "CIMZETT_VAROS":
							$v = $VAROS;
						break;												

						case "CIMZETT_TELEFON":
							$v = $vasarlo->felhasznalo->telefon;
						break;												
					
						default : $v = $a;
					}
					$v = iconv( "UTF-8", "ISO-8859-2", $v );
					$line .= "{$elv}".$v."{$elv}{$mezohatarolo}";
				}
				$line .= $this->getToldalek();
				//$line = substr($line, 0, strlen($line)-1);
				$line .= "\n";
				fputs($fp, $line);					
			}
		}
		fclose($fp);
		
		if( $rendeles->webshop_id == 3 ){
			//print_r($this->webshopArr[$webshop->nev]);
			//echo "<br />:::::".$rendeles->dhl_partnerkod_myparfum;
			//print_r( $this->webshopArr[$webshop->nev]["FTP"][$rendeles->dhl_partnerkod_myparfum] );
			$FTP_HOST = $this->webshopArr[$webshop->nev]["FTP"][$rendeles->dhl_partnerkod_myparfum]["FTP_HOST"];
			$FTP_LOGIN = $this->webshopArr[$webshop->nev]["FTP"][$rendeles->dhl_partnerkod_myparfum]["FTP_LOGIN"];
			$FTP_PW = $this->webshopArr[$webshop->nev]["FTP"][$rendeles->dhl_partnerkod_myparfum]["FTP_PW"];
		}else{
			$FTP_HOST = $this->webshopArr[$webshop->nev]["FTP_HOST"];
			$FTP_LOGIN = $this->webshopArr[$webshop->nev]["FTP_LOGIN"];
			$FTP_PW = $this->webshopArr[$webshop->nev]["FTP_PW"];
		}
		//die(" $FTP_HOST, $FTP_LOGIN, $FTP_PW, $filename, $remote_file ");
		$ret = $this->feltoltFajl( $FTP_HOST, $FTP_LOGIN, $FTP_PW, $filename, $remote_file );
		//die($filename);
		return $ret;
	//die; 
	}
	
	function getToldalek(){
		$ret ="";
		for($i=0; $i<59; $i++){
			$ret .= "|";
		}
		return $ret;
	}

	
	function getOsszesTomeg(){
		$id = $this->getSessionVar("id");
		$q = "select sum(tomeg*quantity) as tomeg from #__wh_tetel where rendeles_id = {$id}";
		$this->_db->setQuery($q);
		return $this->_db->loadResult();		
	}

	var $arrElsosor = array( 
	"A"=>"AUTH-DATA", 
	"B"=>"LOGIN",
	"C"=>"PW",
	"D"=>"FELEADOWEBSHOP_EMAIL",
	"E"=>"",
	"F"=>"",
	"G"=>"",
	"H"=>"",
	"I"=>"",
	"J"=>"",
	"K"=>"",
	"L"=>"",
	"M"=>"",
	"N"=>"",
	"O"=>"",
	"P"=>"",
	"Q"=>"",
	);

	var $arr0 = array( //SHIPMENT
	"A"=>"0", 
	"B"=>"DPEE-SHIPMENT",
	"C"=>"xDescription",
	"D"=>"EPLDOM",
	"E"=>"DATUM",
	"F"=>"",
	"G"=>"",
	"H"=>"xDeliveryRemark",
	"I"=>"",
	"J"=>"",
	"K"=>"",
	"L"=>"",
	"M"=>"",
	"N"=>"1",
	"O"=>"CSOMAGAR",
	"P"=>"",
	"Q"=>"",
	);

	var $arr1 = array( //SENDER
	"A"=>"0", 
	"B"=>"DPEE-SENDER",
	"C"=>"PARTNERKOD",//partnerkód
	"D"=>"CEGNEV",//cégnév
	"E"=>"",
	"F"=>"KAPCSOLATTARTO", //kapcsolattartó
	"G"=>"UTCA", //sender: utca hsz
	"H"=>"",
	"I"=>"",
	"J"=>"IRANYITOSZAM",
	"K"=>"VAROS",
	"L"=>"HU",
	"M"=>"",
	"N"=>"FELEADOWEBSHOP_EMAIL",
	"O"=>"TELEFON",
	"P"=>"",
	"Q"=>"AZONOSITO", //id ????
	);

	var $arr2 = array( //RECEIVER
	"A"=>"0", 
	"B"=>"DPEE-RECEIVER",
	"C"=>"CIMZETT_NEV",
	"D"=>"",
	"E"=>"",
	"F"=>"",
	"G"=>"CIMZETT_NEV",
	"H"=>"CIMZETT_UTCA",
	"I"=>"",
	"J"=>"",
	"K"=>"CIMZETT_IRANYITOSZAM",
	"L"=>"CIMZETT_VAROS",
	"M"=>"HU",
	"N"=>"",
	"O"=>"",
	"P"=>"CIMZETT_TELEFON",
	"Q"=>"",
	);

	var $arr3 = array( //DPEE-ITEM
	"A"=>"0", 
	"B"=>"DPEE-ITEM",
	"C"=>"1",
	"D"=>"",
	"E"=>"",
	"F"=>"",
	"G"=>"itemdescription", //xDescription
	"H"=>"COL",
	"I"=>"",
	"J"=>"",
	"K"=>"",
	"L"=>"",
	"M"=>"",
	"N"=>"",
	"O"=>"",
	"P"=>"",
	"Q"=>"",
	);

var $webshopArr = array(

	"gsmtakacs.hu"=>array(
			"TELEFON"=>"06 20 9275727",
			"KAPCSOLATTARTO"=>"Bimbó Tamás",
			"PARTNERKOD"=>"412160231",
			"LOGIN"=>"gsmtakacs.admin",
			"PW"=>"5891",			
			"FELEADOWEBSHOP_EMAIL"=>"info@gsmtakacs.hu",
			"AZONOSITO" =>"31510",

			"CEGNEV" => "WEB Holding Hungary Kft",
			"IRANYITOSZAM" => "2730",			
			"VAROS" => "Albertirsa",
			"UTCA" => "Pesti u 65",	
			"FTP_HOST" =>"ftp.intraship-eu.dhl.com",
			"FTP_LOGIN" =>"order100000051",
			"FTP_PW" =>"order100000051",
				
	),
		
	"elektroplaza"=>array(
			"TELEFON"=>"06 70 5550222",
			"KAPCSOLATTARTO"=>"Bokor Zsuzsanna",
			"PARTNERKOD"=>"412197309",			
			"LOGIN"=>"webholdingadmin",
			"PW"=>"WHadmin1234",			
			"FELEADOWEBSHOP_EMAIL"=>"info@elektroplaza.hu",
			"AZONOSITO" =>"39069",

			"CEGNEV" => "WEB Holding Hungary Kft",
			"IRANYITOSZAM" => "2730",			
			"VAROS" => "Albertirsa",
			"UTCA" => "Pesti u 65",			
			"FTP_HOST" =>"ftp.intraship-eu.dhl.com",
			"FTP_LOGIN" =>"order100000051",
			"FTP_PW" =>"order100000051",						
			
	),
		
	"MyParfum"=>array(
			"TELEFON"=>"06 20 9275727",
			"KAPCSOLATTARTO"=>"Varga Eszter",
			
			"PARTNERKOD"=>"",
			"LOGIN"=>"webholdingadmin",
			"PW"=>"WHadmin1234",
							
			"FTP"=>array(
				"412197297" =>array(
					"FTP_HOST" =>"ftp.intraship-eu.dhl.com",
					"FTP_LOGIN" =>"order100000051",
					"FTP_PW" =>"order100000051",
				),
				"412197060" =>array(
					"FTP_HOST" =>"ftp.intraship-eu.dhl.com",
					"FTP_LOGIN" =>"order100000051",
					"FTP_PW" =>"order100000051",
				),
			),
			"FELEADOWEBSHOP_EMAIL"=>"info@myparfum.hu",
			"AZONOSITO" =>"39067",
			"CEGNEV" => "WEB Holding Hungary Kft",
			"IRANYITOSZAM" => "2730",			
			"VAROS" => "Albertirsa",
			"UTCA" => "Pesti u 65",
		),
	);
	
}// class
?>
