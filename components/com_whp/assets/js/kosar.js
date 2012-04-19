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