<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Casa Margarita Hotel Boutique | All inclusive hotel in Cancun</title>
        <meta name="description" content="Casa Margarita Hotel Boutique offers an all-inclusive plan with all meals and drinks included in your price. Let us pamper you with our first class service.">
        <?php echo $styles; ?>
        <script src="<?php echo base_url(); ?>js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body class="amenidades">
        <!--[if lt IE 7]><p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p><![endif]-->
        <?php echo $topBar; ?>
        <?php echo $navigation; ?>
        <article class="sideContent">
            <h1 class="amenitiesHeading serviceHeading restHeading"><?php echo lang('rest_tit1'); ?></h1>
        	<p><?php echo lang('rest_p1'); ?></p>
            <p><?php echo lang('rest_p2'); ?></p>
            <p><?php echo lang('rest_p3'); ?></p>
            <p><?php echo lang('rest_p4'); ?></p>
            <p><?php echo lang('rest_p5'); ?></p>
            <p><?php echo lang('rest_hr1'); ?></p>
            <h1 class="amenitiesHeading serviceHeading restHeading"><?php echo lang('rest_tit2'); ?></h1>
            <p><?php echo lang('rest_p6'); ?></p>
            <p><?php echo lang('rest_p7'); ?></p>
            <p><?php echo lang('rest_p8'); ?></p>
            <p><?php echo lang('rest_hr2'); ?></p>
        </article>
        <div class="galleryContainer">
            <ul class="galleryList">
                <li class="thumbElem"><a rel="gallery" class="galleryThumb" href="<?php echo base_url(); ?>img/elems/gallery/rest/gallery_37.jpg"><img src="<?php echo base_url(); ?>img/elems/gallery/rest/thumbs/gallery_37.jpg" alt=""></a></li>
                <li class="thumbElem"><a rel="gallery" class="galleryThumb" href="<?php echo base_url(); ?>img/elems/gallery/rest/gallery_20.jpg"><img src="<?php echo base_url(); ?>img/elems/gallery/rest/thumbs/gallery_20.jpg" alt=""></a></li>
                <li class="thumbElem"><a rel="gallery" class="galleryThumb" href="<?php echo base_url(); ?>img/elems/gallery/rest/gallery_38.jpg"><img src="<?php echo base_url(); ?>img/elems/gallery/rest/thumbs/gallery_38.jpg" alt=""></a></li>
                <li class="thumbElem"><a rel="gallery" class="galleryThumb" href="<?php echo base_url(); ?>img/elems/gallery/rest/gallery_19.jpg"><img src="<?php echo base_url(); ?>img/elems/gallery/rest/thumbs/gallery_19.jpg" alt=""></a></li>
            </ul>
            <a class="gallerySwitch" href="#" title="Open Gallery"></a>
        </div>
        <?php echo $booking_form; ?>
        <?php echo $contact_form; ?>
        <?php echo $scripts; ?>
        <script>
            var _gaq=[['_setAccount','UA-27477660-6'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>