<?php
defined( '_JEXEC' ) or die( '=;)' );
class ar{
	function _( $ar, $penznem = "€", $kerekites = 1, $formaz = false ){
		//is_numeric
		//Float $ar = floatval( $ar );
		//die("{$ar}");
		
		if( $kerekites > 0 ){
			$ar = ar::getKerekitettAr( $ar, $kerekites );
			return number_format($ar, 0, "", " ")." {$penznem}";
		}elseif( $formaz ){
			$ar = "".number_format( $ar, 2, ".", " " )."";
			$arr = explode(".", $ar);
			return $arr[0].",<small>".end($arr)."</small> {$penznem}";
			//return $ar;
			/*
			if( is_float( $ar ) ){
				//die ( $ar . " --------------<br />" );
				$tort = $ar - floor( $ar );
				$egesz =  number_format( floor( $ar ), 0, "", " " );
				$tort = $ar - floor( $ar );
				$tort = substr( $tort, 0, 4 );
				//$tort = ( $tort ) ? ".<small>" . str_replace( "0.", "", $tort ) . "</small> * - * {$egesz} * - * {$ar} * * "  : "";
				$tort = ( $tort ) ? ".<small>" . str_replace( "0.", "", $tort ) . "</small>"  : ".<small>00</small>";
				//die($ar." ---");
			}else{
				$egesz = $ar;
				$tort = ".<small>00</small>";
			}
			 */
			//return "{$egesz}{$tort} {$penznem}";
		}else{
			return number_format( $ar, 2, "", " ")." {$penznem}";				
		}
	}
	
	function getBrutto( $ar, $afa ){
		return $ar*( 1+$afa/100 );
	}

	function getKerekitettAr( $ar, $k = 1 ){
		if($k === 0) return $ar;
		$ar = round( $ar/$k ) * $k;
		($ar%$k) ? $ar+=$k : $ar;
		return $ar;
	}}
?>