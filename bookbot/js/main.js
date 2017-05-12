/* 
	Author: PM @ Green Labs Mx
	Website: http://greenlabs.com.mx
*/
var script = function(){
		
	var bindEvents = function(){
		$('#print').on('click', function(event) {
			event.preventDefault();
			window.print();
		});
	}
	
	var onloadExec = function(){
		bindEvents();
		
	}

	return {
		init:onloadExec
	}
};

$(function(){
	var js = new script();
	js.init();
});