<?php
class AdvertisementUnitJob {
    
    public $unit_id;
    public $run_timestamp;
    
    public function __construct($unit_id,$run_timestamp) {
        $this->unit_id = $unit_id;
        $this->run_timestamp = $run_timestamp;
    }
    
    public function perform() {
        $job = new AdvertisementUnitCommand(1,2);
        $result = $job->actionClose($this->unit_id, $this->run_timestamp);
    }
}
?>