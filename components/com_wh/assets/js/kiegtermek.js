function kiegTermekIrany( irany, kapcsolo_id ){
	termek_id = $('id').value;
	var url="index.php?option=com_wh&controller=termek&task=kiegTermekIrany&format=raw&kapcsolo_id="+kapcsolo_id+"&irany="+irany;
	url += "&termek_id="+$j('#id').val();
	//alert(url);
	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      /*dataType: "html",*/
      success: function(response){
         var resp= $j.parseJSON( response );
		 getKiegTermekek();
      }
	});
}

function torolKiegTermek( kieg_termek_id ){
	var url = "index.php?option=com_wh&controller=termek&task=torolkiegTermek&format=raw";
	url += "&termek_id=" + $j( "#id" ).val( );
	url += "&kieg_termek_id=" + kieg_termek_id;
	//alert( url );
	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      /*dataType: "html",*/
      success: function(response){
         var resp= $j.parseJSON( response );
         //alert( resp );
		 getKiegTermekek();
      }
	});
}

function hozzaadKiegTermek(){
	var url = "index.php?option=com_wh&controller=termek&task=hozzaadkiegTermek&format=raw";
	url += "&termek_id=" + $j("#id").val( );
	url += "&kieg_termek_id=" + $j("#kieg_termek_id").val( );
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
			getKiegTermekek();		 
         if( !resp.error ){
			$j("#kieg_termek_id").val('');
			 getKiegTermekek();
         }else{
            alert( resp.error );//error
         }
      }
	});
}

function getKiegTermekek(){
	var url = "index.php?option=com_wh&controller=termek&task=getKiegTermekek&format=raw";
	url += "&termek_id=" + $j("#id").val( );
	//url += "&value="+encodeURI(value);
	//alert(url);
	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      /*dataType: "html",*/
      success: function(response){
         var resp= $j.parseJSON( response );
         if( !resp.error ){
            $j( '#ajaxContentKiegTermekek' ).html( resp.html );
         }else{
            alert( resp.error );//error
         }
      }
	});
}
