// JavaScript Document

function kepIrany( irany, kep_id ){ 
	//alert(irany);
	var termek_id = $j('#id').val();    
	var url="index.php?option=com_wh&controller=termek&task=kepIrany&format=raw";
	url += "&termek_id="+termek_id;
	url += "&kep_id="+kep_id;
	url += "&irany="+irany;
	//alert( url );

	$j.ajax({
		url: url,
		type: "POST",
		async: false,
		/*dataType: "html",*/
		success: function(response){
		   var resp= $j.parseJSON( response );
		   if( !resp.error ){
			   getKepLista();
		   }else{
				alert(resp.error);
		   }
		}
	});
	
}

function getKepLista(){
	var termek_id = $j('#id').val();    
    var url="index.php?option=com_wh&controller=termek&task=getKepLista&format=raw&termek_id="+termek_id;
	$j.ajax({
		url: url,
		type: "POST",
		async: false,
		/*dataType: "html",*/
		success: function(response){
		   var resp= $j.parseJSON( response );
		   if( !resp.error ){
			   $j('#ajaxContentKepek').html( resp.html );
		   }else{
				alert(resp.error);
		   }
		}
	});
	
}

function mentKepek( objId, kepId ){
	var aktiv = $j("#"+objId).val();
	var termek_id = $j('#id').val();    
    var url="index.php?option=com_wh&controller=termek&task=mentKepek&format=raw";	
	url += "&termek_id="+termek_id;
	url += "&kepId="+kepId;
	url += "&aktiv="+aktiv;			
	//alert( url );
	new Ajax( url, {
			method:"post",
			onComplete: function(response){
				var resp=Json.evaluate(response);
				if(!resp.error){
					//$j('#ajaxContentKepek').html( resp.html );
				}else{
					alert(resp.error);//error
				}
			}
		}
	).request();
}

function mentKep(inputId, kepId){
	termek_id = $j('#id').val();
	var kepalairas = $j("#"+inputId).val()
    var url="index.php?option=com_wh&controller=termek&task=mentKep&format=raw";
	url += "&termek_id="+termek_id;
	url += "&kepId="+kepId;
	url += "&kepalairas="+kepalairas;
	new Ajax( url, {
			method:"post",
			onComplete: function(response){
				var resp=Json.evaluate(response);
				if(!resp.error){
					//getDiscountList();
					//$j('#ajaxContentKepek').html( resp.html );
					getKepLista();
				}else{
					alert(resp.error);//error
				}
			}
		}
	).request();
}

function torolKep(kep_id){
	var termek_id = $('id').value;
    var url="index.php?option=com_wh&controller=termek&task=torolKep&format=raw&kep_id="+kep_id+"&termek_id="+termek_id;
	$j.ajax({
		url: url,
		type: "POST",
		async: false,
		/*dataType: "html",*/
		success: function(response){
		   var resp= $j.parseJSON( response );
		   if( !resp.error ){
			  getKepLista();
		   }else{
				alert(resp.error);
		   }
		}
	});
	
}
