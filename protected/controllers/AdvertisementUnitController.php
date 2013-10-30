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
		if(!Yii::app()->user->isGuest)
			$this->_team = Yii::app()->user->team;
		else
			throw new CHttpException(404,'The requested page does not exist.');
		$filterChain->run();
	}

	public function actionIndex($advertisement_type_id=1) {
		$model = new AdvertisementUnit('search');
		$model->advertisement_type_id = $advertisement_type_id;
		$this->render('index', array('model'=>$model, 'team'=>$this->_team,'advertisement_type_id'=>$advertisement_type_id));
	}

	public function actionFilter($advertisement_type_id) {
		$model = new AdvertisementUnit("search");
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['AdvertisementUnit']))
			$model->attributes=$_GET['AdvertisementUnit'];
		$model->advertisement_type_id = $advertisement_type_id;

		$this->render('index',array(
			'model'=>$model, 'team'=>$this->_team, 'advertisement_type_id'=>$advertisement_type_id
		));
	}
	
	public function actionView($id) {
		$model = $this->loadModel($id);
		$this->render('view', array('model'=>$model, 'team'=>$this->_team));
	}

	public function actionBuy($id) {
		$model = $this->loadModel($id);
		if( $this->_team->hasBalance($model->costAccordingToFrequency) && !$this->_team->unit_log(array('params'=>array('unit_id'=>$model->id))) && !$model->in_auction){
			FinanceLog::create(array(
				'advertisement_unit_id'=>$model->id,
				'team_id'=>$this->_team->id,
				'amount'=>$model->costAccordingToFrequency,
			));
			Yii::app()->user->setFlash('success', 'Bought Successfully');
		}		
		$this->redirect(array('/team/view'));
	}

	public function actionRefund($id) {
		$model = $this->loadModel($id);
		$log = $this->_team->unit_log(array('params'=>array('unit_id'=>$model->id)));
		if( $log && !$model->in_auction){
			$this->_team->finance_amount += $log->amount;
			$this->_team->save();
			$log->delete();
			Yii::app()->user->setFlash('success', 'Refunded Successfully');
		}		
		$this->redirect(array('/team/view'));
	}

	public function actionComputeDefaultBid(){
		if(isset($_POST['id'])) {
			$model = $this->loadModel($_POST['id']);
			if($model->auctionStarted() && $model->auction_deadline >= time()) {
				$amount = $model->minAllowedBidAmount;
				$this->renderPartial('_bid',array('unit'=>$model, 'default_bid'=>$amount));
				return;
			}
			else
				echo 'transferred';
		}
		else
			echo 'error';
	}
	
	public function loadModel($id) {
		$model = AdvertisementUnit::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
?>
