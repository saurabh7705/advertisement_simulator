<?php
class AdvertisementTypeController extends Controller
{
	public function actionIndex($id='') {
		if($id)
			$new_model = $this->loadModel($id);
		else
			$new_model = new AdvertisementType;
		$grid_model = new AdvertisementType('search');
		if(isset($_POST["AdvertisementType"])) {
			$new_model->attributes = $_POST["AdvertisementType"];
			if($new_model->save())
				$this->redirect(array('/admin/advertisementType/index'));
		}
		$this->render('index',array(
			'new_model'=>$new_model,
			'grid_model' => $grid_model
		));
	}
	
	public function actionAdmin() {
		$model=new AdvertisementType('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['AdvertisementType']))
			$model->attributes=$_GET['AdvertisementType'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionDestroy($id) {
		$model = $this->loadModel($id);
		$model->delete();
		$this->redirect(array('/admin/advertisementType/index'));
	}
	
	public function loadModel($id) {
		$model = AdvertisementType::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
?>