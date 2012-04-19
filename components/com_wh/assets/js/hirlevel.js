function kuldHirlevel( hirlevel_id, tol ){ 
	//alert(input_id);
	var lista_id = $j('#lista_id').val();
	if(!lista_id){
		alert(NEM_ADOTT_MEG_LISTAT);
		return false;
	}
	var url="index.php?option=com_wh&controller=hirlevel&task=kuldHirlevel&format=raw"
	url += "&hirlevel_id="+hirlevel_id;
	url += "&tol="+tol;
	url += "&lista_id="+lista_id;
	$j( "#ajaxContentCsiga" ).html("<img src=\"components/com_wh/assets/images/ajax-loader.gif\" >");
	//alert( url );
	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      /*dataType: "html",*/
      success: function(response){
        var resp= $j.parseJSON( response );
		$j("#ajaxContentCsiga" ).html("&nbsp;");
		$j("#ajaxContentFolyamat").html( resp.tol + " / " + resp.osszes);
		var value = resp.tol/resp.osszes * 100;
		$j('#a_hirlevel_kuldes').html(''); 
		if( resp.tol > 0 ){
			$j( "#progressbar" ).progressbar({
				value: value
			});
			setTimeout( function(){ kuldHirlevel( hirlevel_id, resp.tol )}, 4000 );
		}else{
			$j("#ajaxContentFolyamat").html( '100%' );
			$j( "#progressbar" ).progressbar({
				value: 100
			});
			setTimeout(function(){ location.reload(); }, 10000 );
		}
	  }
	});
}

function folyamat( limitstart ){
	var ajaxContentId = "#ajaxContentFolyamat";
	var hirlevel_id = $j( '#id' ).val();
	var url = "index.php?option=com_wh&format=raw";
	try{ url+="&controller=hirlevel" }catch(e){}
	try{ url+="&task=getFolyamat" }catch(e){}
	try{ url+="&uzlet_id="+uzlet_id }catch(e){}
	try{ url+="&hirlevel_id="+hirlevel_id }catch(e){}
	try{ url+="&limitstart="+limitstart }catch(e){}
	
	//alert(url);
	var html = $j.ajax({
		url: url,
		async: false,
		success: function(html){
			//$j(ajaxContentId).append( html + "<br>" );
			if( html != 0 ){
				//$j("#ajaxContentAllapot").load("index.php?option=com_wh&format=raw&controller=hirlevel&task=getAllapot");
				setTimeout( "folyamat( "+html+" )", 3000 );
			}else{
				//$j("#ajaxContentAllapot").html('');
				alert('Hírlevélküldés kész');
			}
		}
	}).responseText;
}

function inditKuldes( hirlevel_id ){
	//var hirlevel_id = $j( '#id' ).val();
	var uzlet_id = $j( '#uzlet_id' ).val();	
	var url = "index.php?option=com_wh&format=raw";
	try{ url+="&controller=hirlevel" }catch(e){}
	try{ url+="&task=mentFolyamat" }catch(e){}
	try{ url+="&uzlet_id="+uzlet_id }catch(e){}
	try{ url+="&hirlevel_id="+hirlevel_id }catch(e){}
	if( uzlet_id ){
		$j.ajax({
			url: url,
			async: false,
			success: function( obj ){
				//var obj = $j.parseJSON( html );
				//alert(obj.html);
				var obj = $j.parseJSON( obj );
				//alert(obj.debug);
				//$j(ajaxContentId).html( obj.html+obj.debug );
				if( !obj.error ){
					folyamat(0);
				}
			}
		});
	}else{
		alert( this.VALASSZ_UZLETET );
	}
}