<?php
class AdvertisementUnitController extends Controller
{
	private $_team;
	public function filters() {
		return array_merge(parent::filters(), array(
			'setTeam',
		));
	}

	public function filterSetTeam($filterChain) {
		if(Yii::app()->user->isTeam)
			$this->_team = Yii::app()->user->team;
		else
			throw new CHttpException(404,'The requested page does not exist.');
		$filterChain->run();
	}

	public function actionIndex() {
		$model = new AdvertisementUnit('search');
		$this->render('index', array('model'=>$model, 'team'=>$this->_team));
	}

	public function actionFilter() {
		$model = new AdvertisementUnit("search");
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['AdvertisementUnit']))
			$model->attributes=$_GET['AdvertisementUnit'];

		$this->render('index',array(
			'model'=>$model, 'team'=>$this->_team
		));
	}
	
	public function actionView($id) {
		$model = $this->loadModel($id);
		$this->render('view', array('model'=>$model, 'team'=>$this->_team));
	}

	public function actionBuy($id) {
		$model = $this->loadModel($id);
		if( $this->_team->hasBalance($model->cost) && !$this->_team->unit_log(array('params'=>array('unit_id'=>$model->id))) && !$model->in_auction){
			FinanceLog::create(array(
				'advertisement_unit_id'=>$model->id,
				'team_id'=>$this->_team->id,
				'amount'=>$model->cost,
			));
			Yii::app()->user->setFlash('success', 'Bought Successfully');
		}		
		$this->redirect(array('/team/view'));
	}
	
	public function loadModel($id) {
		$model = AdvertisementUnit::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
?>