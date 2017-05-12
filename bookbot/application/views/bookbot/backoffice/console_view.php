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
	<title></title>
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/normalize.css">
	<link href='http://fonts.googleapis.com/css?family=Average+Sans' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Rouge+Script' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo base_url(); ?>js/vendor/jquery-ui/css/pepper-grinder/jquery-ui-1.9.2.custom.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/main.css">
	<script src="<?php echo base_url(); ?>js/vendor/modernizr-2.6.2.min.js"></script>
</head>
<body class="controlPanel">
	<div id="container">
		<header id="backofficeHeader">
			<nav>
				<img id="bookLogo" src="<?php echo base_url(); ?>img/bookbot/motel_sensaciones_logo.png" alt="Sensaciones Motel Boutique">
			</nav>
		</header>
		<div class="controlPanel" id="main" role="main">
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">Reservaciones</a></li>
					<li><a href="#tabs-2">Habitaciones</a></li>
					<li><a href="#tabs-3">Disponibilidad</a></li>
					<li><a href="#tabs-4">Temporadas</a></li>
					<li><a href="#tabs-5">Clientes</a></li>
					<li><a href="#tabs-6">Configuraci&oacute;n</a></li>
				</ul>
				<div id="tabs-1">
					<h1>Lista de reservaciones.</h1>
					<?php if (isset($reservas)): ?>
						<div>
							<ul id="reservasList" class="reservList" data-method="reservas">
							<?php foreach ($reservas as $reserveId => $data): ?>
								<li>
									<div class="reservToolBar">
										<input class="reserveId" type="hidden" value="<?php echo $reserveId; ?>">
										<span class="editReserveButton"><?php echo anchor('#', img(array('src'=>'img/interfase/Gear_mini.png')), array('class'=>'editReserv')); ?></span>
										<span class="cancelReserveButton"><?php echo anchor('#', img(array('src'=>'img/interfase/trash_mini.png')), array('class'=>'cancelReserv confirmNew')); ?></span>
										<div class="clear"></div>
									</div>
									<span class="roomQty"><?php echo $data['room_qty']; ?></span>
									<h4 class="reservHeading">
										<strong><?php echo $data['name']; ?></strong>
										<span>( <?php echo $data['country_user']; ?>)</span>
										<span class="checkOut">Check-Out: <?php echo date('D d F Y', strtotime($data['checkout'])); ?></span>
										<span class="checkIn">Check-In: <?php echo date('D d F Y', strtotime($data['checkin'])) ." - ".  $data['arrival']; ?>  </span>
									</h4>
									<div class="reservDetailPars">
										<p class="reservPars commentPar"><?php echo $data['comments_reserve']; ?></p>
										<p class="reservPars bottomRow">
											<span class="emailSpan">Email: <?php echo $data['email_user'] ?></span>
											<span class="telSpan">Tel: <?php echo $data['tel_user']; ?></span>
											<span class="roomTypeName"><?php echo $data['room_type']; ?></span>
										</p>
									</div>
								</li>
							<?php endforeach ?>
						</ul>
					</div>
					<?php endif ?>
					<?php if (isset($unconfirmed)): ?>
						<h1>Reservaciones no confirmadas</h1>
						<div>
							<ul class="reservList" data-method="reservas">
							<?php foreach ($unconfirmed as $reserveId => $data): ?>
								<li>
									<div class="reservToolBar">
										<input class="reserveId" type="hidden" value="<?php echo $reserveId; ?>">
										<span class="activateNotConfirmedButton"><?php echo anchor('#', img(array('src'=>'img/interfase/Power_mini.png')), array('class'=>'activateNotConfirmed')); ?></span>
										<span class="cancelUnconfirmedButton"><?php echo anchor('#', img(array('src'=>'img/interfase/trash_mini.png')), array('class'=>'cancelReserv confirmNew')); ?></span>
										<div class="clear"></div>
									</div>
									<span class="roomQty"><?php echo $data['room_qty']; ?></span>
									<h4 class="reservHeading">
										<strong><?php echo $data['name']; ?></strong>
										<span>( <?php echo $data['country_user']; ?>)</span>
										<span class="checkOut">Check-Out: <?php echo date('D d F Y', strtotime($data['checkout'])); ?></span>
										<span class="checkIn">Check-In: <?php echo date('D d F Y', strtotime($data['checkin']))." - ".  $data['arrival']; ?></span>
									</h4>
									<div class="reservDetailPars">
										<p class="reservPars commentPar"><?php echo $data['comments_reserve']; ?></p>
										<p class="reservPars bottomRow">
											<span class="emailSpan">Email: <?php echo $data['email_user'] ?></span>
											<span class="telSpan">Tel: <?php echo $data['tel_user']; ?></span>
											<span class="roomTypeName"><?php echo $data['room_type']; ?></span>
										</p>
									</div>
								</li>
							<?php endforeach ?>
							</ul>
						</div>
					<?php endif ?>
				</div>
				<div id="tabs-2">
					<h1>Tipos de habitaciones</h1>
					<table class="controlPanel">
						<thead>
							<tr class="headRow">
								<th>Habitaci&oacute;n</th>
								<th>Descripci&oacute;n Ingl&eacute;s</th>
								<th>Descripci&oacute;n Espa&ntilde;ol</th>
								<th>Allotment</th>
								<th>Precio base</th>
							</tr>
						</thead>						
						<tbody>
							<?php
								foreach ($rooms as $id => $data) {
									echo "<tr id=\"room_cat_$id\">";
									foreach ($data as $key => $value) {
										if ($key != 'cat_room_name' && $key != 'cat_room_allotment' && $key != 'cat_room_base_price') {
											echo "<td class='$key'><span class='editableData textAreaEdit'>$value</span></td>";
										}else{
											echo "<td class='$key'><span class='editableData'>$value</span></td>";
										}
									}
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
					<p class="hidden">editSuiteData</p>
				</div>
				<div id="tabs-3">
					<h1>Calendario de disponibilidad</h1>
					<div class="availabilityCalendar">
						<?php echo $calendar; ?>
					</div>
					<h1>Bloqueo de habitaciones</h1>
					<table class="controlPanel" id="roomBlockForm">
						<thead>
							<tr class="headRow">
								<th>Tipo de habitaci&oacute;n</th>
								<th>Primer dia de bloqueo</th>
								<th>&Uacute;ltimo dia de bloqueo</th>
								<th>N&uacute;mero de habitaciones</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<select name="roomType" id="roomType">
										<?php foreach ($rooms as $key => $value): ?>
											<option value="<?php echo $key; ?>"><?php echo $value['cat_room_name']; ?></option>
										<?php endforeach ?>
									</select>
								</td>
								<td><input class="date" type="text" name="startBlock" value="" id="startBlock"></td>
								<td><input class="date" type="text" name="endBlock" value="" id="endBlock"></td>
								<td><input class="shortNumberInput" name="roomQty" value="" id="roomQty" type="text"></td>
								<td><button id="blockRoom">Bloquear &rsaquo;&rsaquo;</button></td>
							</tr>
						</tbody>
					</table>
					<h1>Bloqueos Activos</h1>
					<table id="block" class="controlPanel">
						<thead>
							<tr class="headRow">
								<th>Habitaci&oacute;n</th>
								<th>Primer dia de bloqueo</th>
								<th>&Uacute;ltimo dia de bloqueo</th>
								<th>Habitaciones</th>
								<th>Borrar</th>
							</tr>
						</thead>
						<?php if (isset($bloqueos)): ?>
						<tbody>
							<?php foreach ($bloqueos as $id => $data): ?>
								<tr>
									<td class="hidden"><?php echo $id; ?></td>
									<td><?php echo $data['room_type'] ?></td>
									<td><?php echo date('d/m/Y', strtotime($data['checkin'])); ?></td>
									<td><?php echo date('d/m/Y', strtotime($data['checkout'])); ?></td>
									<td><?php echo $data['roomQty']; ?></td>
									<td><?php echo anchor('#', img(array('src'=>'img/interfase/delete.png')), array('class'=>'cancelBlock confirm')); ?></td>
								</tr>
							<?php endforeach ?>
						</tbody>
						<?php endif ?>
					</table>
				</div>
				<div id="tabs-4">
					<h1>Nueva Temporada</h1>
					<table class="controlPanel" id="newSeason">
						<thead>
							<tr class="headRow">
								<th>Nombre</th>
								<th>Inicia</th>
								<th>Termina</th>
								<th>Descripci&oacute;n</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><input type="text" name="seasonName" id="seasonName"></td>
								<td><input class="date" type="text" name="seasonStart" id="seasonStart"></td>
								<td><input class="date" type="text" name="seasonEnd" id="seasonEnd"></td>
								<td><input type="text" name="seasonDescrip" id="seasonDescrip"></td>
								<td><button id="saveSeason">Guardar</button></td>
							</tr>
						</tbody>
					</table>
					<h1>Temporadas</h1>
					<table id="activeSeasons" class="controlPanel">
						<thead>
							<tr class="headRow">
								<th>Temporada</th>
								<th>Inicia</th>
								<th>Termina</th>
								<th>Comentarios</th>
								<th>Borrar</th>
								<th>Activar</th>
							</tr>
						</thead>
						<tbody>
							<?php
								if (isset($seasons)) {
									foreach ($seasons as $season => $data) {
										echo "<tr id='$season'>";
										foreach ($data as $key => $value) {
											echo "<td class='$key'>$value</td>";
										}
										echo "<td><button class='deleteSeason'>x</button></td>";
										echo "<td><button class='activateSeason'>+</button></td>";
										echo "</tr>";
									}
								}
							?>
						</tbody>
					</table>
					<h1>Precios por temporada</h1>
					<table class="controlPanel" id="seasonEdit">
						<thead>
							<tr class="headRow">
								<th>Temporada</th>
								<?php foreach ($rooms as $key => $data): ?>
									<th><?php echo $data['cat_room_name'] ?></th>
								<?php endforeach ?>
							</tr>
						</thead>
						<?php if (isset($seasonPrices)): ?>
						<tbody>
							<?php foreach ($seasonPrices as $key => $value): ?>
								<?php
									//Gets the season Id
									$roomArray = array_keys($value);
									$object = $roomArray[0];
									$seasonId = $value["$object"];
									$seasonId = $seasonId->season_id;
								?>
								<tr id="season_id_<?php echo $seasonId; ?>">
									<td><?php echo $key; ?></td>
									<?php foreach ($value as $key2 => $data): ?>
										<td class="cat_room_id_<?php echo $data->cat_room_id; ?>"><?php echo $data->season_room_price; ?></td>
									<?php endforeach ?>
								</tr>
							<?php endforeach ?>
						</tbody>
						<?php endif ?>
					</table>
				</div>
				<div id="tabs-5">
					<h1>Lista de hu&eacute;spedes.</h1>
					<div id="customerList">
						<table class="controlPanel" id="clientes">
						<thead>
							<tr class="headRow">
								<th>Id</th>
								<th>Nombre</th>
								<th>Email</th>
								<th>Tel&eacute;fono</th>
								<th>Direcci&oacute;n</th>
								<th>Ciudad</th>
								<th>Pa&iacute;s</th>
								<th>Eliminar</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($users as $userId => $data) {
									echo "<tr>";
									foreach ($data as $key => $value) {
										echo "<td class='$key'>$value</td>";
									}
									echo "<td>".anchor('#', img(array('src'=>'img/interfase/delete.png')), array('class'=>'cancelReserv confirm'))."</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
					</div>
				</div>
				<div id="tabs-6">
					<h1>Variables de configuraci&oacute;n</h1>
					<table class="controlPanel">
						<tr>
							<td><label for="tax">Impuesto: </label></td>
							<td><input id="tax" class="variableInput" type="text" value="<?php echo (float)$tax*100; ?>" /> %</td>
							<td><button class="variableSend">Guardar</button></td>
						</tr>
						<!--
						<tr>
							<td><label for="extra_adult">Adulto extra: </label></td>
							<td>$ <input type="text" id="extra_adult" class="variableInput" value="<?php echo $extra_adult; ?>" /></td>
							<td><button class="variableSend">Guardar</button></td>
						</tr>
						-->
					</table>
				</div>
			</div>
		</div>
		<footer>
			
		</footer>
	</div> <!--! end of #container -->
	<div class="hidden">
		<input id="host" type="hidden" value="<?php echo site_url(); ?>/">
		<table id="tableTemplate">
			<tr id="blockRoomRow">
				<td class="hidden"></td>
				<td class="blockRoomTypeCell"></td>
				<td class="blockRoomStartCell"></td>
				<td class="blockRoomEndCell"></td>
				<td class="blockRoomQtyCell"></td>
				<td><?php echo anchor('#', img(array('src'=>'img/interfase/delete.png')), array('class'=>'cancelBlock confirm')); ?></td>
			</tr>
			<tr id="seasonRow">
				<td class="season_name"></td>
				<td class="date_season_start"></td>
				<td class="date_season_end"></td>
				<td class="season_comments"></td>
				<td><button class='deleteSeason'>x</button></td>
				<td><button class='activateSeason'>+</button></td>
			</tr>
			<tr class="seasonPricesRow">
				<td class="seasonPriceName"></td>
				<td id="tempPriceCell" class="seasonPriceCell"></td>
			</tr>
		</table>
		<div class="preloader">
			<p><img src="<?php echo base_url(); ?>img/interfase/preloader.gif" alt="Guardando"></p>
			<p>Cargando...</p>
		</div>
		<div id="seasonActivate">
			<table class="controlPanel">
				<tr class="headRow">
					<th>Tipo de habitaci&oacute;n</th><th>Precio</th>
				</tr>
				<?php foreach ($rooms as $key => $value): ?>
					<tr class="roomType" id="roomCat_<?php echo $key; ?>">
						<td><?php echo $value['cat_room_name']; ?></td><td><input type="text" name="seasonPriceInput" class="seasonPriceInput"></td>
					</tr>
				<?php endforeach ?>
				<tr>
					<td></td><td><button id="saveSeasonPrices">Guardar</button></td>
				</tr>
			</table>
			<div class="hidden">
				<input type="hidden" value="" id="seasonId">
			</div>
		</div>
		<div class="dateBlockPanel">
			<table class="controlPanel">
				<tr class="headRow">
					<th>Tipo de habitaci&oacute;n</th><th>Disponibilidad</th>
				</tr>
				<?php foreach ($rooms as $key => $value): ?>
					<tr class="roomTypeRow roomAvailCat_<?php echo $key; ?>">
						<td>
							<span class="roomLabelName"><?php echo $value['cat_room_name']; ?></span>
							<input type="hidden" class="roomTypeIdContainer" value="<?php echo $key; ?>">
						</td>
						<td>
							<input type="text" class="roomAllotmentField">
							<input type="hidden" class="roomTypeActualAllotment" value="">
							<input type="hidden" class="roomTypeMaxAllotment" value="<?php echo $value['cat_room_allotment']; ?>">
						</td>
					</tr>
				<?php endforeach ?>
				<tr>
					<td><button id="cancelDateAllotment">Cancelar</button></td>
					<td><button id="saveDateAllotment">Guardar</button><input class="blockingDate" type="hidden" value=""></td>
				</tr>
			</table>
		</div>
	</div>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?php echo base_url(); ?>js/vendor/jquery-1.8.3.min.js"><\/script>')</script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
	<script src="<?php echo base_url(); ?>js/plugins.js"></script>
	<script src="<?php echo base_url(); ?>js/bookbot.js"></script>
	<script>
		$(function(){
			var controlPanel = new backOffice();
			controlPanel.init();
		});
	</script>
</body>
</html>