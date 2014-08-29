var mobile_menu = (function(){
	var init = function() {
		$(document).on('click', '.menu-icon', function(){
			$('.mobile-header').toggleClass('open');
		});
	}

	init();
})();