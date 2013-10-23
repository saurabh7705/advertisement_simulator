window._processingSignup = false;
function checkFacebookAvailable() {
	if(typeof FB === "undefined") {
		alert("Facebook is not loaded yet, please try after complete page load.");
		return false;
	}
	else
		return true;
}
function checkUserStatus(response, invitation_type, token, url) {
	var access_token = response.authResponse.accessToken;
	var uid = response.authResponse.userID;
	$.ajax({
		'type' : 'post',
		'url' : url,
		'data' : {'access_token':access_token, 'invitation_type':invitation_type, 'token':token, 'uid':uid},
		'cache' : false,
		'success' : function(data){
			handleFacebookResponse(data);
		}
	});
}
function processFacebook(invitation_type, token, url) {
	FB.login(function(response) {
		if (response.authResponse) {
            clearTimeout(window.guest_user_register_modal);
			checkUserStatus(response, invitation_type, token, url);
		} else {
              $('.fb_loader_image').hide();
              $('.fb_original_image').show();
		}
	}, {scope: 'user_location, email, publish_stream, read_friendlists, offline_access, user_birthday'});
}

function submitFacebookFriendsLeagueSelectChoice(url) {
	$('.fb_signup_loader').show();
    $('#choose-league-form').addClass('submitted');
	$.ajax({
		'type' : 'post',
		'url' : url,
		'data' : $("#choose-league-form").serialize(),
		'cache' : false,
		'success' : function(data){
			handleFacebookSignUpResponse(data);
		}
  	});
}

function handleFacebookResponse(data) {
	var result = $.parseJSON(data);
	if(result.action_type == 'signup_form')
		$('#fb_register_form').html(result.form_data);
	if(result.action_type == 'error') {
		handleFormErrors(result.error_data, 'field-errors', 'signup_errors');
		$('.fb_signup_loader').hide();
        $('#fb_register_modal_div').find('.modal-footer').show();
        $('#fb-registration-form').removeClass('submitted');
        setProcessingSignup(false);
	}
	else if(result.action_type == 'redirect') {
        setProcessingSignup(false);
		window.location.href = result.redirect_location; //redirect to location
    }
}

function onFocusValidation(form_id) {
	$("#"+form_id+" .focus-out-validation-required").live('focusout', function() {
		var data_to_send = $("#"+form_id).serializeArray();
		data_to_send.push({
		    name: "ajax",
		    value: form_id
		});
		data_to_send = $.param(data_to_send);
		var field = $(this);
		$.ajax({
			'type':'post',
			'data': data_to_send,
			'url':$("#"+form_id).attr('action'),
			'cache':false,
			'success':function(html){
				var returned_responce = $.parseJSON(html);
				var currnet_element_id = field.attr('id');
				var field_error = returned_responce[currnet_element_id];
				if(field_error)
					field.siblings(".field-errors").html('<p class="error">'+field_error+'</p>');
				else
					field.siblings(".field-errors").html('<p class="success"></p>');
			}
		});
	});

	$("#"+form_id+" :input").live('focusin', function() {
		$(this).siblings(".field-errors").html('');
	});
}

function processFacebookSignUp(invitation_type, token, url) {
	FB.login(function(response) {
		if (response.authResponse) {
            $('.signup_facebook_link').addClass('submitted');
			var access_token = response.authResponse.accessToken;
			var data_to_send = $("#registration-form").serializeArray();
			data_to_send.push({name: "access_token", value: access_token});
			data_to_send.push({name: "invitation_type", value: invitation_type});
			data_to_send.push({name: "token", value: token});
			data_to_send = $.param(data_to_send);
			$.ajax({
				'type' : 'post',
				'url' : url,
				'data' : data_to_send,
				'cache' : false,
				'success' : function(data){
					handleFacebookSignUpResponse(data);
				}
			});
		} else {            
			$('.fb_loader_image').hide();
			$('.fb_original_image').show();
            $('.signup_facebook_link').removeClass('submitted');
            setProcessingSignup(false);
		}
	}, {scope: 'user_location, email, publish_stream, read_friendlists, offline_access, user_birthday'});
}

function handleFacebookSignUpResponse(data) {
	var result = $.parseJSON(data);
	if(result.action_type == 'error') {
		handleFormErrors(result.error_data, 'field-errors', 'signup_errors');
		$('.fb_loader_image').hide();
		$('.fb_original_image').show();
        $('.signup_facebook_link').removeClass('submitted');
        setProcessingSignup(false);
	}
	else if(result.action_type == 'signup_form')
		$('#fb_register_form').html(result.form_data);
	else if(result.action_type == 'redirect') {
        setProcessingSignup(false);
		window.location.href = result.redirect_location; //redirect to location
    }
}

function handleManualSignUpResponse(data) {
	var result = $.parseJSON(data);
	if(result.action_type == 'error') {
		handleFormErrors(result.error_data, 'field-errors', 'signup_errors');
		$('.signup_loader').hide();
        $('#registration-form').removeClass('submitted');        
	}
	else if(result.action_type == 'redirect')
		window.location.href = result.redirect_location; //redirect to location
}

function handleFormErrors(errors, field_errors_div_class, remaining_errors_div_id) {
	$('#'+remaining_errors_div_id+', .'+field_errors_div_class).html('');
	$.each(errors, function(index, value) {
		var wrapper_class = index+'_field_wrapper';
		if($("." + wrapper_class).length)
			$("." + wrapper_class+' .'+field_errors_div_class).html('<p class="error">'+value+'</p>');
		else
			$('#'+remaining_errors_div_id).append('<p class="error">'+value+'</p>');
	});
}

function setProcessingSignup(value) {
    window._processingSignup = value;
}