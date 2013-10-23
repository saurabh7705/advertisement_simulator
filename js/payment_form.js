$('.subscription-button').click(function() {
	$('#buy_subscription_form').attr('action', $(this).attr('data-url'));
	if($(this).hasClass('international-button'))
		$('#buy_subscription_form').addClass('international');
})

$('#buy_subscription_form').submit(function() {
	if(!$(this).hasClass('international')) {
		var phone_regex = /[0-9]{10}/;
		var zip_code_regex = /^[0-9]{6}$/;
		var valid = true;

		$(".payment_details_field_error").remove();

		if(!phone_regex.test($("#payment_phone_number").val())) {
			$("<p class='payment_details_field_error'>Please enter valid 10 digit phone number.</p>").insertAfter("#payment_phone_number");
			valid = false;
		}

		if(!$("#payment_email").val()) {
			$("<p class='payment_details_field_error'>Please enter valid email address.</p>").insertAfter("#payment_email");
			valid = false;
		}
		else if(($("#payment_email").val().indexOf("+") != -1) || $("#payment_email").val().length > 32) {
			$("<p class='payment_details_field_error'>Currently, due to a technical issue, we cannot accept emails which are longer than 32 characters or have '+' character inside them. Please use another email. Sorry for the inconvenience.</p>").insertAfter("#payment_email");
			valid = false;
			$.post($("#buy_subscription_form").attr("data_mobikwik_error_url"));
		}
		
		if(!$("#payment_address").val()) {
			$("<p class='payment_details_field_error'>Please enter your address.</p>").insertAfter("#payment_address");
			valid = false;
		}
		
		if(!$("#payment_state").val()) {
			$("<p class='payment_details_field_error'>Please enter your state.</p>").insertAfter("#payment_state");
			valid = false;
		}
		
		if(!$("#payment_name").val()) {
			$("<p class='payment_details_field_error'>Please enter your name.</p>").insertAfter("#payment_name");
			valid = false;
		}
		
		if(!$("#payment_city").val()) {
			$("<p class='payment_details_field_error'>Please enter your city.</p>").insertAfter("#payment_city");
			valid = false;
		}
		
		if(!zip_code_regex.test($("#payment_zip_code").val())) {
			$("<p class='payment_details_field_error'>Please enter valid 6 digit zip code.</p>").insertAfter("#payment_zip_code");
			valid = false;
		}
		return valid;
	}
});