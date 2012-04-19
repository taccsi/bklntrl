function checkCimkeSzuro(){

	$j( ".ch_cimke" ).each( function(){

		

		if ($j(this).attr("checked")){

			//alert('dfsadf');

			kapcsolHiddenByCheck( 'cond_cimke_varazslo_check_'+$j(this).val(), 'cond_cimke_varazslo_hidden'+$j(this).val() ); setTalatiSzamlalo(); 

		}

	})

}

function kapcsolHiddenByCheck(check, hidden){

	//alert(check);

	var v_ = $j( "#"+check +":checked").val();

	if(v_){

		$j("#"+hidden).val( v_ );

	}else{

		$j("#"+hidden).val( '' );

	}

}



function setTalatiSzamlalo(){

	//alert( $j(".ch_cimke:checked").eachval() );

	//alert( $j("input[name='cond_cimke_varazslo[]']:checked").val() );

	//alert ("hello Szabi!");

	$j('.span_rendezes').css('display','none'); 

	$j("#ajaxContentTermekek").css('opacity','0.5');

    $j("#ajaxContentTermekekLoader").css('top','40px');

	var url="index.php?option=com_whp&controller=termekek&task=setTalatiSzamlalo&format=raw";

	url += "&"+$j("#formCimkeKereso").serialize();

	//url += "&termek_id="+$j('#id').val();

	//alert(url);

	$j.ajax({

      url: url,

      type: "POST",

      async: false,

      /*dataType: "html",*/

	  

      success: function(response){

         var resp= $j.parseJSON( response );

		 if( resp.error == '' ){


				$j("#div_talalatok").html( resp.html );

				//$j("#div_talalatok").html( '' );

			

			getTermekek();

		 }else{

		 	alert(resp.error);

		 }

		 //getKiegTermekek();

      }

	});

}



function getTermekek(){

	var url="index.php?option=com_whp&controller=termekek&task=getTermekek&format=raw&cimkekereso=1";

	//url += "&"+$j("#formCimkeKereso").serialize();

	//url += "&termek_id="+$j('#id').val();

	//alert(url);



	$j.each( $j("input[name='cond_cimke_varazslo[]']"), function(){

		url += ( $j(this).val() ) ? "&cond_cimke_varazslo[]=" + $j(this).val() : "" ;

	})

	var cond_kategoria_id = $j("#cond_kategoria_id").val();

	

	$j.each( $j("input[name='cond_megvasarolhato[]']"), function(){

		url += ( $j(this).val() ) ? "&cond_megvasarolhato[]=" + $j(this).val() : "" ;

	})

	var cond_kategoria_id = $j("#cond_kategoria_id").val();



	url += "&cond_kategoria_id="+cond_kategoria_id;

	//alert(url);

	$j.ajax({

      url: url,

      type: "POST",

      async: false,

      /*dataType: "html",*/

	  

      success: function(response){

         var resp= $j.parseJSON( response );

		 if( resp.error == '' ){

		 	$j("#ajaxContentTermekek").html( resp.html );

			

			

		 }else{

		 	alert(resp.error);

		 }

		 //$j("#ajaxContentTermekekLoader").css('opacity','1');

		$j("#ajaxContentTermekek").addClass('ajax_loaded');

		 $j("#ajaxContentTermekek").fadeTo('100', 1, function() {      		 $j("#ajaxContentTermekekLoader").css('top','-999px');         });

		 $j(".tooltip").tooltip({ 
				track: true, 
				delay: 0, 
				showURL: false, 
				showBody: " - ", 
				fade: 250 
			});
		 
		 //getKiegTermekek();

      }

	});

}