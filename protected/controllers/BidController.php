<?php
class BidController extends Controller {
	public function actionCreate() {
		if(Yii::app()->user->isTeam) {
			if(isset($_POST['unit_id']) && isset($_POST['bid'])) {
				$unit = AdvertisementUnit::model()->findByPk($_POST['unit_id']);
				if($unit && $unit->auctionStarted() && $unit->auction_deadline >= time()) {
					$logged_team = Yii::app()->user->team;

					$bid_model = Bid::build(array(
						'amount' => str_replace(',', '', $_POST['bid']),
						'advertisement_unit_id' => $unit->id,
						'team_id' => $logged_team->id,
					));
					
					if($bid_model->save())						
						Yii::app()->user->setFlash('success','Bid has been successfully placed.');
					else {
						foreach($bid_model->getErrors() as $error)
							$message[] = $error[0];
						Yii::app()->user->setFlash('error', implode("<br />", $message));
					}
				}
				$this->redirect(array("advertisementUnit/view","id"=>$unit->id));
			}
		}
		else
			$this->redirect(array("site/login"));
	}
}
