function getKedvezmeny(  ){ 
	var kampany_id = $j('#id').val();
	var kedvezmeny = $j("#kedvezmeny").val();
	var kedvezmeny_tipus = $j("#kedvezmeny_tipus").val();
	
	kampany_id = ( kampany_id == undefined ) ? "" : kampany_id;
	kedvezmeny = ( kedvezmeny == undefined ) ? "" : kedvezmeny;
	kedvezmeny_tipus = ( kedvezmeny_tipus == undefined ) ? "" : kedvezmeny_tipus;
	var kedvezmeny_brutto = kedvezmeny * 1.25;
	//alert($j("#kedvezmeny_tipus").val() );
	var url="index.php?option=com_wh&controller=kampany&task=getKedvezmeny&format=raw";
	url += "&kampany_id="+kampany_id;
	url += "&kedvezmeny="+kedvezmeny;
	url += "&kedvezmeny_tipus="+kedvezmeny_tipus;	
	//url += "&kedvezmeny_brutto="+kedvezmeny_brutto;		
	
	//alert( url );
	new Ajax( url, {
			method:"post",
			onComplete: function(response){
				var resp=Json.evaluate(response);
				if(!resp.error){
					$j('#ajaxContentKedvezmeny').html( resp.html );
					//getKepLista();
				}else{
					alert(resp.error);//error
				}
			}
		}
	).request();
}

function setKedvezmeny( tipus, ertek ){
	if( tipus == "netto"){
		var value = ertek * 1.25;
		$j("#kedvezmeny_brutto").val( value );
	}else{
		var value = Math.round( ertek / 1.25 );		
		$j("#kedvezmeny").val( value );	
	}
}
