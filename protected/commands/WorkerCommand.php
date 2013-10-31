<?php 
class WorkerCommand extends CConsoleCommand {
	public function actionStart($queue = 'default') {
		date_default_timezone_set('Asia/Kolkata');
		$pdo = Yii::app()->db->getPdoInstance();
		$worker = new DJWorker(array('max_attempts'=>1 , 'queue'=>$queue));
		$worker->setConnection($pdo);
		$worker->setLogLevel(DJWorker::INFO);
		//$worker->setLogType(DJWorker::FILE);
		//$worker->setLogFile("/var/log/djjob.log");
		$worker->start();
	}
} ?>