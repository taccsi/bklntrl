/*
function confirm(i){
    var options = '<br/><br/><input class="button1" value="Yes" type="button" onclick="return true"> <input class="button1" value="No" type="button" onclick="return false"></input>';
    $j('#text').html(i+options);
    $j('#confirmDiv').fadeIn('fast');
}
*/
function clearBasketField(termek_id){
	$j('#mennyiseg_kosarba_'+termek_id).val('');
}

function setFoglalGomb(termek_id){
	var url = "index.php?option=com_whp&controller=termek&task=getFoglalGomb&format=raw";
	url += "&termek_id="+termek_id;
	//alert(url);
	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      /*dataType: "html",*/
      success: function(response){
         var resp= $j.parseJSON( response );
         if( !resp.error ){
            $j( '#ajaxContentFoglalgomb' ).html( resp.html );
			//getKosar();
         }else{
            alert( resp.error );//error
         }
      }
	});
}


function setFoglalasIdopont(){
	var idopont = $j('#idopont').val();
	var url = "index.php?option=com_whp&controller=kosar&task=setFoglalasIdopont&format=raw";
	url +='&idopont='+idopont;
	//alert(url);

	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      /*dataType: "html",*/
      success: function(response){
         var resp= $j.parseJSON( response );
         if( !resp.error ){
           alert(resp.message);
            //$j( '#ajaxContentKiskosar' ).html( resp.html );
		//getKosar();
         }else{
            alert( resp.error );//error
         }
     }
	});
	getKiskosar();
}

function blurField(obj){
	var def = $j(obj).attr('default');
	var val = $j(obj).val();
	
	if (val == ''){$j(obj).val(def)}
}
function clickField(obj){
	var def = $j(obj).attr('default');
	var val = $j(obj).val();
	
	if (def == val){$j(obj).val('')}
}
function setConfirm(){
	$j('.confirm').click(function(){
		var elem = $j(this).closest('.item');
		$j.confirm({
			'title'		: RENDSZERUZENET,
			'message'	: ON_REGISZTRALT_FELHASZNALO,
			'buttons'	: {
				'Igen'	: {
					'class'	: 'gray',
					'action': function(){
						window.location = "index.php?option=com_whp&controller=felhasznalo&Itemid=35";
					}
				},
				'Nem'	: {
					'class'	: 'gray',
					'action': function(){
						$j('#adminForm > #controller').val('rendeles'); $j('#adminForm').submit();
						//window.location = "index.php?option=com_whp&controller=felhasznalo&Itemid=35";
					}
				}
			}
		});
	});
}
function submitBasketForm(kosarId,termek_id,userEditDefault){
	var userEdit = $j('#userEdit_'+termek_id).val();
	var mennyiseg = $j('#mennyiseg_kosarba_'+termek_id).val(); 
	//alert(mennyiseg);
	/*var termVarId = $j('#termVarId').val();
	if(termVarId){
		addToBasket(kosarId,'1',termek_id,$j('#termVarId').val(),$j(#userEdit_{$termek_id}.val()));
	}else{
		alert('".jtext::_("KEREM_VALASSZON_TERMEKVARIACIOT")."')
	}*/
	
	if (userEdit){
		if (userEdit == ''){alert('Kérem, töltse ki a mezőt a kívánt domainnévvel!'); return false;}			
		if (userEdit == userEditDefault){alert('Kérem, töltse ki a mezőt a kívánt domainnévvel!'); return false;}
		addToBasket(kosarId,1,termek_id,0,0);
		
	} else {
		
		addToBasket(kosarId,mennyiseg,termek_id,0,0);
	}
	
}
function removeBasket(kosarKulcs){
	var url = "index.php?option=com_whp&controller=kosar&task=tetel_torol&format=raw";
	
	//alert(url);

	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      data: { tetel_id: escape(kosarKulcs)},
      dataType: "html",
     	
      success: function(response){
         var resp= $j.parseJSON( response );
        
         if( !resp.error ){
           alert(resp.msg);
           getKiskosar();
            //$j( '#ajaxContentKiskosar' ).html( resp.html );
		//getKosar();
         }else{
            
            alert( resp.error );//error
         }
     }
	});
	//alert('sdfs');
	
}
function addToBasket(kosarId,mennyiseg,termek_id,termVarId,userEdit){
	

	var url = "index.php?option=com_whp&controller=kosar&task=addToBasket&format=raw";
	url +='&kosarId='+kosarId;
	url +='&mennyiseg='+mennyiseg;
	url +='&termek_id='+termek_id;
	url +='&termVarId='+termVarId;
	url +='&userEdit='+userEdit;
	//alert(url);
	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      /*dataType: "html",*/
      success: function(response){
         var resp= $j.parseJSON( response );
         if( !resp.error ){
            //$j( '#ajaxContentKiskosar' ).html( resp.html );
			alert('A tétel a kosárba került!'); 
			//getKosar();
         }else{
            alert( resp.error );//error
         }
     }
	});
	getKiskosar();
}
function initKeresoEnter(){

	$j('#vsSearchForm_mini').bind( 'keypress', function(e) {

		if( e.keyCode==13 ){

			elokeszitKereses( 'kulcsszó...' );

			$j('input[name=indexpage]').val('');

			$j('input[name=cond_kategoria_id]').val('');
			
			$j('input[name=cond_kampany_id]').val('');

			$j('#vsSearchForm_mini').submit()			

		}

	});

}

	



