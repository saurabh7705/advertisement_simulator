<?php
class AdminModule extends CWebModule
{
	public $defaultController = 'team';
	public function init() {
		$this->layoutPath = Yii::getPathOfAlias('application.views.layouts');
		$this->layout='main';
	}

	public function beforeControllerAction($controller, $action) {                                                                                                             
		if(parent::beforeControllerAction($controller, $action)) {
			if(!Yii::app()->user->isAdmin)
				throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
			return true;
		}
		else
			return false;
	}
}
