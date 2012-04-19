



function termvarIrany(irany, termvar_id){

	termek_id = $('id').value;

	var fx=new Fx.Style($("ajaxContentParameterLista"), "color", {duration:400});

		//$("ajaxContentParameterLista").empty().addClass("ajax-loading").setHTML("<img src='components/com_whp/assets/images/ajax-loader.gif' border='0'>" );

        var url="index.php?option=com_whp&controller=termek&task=termvarIrany&format=raw&termek_id="+termek_id+"&termvar_id="+termvar_id+"&irany="+irany;

        var a=new Ajax(url,{

            method:"post",

            onComplete: function(response) {

                var resp=Json.evaluate(response);

                $("ajaxContentParameterLista").removeClass("ajax-loading").setHTML(resp.html);

                fx.set("#fff").start("#000").chain(function() {

                    this.start.delay(0, this, "#000");

                });

            }

        }).request();

}



function changeVal(obj, caller){

	if(caller.checked){

		obj.value = 1;

	}else{

		obj.value=2;

	}

	/*

	if(obj.value==1){

		obj.value=2;

	}else{

		obj.value=1;

	}

	*/

	//alert(obj);

}



function sorrend(id, irany){

		document.getElementById('sorrendId').value=id;

		document.getElementById('irany').value=irany;				

		document.getElementById('task').value='sorrend';

		document.getElementById('adminForm').submit();

	}



function setArMezok(id, N, B, sw){

	var afa =$(id)[$(id).selectedIndex].text;

	arNettoBrutto(N, B, afa, sw);

}



function arNettoBrutto(N, B, afa, sw){

	var sz = afa/100+1;	

	if(sw=="nettoBol"){

		$(B).value = $(N).value*sz;

	}else{

		var sz = afa/100+1;

		$(N).value = $(B).value/sz;

	}

}



function letrehozUjTermekVariacio(termek_id){

	//alert(parameter_id);

	var fx=new Fx.Style($("ajaxContentParameterLista"), "color", {duration:400});

		$("ajaxContentParameterLista").empty().addClass("ajax-loading").setHTML("<img src='components/com_whp/assets/images/ajax-loader.gif' border='0'>" );

        var url="index.php?option=com_whp&controller=termek&task=letrehozUjTermekVariacio&format=raw&termek_id="+termek_id;

        var a=new Ajax(url,{

            method:"post",

            onComplete: function(response) {

                var resp=Json.evaluate(response);

                $("ajaxContentParameterLista").removeClass("ajax-loading").setHTML(resp.html);

                fx.set("#fff").start("#000").chain(function() {

                    this.start.delay(0, this, "#000");

                });

            }

        }).request();

}



function torolTermekVariacio(tvar_id, termek_id){

	//alert(parameter_id);

	var fx=new Fx.Style($("ajaxContentParameterLista"), "color", {duration:400});

		$("ajaxContentParameterLista").empty().addClass("ajax-loading").setHTML("<img src='components/com_whp/assets/images/ajax-loader.gif' border='0'>" );

        var url="index.php?option=com_whp&controller=termek&task=torolTermekVariacio&format=raw&tvar_id="+tvar_id+"&termek_id="+termek_id;

        var a=new Ajax(url,{

            method:"post",

            onComplete: function(response) {

                var resp=Json.evaluate(response);

                $("ajaxContentParameterLista").removeClass("ajax-loading").setHTML(resp.html);

                fx.set("#fff").start("#000").chain(function() {

                    this.start.delay(0, this, "#000");

                });

            }

        }).request();

}



function getTermekVariaciok(termek_id){

	var fx=new Fx.Style($("ajaxContentParameterLista"), "color", {duration:400});

		$("ajaxContentParameterLista").empty().addClass("ajax-loading").setHTML("<img src='components/com_whp/assets/images/ajax-loader.gif' border='0'>" );

        var url="index.php?option=com_whp&controller=termek&task=getTermekVariaciok&format=raw&termek_id="+termek_id;

        var a=new Ajax(url,{

            method:"post",

            onComplete: function(response) {

                var resp=Json.evaluate(response);

                $("ajaxContentParameterLista").removeClass("ajax-loading").setHTML(resp.html);

                fx.set("#fff").start("#000").chain(function() {

                    this.start.delay(0, this, "#000");

                });

            }

        }).request();

}



function kapcsolHiddenByCheck(check, hidden){

	if($(check).checked){

		$(hidden).value = $(check).value;

	}else{

		$(hidden).value ='';

	}

}



function torolKep(kep_id){

	var termek_id = $('id').value;

	var fx=new Fx.Style($('ajaxContentKepek'), "color", {duration:400});

		$('ajaxContentKepek').empty().addClass("ajax-loading").setHTML('<img src="components/com_whp/assets/images/ajax-loader.gif">' );

        var url="index.php?option=com_whp&controller=termek&task=torolKep&format=raw&kep_id="+kep_id+"&termek_id="+termek_id;

        var a=new Ajax(url,{

            method:"post",

            onComplete: function(response) {

				//alert("d");

                var resp=Json.evaluate(response);

                $('ajaxContentKepek').removeClass("ajax-loading").setHTML(resp.html);

                fx.set("#fff").start("#000").chain(function() {

                    this.start.delay(0, this, "#000");

                });

            }

        }).request();

}



function getKepLista(){

	//alert('fsdfsdf');

	var termek_id = $('id').value;

	var fx=new Fx.Style($('ajaxContentKepek'), "color", {duration:400});

		$('ajaxContentKepek').empty().addClass("ajax-loading").setHTML('<img src="components/com_whp/assets/images/ajax-loader.gif">' );

        var url="index.php?option=com_whp&controller=termek&task=getKepLista&format=raw&termek_id="+termek_id;

        var a=new Ajax(url,{

            method:"post",

            onComplete: function(response) {

				//alert("d");

                var resp=Json.evaluate(response);

                $('ajaxContentKepek').removeClass("ajax-loading").setHTML(resp.html);

                fx.set("#fff").start("#000").chain(function() {

                    this.start.delay(0, this, "#000");

                });

				$j("a.zoom").fancybox({"zoomSpeedIn":300,"zoomSpeedOut":300,"overlayShow":true,"hideOnOverlayClick":true});

            }

        }).request();

}





/*

function initSortable__(){

   $j(document).ready(

      function(){ 

	  //alert("dasdasd");

         //$j( "#sortable_" ).bind( "sortupdate", function(event, ui) {     });

         $j( "#sortable" ).sortable({ cursor: 'crosshair' });        

         $j("#sortable").sortable();         

      }

   ); 

}



function initSortable(){

	//alert("sd");

	//$j( "#sortable_" ).bind( "sortupdate", function(event, ui) {     });

	//$j( "#sortable" ).sortable({ cursor: 'crosshair' });        

	$j("#sortable").sortable();         

}

*/