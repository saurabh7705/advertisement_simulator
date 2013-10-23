function assignForumTitlePopovers() {
	$('.forum_title_popover').popover({placement:'top', trigger:'hover'});
}

$(function() {
	assignForumTitlePopovers();
	$(".forum_title_popover").click(function() { return false; });
	$(".forum_ignore").click(function() { 
		if(confirm("You want to remove this post from you feeds?")) {
			var button = $(this);
			$.get(button.attr('data-url'), {'id': button.attr('data-id')}, function() {
				button.parents('.short_post').hide();
			});
		}			
		return false;
	});
	
	$(".short_post").mouseenter(function(){ 
		$(this).find('.forum_ignore_multiple_checkbox').show(); 
	}).mouseleave(function(){ 
		if(!$(this).find('.forum_ignore_multiple_checkbox').is(":checked")) 
			$(this).find('.forum_ignore_multiple_checkbox').hide(); 
	});
	
	$("#forum_ignore_multiple").click(function() {
		var selected_posts_to_ignore = $(".forum_ignore_multiple_checkbox:checked");
		if(selected_posts_to_ignore.length == 0)
			alert("Please select at-least some posts to ignore.");
		else {
			if(confirm("Ignore "+selected_posts_to_ignore.length+" posts?")) {
				var button = $(this);
				$.post(
					button.attr('data-url'), 
					{'ignore_ids': selected_posts_to_ignore.map(function() { return($(this).attr('data-id')) }).get() }, 
					function() { 
						selected_posts_to_ignore.each(function() { 
							$(this).parents('.short_post').hide();
						})
					}
				);
			}
		}		
	})
});