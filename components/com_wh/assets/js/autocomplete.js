function initAutoComplete( inputId, jsArr ){
	$j("#"+inputId).autocomplete( this[jsArr], { matchContains :true, selectFirst : false, max : 100} );
}