function formazKosar(){

	$j(document).ready(function() {

		initKeresoEnter();

		$j("td:contains('Szállítási költség')").parent().addClass('szallitas');

	});

}



function checkInt(){

 if($j('.mennyiseg_kosarba').val().match('^(0|[1-9][0-9]*)$')){

  

  } else {

	  alert ('Kérem, egész számot adjon meg!');

   	  $j('.mennyiseg_kosarba').val('1');

}

   

}



function getKosar(){

	var szallitasiMod = $j('#szallitas').val();

	url = "index.php?option=com_whp&controller=rendeles&task=getKosar&format=raw&szallitasiMod="+szallitasiMod;

	$j("#ajaxContentKosar").html( '' );

	$j("#ajaxContentKosar").load( url );

	getAjaxMezok();

}



function getAjaxMezok(){

	var szallitasiMod = $j('#szallitas').val();

	var azonosito_kod = $j('#azonosito_kod').val();	

	var url = "index.php?option=com_whp&controller=rendeles&task=getAjaxMezok&format=raw";

	if (szallitasiMod !=''){ url +='&szallitasiMod='+szallitasiMod}

	//alert(url);

	$j.ajax({

      url: url,

      type: "POST",

      async: false,

      /*dataType: "html",*/

      success: function(response){

         var resp= $j.parseJSON( response );

         if( !resp.error ){

			//alert('----');

            $j( '#ajaxContentRendeles' ).html( resp.html );

			//getKosar();

         }else{

            alert( resp.error );//error

         }

      }

	});

}



function getKiskosar(){
	var azonosito_kod = $j('#azonosito_kod').val();	
	var url = "index.php?option=com_whp&controller=kosar&task=getKiskosar&format=raw";
	//alert(url);
	$j.ajax({
      url: url,
      type: "POST",
      async: false,
      /*dataType: "html",*/
      success: function(response){
         var resp= $j.parseJSON( response );
         if( !resp.error ){
            $j( '#ajaxContentKiskosar' ).html( resp.html );
			//getKosar();
         }else{
            alert( resp.error );//error
         }
      }
	});
	setFoglalGomb();
}



function ellenorizKupon(){

	var azonosito_kod = $j('#azonosito_kod').val();	

	var url = "index.php?option=com_whp&controller=rendeles&task=ellenorizKupon&format=raw";

	url += "&azonosito_kod="+azonosito_kod;

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

            $j( '#ajaxContentKupon' ).html( resp.html );

			getKosar();

			getKiskosar();

         }else{

            alert( resp.error );//error

         }

      }

	});

}



