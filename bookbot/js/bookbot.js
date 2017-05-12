/* 
	Author: Pablo Martínez Novelo
	Website: http://greenlabs.com.mx
*/

//defines a new method for the array class to search occurrencies in it
Array.prototype.hasInArray = function(value) {
	var i;
	for (var i = 0, loopCnt = this.length; i < loopCnt; i++) {
		if (this[i] == parseInt(value)) {
			return true;
		}
	}
	return false;
};
var appProgressBar = function(){
	//progressBar
	var setProgressBar = function(step){
		var progress = $('div#progress');
		progress.animate({width: step}, 'slow');
	}
	
	return{
		init: function(step){
			setProgressBar(step);
		}
	}
}
/*============================================== Contact NAMESPACE =====================================================*/
var contactScript = function(){
	
	var host = $('input#host').val();
	
	var bindEvents = function(){
		$('input#contactSubmitBot').live('click', function(event) {
			event.preventDefault();
			var contactObject = '';
			$('form#contactForm :input').each(function(index) {
				if($(this).is(':text, textarea')){
					contactObject += $(this).attr('name')+"="+$(this).val()+"&";
				}
			});
			contactObject = contactObject.substring(0, contactObject.length-1);
			contactSubmit(contactObject);
		});
		/*$('a.callToReserve, a#bookingLink').live('click', function(event) {
			event.preventDefault();
			//var lang = $.trim($('div#lang').text());
			//location.href = 'http://www.joliejungle.com/reservaciones/date_select/'+lang;
			alert('Our booking system is down for maintenance, please check back in a few hours, thank you!');
		});*/
	}
	
	var contactSubmit = function(contactObject){
		var preloaderWhite = $('img#preloaderWhite');
		$('input#contactSubmitBot').fadeOut('fast', function(){
			preloaderWhite.clone().appendTo('p#submit').addClass('temp');
		});
		$.ajax({
			url: host+'contact/sendContact/',
			type: 'POST',
			dataType: 'text',
			data: contactObject,
			success: function(data, textStatus, xhr) {
				$('.temp').remove();
				$('input#contactSubmitBot').fadeIn('fast');
				var msg = "Your message was sent successfully! / Su mensaje fué enviado exitosamente!";
				alert(msg);
				$('form:input').each(function(index) {
					$(this).val('');
				});
			},
			error: function(xhr, textStatus, errorThrown) {
				$('.temp').remove();
				log(errorThrown);
			}
		});
	}
	
	var onloadExec = function(){
		bindEvents();
	}
	
	return {
		init:onloadExec
	}
}
/*==============================================Dates selection NAMESPACE =====================================================*/
var selectDates = function(){

	//Clears the form on page load
	var clearForms = function(){
		return false;
	}
	
	//animates de progress bar
	var setProgress = function(progressPctg){
		var progress = $('div#progress');
		if (progress.width() < progressPctg) {
			progress.animate({width: progressPctg}, 'slow');
		}
	}
	
	var setMessage = function(step){
		var message = $('p#progressMsg');
		switch(step){
			case 1:
				message.text($('li#book_step_1').text());
				break;
			case 2:
				message.text($('li#book_step_2').text());
				break;
			case 3:
				message.text($('li#book_step_3').text());
				break;
			case 4:
				message.text($('li#book_step_4').text());
				break;
			default:
				message.text($('li#book_step_1').text());
		}
	}

	//binds all events in page
	var bindEvents = function(){
		$('#checkIn').datepicker({
			numberOfMonths: 2,
			minDate: +0,
			onSelect: function(selectedDate){
				$('input#checkInput').val(selectedDate);
				$('form#bookingForm').submit();
			}
		});
		$('.gotoCheckin').on('click', function(event) {
			event.preventDefault();
			$(this).attr('disabled', 'disabled');
			$('fieldset#datosHospedaje').animate({
					left: '0px'
				},
				400, function() {
				$('#checkInput').val('');
			});
		});
		/*
		================================SLIDERS==============================*/
		$('input#checkInput').change(function() {
			
		});
		$('input#checkOut').change(function() {
			/*
			var progress = $('div#progress');
			if (progress.width() < 200) {
				progress.animate({width: 200}, 'slow');
				setMessage(3);
			}
			$( "div#adultSlider" ).slider('enable');
			$( "div#childSlider" ).slider('enable');
			$( "div#roomSlider" ).slider('enable');
			*/
		});
	}
	
	var onloadExec = function(){
		clearForms();
		setMessage(1);
		bindEvents();
	}
	
	return {
		init:onloadExec
	}
}

