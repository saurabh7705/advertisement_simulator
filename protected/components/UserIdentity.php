<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */

	public function getId() {
		return $this->_id;
	}

	public function authenticate()
	{
		$team = Team::model()->find(array('condition'=>"email=:email", 'params'=>array('email'=>$this->username)));
		if($team) {
			if(md5($this->password) == $team->password) {
				$this->_id = $team->id;
				$this->username = $team->email;
				$this->errorCode = self::ERROR_NONE;
			}
			else 
				$this->errorCode=self::ERROR_PASSWORD_INVALID;  
		}
		else
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		return !$this->errorCode;
	}
}