function initAutoCompleteKereso( inputId, arrName ){

	//alert(inputId);

	var option = {

		matchContains :true,

		selectFirst : false,

		formatItem: function(row, i, max) {

			//return i + "/" + max + ": \"" + row.option + "\" [" + row.value + "]";

			//return row.nev + ' (' + row.cikkszam + ')';

			return row.nev;

		},

		

		formatMatch: function(row, i, max) {

			//$j("#"+inputIdHidden).val( row.value+2222 );

			//return row.nev + ' (' + row.cikkszam + ')';// + row.ID ;

			return row.nev;			

		},

		

		formatResult: function(row) {

			//alert(inputIdHidden);

			return row.nev;

		},

		max : 100

	}

	//alert(  "#"+inputId );

	$j( "#"+inputId ).autocomplete( this[arrName], option );

}



function initAutoCompleteKereso__( inputId, arrName ){

	var option = {

		matchContains :true,

		selectFirst : false,

		formatItem: function(row, i, max) {

			//return i + "/" + max + ": \"" + row.option + "\" [" + row.value + "]";

			return row.nev;

		},

		

		formatMatch: function(row, i, max) {

			//$j("#"+inputIdHidden).val( row.value+2222 );

			return row.nev;// + row.ID ;

		},

		

		formatResult: function(row) {

			//alert(inputIdHidden);

			return row.nev;

		},

		max : 100

	}

	//alert(  "#"+inputId );

	$j( "#"+inputId ).autocomplete( this[arrName], option );

}





function initFancybox(){

	$j("a.zoom").fancybox({"zoomSpeedIn":300,"zoomSpeedOut":300,"overlayShow":true, "overlayColor":"#000", "overlayOpacity":0.8, "cyclic":true}); 

	//$j("a.ajanlo").fancybox({"zoomSpeedIn":300,"zoomSpeedOut":300,"overlayShow":true, "overlayColor":"#000", "overlayOpacity":0.8}); alert('lefut');	



}



function elokeszitKereses( COND_NEV ){

	//alert( COND_NEV );

	if( $j('#cond_nev').val() == COND_NEV ){

		$j('#cond_nev').val('');

	}

	//alert('dsffs');

	

	//try{if($('cond_nev2').value == KERESOSZO) $('cond_nev2').value='';} catch(e){};	

	//try{if($('cond_kiado').value == KIADO) $('cond_kiado').value='';} catch(e){};

	//try{if($('cond_barmilyen_kifejezes').value == EGYEZES_BARHOL) $('cond_barmilyen_kifejezes').value='';} catch(e){};	

}



function getUrlVars()

{

    var vars = [], hash;

    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

    for(var i = 0; i < hashes.length; i++)

    {

        hash = hashes[i].split('=');

        vars.push(hash[0]);

        vars[hash[0]] = hash[1];

    }

    return vars;

}



function initAutoComplete(){

	//alert("termekek_");

	try{

		//$j("#cond_nev").autocomplete( termekek, { matchContains :true, selectFirst : false, max : 100} );

	}catch( e ){

	}

	try{

		//switch()

		var v_ = this["termekek"];				

		$j("#cond_nev2").autocomplete( v_, { matchContains :true, selectFirst : false, max : 100} );

	}catch(e){

	}	

}





function MasolSzallitasiAdatok(){

	

	$j('#szamlazasi_nev').val( $j('#sz_nev').val() );

	$j('#irszam').val($j('#sz_irszam').val() );

	$j('#varos').val($j('#sz_varos').val() );

	$j('#utca').val($j('#sz_utca').val() );

}



