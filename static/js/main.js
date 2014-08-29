var mobile_menu = (function(){
	var init = function() {
		$(document).on('click touchend', '.menu-icon', function(){
			$('.mobile-header').toggleClass('open');
		});
	}

	init();
})();