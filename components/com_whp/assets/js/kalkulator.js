function kalkulalMeteresTermek(obj){
	var id = $j(obj).attr("id");
	var value = $j( obj ).val();
	if(!value){
		torolKosarAjax( );
		return false;
	}
	value = value.replace( ",", "." );
	//var szelesseg = $j("#szelesseg").val();
	var ea = $j("#egysegar_kal").val();
	var afa_kal = $j("#afa_kal").val();		
	var url = "index.php?option=com_whp&controller=termek&task=kalkulalMeteresTermek&format=raw";
	url += "&value=" + value;
	url += "&input_id=" + id;
	url += "&mennyiseg_kal=" + value
	url += "&ea=" + ea;
	url += "&afa_kal=" + afa_kal;
	url += "&termVarId=" + $j("#termVarSelect").val();
	url += "&termek_id=" + $j("#termek_id_").val();
	//url += getKalkulaciosInformaciok(url);
	//console.log( url );
	alert(url);			
	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      /*dataType: "html",*/
      success: function(response){
		 var resp= $j.parseJSON( response );
      	 if( !resp.error ){
			$j( "#ajaxContentKalkulatorEredmeny").html( resp.html );
			//$j("#mennyiseg_kal").val( resp.szukseges_hosszusag );
			//alert( resp.szukseges_hosszusag );
			//alert( kalkulaciosInformaciok );
			//var kalkulaciosInformaciok = "Szélesség: " + $j("#szelesseg").val() + " m|";
			//kalkulaciosInformaciok += "Hosszúság: " + resp.szukseges_hosszusag + " fm ";
			$j("#mennyiseg_kal").val(resp.szukseges_hosszusag);
			$j("#kalkulaciosInformaciok").val( kalkulaciosInformaciok );				
         }else{
            alert( resp.error );//error
         }
      }
	});
}
// JavaScript Document

function torolKosarAjax( obj ){
	if(obj) $j(obj).val('');
	$j( "#ajaxContentKalkulatorEredmeny" ).html('');
}

function kalkulalTekercsesTermek(obj){
	//alert( "kalkulalTekercsesTermek" );
	var id = $j(obj).attr("id");
	var value = $j( obj ).val();
	// alert( "kalkulalTekercsesTermek" );
	if(!value){ 
		torolKosarAjax( );
		return false;
	}
	value = value.replace( ",", "." );
	var szelesseg = $j("#szelesseg").val();
	var ea = $j("#egysegar_kal").val();
	var afa_kal = $j("#afa_kal").val();		
	var url = "index.php?option=com_whp&controller=termek&task=kalkulalTekercsesTermek&format=raw";
	url += "&value=" + value;
	url += "&input_id=" + id;
	url += "&szelesseg=" + szelesseg
	url += "&ea=" + ea;
	url += "&afa_kal=" + afa_kal;
	url += "&termVarId=" + $j("#termVarSelect").val();
	url += "&termek_id=" + $j("#termek_id_").val();
	//url += getKalkulaciosInformaciok(url);
	//console.log( url );
	//alert(url);			
	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      /*dataType: "html",*/
      success: function(response){
		 var resp= $j.parseJSON( response );
      	 if( !resp.error ){
			$j( "#ajaxContentKalkulatorEredmeny").html( resp.html );
			$j("#mennyiseg_kal").val( resp.szukseges_hosszusag );
			//$j("#mennyiseg_kal").val( 88888888 );
			
			//alert( resp.szukseges_hosszusag );
			//alert( kalkulaciosInformaciok );

			var kalkulaciosInformaciok = "Szélesség: " + $j("#szelesseg").val() + " m|";

			kalkulaciosInformaciok += "Hosszúság: " + resp.szukseges_hosszusag + " fm ";

			//$j("#mennyiseg_kal").val(resp.szukseges_hosszusag);

			$j("#kalkulaciosInformaciok").val( kalkulaciosInformaciok );				

         }else{

            alert( resp.error );//error

         }

      }

	});

}



function getKalkulaciosInformaciok( url ){

	//alert('fut');

	$j( ".termek_tipus_input" ).each(function(){

		var name = $j(this).attr("name");

		url += "&inputArrName[]=" + name;

		url += "&inputArrValue[]=" + $j(this).val();

		url += "&inputArrMe[]=" + $j(this).attr('me');

		//alert(name+': '+$j(this).val()+' '+$j(this).attr('me'));

	})

	return url;

}

