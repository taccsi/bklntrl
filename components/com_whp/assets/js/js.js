/*
function confirm(i){
    var options = '<br/><br/><input class="button1" value="Yes" type="button" onclick="return true"> <input class="button1" value="No" type="button" onclick="return false">';
    $j('#text').html(i+options);
    $j('#confirmDiv').fadeIn('fast');
}
*/

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





function initFacybox(){

	//$j("a.zoom").fancybox({"zoomSpeedIn":300,"zoomSpeedOut":300,"overlayShow":true, "overlayColor":"#000", "overlayOpacity":0.8, "cyclic":true}); 

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

	//alert($('ugy_id').value) ;

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