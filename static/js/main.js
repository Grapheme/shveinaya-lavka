var media = ['desktop', 'mobile'];
	media['desktop'] = [1460, 1040, 860];
	media['mobile'] = [680, 325];

var is_mobile = function() {
	return ($(window).width() <= media['mobile'][0]);
};

var mobile_menu = (function(){
	var open = false;
	var out = false;
	var header = $('.main-header');

	var init = function() {
		$(document).on('click', '.menu-icon', function(){
			if(open) {
				header.removeClass('open');
				out = setTimeout(function(){
					header.css({ 'overflow': 'hidden' });
				}, 500);
				open = false;
			} else {
				clearTimeout(out);
				header.addClass('open');
				header.css({ 'overflow': 'visible' });
				open = true;
			}
		});
	};

	init();
})();

var scroll_menu = (function(){
	var header, orig_nav;
		header = $('.main-header');

	setVars();
	setNav();

	$(window).on('scroll', function(){
		if(!is_mobile()) {
			setNav();
		}
	});

	$(window).on('resize', function(){
		if(!is_mobile()) {
			navReset();
			setVars();
			setNav();
		} else {
			navReset();
		}
	});

	function setVars() {
		orig_nav = header.find('.head-nav').offset().top;
	}

	function setNav() {
		if($(window).scrollTop() >= orig_nav) {
			header.css({
				'position': 'fixed',
				'top': -orig_nav
			});
			header.addClass('min-header');
		} else {
			header.css({
				'position': 'absolute',
				'top': 0
			});
			header.removeClass('min-header');
		}
	}

	function navReset() {
		if(is_mobile()) {
			header.css({
				'position': 'fixed',
				'top': 0
			});
		} else {
			header.css({
				'position': 'absolute',
				'top': 0
			});
		}
		header.removeClass('min-header');
	}
})();