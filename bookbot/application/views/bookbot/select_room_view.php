<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!-- Consider adding an manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Sensaciones Motel Boutique | Book Now!</title>
	<link href='http://fonts.googleapis.com/css?family=Alex+Brush' rel='stylesheet' type='text/css'>
    <style>
        
        #zip {
            height: 28px;
            border: none;
            border: 1px solid #cbcbcb;
            color: #a9a9a9 !important;
            height: 37px;
            padding-left: 15px;
            width: 240px;
            background-color: #fff;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            -o-border-radius: 3px;
            border-radius: 3px;
            -moz-box-shadow: inset 0 1px 1px #d5d5d5, 0 1px 1px #fff;
            -webkit-box-shadow: inset 0 1px 1px #d5d5d5, 0 1px 1px #fff;
            -o-box-shadow: inset 0 1px 1px #d5d5d5, 0 1px 1px #fff;
            box-shadow: inset 0 1px 1px #d5d5d5, 0 1px 1px #fff;
        }
    </style>
	<?php echo $styles; ?>
</head>
<body class="bookbot">
	<div id="container">
		<header class="bookHeader">
			<img id="bookLogo" src="<?php echo base_url(); ?>img/bookbot/motel_sensaciones_logo.png" alt="Sensaciones Motel Boutique">
        	<ul id="bookNav" class="clearfix">
        		<li class="navElem actualNavLink"><a class="navLink" href="http://sensacionesmotel.com/">Sensaciones Motel</a></li>
                <li class="navElem"><a class="navLink" href="http://sensacionesmotel.com/ubicacion.html">Ubicaci&oacute;n</a></li>
                <li class="navElem"><a class="navLink" href="http://sensacionesmotel.com/contacto.html">Contacto</a></li>
        	</ul>
		</header>
		<div id="main" role="main">
			<div id="progressBar">
				<p id="progressMsg"></p>
				<div id="progress">
					
				</div>
			</div>
			<div class="reservData clearfix">

				<div id="roomSelect">
					<ul id="roomTypeList">
						<?php foreach ($roomTypeList as $roomId => $roomData): ?>
							<li>
								<a class="roomTypeSelect" href="#roomType_<?php echo $roomId; ?>"><?php echo $roomData['name']; ?></a>
							</li>
						<?php endforeach ?>
					</ul>
				</div>

				<div id="roomTypeInfo">
					<?php foreach ($roomTypeList as $roomId => $roomData): ?>
						<div id="roomType_<?php echo $roomId ?>" class="roomDescrip">
							<h1 class="roomTypeTitle"><?php echo $roomData['name']; ?> &raquo;</h1>
							<div class="roomTypePic">
								<img src="<?php echo base_url(); ?>img/rooms/<?php echo $roomId ?>/main.jpg" width="400" height="171" alt="<?php echo $roomData['name'] ?>_pic">
							</div>
							<div class="roomTypeDescription">
								<p><?php echo $roomData['description_'.$cont['lang']]; ?></p>
							</div>
						</div>
					<?php endforeach ?>
				</div>
				<div id="calendarCol">
					<div id="staticCalendars">
						<?php echo $calendar[1]; ?>
						<?php 
							//if dates are in 2 months
							if (isset($calendar[2])){
								echo $calendar[2];
							}
						?>
					</div>
					<div id="vacationDateList">
						<ul id="dateList">
							<?php 
								$datesArray = $priceArray;
								array_pop($priceArray);
								foreach ($priceArray as $date => $data): ?>
								<li>
									<h5>
										<?php 
											echo date("d M Y", strtotime($date));
										?>
									</h5>
									<?php
										foreach ($data as $roomId => $roomDetails) {
											if ($roomDetails['allotment'] >= $rooms) {
												echo "<p class='roomType roomType_$roomId'>";
												echo "<span class='baseCost'>$ ".number_format((float)$roomDetails['price']*1.15, 2)."</span><br>";
												echo "$ <span class='roomCost'>".number_format($roomDetails['price'], 2)."</span>";
												echo "</p>";
											}else{
												echo "<p class='roomType noAllotment'>";
												echo "<span class='roomType'>$roomId</span>";
												echo "$ <span class='roomCost'>".$roomDetails['price']."</span>";
												echo "</p>";
											}
										}
									?>
								</li>
							<?php endforeach ?>
						</ul>
					</div>
					<div id="bookExtras" class="clearfix">

					</div>
					<div id="bookFooter">
						<div class="hidden" id="totals">
							<p id="roomTypeTotal"><?php echo count($priceArray); ?> <?php echo $bookCont['room_type_total']; ?> <span id="totalRoomType"></span></p>
							<p id="totalAmount"> $<span id="totalCost"></span></p>
							<div class="clear"></div>
							<p id="roomsQty">x <span id="roomsQtyNo"><?php echo $rooms; ?></span> <?php echo $bookCont['unit_label']; ?></p>
							<p id="grandTotalPar"> $<span id="grandTotal"></span></p>
							<div class="clear"></div>
							<p id="extraAdultsTitle"><span id="extraNumberOfAdults"><?php echo $extra; ?></span> <?php echo $bookCont['extra_adults']; ?></p>
							<p id="extraAdults">$ <span id="extraAdultsAmount"><?php echo number_format($extraAdultCost * $extra, 2); ?></span></p>
							<div class="clear"></div>
							<p id="taxTitle"><?php echo $bookCont['tax_label']; ?> (<span id="taxAmount"><?php echo $tax*100; ?></span>%)</p>
							<p id="taxTotal">$ <span id="taxTotalAmount"></span></p>
							<div class="clear totalRow"><hr></div>
							<p id="totalAmountTitle"><?php echo $bookCont['room_amount_total']; ?></p>
							<p id="totalAmount">$ <span id="totalAmountNumber"></span></p>
						</div>
						<div class="bookCallToAction">
							<button id="toPersonalData" class="callToSmall inactive" disabled="disabled"><?php echo $bookCont['reserve_now_button']; ?></button>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div id="personalDetails">
				<?php echo form_open('reservaciones/registroUsuario'); ?>
				<fieldset>                         
					<p class="inputParWide"><input tabindex="1" type="text" name="name" id="name"> <label class="personalDetailsLabel" for="nombre"><?php echo $bookCont['form_label_name']; ?> &lowast;</label></p>
					<p class="inputParWide"><input tabindex="2" type="text" name="lastname" id="lastname"> <label class="personalDetailsLabel" for="apellidos"><?php echo $bookCont['form_label_lastname']; ?></label></p>
					<p class="inputParWide"><input tabindex="3" type="text" name="email" id="email"> <label class="personalDetailsLabel" for="email"><?php echo $bookCont['form_label_email']; ?></label></p>
					<p class="inputParWide"><input tabindex="4" type="text" name="tel" id="tel"> <label class="personalDetailsLabel" for="tel"><?php echo $bookCont['form_label_tel']; ?></label></p>
					<p class="inputParWide"><input tabindex="5" type="text" name="country" id="country"> <label class="personalDetailsLabel" for="country"><?php echo $bookCont['form_label_country']; ?></label></p>
					<div>
						<button id="backRoomSel" class="callToSmall"><?php echo $bookCont['back_button']; ?></button>
					</div>
				</fieldset>
				<fieldset id="secCol">
					<p class="textareaPar"><textarea tabindex="6" name="address" id="address" cols="30" rows="10"></textarea> <label class="personalDetailsLabel" for="address"><?php echo $bookCont['form_label_address']; ?></label></p>
					<p class="inputParWide"><input tabindex="7" type="text" name="city" id="city"> <label class="personalDetailsLabel" for="city"><?php echo $bookCont['form_label_city']; ?></label></p>
					<p class="inputParWide">
                        <select tabindex="8" name="zip" id="zip">
                            <option value="N/A"></option> 
                            <option value="01 PM">01 PM</option> 
                            <option value="02 PM">02 PM</option> 
                            <option value="03 PM">03 PM</option> 
                            <option value="04 PM">04 PM</option> 
                            <option value="05 PM">05 PM</option> 
                            <option value="06 PM">06 PM</option> 
                            <option value="07 PM">07 PM</option> 
                            <option value="08 PM">08 PM</option> 
                            <option value="09 PM">09 PM</option> 
                            <option value="10 PM">10 PM</option> 
                            <option value="11 PM">11 PM</option>
                            <option value="12 AM">12 AM</option> 
                            <option value="01 AM">01 AM</option> 
                            <option value="02 AM">02 AM</option> 
                            <option value="03 AM">03 AM</option> 
                            <option value="04 AM">04 AM</option> 
                            <option value="05 AM">05 AM</option> 
                            <option value="06 AM">06 AM</option> 
                            <option value="07 AM">07 AM</option> 
                            <option value="08 AM">08 AM</option> 
                            <option value="09 AM">09 AM</option> 
                            <option value="10 AM">10 AM</option> 
                            <option value="11 AM">11 AM</option> 
                            <option value="12 PM">12 PM</option> 
                        </select>
                        <label class="personalDetailsLabel" for="zip"><?php echo $bookCont['form_label_zip']; ?></label></p>
					<p class="textareaPar"><textarea tabindex="9" name="comments" id="comments" cols="30" rows="10"></textarea> <label class="personalDetailsLabel" for="comments"><?php echo $bookCont['form_label_comments']; ?></label></p>
					<p class="inputPar shortyPar"><input tabindex="10" type="checkbox" name="terms" id="terms"> <label class="personalDetailsLabel" for="terms"><?php echo $bookCont['form_label_terms_agree']; ?></label><span id="termsLink"><small><a id="termsConditions" href="#">T&eacute;rminos &amp; Condiciones</a></small></span></p>
					<div class="bookCallToAction"><button id="captureDataAndBook" class="callToSmall">Reservar Habitaci&oacute;n</button></div>
				</fieldset>
				<?php echo form_close(); ?>
			</div>
			<div class="clearfix"></div>
		</div>
		<div id="secondaryContent" class="bookingSecondaryCont">
			
		</div>
		<footer>
			
		</footer>
	</div> <!--! end of #container -->
	<div class="hidden">
		<input id="host" type="hidden" value="<?php echo site_url(); ?>/">
		<?php foreach ($roomTypeList as $key => $value): ?>
			<input type="hidden" id="roomTypeAllotment_<?php echo $key; ?>" value="<?php echo $value['allotment'] ?>" />
		<?php endforeach ?>
		<form action="<?php echo site_url(); ?>/paypalConfirm/confirmation/" method="post" accept-charset="utf-8" id="paypalSubmitForm">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="upload" value="1">
			<input type="hidden" name="no_note" value="1">
			<input type="hidden" name="business" value="ventas@hotelcasamargarita.com">
			<input id="paypalIpnUrl" class="ipnNotify" type="hidden" name="notify_url" value="<?php echo site_url(); ?>/paypal_ipn/receiveData">
			<input id="paypalName" type="hidden" name="item_name" value="">
			<input id="paypalAmount" type="hidden" name="amount" value="">
			<input type="hidden" name="currency_code" value="USD">
			<input id="returnUrl" type="hidden" name="return" value="<?php echo site_url('reservaciones/receiveConfirm/'.$cont['lang']); ?>">
			<div id="paypalMsg">
				<p>Tu reservaci&oacute;n ser&aacute; confirmada al presionar el bot&oacute;n.</p>
				<p>Recibir&aacute;s un correo electr&oacute;nico de nuestro sistema, impr&iacute;melo y etregalo al llegar con nosotros.</p>
				<p id="submitPar"><input id="paypalSubmitButton" type="submit" value="Confirmar Reservaci&oacute;n &nbsp;&nbsp;&rsaquo;"></p>
			</div>
		</form>
		<p id="checkin"><?php reset($datesArray); echo key($datesArray); ?></p>
		<p id="checkout"><?php echo end(array_keys($datesArray)); ?></p>
		<form id="confirmBook">
			<input type="hidden" name="userId" id="userId">
			<input type="hidden" name="checkin" id="checkin">
			<input type="hidden" name="checkout" id="checkout">
			<input type="hidden" name="roomType" id="roomType">
		</form>
		<ul id="bookingSteps">
			<li id="book_step_1"><?php echo $bookCont['book_step_1']; ?></li>
			<li id="book_step_2"><?php echo $bookCont['book_step_2']; ?></li>
			<li id="book_step_3"><?php echo $bookCont['book_step_3']; ?></li>
			<li id="book_step_4"><?php echo $bookCont['book_step_4']; ?></li>
			<li id="book_step_5"><?php echo $bookCont['book_step_5']; ?></li>
			<li id="book_step_6"><?php echo $bookCont['book_step_6']; ?></li>
			<li id="book_step_7"><?php echo $bookCont['book_step_7']; ?></li>
		</ul>
		<p id="buttonText"><?php echo $bookCont['reserve_now_button']; ?></p>
		<p id="noAvailability"><?php echo $bookCont['button_noAllotment']; ?></p>
	</div>
	<?php echo $scripts; ?>
	<?php if ($cont['lang'] != 'en'): ?>
		<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/localization/messages_<?php echo strtoupper($cont['lang']); ?>.js"></script>
	<?php endif ?>
	<script>
		$(function(){
			var reserve = new selectReservation();
			reserve.init();
		});
	</script>
</body>
</html>