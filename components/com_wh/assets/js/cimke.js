
function cimkeIrany( irany, kapcsolo_id ){
	var termek_id = $j('#id').val();
	var url="index.php?option=com_wh&controller=termek&task=cimkeIrany&format=raw&kapcsolo_id="+kapcsolo_id+"&irany="+irany;
	url += "&termek_id="+$j('#id').val();
	//alert(url);
	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      /*dataType: "html",*/
      success: function(response){
		 //alert(response);
         var resp= $j.parseJSON( response );
         if( !resp.error ){
			listazCimkek( termek_id );
         }else{
            alert( resp.error );//error
         }
      }
	});
}

function listazCimkek( termek_id ){
	//alert('*');
	var termek_id = $j("#id").val();	
	var url = "index.php?option=com_wh&controller=termek&task=getCimkelista&format=raw&termek_id="+termek_id;
	//return false;
	//url += "&value="+encodeURI(value);
	//alert(url);
	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      /*dataType: "html",*/
      success: function(response){
		 //alert(response);
         var resp= $j.parseJSON( response );
         if( !resp.error ){
            $j( '#ajaxContentCimkek' ).html( resp.html );
         }else{
            alert( resp.error );//error
         }
      }
	});
//alert(ugy_id);
	//alert($('ugy_id').value) ;
}

function setCimkeKategoria( kategoria_id, cimke_id ){
	termek_id = $j('#id').val();
	var url = "index.php?option=com_wh&controller=termek&task=mentCimkeKategoria&format=raw";
	url += "&kategoria_id="+kategoria_id;
	url += "&cimke_id="+cimke_id;
	//alert(url);
	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      /*dataType: "html",*/
      success: function(response){
		 //alert(response);
         var resp= $j.parseJSON( response );
         if( !resp.error ){
			listazCimkek( termek_id )
            //$j( '#ajaxContentCimkek' ).html( resp.html );
         }else{
            alert( resp.error );//error
         }
      }
	});
}

function mentCimkeKapcsolo( termek_id, cimkeId, obj_id ){
	
	cimke_kapcsolo = $j('#'+obj_id).val();
	
	var url = "index.php?option=com_wh&controller=termek&task=mentCimkeKapcsolo&format=raw";
		url += "&termek_id="+termek_id;
		url += "&cimkeId="+cimkeId;	
		url += "&cimke_kapcsolo="+cimke_kapcsolo;		

	//url += "&value="+encodeURI(value);
	//alert(url);
	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      /*dataType: "html",*/
      success: function(response){
		 //alert(response);
         var resp= $j.parseJSON( response );
         if( !resp.error ){
            $j( '#ajaxContentCimkek' ).html( resp.html );
         }else{
            alert( resp.error );//error
         }
      }
	});
	//alert( aktiv );
}

function torolCimke( cimke_id, termek_id ){
	var url = "index.php?option=com_wh&controller=termek&task=torolCimke&format=raw&cimke_id="+cimke_id+"&termek_id="+termek_id;

	//url += "&value="+encodeURI(value);
	//alert(url);
	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      /*dataType: "html",*/
      success: function(response){
		 //alert(response);
         var resp= $j.parseJSON( response );
         if( !resp.error ){
            listazCimkek(termek_id);
         }else{
            alert( resp.error );//error
         }
      }
	});

}

function addcimke( termek_id ){
	
	//alert(controller);
	var text = $j('#text').val();
	$j('#text').val('');
	if (text==''){alert('A mező kitöltése kötelező')} else {
		var url = "index.php?option=com_wh&controller=termek&task=addCimke&format=raw&text="+text+"&termek_id="+termek_id;
		//url += "&value="+encodeURI(value);
		//alert(url);
		$j.ajax({
		  url: url,
		  type: "POST",
		  async: false,
		  /*dataType: "html",*/
		  success: function(response){
			 //alert(response);
			 var resp= $j.parseJSON( response );
			 if( !resp.error ){
				listazCimkek(termek_id);
			 }else{
				alert( resp.error );//error
			 }
		  }
		});
	}
	
}
