<?php
class TeamController extends Controller
{
	public function actionIndex($id='') {
		if($id)
			$new_model = $this->loadModel($id);
		else {
			$new_model = new Team;
			$new_model->scenario = 'create_or_update';
		}
		$grid_model = new Team('search');
		if(isset($_POST["Team"])) {
			$new_model->attributes = $_POST["Team"];
			if($new_model->isNewRecord) {
				$new_model->password = md5($new_model->password);
				$new_model->password_repeat = md5($new_model->password_repeat);
			}
			if($new_model->save())
				$this->redirect(array('/admin/team/index'));
		}
		$this->render('index',array(
			'new_model'=>$new_model,
			'grid_model' => $grid_model
		));
	}
	
	public function actionAdmin() {
		$model=new Team('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Team']))
			$model->attributes=$_GET['Team'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionDestroy($id) {
		$model = $this->loadModel($id);
		$model->delete();
		$this->redirect(array('/admin/team/index'));
	}
	
	public function loadModel($id) {
		$model = Team::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
?>