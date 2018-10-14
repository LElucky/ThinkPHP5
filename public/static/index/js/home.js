function light() {
	var a = $('.light_switch span').html();
	var w = $(window).width();
	var h = $(window).height();
	if (a == '开灯') {
		$('body').removeAttr("style");
		$('.player').removeAttr("style");
		$('.light_switch span').html('关灯');
		$('.light_switch span').css('color', '#111');
		$('.light_switch').css('z-index', '999');
		$('.playshow_mask').hide()
	} else {
		$('html,body').animate({
			scrollTop: 200
		}, 1000);
		$('body').css("height", h);
		$('body').css("overflow", "hidden");
		$('.light_switch span').html('开灯');
		$('.light_switch span').css('color', '#ddd');
		$('.light_switch').css('z-index', '10002');
		$('.light_switch').css('right', '2');
		$('.light_switch').css('top', '5');
		$('.playshow_mask').show();
		$('.player').css("position", "relative");
		$('.player').css("max-width", w);
		$('.player').css("height", h);
		$('.player').css("top", "150px");
		$('.player').css("left", "0");
		$('.player').css("z-index", "8000")
	}
}