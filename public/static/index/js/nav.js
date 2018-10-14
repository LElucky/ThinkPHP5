$(function(){
    $('#navbar').click(function(){
		var clas = $('#nav_status').attr('class');
			console.log(clas)
		if(clas == 'collapse navbar-collapse'){
			$('#nav_status').addClass('in');
		}else{
			$('#nav_status').removeClass('in');
		}
    })
})