function kalkulalCsomagoltTermek( obj ){
	//alert('dsffa');
	var id = $j(obj).attr("id");
	var value = $j( obj ).val();
	//alert(value);
	if(!value){
		torolKosarAjax( );
		return false;
	}
	value = value.replace( ",", "." );
	var csomagolasi_egyseg = $j("#csomagolasi_egyseg").val();
	var ea = $j("#egysegar_kal").val();
	var mennyisegi_egyseg_kal = $j("#mennyisegi_egyseg_kal").val();
	var afa_kal = $j("#afa_kal").val();		
	//var csomagolasi_ar = $j("#csomagolasi_ar").val();
	var url = "index.php?option=com_whp&controller=termek&task=kalkulalCsomagoltTermek&format=raw";
	url += "&value=" + value;
	url += "&input_id=" + id;
	url += "&csomagolasi_egyseg=" + csomagolasi_egyseg
	url += "&ea=" + ea;
	url += "&mennyisegi_egyseg_kal=" + mennyisegi_egyseg_kal;
	url += "&afa_kal=" + afa_kal;
	//url += "&csomagolasi_ar=" + csomagolasi_ar;
	url += "&termVarId=" + $j("#termVarSelect").val();
	url += "&termek_id=" + $j("#termek_id_").val();
	url += getKalkulaciosInformaciok(url);	
	//alert(url);			
	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      /*dataType: "html",*/
      success: function(response){
		 var resp= $j.parseJSON( response );
      	 if( !resp.error ){
			$j( "#ajaxContentKalkulatorEredmeny").html( resp.html );
			//alert(id);
			if( id == "mennyiseg_kal" ){
				$j("#csomagszam_kal").val( resp.csomag );
				//var kalkulaciosInformaciok = "Szükséges mennyiség: " + $j("#mennyiseg_kal").val() + " m2|";
				var kalkulaciosInformaciok = "Csomag: " + resp.csomag + " csomag";
				//resp.szamolt_mennyiseg
				$j("#kalkulaciosInformaciok").val( kalkulaciosInformaciok );				
			}else{
				// csomagszam_kal
				//alert( resp.csomag );
				$j("#csomagszam_kal").val( resp.csomag );
				$j("#mennyiseg_kal").val( resp.szamolt_mennyiseg );		
				//var kalkulaciosInformaciok = "Szükséges mennyiség: " + resp.szamolt_mennyiseg + " m2|";
				var kalkulaciosInformaciok = "Csomag: " + $j("#csomagszam_kal").val( ); 
				
				$j("#kalkulaciosInformaciok").val( kalkulaciosInformaciok ) + " csomag";;								
			}
         }else{
            alert( resp.error );//error
         }
      }
	});
}

function setKosarMezok(){
	var termek_id = $j("#termek_id_").val();
	var tv_id = $j("#termVarSelect").val();
	$j("#termVarId").val(tv_id);
	$j("#kosarba_id").val(termek_id);
	var url = "index.php?option=com_whp&controller=termek&task=setKosarMezok&format=raw";
	url += "&tv_id=" + tv_id;
	url += "&termek_id=" + termek_id;
	url += "&egysegar_kal=" + $j("#egysegar_kal").val();	
	//alert(url);
	//console.log(url) 
	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      /*dataType: "html",*/
      success: function(response){
		 var resp= $j.parseJSON( response );
      	 if( !resp.error ){
            $j( '.arHTML' ).html( resp.html );
			$j( "#ajaxContentKalkulatorEredmeny").html('');
			//getKosar();
         }else{
            alert( resp.error );//error
         }
      }
	});
}

function getKalkulator( mennyiseg ){
	var termek_id = $j("#termek_id_").val();
	var tv_id = $j("#termVarSelect").val();
	var url = "index.php?option=com_whp&controller=termek&task=getKalkulator&format=raw";
	url += "&termek_id=" + termek_id;
	url += "&tv_id=" + tv_id;
	url += "&mennyiseg=" + mennyiseg;	
	url += "&egysegar_kal="+$j("#egysegar_kal").val();
	//console.log(url); 
	//alert(url);
	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      success: function(response){
         var resp= $j.parseJSON( response );
         if( !resp.error ){
            $j( '#ajaxContentKalkulator' ).html( resp.html );
			var termek_tipus = $j("#termek_tipus_").val();
			if( termek_tipus == "CSOMAGOLT_ARU" ){
				kalkulalCsomagoltTermek( $j("#mennyiseg_kal") );
			}
			if( termek_tipus == "TEKERCSES_ARU" ){
				kalkulalTekercsesTermek( $j("#mennyiseg_kal") );
			}
			if( termek_tipus == "METERES_TERMEKEK" ){
				//alert( $j("#mennyiseg_kal") );
				kalkulalMeteresTermek( $j("#mennyiseg_kal") );
			}
         }else{
            alert( resp.error );//error
         }
      }
	});
}

function getKalkulaltKosar( kalkulaltMennyiseg ){

	var azonosito_kod = $j('#azonosito_kod').val();	

	var url = "index.php?option=com_whp&controller=termek&task=getKalkulaltKosar&format=raw";

	url += "&kalkulaltMennyiseg=" + kalkulaltMennyiseg;

	//alert(url);

	$j.ajax({

      url: url,

      type: "POST",

      async: false,

      /*dataType: "html",*/

      success: function(response){

         var resp= $j.parseJSON( response );

         if( !resp.error ){

            $j( '#ajaxContentKalkulaltKosar' ).html( resp.html );

			//getKosar();

         }else{

            alert( resp.error );//error

         }

      }

	});

}



function kalkulalMennyiseg( m2csomag ){

	var m2 = $j("#m2").val();

	m2 = m2.replace(",", ".");

	var m2 = parseFloat( m2 );

	if(!m2){

		alert( KEREM_ADJON_MEG_ERTEKET );

	}else{

		m2csomag = parseFloat( m2csomag );

		var csomag = Math.ceil( m2/m2csomag ) ;

		var kalkulaltMennyiseg = csomag * m2csomag ;

		getKalkulaltKosar( kalkulaltMennyiseg );

	}

}





function isInteger(s) {

  return (s.toString().search(/^-?[0-9]+$/) == 0);

}

