function mentSzallitasiDijtetel( divId ){
	var nev = $j("#"+divId+" .nev").val();
	var tol = $j("#"+divId+" .tol").val();
	var ig = $j("#"+divId+" .ig").val();
	var dij = $j("#"+divId+" .dij").val();
	var tetel_id = $j("#"+divId+" .id").val();
	var url="index.php?option=com_wh&controller=webshop&task=mentSzallitasiDijtetel&format=raw";
	url += "&id="+tetel_id;	
	url += "&nev="+nev;	
	url += "&tol="+tol;
	url += "&ig="+ig;
	url += "&dij="+dij;
	//alert( url );
	$j.ajax({
		url: url,
		type: "POST",
		async: false,
		/*dataType: "html",*/
		success: function(response){
		   getSzallitasiDijtetelek();
		}
	});
}
//
function torolSzallitasiDijtetel( divId ){
	//alert(divId);
	var webshop_id = $j("#id").val();
	var tetel_id = $j("#"+divId+" .id").val();
    var url="index.php?option=com_wh&controller=webshop&task=torolSzallitasiDijtetel&format=raw";
	url += "&tetel_id="+tetel_id;	
	var ajaxContentId = "ajaxContentSzallitasiDijtetelek";
	//alert(url);
	$j.ajax({
		url: url,
		type: "POST",
		async: false,
		/*dataType: "html",*/
		success: function(response){
		   getSzallitasiDijtetelek();
		}
	});
}

function getSzallitasiDijtetelek(){
	//alert( 'getSzallitasiDijtetelek' );
	var webshop_id = $j("#id").val();
    var url="index.php?option=com_wh&controller=webshop&task=getSzallitasiDijtetelek&format=raw&webshop_id="+webshop_id;
	var ajaxContentId = "ajaxContentSzallitasiDijtetelek";
	//alert( url );
	$j.ajax({
		url: url,
		type: "POST",
		async: false,
		/*dataType: "html",*/
		success: function(response){
		   //alert(response);
		   var resp= $j.parseJSON( response );
		   if( !resp.error ){
			   //alert(resp.debug);
			   $j("#"+ajaxContentId).html( resp.html );
			  //getKapcsolodoTermekek();
		   }else{
			  alert( resp.error );//error
		   }
		}
	});
}

function hozzaadSzallitasiDijtetel(){
	var webshop_id = $j("#id").val();
	var nev = $j("#div_hozzaad .nev").val();
	var tol = $j("#div_hozzaad .tol").val();
	var ig = $j("#div_hozzaad .ig").val();
	var dij = $j("#div_hozzaad .dij").val();
	var afa_id = $j("#div_hozzaad #afa_id").val();
    var url="index.php?option=com_wh&controller=webshop&task=hozzaadSzallitasiDijtetel&format=raw";
	url += "&webshop_id="+webshop_id;
	url += "&nev="+nev;	
	url += "&tol="+tol;
	url += "&ig="+ig;
	url += "&dij="+dij;
	url += "&afa_id="+afa_id;
	//alert( url );
	var ajaxContentId = "ajaxContentSzallitasiDijtetelek";
	$j.ajax({
		url: url,
		type: "POST",
		async: false,
		/*dataType: "html",*/
		success: function(response){
		   getSzallitasiDijtetelek();
		}
	});
}
