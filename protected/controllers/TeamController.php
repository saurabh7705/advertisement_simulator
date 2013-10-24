<?php
class TeamController extends Controller
{
	private $_team;
	public function filters() {
		return array_merge(parent::filters(), array(
			'setTeam',
		));
	}

	public function filterSetTeam($filterChain) {
		if(Yii::app()->user->isAdmin && isset($_GET['id']))
			$this->_team = $this->loadModel($_GET['id']);
		elseif(Yii::app()->user->isTeam)
			$this->_team = Yii::app()->user->team;
		else
			throw new CHttpException(404,'The requested page does not exist.');
		$filterChain->run();
	}
	
	public function actionView($id='') {
		$this->render('view', array('team'=>$this->_team));
	}
	
	public function loadModel($id) {
		$model = Team::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
?>