/*function szallitasiMod(){

	var szallitasiMod = $j('#szallitas').val();

	url = "index.php?option=com_whp&controller=rendeles&task=getAjaxMezok&format=raw&szallitasiMod="+szallitasiMod;

	$j("#ajaxContentRendeles").html( '' );

	$j('#ajaxContentRendeles').ajaxSuccess(function() {

		//alert('--');

	  //getKosar();

	});

	

	$j("#ajaxContentRendeles").load( url );

	//alert( szallitasiMod );

}

*/

function kosarba(mennyId, id, termVarSelectId, alertMsg){

	if( termVarSelectId && !$(termVarSelectId).value ){

		alert( alertMsg );

	}else{

		

		document.getElementById('mennyiseg_kosarba').value=$(mennyId).value;	

		document.getElementById('kosarba_id').value=id;

		//alert($j('#adminForm'));	

		$j("#adminForm > #controller").val('kosar');

		//alert($j('#adminForm > #controller').val());

		document.getElementById('task').value='add';

		//alert( $('task').value );

		document.getElementById('adminForm').submit();

	}

}



function torolUzenet( uzenet_id,ugy_id,controller ){



    var url="index.php?option=com_whp&controller="+controller+"&task=torolUzenet&format=raw&uzenet_id="+uzenet_id+"&ugy_id="+ugy_id;

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

            $j( '#ajaxContentUzenetek' ).html( resp.html );

         }else{

            alert( resp.error );//error

         }

      }

	});

}



function listazUzenetek( ){

	//alert(ugy_id);

	//alert($('ugy_id').value) ;

	var url = "index.php?option=com_whp&controller=termek&task=listazUzenet&format=raw";

	url += "&termek_id=" + $j("#termek_id").val( );

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

            $j( '#ajaxContentUzenetek' ).html( resp.html );

         }else{

            alert( resp.error );//error

         }

      }

	});

}



function addUzenet( ){

	

		

		//alert(ugy_id);

		var termek_id = $j('#termek_id').val();

		var szoveg = $j('#szoveg').val();

		var nev = $j('#nev').val();

		var user_nev = $j('#user_nev').val();

		var user_email = $j('#user_email').val();

		var aktiv = $j('#aktiv').val();

		var datum = $j('#datum').val();

		//alert(fcsoport_id);

		

		var url="index.php?option=com_whp&controller=termek&task=addUzenet&format=raw&termek_id="+termek_id+"&datum="+datum+"&szoveg="+szoveg+"&nev="+nev+"&user_nev="+user_nev+"&user_email="+user_email;

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

            $j( '#ajaxContentUzenetek' ).html( resp.html );

         }else{

            alert( resp.error );//error

         }

      }

	});



}

function urlencode(str) {

	str = escape(str);

	str = str.replace('+', '%2B');

	str = str.replace('%20', '+');

	str = str.replace('*', '%2A');

	str = str.replace('/', '%2F');

	str = str.replace('@', '%40');

return str;

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

$j(document).ready(function(){ ellenorizTelefonszam( "telefon" );})

function ellenorizTelefonszam( inputId ){
	$j( '#'+inputId ).bind( 'keypress', function(e){
		var code = (e.keyCode ? e.keyCode : e.which);
		if( 48 <= code && code <= 57 ){
			//alert("ok");
		}else{
			//alert("nem ok");
			var str = $j("#"+inputId).val();
			//alert(str);
			//str.replace(code, "" );
			//$j("#"+inputId).val( str );
		}
	});
}

function addAjax(kosarId){
	//alert( kosarId );
}

function feladKosar(){
	if($j("#termVarId").val() != '' && $j("#kosarba_id").val() !='' && $j("#termVarId").val() != '0' && $j("#kosarba_id").val() !='0'){
		$j('#TVkosar').submit()
	}else{
		alert('Kérem, válasszon terméket!');
	}
}

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
	var id = $j(obj).attr("id");
	var value = $j( obj ).val();
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
			//$j("#mennyiseg_kal").val( 111 );
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
