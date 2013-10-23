function showCountdown(seconds_left, container_id) {
	var time_to_display = getDisplayTimeLeftFromSeconds(seconds_left);
	$("#"+container_id+" #time_left").html(time_to_display);
    if(seconds_left == 0) {
		$("#"+container_id).addClass('countdown_completed');
		$("#"+container_id+" #message").html('Your practice match has started, see the match!');
	}
	else {
		seconds_left = seconds_left-1;
		setTimeout(function(){showCountdown(seconds_left, container_id);},1000);
	}
    return false;
}

function getDisplayTimeLeftFromSeconds(seconds_left){
	if(seconds_left == 0)
		return null;
	else if (seconds_left < 60)
		return "00:"+seconds_left;
	else{
		if(seconds_left >= 3600){
			hours = parseInt(seconds_left / 3600);
			seconds_left = seconds_left % 3600;
			
			minutes = parseInt(seconds_left / 60);
			if(minutes < 10)
				minutes = "0"+ minutes;
				
			seconds_left = seconds_left % 60;
			if(seconds_left < 10)
				seconds_left = "0"+ seconds_left;
			return hours + ":" + minutes + ":" + seconds_left;
		}
		else{
			if(seconds_left % 60 == 0)
				return seconds_left/60 + ":00";
			else if(seconds_left % 60 < 10)
				return parseInt(seconds_left / 60) + ":0" + seconds_left % 60;
			else
				return parseInt(seconds_left / 60) + ":" + seconds_left % 60;
		}
	}
}
