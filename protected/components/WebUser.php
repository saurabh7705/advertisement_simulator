<?php
class WebUser extends CWebUser{
	private $_team;
	
	//is the user an administrator ?
	function getIsAdmin(){
		return ( $this->team && $this->team->id == 1 );
	}
	
	function getIsTeam() {
		return ( $this->team && $this->team->id != 1 );
	}
	
	//get the logged team
	function getTeam(){
		if( $this->isGuest )
			return;
		if( $this->_team === null ){
			$this->_team = Team::model()->findByPk( $this->id );
		}
		return $this->_team;
	}
}
?>