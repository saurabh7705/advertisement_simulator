var levels = {"0":{"name":"Non-existant","value":0},"1":{"name":"Horrible","value":1000},"2":{"name":"Hopeless","value":2000},"3":{"name":"Useless","value":3000},"4":{"name":"Mediocre","value":4000},"5":{"name":"Average","value":5000},"6":{"name":"Reliable","value":6000},"7":{"name":"Accomplished","value":7000},"8":{"name":"Remarkable","value":8000},"9":{"name":"Brilliant","value":9000},"10":{"name":"Exemplary","value":10000},"11":{"name":"Prodigious","value":11000},"12":{"name":"Fantastic","value":12000},"13":{"name":"Magnificent","value":13000},"14":{"name":"Masterful","value":14000},"15":{"name":"Supreme","value":15000},"16":{"name":"Magical","value":16000},"17":{"name":"Legendary","value":17000},"18":{"name":"Wonderous","value":18000},"19":{"name":"Demigod","value":19000},"20":{"name":"Titan","value":20000},"21":{"name":"Titan","value":21000},"22":{"name":"Titan","value":22000},"23":{"name":"Titan","value":23000},"24":{"name":"Titan","value":24000},"25":{"name":"Titan","value":25000}}
var secondary_levels = {"1":{"name":"Hopeless","value":1000},"2":{"name":"Poor","value":2000},"3":{"name":"Unreliable","value":3000},"4":{"name":"Decent","value":4000},"5":{"name":"Good","value":5000},"6":{"name":"Superb","value":6000}};
$(document).ready(function(){
	$('.show_tooltip').tooltip();
        $('.alert').alert();
        assignPopover();
        assignSecondaryPopover();
});

function skillLevelText(level_no) {
	var level_name = levels[String(level_no)]['name'];
	//return "<span class='skill_"+level_no+" current_skill'>"+ level_name +'</span>';
	return level_name;
}

function secondarySkillLevelText(level_no) {
	var level_name = secondary_levels[String(level_no+1)]['name'];
	return level_name;
}

function popoverContent(level){
        level = parseInt(level);
        var content = '<ol class="popup_skills" start="0">';
        //var player_level = parseInt(level/1000);
        var player_level = parseInt(level);
        for(var i=0;i<=20000;i=i+1000){
            var level_no = parseInt(i/1000);
            var level_name = levels[String(level_no)]['name'];
            if(player_level == level_no)
                content += "<li class='skill_"+level_no+" current_skill'>"+ level_name +'</li>';
            else
                content += "<li class='skill_"+level_no+"'>" + level_name + '</li>';
        }
        content += '</ol>';
        return content;
}

function assignPopover(){
    var skills = $('.show_popover');
    for(var i=0 ; i < skills.length ; i++){
        $(skills[i]).popover({
                live: true,
                placement: 'right',
                trigger:'hover',
                title: 'Skill Levels',
                html: true,
                content:popoverContent($(skills[i]).attr('data'))
        });
    }
}

function loadTutorial(loader, url) {
	$("#tutorial").html(loader);
	$.get(url, function(data) {
		$("#tutorial").html(data);
	});
}

function assignSecondaryPopover(){
    var skills = $('.show_secondary_popover');
    for(var i=0 ; i < skills.length ; i++){
        $(skills[i]).popover({
                live: true,
                placement: 'right',
                trigger:'hover',
                title: 'Skill Levels',
                html: true,
                content:popoverSecondaryContent($(skills[i]).attr('data'))
        });
    }
}

function popoverSecondaryContent(level){
        level = parseInt(level);
        var content = '<ol class="popup_skills">';
        //var player_level = parseInt(level/1000);
        var player_level = level;
        for(var i=0;i<=6000;i=i+1000){
            var level_no = parseInt(i/1000);
			if(level_no==0) {
				level_no = 1;
				i+=1000;
			}
			if(player_level==0)
				player_level=1;
			var level_name = secondary_levels[String(level_no)]['name'];
            if(player_level == level_no){

                content += "<li class='secondary_skill_"+level_no+" current_skill'>"+ level_name +'</li>';
            }
            else
                content += "<li class='secondary_skill_"+level_no+"'>" + level_name + '</li>';
        }
        content += '</ol>';
        return content;
}

function updatePageTitleNotificationCount(notification_count) {
	title_without_count = $("title").attr("data-title");
	if(title_without_count == undefined || !title_without_count) {
		title_parts = $(document).attr('title').split(') ');
		title_without_count = title_parts[title_parts.length - 1];
	}
		
	if(notification_count == 0)
		$(document).attr('title', title_without_count);
	else
		$(document).attr('title', '('+notification_count+') '+title_without_count);
}

function updateUserPointsAndNotificationCounter(url) {
  $.ajax({
            'type':'post',
            'url':url,
            'cache':false,
            'success':function(data){
                        data = $.parseJSON(data);
                        if(data['success']){
                            updatePageTitleNotificationCount(data['notification_counter']);
                            if(data['notification_counter'] > 0)							  
                              $('#notification_counter').html(data['notification_counter']);
                            if(data['notification_counter'] == 0)
                              $('#notification_counter').html('');
							  //commentr below 2 lines for points
                           if( parseInt($('#user_points_value').html()) != data['points'])
                              $('#user_points_value').html(data['points']);
                            setTimeout("updateUserPointsAndNotificationCounter('"+url+"')",30000);
                        }
                    }
        });
}

// This adds 'placeholder' to the items listed in the jQuery .support object. 
jQuery(function() {
   jQuery.support.placeholder = false;
   test = document.createElement('input');
   if('placeholder' in test) jQuery.support.placeholder = true;
});


// This adds placeholder support to browsers that wouldn't otherwise support it. 
$(function() {
   if(!$.support.placeholder) { 
      var active = document.activeElement;
      $(':text').focus(function () {
         if ($(this).attr('placeholder') != '' && $(this).attr('placeholder') != undefined && $(this).val() == $(this).attr('placeholder')) {
            $(this).val('').removeClass('hasPlaceholder');
         }
      }).blur(function () {
         if ($(this).attr('placeholder') != '' && $(this).attr('placeholder') != undefined && ($(this).val() == '' || $(this).val() == $(this).attr('placeholder'))) {
            $(this).val($(this).attr('placeholder')).addClass('hasPlaceholder');
         }
      });
      $(':text').blur();
      $(active).focus();
      $('form:eq(0)').submit(function () {
         $(':text.hasPlaceholder').val('');
      });
   }

	//form stop multi submit
	$(".no_multi_submit").submit(function() {
		if(!$(this).hasClass('submitted')) {
			$(this).addClass('submitted');
			return true;
		}
		else
			return false;
	});
	
});

function checkoutForPayments(url, order_id) {
    $.post(url, {'order_id': order_id}, function(data) {
      data = $.parseJSON(data);
      if(data.error) {
        window.location.href = data.redirect_url;
      }
      else {
        var form = $("<form style='display:none;' method='post' action='"+data.action_url+"'></form>");
        $.each(data.attributes, function(name, value){
          form.append('<input type="hidden" name="'+name+'" value="'+value+'">');
        });
        $('body').append(form);
        form.submit();
      }
    })  
}

function animateProgressBar(applied_width, $element) {
    $element.animate({ width: applied_width }, 700);
}

function commaSeparateNumber(val){
	while (/(\d+)(\d{3})/.test(val.toString())){
		val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
	}
	return val;
}