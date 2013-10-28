<?php
class AdvertisementUnitController extends Controller
{
	public function actionIndex($id='', $advertisement_type_id=1) {
		if($id)
			$new_model = $this->loadModel($id);
		else
			$new_model = new AdvertisementUnit;
		$grid_model = new AdvertisementUnit('search');
        $grid_model->advertisement_type_id = $advertisement_type_id;
		if(isset($_POST["AdvertisementUnit"])) {
			$new_model->attributes = $_POST["AdvertisementUnit"];
			if($_POST['AdvertisementUnit']['auction_deadline'])
				$new_model->auction_deadline = strtotime($_POST['AdvertisementUnit']['auction_deadline']);

			$new_model->file_name = CUploadedFile::getInstance($new_model, 'file_name');
			if($new_model->file_name)
				$new_model->extension = $new_model->file_name->getExtensionName();

			if($new_model->save()){
				if($new_model->file_name) {
					$path = Yii::app()->basePath."/../ad_units/$new_model->id.$new_model->extension";
					$new_model->file_name->saveAs($path);
				}
				$this->redirect(array('/admin/advertisementUnit/index'));
			}
		}
		$this->render('index',array(
			'new_model'=>$new_model,
			'grid_model' => $grid_model,
			'advertisement_type_id'=>$advertisement_type_id
		));
	}
	
	public function actionAdmin() {
		$model=new AdvertisementUnit('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['AdvertisementUnit']))
			$model->attributes=$_GET['AdvertisementUnit'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionStartAuction($id) {
		$model = $this->loadModel($id);
		$model->startAuction();
		Yii::app()->user->setFlash('success', 'Auction started successfully.');
		$this->redirect(array('/admin/advertisementUnit/index'));
	}

	public function actionStartAllAuction() {
		$units = AdvertisementUnit::model()->findAll('in_auction = 1');
		foreach($units as $unit)
			$unit->startAuction();
		Yii::app()->user->setFlash('success', 'All Auctionable ad units have been put onto the auction list.');
		$this->redirect(array('/admin/advertisementUnit/index'));
	}

	public function actionDestroy($id) {
		$model = $this->loadModel($id);
		$model->delete();
		$this->redirect(array('/admin/advertisementUnit/index'));
	}

	public function actionDuplicate($id) {
		$model = $this->loadModel($id);
		$new_model = new AdvertisementUnit;
		$new_model->attributes = $model->attributes;

		if($new_model->save()){
			if($new_model->file_name) {
				$model_path = Yii::app()->basePath."/../ad_units/$model->id.$model->extension";
				$new_model_path = Yii::app()->basePath."/../ad_units/$new_model->id.$new_model->extension";
				copy($model_path, $new_model_path);
			}
			$this->redirect(array('/admin/advertisementUnit/index', 'id'=>$new_model->id));
		}
		else
			throw new CHttpException(404,'The requested page does not exist.');
	}
	
	public function loadModel($id) {
		$model = AdvertisementUnit::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
?>