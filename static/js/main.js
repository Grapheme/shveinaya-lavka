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

	
	if(!is_mobile()) {
		setVars();
		setNav();
	}

	$(window).on('scroll', function(){
		if(!is_mobile()) {
			setNav();
		}
	});

	$(window).on('resize', function(){
		navReset();
		if(!is_mobile()) {
			setVars();
			setNav();
		}
	});

	function setVars() {
		orig_nav = header.find('.head-nav').offset().top;
	}

	function setMin() {
		header.css({
			'position': 'fixed',
			'top': -orig_nav
		});
		header.addClass('min-header');
	}

	function setBig() {
		header.css({
			'position': 'absolute',
			'top': 0
		});
		header.removeClass('min-header');
	}

	function setNav() {
		if($(window).scrollTop() >= orig_nav) {
			setMin();
		} else {
			setBig();
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

	return {setMin: setMin, setBig: setBig};

})();

$.fn.main_slider = function(slide) {
	var direction = false;
	var down_timeout = false;
	var up_timeout = false;
	var active__frame = slide.eq(0);
	var allow = true;
	var length = slide.length - 1;

	function init() {
		$('html').css('overflow', 'hidden');
		$('.main-header').addClass('animate');
		active__frame.addClass('active');
	}

	function setActive() {
		active__frame.addClass('active').siblings().removeClass('active');
	}

	function setTransform(limit, id) {
		if(active__frame.index() == limit) return;

		var perc = -1 * id * 100;
		$('.index-page').attr('style', '-webkit-transform: translateY(' + perc + '%); transform: translateY(' + perc + '%);');
		active__frame = slide.eq(id);
		setActive();
		if(active__frame.index() == 0) {
			scroll_menu.setBig();
		} else {
			scroll_menu.setMin();
		}
	}

	function down() {
		setTransform(length, active__frame.index() + 1);
	}

	function up() {
		setTransform(0, active__frame.index() - 1);
	}

	function goto(clear_time, act_time, func, condition) {
		allow = false;
		clearTimeout(clear_time);
		act_time = setTimeout(function(){
			if(condition) {
				func();
				setTimeout(function(){
					allow = true;
				}, 1450);
			}
		}, 50);
	}

	$(this).on('mousewheel DOMMouseScroll', function(event){
		if(!allow) return;
		if (event.type == 'mousewheel') {
			delta = event.originalEvent.wheelDelta;
		} else

		if (event.type == 'DOMMouseScroll') {
			delta = -1 * event.originalEvent.detail;
		}

		if(delta < 0) {
			goto(up_timeout, down_timeout, down, (delta < 0));
		} else {
			goto(down_timeout, up_timeout, up, (delta > 0));
		}
		return false;
	});

	$(this).on('click', '.slide-link', function(){
		if($(this).parent().index() != length) {
			down();
		} else {
			setTransform(0, 0);
		}
	});

	init();
};