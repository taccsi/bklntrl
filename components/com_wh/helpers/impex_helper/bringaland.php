<?php
defined( '_JEXEC' ) or die( '=;)' );
	function setTermekvariacioBringaland( $termek_id, $str ){
		$arr = explode( ",", $str );
		if( count($arr) ){
			$q = "delete from #__wh_termekvariacio where termek_id = {$termek_id} ";
			$this->_db->setQuery($q);
			$this->_db->Query();
			$mezo_ertek = trim( $arr[0] );
			if( @$mezo_id = $this->termvarSablon[ $mezo_ertek ] ){
				foreach($arr as $a){
					if( $ind = array_search($a, $arr) ){
						$tv = "";
						$tv->termek_id = $termek_id;
						$tv->ertek = "&mezoid_{$mezo_id}={$a}&";
						$this->_db->insertObject( "#__wh_termekvariacio", $tv, "id" );
					}
				}
			}else{
				$ertek = "&";
				foreach($this->osszesMezoIdArr as $id){
					$ertek .= "mezoid_{$id}=&";
				}
				$tv = "";
				$tv->site_kapcsolo = "bringaland"; 				
				$tv->termek_id = $termek_id;
				$tv->ertek = $ertek;
				$this->_db->insertObject( "#__wh_termekvariacio", $tv, "id" );
			}
		}
	}

	function setKepBringaland( $termek_id, $termeknev ){
		//$t = $this->getObj( "#__wh_termek", $termek_id );
		$q = "select * from brl_rsgallery2_files where title = '{$termeknev}' order by title limit 1";
		$this->_db->setQuery($q);
		$arr = $this->_db->loadObjectList();
		if( count($arr) || 1 )$this->delKepek( $termek_id );
		foreach( $arr as $a ){
			$forras_kep = "importkepek/bringaland/{$a->name}";			
			//echo $forras_kep."<br />";
			if( file_exists( $forras_kep ) && is_file($forras_kep) ){
				$k  = "";
				$k->termek_id = $termek_id;
				$k->aktiv = "igen";
				$k->site_kapcsolo = "bringaland"; 
				$this->_db->insertObject("#__wh_kep", $k, "id");
				$id = $this->_db->insertId();
				$celkep = "media/termekek/{$id}.jpg";
				copy($forras_kep, $celkep);
			}
		}
	}

?>