HitwicketLogin = {
  credentials: {},
  signup: function() {

  },
  checkIfExistingUserOrSignup: function(FbCallback) {
	
	
  
  }, 
  login: function() {
	
	
  }, 
  setFacebookCredentials: function(response) {
	
  },
  fbButtonClick: function() {

  }, 
  fbFormSubmit: function() {



  }, 
  fbClickHandler: function(FbCallback) {
	FB.login(function(response) {
		if(response.authResponse) {
			this.setFacebookCredentials(response);
			checkIfExistingUserOrSignup(FbCallback)
		}
	})
  }	
}

HitwicketLogin.fbClickHandler(HitwicketLogin.fbFormSubmit);
HitwicketLogin.fbClickHandler(HitwicketLogin.fbLoginButtonClick);