/*==========================================RESERVATION SELECTION, selectReservation NAMESPACE=====================================================*/
var selectReservation = function(){
	
	var bookData = {};
	var noAllotment = false;
	var noAllotmentType = new Array();
	var roomSelectProgress = false; //room selection progress for progressbar
	var tax = parseFloat($('span#taxAmount').text())/100;
	var host = $('input#host').val();

	
	var setMessage = function(step){
		var message = $('p#progressMsg');
		switch(step){
			case 5:
				message.text($('li#book_step_5').text());
				break;
			case 6:
				message.text($('li#book_step_6').text());
				break;
			case 7:
				message.text($('li#book_step_7').text());
				break;
			default:
				message.text($('li#book_step_5').text());
		}
	}
	
	//hides all room prices and descriptions at init and displays first room type in navigation
	var hideRoomPricesDescriptions = function(){
		$('p.roomType').hide();
		$('div.roomDescrip').hide();
	}
	
	//hides all room descriptions and pictures
	var showHideRoomDescriptions = function(roomType){
		$('div.roomDescrip').hide().removeClass('activeDescription');
		$('div#'+roomType).show().addClass('activeDescription');
	}
	
	//shows room types and executes calculateTotal()
	var showHideRoomTypes = function(roomClass){
		$('p.roomType').hide().removeClass('activeRoom');
		$('p.'+roomClass).show().addClass('activeRoom');
		calculateTotal();
	}
	
	//calculates total and inserts it in page
	var calculateTotal = function(){
		var prices = $('p.activeRoom').find('span.roomCost');
		var total = 0;
		prices.each(function(index) {
			total += parseFloat($(this).text());
		});
		var totalMultiplyByRooms = parseFloat((total.toFixed(2) * parseFloat($.trim($('span#roomsQtyNo').text()))).toFixed(2));
		var taxTotal = parseFloat(totalMultiplyByRooms*tax);
		var extraAdultsTotal = parseFloat($('span#extraAdultsAmount').text());
		$('span#totalCost').text(total.toFixed(2));
		$('span#grandTotal').text(totalMultiplyByRooms.toFixed(2));
		$('span#taxTotalAmount').text(taxTotal.toFixed(2));
		$('span#totalAmountNumber').text((totalMultiplyByRooms + taxTotal + extraAdultsTotal).toFixed(2));
	}


	/****************************
	|	BUG ALERT ?				|
	****************************/
	//gets booking data to continue with user registration.
	var getBookingData = function(){
		//tye of room that's gonna be booked
		bookData.roomType = $('a.activeRoomType').attr('href').substr(10);
		var count = 0;
		if (noAllotment){
			if (noAllotmentType.hasInArray(bookData.roomType)) {
				alert('There is no vacancy on '+noAllotmentType.length+' dates that you chose, please change the date or the room type');
				return false;
			}else{
			 	bookData.checkin = $('p#checkin').text();
			 	bookData.checkout = $('p#checkout').text();
			 	bookData.rooms = $.trim($('span#roomsQtyNo').text());
				return true;
			 }
		}else{
		 	bookData.checkin = $('p#checkin').text();
		 	bookData.checkout = $('p#checkout').text();
		 	bookData.rooms = $.trim($('span#roomsQtyNo').text());
		 	return true;
		 }
	}
	
	var capturePersonalData = function(){
		loadValidationRules();
		if ($('div#personalDetails form').valid() && $('input#terms').is(':checked')) {
			var personalData = {
				name: $('input#name').val(),
				lastname: $('input#lastname').val(),
				email: $('input#email').val(),
				tel: $('input#tel').val(),
				country: $('input#country').val(),
				address: $('textarea#address').val(),
				city: $('input#city').val(),
				zip: $('select#zip').val()
			}
			var bookComments = $('textarea#comments').val();
			if (personalData) {
				var button = $('button#submitPersonalDetails');
				button.attr('disabled', 'disabled');
				//saving personal details msg
				$.ajax({
					url: host+'reservaciones/registroUsuario/',
					type: 'POST',
					dataType: 'json',
					data: personalData,
					complete: function(xhr, textStatus) {
						button.removeAttr('disabled');
					},
					success: function(data, textStatus, xhr) {
						bookData.userId = data.user;
						bookData.amount = $.trim($('span#totalAmountNumber').text());
						confirmBookAndPay(bookComments);
					},
					error: function(xhr, textStatus, errorThrown) {

					}
				});
			}
		}else{
			return false;
		}
	}
	
	//gets personal user data to login and proceed with booking
	var goToPersonalDataCapture = function(){
		$('button#toPersonalData').attr('disabled', 'disabled');
		//animate booking pane and go to personal details pane
		$('div.reservData').fadeOut('slow', function() {
			$('div#personalDetails').fadeIn('slow');
		});
	}
	
	var confirmBookAndPay = function(bookComments){
		$.ajax({
			url: host+'reservaciones/confirmAndBook/',
			type: 'POST',
			dataType: 'json',
			data: {
				userId: bookData.userId,
				checkin: bookData.checkin,
				checkout: bookData.checkout,
				roomType: bookData.roomType,
				roomQty: bookData.rooms,
				total_amount: bookData.amount,
				comments: bookComments
			},
			complete: function(xhr, textStatus) { },
			success: function(data, textStatus, xhr) {
				var returnUrl = $('input#returnUrl').val();
				var paypalIpnUrl = $('input#paypalIpnUrl').val();
				var formAction = $('form#paypalSubmitForm').attr('action')+data.reservationId;
				$('form#paypalSubmitForm').attr('action', formAction);
				$('input#returnUrl').val(returnUrl+"/"+data.reservationId);
				$('input#paypalIpnUrl').val(paypalIpnUrl+"/"+data.reservationId);
				$('form#paypalSubmitForm').dialog({
					title: 'Confirma tu reservaci&oacute;n',
					width:400,
					modal:true
				});
			},
			error: function(xhr, textStatus, errorThrown) { }
		});
	}   
	    
	//checks if there's a date with no allotment and stores the type of room that's unavaliable
	var checkNoAllotment = function(){
		var noAllotmentArray = new Array();
		$.each($('ul#dateList li').has('p.noAllotment'), function(index, val) {
			var dateElement = $(this);
			$.each(dateElement.find('span.roomType'), function(index, val) {
				noAllotmentRoomType = $(this).text();
				if (!noAllotmentType.hasInArray(noAllotmentRoomType)) {
					noAllotmentType.push(noAllotmentRoomType);
				};
			});
		});
		if(noAllotmentType.length > 0){
			noAllotment = true;
		}
	}
	
	//Jquery validation rules
	var loadValidationRules = function(){
		$('div#personalDetails form').validate({
			rules: {
				name: {required:true},
				lastname: {required:true},
				email: {required:true, email:true},
				tel: {required:true},
				address: {required:true},
				city: {required:true},
				zip: {required:true}
			},
			errorPlacement: function(error, element) {
		        element.siblings('label').after(error);
		    }

		});
	}
	
	//animates de progress bar
	var setProgress = function(progressPctg){
		var progress = $('div#progress');
		//if (progress.width() < progressPctg) {
			progress.animate({width: progressPctg}, 'slow');
		//}
	}
	
	//binds all events in page
	var bindEvents = function(){
		//select room types to display prices and enable or disable book button if no allotment
		$('a.roomTypeSelect').live('click',function(event) {
			event.preventDefault();
			var roomType = $(this).attr('href').substr(1);
			var roomTypeName = $(this).text();
			$('a.roomTypeSelect').removeClass('activeRoomType');
			$(this).addClass('activeRoomType');
			showHideRoomTypes(roomType);
			showHideRoomDescriptions(roomType);
			if (noAllotmentType.hasInArray(roomType.substr(9))||parseInt($('span#roomsQtyNo').text()) > parseInt($('input#roomTypeAllotment_'+roomType.substr(9)).val())) {
				$('button#toPersonalData').text($('p#noAvailability').text());
				$('p#roomTypeTotal').addClass('hidden');
				$('button#toPersonalData').addClass('inactive');
				$('button#toPersonalData').attr('disabled', 'disabled');
				$('span#totalRoomType').text('N/A');
				if (parseInt($('span#roomsQtyNo').text()) > parseInt($('input#roomTypeAllotment_'+roomType.substr(9)).val())) {
					//Alert user or something
				};
			}else{
				$('button#toPersonalData').text($('p#buttonText').text());
				$('p#roomTypeTotal').removeClass('hidden');
				$('button#toPersonalData').removeClass('inactive');
				$('button#toPersonalData').removeAttr('disabled');
				$('span#totalRoomType').text(roomTypeName);
			}
			if (roomSelectProgress) {
				setProgress(600);
				setMessage(6);
			}else{
				setProgress(400);
				roomSelectProgress = true;
			}
		});
		//executes booking
		$('button#toPersonalData').live('click',function(event) {
			event.preventDefault();
			if (getBookingData()) { //verifies allotment and dates
				$('input#paypalName').val($.trim($('p#roomTypeTotal').text())+' '+$.trim($('p#roomsQty').text()));
				$('input#paypalAmount').val($.trim($('span#totalAmountNumber').text()));
				goToPersonalDataCapture();
				setProgress(800);
				setMessage(7);
			};
		});
		$('button#backRoomSel').live('click', function(event) {
			event.preventDefault();
			$('button#toPersonalData').removeAttr('disabled');
			//animate booking pane and go to personal details pane
			$('div#personalDetails').fadeOut('slow', function(){
				$('div.reservData').fadeIn('slow');
			});
			setProgress(600);
			setMessage(6);
		});
		$('button#captureDataAndBook').live('click',function(event) {
			event.preventDefault();
			capturePersonalData();
		});
		$('a#termsConditions').live('click', function(event) {
			event.preventDefault();
			showTermsAndConditions();
		});
		$(window).load(function() {
			$('a.roomTypeSelect:first-child').click();
		});
	}
	
	var onloadExec = function(){
		bindEvents();
		hideRoomPricesDescriptions();
		checkNoAllotment();
		setMessage(5);
	}
	
	return {
		init:onloadExec
	}
}
/*============================================== Control Panel =====================================================*/
var backOffice = function(){
	
	var host = $('input#host').val();
	var modalDialog = null;
	var backgroundFadeTimer = null;
	var seasonModalDialog = null;
	var availabilityDialog = null;
	
	var bindEvents = function(){
		//inline edits
		$('.editableData').live('click', function(event) {
			var elem = $(this);
			if (!elem.hasClass('editing')){
				var origVal = $.trim(elem.html());
				if (!elem.hasClass('textAreaEdit')) {
					elem.addClass('editing')
						.empty()
						.append('<input class="inlineEdit" type="text" name="" value="'+origVal+'" />');
					elem.find('input').fadeIn('slow', function() {
						elem.find('input').focus();
					});
				}else{
					elem.addClass('editing')
						.empty()
						.append('<textarea class="inlineEdit" type="text" name="">'+origVal+'</textarea>');
					elem.find('textarea').fadeIn('slow', function() {
						elem.find('textarea').focus();
					});
				}
			}
		}).live('blur', function(event) {
			var elem = $(this);
			if (elem.hasClass('editing')){
				elem.removeClass('editing');
				if (!elem.hasClass('textAreaEdit')) {
					var pDataToSend = $.trim(elem.find('input').val());
					elem.find('input').remove();
					elem.append(pDataToSend);
				}else{
					var pDataToSend = $.trim(elem.find('textarea').val());
					elem.find('textarea').remove();
					elem.append(pDataToSend);
				}
				//campo de la db para enviar
				var method = $.trim(elem.closest('table').siblings('p.hidden').text());
				var field = elem.closest('td').attr('class');
				var id = elem.closest('tr').attr('id').substr(9);
				activatePreloader();
				$.post(
					host+'backoffice/'+method,	
					'cat_room_id='+$.trim(id)+'&'+field+'='+pDataToSend,
					function(data, textStatus, xhr) {
						deactivatePreloader();
					}
				);
			}
		});

		//creates tabs
		$('div#tabs').tabs();

		//calendars
		$('input.date').datepicker({
			numberOfMonths: 2
		});
		//confirm links for deletion
		$('a.confirm').live('click', function(event) {
			event.preventDefault();
			var answer = confirm('Está seguro de querer eliminar este elemento? No se podrá cancelar la eliminación.');
				if (answer){
					var kind = $(this).closest('table').attr('id');
					var itemId = $.trim($(this).closest('tr').children('td').first().text());
					var row = $(this).closest('tr');
					deleteItemRow(kind, itemId, row);
				}else{
					return false;
				}
		});
		//Confirm deletion without tables
		$('a.confirmNew').live('click', function(event) {
			event.preventDefault();
			var answer = confirm('Está seguro de querer eliminar este elemento? No se podrá cancelar la eliminación.');
			if (answer){
				var kind = $(this).closest('ul').data('method');
				var itemId = $.trim($(this).closest('span').siblings('input.reserveId').val());
				var row = $(this).closest('li');
				deleteItemRow(kind, itemId, row);
			}else{
				return false;
			}
		});
		//Activate a not confirmed season
		$('a.activateNotConfirmed').live('click', function(event) {
			event.preventDefault();
			activatePreloader();
			var reserveId = $.trim($(this).closest('span').siblings('input.reserveId').val());
			activateUnconfirmedReserve(reserveId, $(this).closest('li'));
		});
		//Delete season confirm
		$('button.deleteSeason').live('click', function(event) {
			event.preventDefault();
			var answer = confirm('Está seguro de querer eliminar esta temporada? No se podrá cancelar la eliminación.');
				if (answer){
					var seasonId = $.trim($(this).closest('tr').attr('id'));
					var row = $(this).closest('tr');
					deleteSeason(seasonId, row);
				}else{
					return false;
				}
		});
		//Blocks dates for maintenance
		$('button#blockRoom').live('click', function(event) {
			activatePreloader();
			var start = $('input#startBlock').val();
			var end = $('input#endBlock').val();
			var roomType = $('select#roomType').val();
			var roomName = $('select#roomType option:selected').text();
			var roomQty = $('input#roomQty').val();
			if (start == '' || end == '' || roomQty == '') {
				notify({
					status: 0,
					msg: 'Por favor llene todos los campos requeridos!'
				});
				deactivatePreloader();
				return false;
			};
			var monthList = {
			    en: {
			        "January": 	1,
			        "February": 2,
			        "March": 	3,
			        "April": 	4,
			        "May": 		5,
			        "June": 	6,
			        "July": 	7,
			        "August": 	8,
			        "September":9,
			        "October": 	10,
			        "November": 11,
			        "December": 12
			    }
			};
			var year = $('#dashboardCalendarActualMonth').text();
			var month = year.substring(0, year.length - 5);
			month = monthList.en[month];
			var year = year.substr(year.length - 4);
			$.ajax({
		   		url: host+'backoffice/checkDatesForDateRangeBlocking/',
		   		type: 'POST',
		   		dataType: 'json',
		   		data: {
					checkIn: start,
					checkOut: end,
					dateRange: 1
		   		},
		   		complete: function(xhr, textStatus) {
		   			reloadCalendar(year, month);
		   		},
		   		success: function(data, textStatus, xhr) { 
			 		if (verifyDatesforBlock(data, roomType)) {
						blockDates(start, end, roomType, roomQty, roomName, 1);
					};
				},
		   		error: function(xhr, textStatus, errorThrown) { }
			});
		});
		//saves a season
		$('button#saveSeason').live('click', function(event) {
			activatePreloader();
			addSeason($(this));
		});
		//activate seasons in table
		$('button.activateSeason').live('click', function(event) {
			var seasonId = $.trim($(this).closest('tr').attr('id'));
			var seasonName = $(this).closest('tr').find('td.season_name').text();
			activateSeasonPanel(seasonId, seasonName);
		});
		//save season prices
		$('button#saveSeasonPrices').click(function() {
			var seasonName = $(this).closest('.ui-dialog').find('.ui-dialog-title').text();
			activateSeason(seasonName);
		});
		//Saves a config variable
		$('button.variableSend').live('click', function(event) {
			sendVariable($(this));
			activatePreloader();
		});
		//Calendar interaction
		$('th#prevMonth>a, th#nextMonth>a').live('click', function(event) {
			event.preventDefault();
			activatePreloader();
			var action = $(this).attr('href')+'/1/';
			$.ajax({
				url: action,
				type: 'GET',
				dataType: 'html',
				complete: function(xhr, textStatus) {
					deactivatePreloader();
				},
				success: function(data, textStatus, xhr) {
					$('div.availabilityCalendar').empty();
					$('div.availabilityCalendar').append(data);
					var row = $('table.dashboardCalendar').find('tr:first');
					fadeOutBackgroundInNewRow(row, 0.9);
				},
				error: function(xhr, textStatus, errorThrown) {}
			});
		});
		$('td.calday').live('click', function(event) {
			var cell = $(this);
			var date = cell.find('.hiddenCalDate').val();
			var roomTypeId = "";
			var element = "";

			if (date !== undefined) {
				var dateBlockPanel = $('div.dateBlockPanel').clone();
				availabilityDialog = dateBlockPanel.dialog({
					modal: true,
					title: "Disponibilidad por fecha",
					open: function(){
						cell.find('p.dateAllotment').each(function(event) {
							roomTypeId = $(this).find('input.roomTypeId').val();
							roomAvailability = $(this).find('span.allotmentNumber').text();
							$('tr.roomAvailCat_'+roomTypeId).find('input.roomAllotmentField').val(roomAvailability);
							$('tr.roomAvailCat_'+roomTypeId).find('input.roomTypeActualAllotment').val(roomAvailability);
						});
						dateBlockPanel.find('.blockingDate').val(cell.find('.hiddenCalDate').val());
					},close: function(){
						availabilityDialog.remove();
						availabilityDialog = null;
					}
				});
			};
		});
		$('button#saveDateAllotment').live('click', function(event) {
			event.preventDefault();
			activatePreloader();
			var button = $(this);
			var roomRows = button.closest('table.controlPanel').find('tr.roomTypeRow');
			var loops = 0;
			roomRows.each(function(index) {
				var actualValue = $(this).find('input.roomTypeActualAllotment').val();
				var newValue = $(this).find('input.roomAllotmentField').val();
				if (actualValue > newValue) {
					var roomQty = actualValue - newValue;
					var roomType = $(this).find('input.roomTypeIdContainer').val();
					var roomName = $(this).find('.roomLabelName').text();
					var start = button.siblings('.blockingDate').val();
					var end = start.split('-');
					var year = end[0];
					var month = end[1];
					end = new Date(end[0], end[1] - 1, end[2]);
					end = new Date(end.getFullYear(), end.getMonth(), end.getDate()+1);
					end = end.getFullYear()+'-'+parseInt(end.getMonth()+1)+'-'+end.getDate();
					start = start.split('-');
					start = start[0]+'/'+start[1]+'/'+start[2];
					end = end.split('-');
					end = end[0]+'/'+end[1]+'/'+end[2];
					loops++;
					$.ajax({
				   		url: host+'backoffice/checkDatesForBlocking/',
				   		type: 'POST',
				   		dataType: 'json',
				   		data: {
							checkIn: start,
							checkOut: end
				   		},
				   		complete: function(xhr, textStatus) {
				   			loops--;
				   			if (loops === 0) {
								availabilityDialog.remove();
								availabilityDialog = null;
								reloadCalendar(year, month);
							};
				   		},
				   		success: function(data, textStatus, xhr) { 
					 		if (verifyDatesforBlock(data, roomType)) {
								blockDates(start, end, roomType, roomQty, roomName, 0);
							};
						},
				   		error: function(xhr, textStatus, errorThrown) { }
					});
				};
			});

		});
	}

	var zebraStripes = function(){
		$('tbody tr').removeClass('zebra');
		$('tbody tr:even').addClass('zebra');
	}

	var activatePreloader = function(){
		var preloader = $('div.preloader').clone();
		modalDialog = preloader.dialog({
			modal: true
		});
	}

	var deactivatePreloader = function(){
		modalDialog.dialog('close');
		modalDialog.remove();
		modalDialog = null;
	}

	var sendVariable = function(button){
		var inputField = button.closest('tr').find('input')
		var variable = inputField.attr('id');
		var value = $.trim(inputField.val());
		$.ajax({
		  	url: host+'backoffice/setVariable',
		  	type: 'POST',
			dataType: 'json',
			data: {
				value: value,
				variable: variable
			},
			complete: function(xhr, textStatus) {
				deactivatePreloader();
			},
			success: function(data, textStatus, xhr) {
				notify(data);
			},
			error: function(xhr, textStatus, errorThrown) {}
		});	
	}
	
	var activateUnconfirmedReserve = function(reserveId, row){
		$.getJSON(host+'backoffice/activateUnconfirmedReserve/'+reserveId, function(json, textStatus) {
			deactivatePreloader();
			if (parseInt(json.requestStatus)) {
				var newRow = row.clone();
				row.remove();
				$('ul#reservasList').append(newRow);
				var newEditButton = newRow.prev('li').find('span.editReserveButton').clone();
				var newDeleteButton = newRow.prev('li').find('span.cancelReserveButton').clone();
				newRow.find('span.activateNotConfirmedButton').replaceWith(newEditButton);
				newRow.find('span.cancelUnconfirmedButton').replaceWith(newDeleteButton);
			};
		});
	}

	var populateSeasonPricesRow = function(seasonId, newSeasonObject){
		var newRow = $('tr.seasonPricesRow').clone().attr('id', 'season_id_'+seasonId).removeClass('seasonPricesRow');
		newRow.find('td.seasonPriceName').text(newSeasonObject.seasonName);
		var newCell = newRow.find('td.seasonPriceCell').clone().removeAttr('id');
		newRow.find('td#tempPriceCell').remove();
		$.each(newSeasonObject.priceList, function(index, val) {
			var priceCell = newCell.clone().text(newSeasonObject.priceList[index])
				.removeClass('seasonPriceCell');
			newRow.append(priceCell);
		});
		$('table#seasonEdit').append(newRow);
		fadeOutBackgroundInNewRow(newRow, .9);
	}
	
	var activateSeason = function(seasonName){

		activatePreloader();
		var seasonId = $('div#seasonActivate').find('input#seasonId').val();
		var requests = $('tr.roomType').size();
		var loop = 0;
		var newSeasonObject = new Object();
		var errorFlag = false;
		newSeasonObject['priceList'] = new Object();
		newSeasonObject['seasonName'] = seasonName;
		$('tr.roomType input.seasonPriceInput').each(function(index){
			if ($(this).val() == '') {
				notify({
					status: 0,
					msg: 'Por favor ingrese los precios de cada tipo de habitacion.'
				});
				errorFlag = true;
			}
		});
		if (errorFlag) {
			deactivatePreloader();
			return false;
		};
		$('tr.roomType').each(function(index) {
			var roomCat = $(this).attr('id').substr(8);
			var	price = $(this).find('input.seasonPriceInput').val();
			newSeasonObject['priceList']['cat_room_id_'+roomCat] = price;
			$.ajax({
				url: host+'backoffice/setSeasonPrice/',
				type: 'POST',
				dataType: 'json',
				data: {
					seasonId:seasonId,
					roomCat:roomCat,
					price: price
				},
				complete: function(xhr, textStatus){},
				success: function(data, textStatus, xhr){
					loop++;
					if (loop == requests) {
						populateSeasonPricesRow(seasonId, newSeasonObject);
						seasonModalDialog.dialog('close');
						seasonModalDialog = null;
						deactivatePreloader();
					};
				},
				error: function(xhr, textStatus, errorThrown){}
			});
		});
	}
	
	var activateSeasonPanel = function(seasonId, seasonName){
		$('div#seasonActivate').find('input#seasonId').val(seasonId);
		seasonModalDialog = $('div#seasonActivate').dialog({
			width: 500,
			modal: true,
			title: seasonName
		});
	}
	
	var addSeason = function(button){
		$.ajax({
			url: host+'backoffice/newSeason/',
			type: 'POST',
			dataType: 'json',
			data: {
				season_name: $('input#seasonName').val(),
			    date_season_start: $('input#seasonStart').val(),
			    date_season_end: $('input#seasonEnd').val(),
			    season_comments: $('input#seasonDescrip').val()
			},
			complete: function(xhr, textStatus) {
				deactivatePreloader();
			},
			success: function(data, textStatus, xhr) {
				notify(data);
				if (data.status) {
					var row = button.closest('tr');
					var newRow = $('tr#seasonRow').clone().attr('id', data.seasonId);
					newRow.find('td.season_name').text($('input#seasonName').val()).end()
						.find('td.date_season_start').text($('input#seasonStart').val()).end()
						.find('td.date_season_end').text($('input#seasonEnd').val()).end()
						.find('td.season_comments').text($('input#seasonDescrip').val()).end()
						.css('background-color', '#87dc8d');
					$('table#activeSeasons tbody').prepend(newRow);
					setTimeout(function(){
						fadeOutBackgroundInNewRow(newRow, .9);
					}, 2000);
				};
			},
			error: function(xhr, textStatus, errorThrown) { }
		});
	}
	
	var deleteSeason = function(seasonId, row){
		activatePreloader();
		$.ajax({
			url: host+'backoffice/deleteSeason/'+seasonId,
			type: 'GET',
			dataType: 'json',
			complete: function(xhr, textStatus) { 
				deactivatePreloader();
			},
			success: function(data, textStatus, xhr) {
				if (data.status) {
					$('tr#season_id_'+seasonId).remove();
					row.fadeOut('slow');
				};
				notify(data);
			},
			error: function(xhr, textStatus, errorThrown) { }
		});
	}
	
	var verifyDatesforBlock = function(data, roomType){
		//Method for getting size of object
		Object.size = function(obj) {
		    var size = 0, key;
		    for (key in obj) {
		        if (obj.hasOwnProperty(key)) size++;
		    }
		    return size;
		};
		//contains dates that are already booked by the system
		var unavailable = new Object();
		//goes through the object to see if there are no allotment dates
		$.each(data, function(date, obj) {
 			$.each(obj, function(roomCat, allotment) {
 				if (roomCat == roomType) {
					if (allotment == 0) {
						var objProperty = date;
						unavailable[date] = allotment;
					};
				};
 			});
 		});
		var size = Object.size(unavailable);
		if (size > 0) {
			var datesString = '';
			$.each(unavailable, function(index, val) {
				datesString += index+' ';
			});
			var notifyMsg = {
				status: 0,
				msg: 'Todas las habitaciones están reservadas en las siguientes fechas: '+datesString
			}
			notify(notifyMsg);
			return false;
		}else{
			return true;
		}
	}

	var blockDates = function(start, end, roomType, roomQty, roomName, dateRange){
		$.ajax({
			url: host+'backoffice/blockRoomDates/',
			type: 'POST',
			dataType: 'json',
			data: {
				start: start,
				end: end,
				roomType: roomType,
				roomQty: roomQty,
				range: dateRange
			},
			complete: function(xhr, textStatus) {},
			success: function(data, textStatus, xhr) { 
				var row = $('tr#blockRoomRow').clone();
				var blockId = data.reservationId;
				var newStart = start.split("/");
				var newEnd = end.split("/");
				//agrega ceros en fechas de un digito (mes y dias)
				if (newStart[2].length == 1) {
            		newStart[2] = "0" + newStart[2];
        		}
        		if (newStart[1].length == 1) {
            		newStart[1] = "0" + newStart[1];
        		}
        		if (newEnd[2].length == 1) {
            		newEnd[2] = "0" + newEnd[2];
        		}
        		if (newEnd[1].length == 1) {
            		newEnd[1] = "0" + newEnd[1];
        		}
				row.removeAttr('id')
					.find('td.hidden').text(blockId).end()
					.find('td.blockRoomTypeCell').text(roomName).end()
					.find('td.blockRoomStartCell').text(newStart[2]+"/"+newStart[1]+"/"+newStart[0]).end()
					.find('td.blockRoomQtyCell').text(roomQty).end()
					.find('td.blockRoomEndCell').text(newEnd[2]+"/"+newEnd[1]+"/"+newEnd[0]);
				$('table#block').prepend(row);
			},
			error: function(xhr, textStatus, errorThrown) {}
		});
	}

	var reloadCalendar = function (year, month){
		var action = host+'backoffice/availabilityCalendar/'+year+'/'+month+'/1/';
		$.ajax({
			url: action,
			type: 'GET',
			dataType: 'html',
			complete: function(xhr, textStatus) {
				deactivatePreloader();
			},
			success: function(data, textStatus, xhr) {
				$('div.availabilityCalendar').empty();
				$('div.availabilityCalendar').append(data);
				var row = $('table.dashboardCalendar').find('tr:first');
				fadeOutBackgroundInNewRow(row, 0.9);
			},
			error: function(xhr, textStatus, errorThrown) {}
		});
	}
	
	var notify = function(data){
		if (data.status) {
			var modalNotification = $('<p class="confirmation">'+data.msg+'</p>').dialog({
				modal:true,
				buttons:{
						Ok: function() {
							$( this ).dialog( "close" );
						}
					}
			});
		}else{
			var modalNotification = $('<p class="error">'+data.msg+'</p>').dialog({
				modal:true,
				buttons:{
						Ok: function() {
							$( this ).dialog( "close" );
						}
					}
			});
		}
		zebraStripes();
		setTimeout(function(){
			modalNotification.dialog( "close" );
		}, 1500);
	}
	
	var deleteItemRow = function(kind, itemId, row){
		activatePreloader();
		switch(kind){
			case 'reservas':
				cancelReservation(itemId, row);
				break;
			case 'reservasNoConfirmadas':
				cancelReservation(itemId, row);
				break;
			case 'clientes':
				deleteClient(itemId, row);
				break;
			case 'block':
				cancelReservation(itemId, row);
				break;
		}
	}
	
	var cancelReservation = function(reserveId, row){
		$.ajax({
			url: host+'backoffice/cancelReserve/'+reserveId,
			type: 'GET',
			dataType: 'json',
			complete: function(xhr, textStatus) {
				deactivatePreloader();
			},
			success: function(data, textStatus, xhr) {
				notify(data);
				if (data.status) {
					row.fadeOut('slow');
				};
			},
			error: function(xhr, textStatus, errorThrown) { }
		});
	}
	
	var deleteClient = function(clientId, row){
		$.ajax({
			url: host+'backoffice/deleteClient/'+clientId,
			type: 'GET',
			dataType: 'json',
			complete: function(xhr, textStatus) {
				deactivatePreloader();
			},
			success: function(data, textStatus, xhr) {
				notify(data);
				if (data.status) {
					row.fadeOut('slow');
				};
			},
			error: function(xhr, textStatus, errorThrown) { }
		});
	}

	var fadeOutBackgroundInNewRow = function(row, alpha){
		alpha -= .01;
    	if (alpha < 0.1) {
    	    clearTimeout(backgroundFadeTimer);
    	    row.removeAttr("style");
    	    zebraStripes();
    	    return false;
    	}
    	row.css( 'background-color', 'rgba(135,220,141,' + alpha + ')' );
    	backgroundFadeTimer = setTimeout( function(){
    		fadeOutBackgroundInNewRow(row, alpha);
    	}, 50);
	}
	
	var onloadExec = function(){
		bindEvents();
		zebraStripes();
	}
	
	return {
		init:onloadExec
	}
}
/*==============================================MAIN=====================================================*/
$(function(){
	var selectDatesScript = new selectDates();
	selectDatesScript.init();
});