$('.press_release_like_button').live('click', function(e){
	var button = $(this);
	var counter = parseInt(button.attr('data-counter'));
	$.get(button.attr('data-href'), function(data) {
		if(data == 'success') {
			button.parent().find('.vote_count').remove();
			var msg = getLikeMessage(counter+1);
			button.parent().find('.self_like_mssg').html(msg).show();
			button.remove();
		}
	});
	return false;
});

$('.show_liker_users').live('click', function() {
	$("#"+$(this).attr('data-target-id')).show();
	return false;
})

$(".show_press_release_details, #show_all_press_releases").live('click', function() {
	button = $(this);
	$.get(button.attr('data-href'), function(data) {
		$("#press_release_box").html(data);
	});
	$("#show_all_press_releases").toggle();
	return false;
});

function getLikeMessage(counter) {
	if(counter > 1) 
		var msg = "You and "+(counter-1)+" other(s) like this post.";
	else
		var msg = "You like this post.";
	return msg;
}

$(".press_release_comment_form").live('submit', function() {
	var form = $(this);
	$.post(form.attr('action'), form.serialize(), function(data) {
		data = $.parseJSON(data);
		if(data.result == 'success') {
			$("#"+form.attr('data-comments-section-id')).html(data.display_html);
		}
		else if(data.result == 'error') {
			//showErrors(data.error_data, form.find('.errors'))
		}
	});
	return false;
});

$(".comment_delete_button").live('click', function() {
	button = $(this);
	if(confirm(button.attr('data-confirm-message'))) {
		$.getJSON(button.attr('data-href'), function(data) {
			if(data.result == 'success') {
				$("#"+button.attr('data-comment-section-id')).html(data.display_html);
			}
		});
	}
	return false;
});

$(".show_old").live('click', function() {
	button = $(this);
	$.getJSON(button.attr('data-href'), function(data) {
		if(data.result == 'success') {
			$("#"+button.attr('data-comment-section-id')).html(data.display_html);
		}
	});
	return false;
});