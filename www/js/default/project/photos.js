var timeout;

$(function() {
	$(".photoElement").mouseover(function() {
		var id = $(this).attr('id').split('-')[1];
		
		timeout = setTimeout(function() {
			$(".photo").css('display', 'none');
			$("#photo-"+id).css('display', 'block');
		}, 500);
	});
	
	$(".photoElement").mouseout(function() {
		clearTimeout(timeout);
		$(".photo").css('display', 'none');
	});
});