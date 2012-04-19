//alert("valami");
function initDateField(){
	//alert('initDatum');
	var option = { /*appendText: '(éééé-hh-nn)', */
		yearRange: '2010:2020',	
		/* dateFormat: 'yy-mm-dd' , */
		dateFormat: 'yy-mm-dd' ,
		dayNames: ['Vasárnap', 'Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek', 'Szombat'],
		dayNamesShort: ['V', 'H', 'K', 'Sze', 'Cs', 'P', 'Szo'],
		dayNamesMin:['V', 'H', 'K', 'Sze', 'Cs', 'P', 'Szo'],
		firstDay: 1,
		monthNamesShort: ['Jan','Feb','Már','Ápr','Máj','Jún','Júl','Aug','Szep','Okt','Nov','Dec'],
		monthNames: ['Január','Február','Március','Április','Május','Június','Július','Augusztus','Szepember','Október','November','December'],
		navigationAsDateFormat: true,
		showButtonPanel: false,
		showMonthAfterYear: true,
		showOtherMonths:true,
		buttonImageOnly: true,
		numberOfMonths: 1,
		changeYear: true,
		changeMonth: true,
		gotoCurrent: true,
		onChangeMonthYear: function(year, month, inst) { getStatList(); /*alert('-');*/ },
		/*defaultDate: +7*/
		/*showWeek:true*/
		/*buttonImage: 'templates/medicitravel/js/js/images/datepicker.gif'*/
	};
	$j( "#datum" ).datepicker(option);
}
