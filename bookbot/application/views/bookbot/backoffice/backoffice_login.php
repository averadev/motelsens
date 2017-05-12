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
    <link rel="stylesheet" href="<?php echo base_url(); ?>js/vendor/fancybox/jquery.fancybox.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/main.css">
</head>
<body class="controlPanel">
	<div id="container">
		<header>
			<nav>
				<img id="bookLogo" src="<?php echo base_url(); ?>img/bookbot/motel_sensaciones_logo.png" alt="Sensaciones Motel Boutique">
			</nav>
		</header>
		<div id="main" role="main">
			<?php 
				echo form_open('backoffice/validateAdmin', array('id' => 'loginForm' ));
			?>
				<legend><img src="<?php echo base_url(); ?>img/interfase/Gear.png" width="24" height="24" alt="Gear"> Ingreso al panel de control</legend>
			<?php
				echo "<p>";
				echo form_input('username');
				echo form_label(' Nombre de usuario', 'username');
				echo "</p>";
				echo "<p>";
				echo form_password('password');
				echo form_label(' Password', 'password');
				echo "</p>";
			?>
				<p id="error"><?php echo $error; ?></p>
				<p><input type="submit" class="callToSmall" value="Ingresar" /></p>
			<?php
				echo form_close();
			?>
		</div>
		<footer>
			<div id="zigzag">
				
			</div>
			<div id="textureSpace">
				
			</div>
		</footer>
	</div> <!--! end of #container -->
	<div class="hidden">
		<div id="lang"></div>
	</div>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?php echo base_url(); ?>js/vendor/jquery-1.8.3.min.js"><\/script>')</script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
	<script src="<?php echo base_url(); ?>js/plugins.js"></script>
	<script src="<?php echo base_url(); ?>js/bookbot.js"></script>
	<script src="<?php echo base_url(); ?>js/main.js"></script>
</body>
</html>