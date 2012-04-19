function saveLang( formId, code, controller ){

	//alert(formId);

	var url="index.php?option=com_wh&controller=" + controller + "&task=saveLang&format=raw";

	url += "&lang_code="+code;

	url += "&productId="+$j( "#id" ).val();

	var value = "";

	//alert(formId);
	$j( "#"+formId +" .alapinput, " + "#"+formId + " .wfEditor" ).each( function(){

		
		if( $j(this).attr("type") != 'button' ){

			var id = $j(this).attr( "id" );
			var clss = $j(this).attr( "class" );
			
			if( clss.match( 'wfEditor' ) ){
				//alert(id);
				//id = 'leiras_' + code;

				url += "&"+id+"=" + encodeURI( WFEditor.getContent(id) );

				//url += "&"+id+"=" + $j.URLEncode( JContentEditor.getContent(id) );

				//alert( JContentEditor.getContent(id) );

			}else{

				value = $j("#"+id).val();

				url += "&"+id+"=" + encodeURI( value );				

				//url += "&"+id+"=" + $j.URLEncode(  value );

			}

			//url += "&"+id+"=" + $j("#"+id).val();

		}

	});

	alert( url );



	$j.ajax({

      url: url,

      type: "POST",

      async: false,

      /*dataType: "html",*/

      success: function(response){

		 //alert(response);

         var resp= $j.parseJSON( response );

         if( !resp.error ){

			alert(SAVED);

         }else{

            alert( resp.error );//error

         }

      }

	});

}



function initTabs(){

	//alert('-');

	$j(".tab_content").hide(); //Hide all content

	$j("ul.tabs li:first").addClass("active").show(); //Activate first tab

	$j(".tab_content:first").show(); //Show first tab content

	//On Click Event

	$j("ul.tabs li").click( function() {

		if( $j(this).attr("type") == "pictures" ){

			//getKepLista();

			//$j('#uploader').fileUploader();

		}

		$j("ul.tabs li").removeClass("active"); //Remove any "active" class

		$j(this).addClass("active"); //Add "active" class to selected tab

		$j(".tab_content").hide(); //Hide all tab content

		var activeTab = $j(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content

		$j(activeTab).fadeIn(); //Fade in the active ID content

		return false;

	});

}

