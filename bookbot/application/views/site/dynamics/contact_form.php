<form id="contactForm" action="#">
            <a href="#" class="closeForm"><img src="<?php echo base_url(); ?>img/elems/close_button.png" alt="Close"></a>
            <fieldset class="textContact">
                <p><?php echo lang('tel_tit'); ?></p>
                <p class="contactCont"><a href="tel:">USA: +1 855 222 75 65</a></p>
                <p class="contactCont"><a href="tel:">MEX: +52 (998) 8 83 26 11</a></p>
                <p><?php echo lang('email_tit'); ?></p>
                <p class="contactCont"><a href="mailto:info@hotelcasamargarita.com">info@hotelcasamargarita.com</a></p>
                <p class="contactCont"><a href="mailto:ventas@hotelcasamargarita.com">ventas@hotelcasamargarita.com</a></p>
                <p><?php echo lang('address_tit'); ?></p>
                <p class="contactCont"><?php echo lang('hotel_zone'); ?>, Pok-Ta-Pok 15b.</p>
                <p class="contactCont">Canc&uacute;n, Quintana Roo.</p>
            </fieldset>
            <fieldset class="contactInputs">
                <p class="contactPar"><label for="name"><?php echo lang('name_label'); ?></label></p>
                <p class="contactPar"><input class="contactInput" id="name" type="text"></p>
                <p class="contactPar"><label for="email"><?php echo lang('email_label'); ?></label></p>
                <p class="contactPar"><input class="contactInput" id="email" type="text"></p>
                <p class="contactPar"><label for="subject"><?php echo lang('asunto_label'); ?></label></p>
                <p class="contactPar"><input class="contactInput" id="subject" type="text"></p>
                <p class="contactPar"><label for="message"><?php echo lang('mensaje_label'); ?></label></p>
                <p class="contactPar"><textarea class="contTextarea" id="message" cols="30" rows="10"></textarea></p>
                <p class="contactPar"><input id="submitContact" type="submit" value="<?php echo lang('submit_label'); ?>"></p>
                <input id="host" type="hidden" value="<?php echo site_url(); ?>">
            </fieldset>
            <div class="hidden">
                <img class="preloader" src="<?php echo base_url() ?>img/elems/preloader.gif" alt="Sending...">
            </div>
        </form>