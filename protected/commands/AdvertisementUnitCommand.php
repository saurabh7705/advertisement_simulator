<?php
class AdvertisementUnitCommand extends CConsoleCommand {
	public function actionClose($id,$run_timestamp) {
		$unit = AdvertisementUnit::model()->findByPk($id);
		if($unit && $unit->status != AdvertisementUnit::AUCTION_CLOSED && $unit->auction_deadline == $run_timestamp)
			$transfer->closeAuction();
	}

	public function actionCloseManually($id) {                           
		$unit = AdvertisementUnit::model()->findByPk($id);
		$unit->closeAuction();
	}
}