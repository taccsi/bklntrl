// JavaScript Document

function kuldAllapotvaltozas(  ){
	var rendeles_id = $j('#id').val();
	var allapot = $j('#allapot').val();	
	var allapot_megjegyzes= $j('#allapot_megjegyzes').val();	
	var url="index.php?option=com_wh&controller=rendeles&task=kuldAllapotvaltozas&format=raw";
	url += "&rendeles_id="+rendeles_id;
	url += "&allapot=" + allapot;
	url += "&allapot_megjegyzes=" + encodeURI(allapot_megjegyzes);	
	//alert(url);
	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      /*dataType: "html",*/
      success: function(response){
         var resp= $j.parseJSON( response );
		 $j("#allapotv_email_datum").html(resp.html);
		 //getKiegTermekek();
      }
	});
}
