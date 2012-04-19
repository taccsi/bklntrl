function getCommentList(termek_id, limit){

	

	var url = "index.php?option=com_whp&controller=termek&format=raw";

	url += "&task=getCommentList";

	url += "&termek_id="+termek_id;	

	url += "&limit="+limit;	

	//alert( url );		

	var ret = false;

	$j.ajax({

      url: url,

      type: "POST",

      async: false,

      success: function(response){

         var resp= $j.parseJSON( response );

         if( !resp.error ){

 			$j("#ajaxContentUzenetek").html( resp.html );

			initStarsLista();

         }else{

         }

      }

	});

}



function initStarsLista(){

	//alert('');

	$j('.hover-star').rating( {'readOnly':true} );

}



function initStars(){

	$j('.hover-star').rating({});

}



function initCommentLink(){

	$j("a.a_hozzaszolas").fancybox({"zoomSpeedIn":300,"zoomSpeedOut":300,"overlayShow":true,"hideOnOverlayClick":true, "overlayColor":"#000", "overlayOpacity":0.8, "cyclic":true}); 

}



function initajaxForm( formId ){

	//alert( ('#'+formId) );

	$j( '#'+formId).ajaxForm(

		{success: showResponse, 

		beforeSubmit: validateForm,

		resetForm : true

		}

	);

	//alert( formId );

}



function initajaxFormComment( formId ){

	//alert( ('#'+formId) );

	$j( '#'+formId).ajaxForm(

		{success: showResponseComment, 

		beforeSubmit: validateForm,

		resetForm : true

		}

	);

	//alert( formId );

}



function showResponseComment(responseText, statusText, xhr, $form ){ 

	var obj = $j.parseJSON( responseText );

	var formId = $form.attr("id");

	//$j("#"+formId + " #id").val(obj.id);

	alert( KOSZONJUK_AZ_ERTEKELEST );

	getCommentList( $j("#"+formId+" #termek_id").val(),3 );

	$j.fancybox.close();

}



function showResponse(responseText, statusText, xhr, $form ){ 

	var obj = $j.parseJSON( responseText );

	var formId = $form.attr("id");

	//$j("#"+formId + " #id").val(obj.id);

	alert( SIKERES_MENTES );

	getCommentList($j("#"+formId+" #termek_id").val(),3);

}



function initajaxFormReccomend( formId ){

	$j( '#'+formId).ajaxForm(

		{

			success: function(){alert( SIKERES_AJANLAS ); $j.fancybox.close(); },

			beforeSubmit: validateFormRecommend,

			resetForm : true

		}

	);

}



function validateFormRecommend( formData, jqForm, options ) { 

	var url = "index.php?";

	url += $j( jqForm ) . formSerialize();

	var form = jqForm[0];

	if(

		form.ajanlo_nev.value &&

		//form.ajanlo_email.value &&

		form.cimzett_nev.value &&

		form.cimzett_email.value 

		//form.szoveg.value

	){

		return true;

	}else{

		alert( KEREM_MINDEN_ADATOT_TOLTSON_KI );

		return false;

	}

}



function validateForm( formData, jqForm, options ) { 

	var url = "index.php?";

	//alert($j(jqForm).attr("id"));

	url += $j( jqForm ) . formSerialize();

	//url += $j( "#"+"commentForm" ) . formSerialize();

	url += "&task=validateForm";

	//url += "&task=$j( jqForm ).validateFunc";	

	//alert( url );		

	//$j( "#"+"commentForm task" ).val('validate');

	var ret = false;

	$j.ajax({

      url: url,

      type: "POST",

      async: false,

      success: function(response){

         var resp= $j.parseJSON( response );

         if( !resp.error ){

			ret = true;

         }else{

			alert( resp.error );//error

			ret = false;			 

         }

      }

	});

	return ret;

}