<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Sensaciones Motel Boutique | Book Now!</title>
	<link href='http://fonts.googleapis.com/css?family=Alex+Brush' rel='stylesheet' type='text/css'>
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
				<p id="progressMsg">Reservaci&oacute;n confirmada, por favor imprima esta ficha!</p>
				<div id="progress">
					
				</div>
			</div>
			<div id="tableConfirm">
				<table id="reservDetails">
					<tr class="highlight"><td>Reservaci&oacute;n: </td><td><?php echo $reserva['id_reserve']; ?></td></tr>
					<tr>
						<td><?php echo $reserva['checkin']; ?></td>
						<td>
							<?php
								$formatDate = explode("-", $reserva['payment_date']);
								echo $formatDate[2]."/".$formatDate[1]."/".$formatDate[0]." ";
							?>
						</td>
					</tr>
					<tr><td>Nombre: </td><td><?php echo $user['name_user']; ?> <?php echo $user['lastname_user']; ?></td></tr>
					<tr><td>Tel&eacute;fono: </td><td><?php echo $user['tel_user']; ?></td></tr>
					<tr><td>Email: </td><td><?php echo $user['email_user']; ?></td></tr>
					<tr>
						<td>Entrada</td>
						<td>
							<?php
								$date1 =  $reserva['checkin'];
								$formatDate1 = explode("-", $date1);
								echo $formatDate1[2]."/".$formatDate1[1]."/".$formatDate1[0];
							?>
						</td>
					</tr>
					<tr><td></td><td><button id="print">Imprimir Ficha</button></td></tr>
				</table>
			</div>
			<div class="clearfix"></div>
		</div>
		<div id="secondaryContent" class="bookingSecondaryCont">
			
		</div>
		<footer>
			
		</footer>
	</div> <!--! end of #container -->
	<?php echo $scripts; ?>
</body>
</html>