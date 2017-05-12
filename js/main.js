/* 
	Author: PM @ Green Labs Mx
	Website: http://greenlabs.com.mx
*/
var script = function(){
		
	var bindEvents = function(){
		$('ul#roomsDropDown, a#roomDropdown').on('mouseenter', $(this), function(event) {
			event.preventDefault();
			if (!(isiPad()||isiPhone())) {
				handleDropDown();
			};
		}).on('mouseleave', function(event) {
			event.preventDefault();
			if (!(isiPad()||isiPhone())) {
				handleDropDown();
			};
		});
		$('a#roomDropdown').on('click', function(event) {
			event.preventDefault();
			if (isiPad()||isiPhone()) {
				handleDropDown();
			};
		});
		$('a#bookbotDropBot').on('click', function(event) {
			event.preventDefault();
			handleBookbotDropDown();
		});
		$('a.bookNowButton').on('click', function(event) {
			event.preventDefault();
			handleBookNowSuiteButton();
		});
		$('.contactInput').on('focus', function(event) {
			$(this).addClass('activeInput');
		}).on('blur', function(event) {
			$(this).removeClass('activeInput');
		});
		$('.sliderArrowLeft').on('click', function(event) {
			event.preventDefault();
			$('.sliderContainer').cycle('prev');
		});
		$('.sliderArrowRight').on('click', function(event) {
			event.preventDefault();
			$('.sliderContainer').cycle('next');
		});
		try{
			$('.sliderContainer').cycle({
				fx: 'fade',
				timeout: 10000
			});
		}catch(e){};
		try{
			$('a.verMasSingle').fancybox();
			$('a#sexshopLink').on('click', function(event) {
				event.preventDefault();
				$.fancybox([
					'http://sensacionesmotel.com/img/elems/galleries/sexshop/page1.jpg',
					'http://sensacionesmotel.com/img/elems/galleries/sexshop/page2.jpg',
					'http://sensacionesmotel.com/img/elems/galleries/sexshop/page3.jpg',
					'http://sensacionesmotel.com/img/elems/galleries/sexshop/page4.jpg'
				], {
					'padding'			: 0,
					'transitionIn'		: 'none',
					'transitionOut'		: 'none',
					'type'              : 'image',
					'changeFade'        : 0
				});
			});
			$('a#roomserviceLink').on('click', function(event) {
				event.preventDefault();
				$.fancybox([
					'http://sensacionesmotel.com/img/elems/galleries/roomservice/pag1.jpg',
					'http://sensacionesmotel.com/img/elems/galleries/roomservice/pag3.png',
					'http://sensacionesmotel.com/img/elems/galleries/roomservice/pag4.png',
					'http://sensacionesmotel.com/img/elems/galleries/roomservice/pag5.png'
				], {
					'padding'			: 0,
					'transitionIn'		: 'none',
					'transitionOut'		: 'none',
					'type'              : 'image',
					'changeFade'        : 0
				});
			});
		}catch(e){};
	}

	var onloadExec = function(){
		bindEvents();
		imperialSlideshow();
		disableBackBot();
		try{
			googleMap();
		}catch(e){}
	}

	var imperialSlideshow = function(){
		if ($('.bgSlider').length) {
			var loop = 1;
			setInterval(function(){
				$('#imperialSlide'+loop).fadeOut('slow');
				if (loop >= 3) {
					loop = 0;
				};
				loop++;
				$('#imperialSlide'+loop).fadeIn('slow');
			}, 10000);
		}else{
			return false;
		}
	}

	var disableBackBot = function(){
		$('.gotoCheckin').attr('disabled', 'disabled');
	}

	var isiPad = function(){
		return (navigator.platform.indexOf("iPad") != -1);
	}

	var isiPhone = function(){
		return (navigator.platform.indexOf("iPhone") != -1);
	}

	var handleDropDown = function(){
		$('ul#roomsDropDown').stop().slideToggle(200);
	}

	var handleBookbotDropDown = function(){
		var bookbot = $('div#bookbotDropdown');
		var listElem = bookbot.closest('li');
		bookbot.stop().slideToggle(200);
		if (listElem.hasClass('activeBookbot')) {
			listElem.removeClass('activeBookbot');
		}else{
			listElem.addClass('activeBookbot');
		}
	}

	var handleBookNowSuiteButton = function(){
		$('html, body').animate({ scrollTop: 0 }, 'slow');
		$('a#bookbotDropBot').click();
	}

	var googleMap = function(){
		infowindow = null;
		var initialize = function(div, lat, longi) {
			var latlng = new google.maps.LatLng(lat, longi);
			var centerPos = new google.maps.LatLng(20.99, -86.865018);
	    	var myOptions = {
	    	  	zoom: 10,
	    	  	center: centerPos,
				mapTypeControlOptions: {
					mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
				},
				panControl: true,
				zoomControl: true,
				scaleControl: true,
	    	  	mapTypeId: google.maps.MapTypeId.ROADMAP,
	    	  	scrollwheel: false
	    	};
	    	var map = new google.maps.Map(document.getElementById(div), myOptions);
			var marker = new google.maps.Marker({
				position: latlng,
				map: map,
				title:"Motel Sensaciones"
			});
			var contentString = '<div id="infoWindowDiv"><img src="img/elems/fachada.jpg"></div>';
			infowindow = new google.maps.InfoWindow({
			    content: contentString
			});
			infowindow.open(map,marker);
		}
		initialize("mainMap", 20.962227, -86.865018);
	};

	return {
		init:onloadExec
	}
};

$(function(){
	var js = new script();
	js.init();
});