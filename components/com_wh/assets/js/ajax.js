function torolKategoriaKedvezmeny( kategoria_kedvezmeny_id  ){
	//alert(fcsoport_id);
	var fcsoport_id = $('id').value;	
	var fx=new Fx.Style($("ajaxContentKategoriaKedvezmenyek"), "color", {duration:400});
		$("ajaxContentKategoriaKedvezmenyek").empty().addClass("ajax-loading").setHTML("<img src='components/com_wh/assets/images/ajax-loader.gif' border='0'>" );
        var url="index.php?option=com_wh&controller=fcsoport&task=torolKategoriaKedvezmeny&format=raw&kategoria_kedvezmeny_id="+kategoria_kedvezmeny_id+"&fcsoport_id="+fcsoport_id;
        var a=new Ajax(url,{
            method:"post",
            onComplete: function(response) {
                var resp=Json.evaluate(response);
                $("ajaxContentKategoriaKedvezmenyek").removeClass("ajax-loading").setHTML(resp.html);
                fx.set("#fff").start("#000").chain(function() {
                    this.start.delay(0, this, "#000");
                });
            }
        }).request();
}

function listazKategoriaKedvezmeny(  ){
	var fcsoport_id = $j('#id').val();
	//alert(fcsoport_id);
	/*
	var fx=new Fx.Style($("ajaxContentKategoriaKedvezmenyek"), "color", {duration:400});
		$("ajaxContentKategoriaKedvezmenyek").empty().addClass("ajax-loading").setHTML("<img src='components/com_wh/assets/images/ajax-loader.gif' border='0'>" );
        var url="index.php?option=com_wh&controller=fcsoport&task=listazKategoriaKedvezmeny&format=raw&fcsoport_id="+fcsoport_id;
		//alert(url);
        var a=new Ajax(url,{
            method:"post",
            onComplete: function(response) {
                var resp=Json.evaluate(response);
                $("ajaxContentKategoriaKedvezmenyek").removeClass("ajax-loading").setHTML(resp.html);
                fx.set("#fff").start("#000").chain(function() {
                    this.start.delay(0, this, "#000");
                });
            }
        }).request();
	*/
	var url="index.php?option=com_wh&controller=fcsoport&task=listazKategoriaKedvezmeny&format=raw&fcsoport_id="+fcsoport_id;
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
			//listazCimkek( termek_id );
			$j("#ajaxContentKategoria").html( resp.html );
         }else{
            alert( resp.error );//error
         }
      }
	});
        
}

function mentKategoriaKedvezmeny(  ){
	var kategoria_kedvezmeny_tipus = $('kategoria_kedvezmeny_tipus').value;
	var kategoria_kedvezmeny = $('kategoria_kedvezmeny').value;
	var kategoria_id = $('kategoria_id').value;
	var fcsoport_id = $('id').value;
	//alert(fcsoport_id);
	var fx=new Fx.Style($("ajaxContentKategoriaKedvezmenyek"), "color", {duration:400});
		$("ajaxContentKategoriaKedvezmenyek").empty().addClass("ajax-loading").setHTML("<img src='components/com_wh/assets/images/ajax-loader.gif' border='0'>" );
        var url="index.php?option=com_wh&controller=fcsoport&task=mentKategoriaKedvezmeny&format=raw&kategoria_kedvezmeny_tipus="+kategoria_kedvezmeny_tipus+"&kategoria_kedvezmeny="+kategoria_kedvezmeny+"&fcsoport_id="+fcsoport_id+"&kategoria_id="+kategoria_id;
        var a=new Ajax(url,{
            method:"post",
            onComplete: function(response) {
                var resp=Json.evaluate(response);
                $("ajaxContentKategoriaKedvezmenyek").removeClass("ajax-loading").setHTML(resp.html);
                fx.set("#fff").start("#000").chain(function() {
                    this.start.delay(0, this, "#000");
                });
            }
        }).request();
}

function setKategoriak( elementId ){
	var webshop_id = $(elementId).value;
	var url="index.php?option=com_wh&controller=fcsoport&task=setKategoriak&format=raw&webshop_id="+webshop_id;
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
			//listazCimkek( termek_id );
			$j("#ajaxContentKategoria").html( resp.html );
         }else{
            alert( resp.error );//error
         }
      }
	});
}

function letrehozUjParameter(termek_id){
	//alert(parameter_id);
	var fx=new Fx.Style($("ajaxContentParameterLista"), "color", {duration:400});
		$("ajaxContentParameterLista").empty().addClass("ajax-loading").setHTML("<img src='components/com_wh/assets/images/ajax-loader.gif' border='0'>" );
        var url="index.php?option=com_wh&controller=ajax&task=letrehozUjParameter&format=raw&termek_id="+termek_id;
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

function torolParameter(parameter_id, termek_id){
	//alert(parameter_id);
	var fx=new Fx.Style($("ajaxContentParameterLista"), "color", {duration:400});
		$("ajaxContentParameterLista").empty().addClass("ajax-loading").setHTML("<img src='components/com_wh/assets/images/ajax-loader.gif' border='0'>" );
        var url="index.php?option=com_wh&controller=ajax&task=torolParameter&format=raw&parameter_id="+parameter_id+"&termek_id="+termek_id;
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

function getParameterek(termek_id){
	var fx=new Fx.Style($("ajaxContentParameterLista"), "color", {duration:400});
		$("ajaxContentParameterLista").empty().addClass("ajax-loading").setHTML("<img src='components/com_wh/assets/images/ajax-loader.gif' border='0'>" );
        var url="index.php?option=com_wh&controller=ajax&task=getParameterek&format=raw&termek_id="+termek_id;
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

function torolszolg(){
	try{
		$('id_radioTv').value="";
		$('id_radioInternet').value="";
		$('id_radioTelefon').value="";
		$('id_radioKombinalt').value="";		
	}catch(err){
	}
}

//window.addEvent("domready", ajaxHivas);
