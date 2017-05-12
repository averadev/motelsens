<?php echo form_open("reservaciones/checkDates/".lang('language'), array('id' => 'bookingForm')); ?>
            <a href="#" class="closeForm"><img src="<?php echo base_url(); ?>img/elems/close_button.png" alt="Close"></a>
            <h1 id="bookFormHeading"><?php echo lang('book_now'); ?></h1>
            <fieldset id="datosHospedaje">
                <p class="inputPar dateField"><img class="calpicker" src="<?php echo base_url(); ?>img/elems/checkin_ico.png" width="16" height="16" alt="Check in calendar" /> <input type="text" class="datepicker" name="checkIn" value=""  id="checkIn" placeholder="Check In" /></p>
                <p class="inputPar dateField"><img class="calpicker" src="<?php echo base_url(); ?>img/elems/checkout_ico.png" width="16" height="16" alt="Check out calendar" /> <input type="text" class="datepicker" name="checkOut" value="" id="checkOut" placeholder="Check Out" /></p>
            </fieldset>
            <fieldset id="slidersHospedaje">
                <div id="sliders">
                    <p class="sliderInput"><label class="sliderLabel" for="adults"><?php echo lang('adults'); ?></label> <input class="digit" type="text" name="adults" value="2" id="adults"></p>
                    <!-- <p class="sliderInput"><label class="sliderLabel" for="children"><?php echo lang('children'); ?></label> --> <input class="digit" type="hidden" name="children" value="0" id="children"> <!-- </p> -->
                    <p class="sliderInput"><label class="sliderLabel" for="rooms"><?php echo lang('rooms'); ?></label> <input class="digit" type="text" name="rooms" value="1" id="rooms"></p>
                </div>
                <p id="submitDatesButton"><input class="callToSmall" type="submit" name="submitReserve" value="<?php echo lang('avail_button'); ?>" id="submitReserve"></p>
            </fieldset>
        </form>