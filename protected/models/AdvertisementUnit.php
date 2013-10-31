<?php

/**
 * This is the model class for table "advertisement_unit".
 *
 * The followings are the available columns in table 'advertisement_unit':
 * @property string $id
 * @property integer $advertisement_type_id
 * @property string $title
 * @property string $description
 * @property integer $cost
 * @property integer $impressions
 * @property integer $in_auction
 * @property integer $auction_deadline
 * @property integer $created_at
 * @property integer $updated_at
 */
class AdvertisementUnit extends CActiveRecord
{
	const AUCTION_INACTIVE = 0;
	const AUCTION_ACTIVE = 1;
	const AUCTION_CLOSED = 2;
	public $advertisement_type_name;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'advertisement_unit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('advertisement_type_id, title', 'required'),
			array('advertisement_type_id, cost, impressions, in_auction, created_at, updated_at, high_frequency', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('description, auction_deadline, auction_status, active_bid_id, index, file_name, extension', 'safe'),
			array('auction_deadline', 'requiredWhenInAuction'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, advertisement_type_id, title, description, cost, impressions, in_auction, auction_deadline, high_frequency, index, created_at, updated_at, advertisement_type_name', 'safe', 'on'=>'search'),
		);
	}

	public function requiredWhenInAuction($attribute,$params) {
		if($this->in_auction == 1 && !$this->auction_deadline)
			$this->addError($attribute, "Auction Deadline cannot be blank.");
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'advertisement_type'=>array(self::BELONGS_TO, 'AdvertisementType', 'advertisement_type_id'),
			'active_bid'=>array(self::BELONGS_TO, 'Bid', 'active_bid_id'),
			'bids_count'=>array(self::STAT, 'Bid', 'advertisement_unit_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'advertisement_type_id' => 'Advertisement Type',
			'title' => 'Title',
			'description' => 'Description',
			'cost' => 'Cost',
			'impressions' => 'Impressions',
			'in_auction' => 'In Auction',
			'auction_deadline' => 'Auction Deadline',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
		);
	}

	public function beforeSave() {
		if($this->isNewRecord)
			$this->created_at = time();
		$this->updated_at = time();
		return parent::beforeSave();
	}
	
	public function afterSave() {
		if($this->isNewRecord)
			$this->startAuction();
		return parent::afterSave();
	}

	public static function create($attributes) {
		$model = new AdvertisementUnit;
		$model->attributes = $attributes;
		$model->save();
		return $model;
	}

	public function startAuction() {
		if($this->in_auction == 1) {
			$this->isNewRecord = false;
			$this->auction_status = self::AUCTION_ACTIVE;
			$this->saveAttributes(array('auction_status'));
			DJJob::enqueue(new AdvertisementUnitJob($this->id, $this->auction_deadline),"default",date("Y-m-d H:i:s",$this->auction_deadline), 1);
		}
	}

	public function auctionStarted() {
		return ($this->auction_status == self::AUCTION_ACTIVE);
	}

	public function closeAuction() {
		if($this->auctionStarted() && $this->active_bid) {
			FinanceLog::create(array(
				'advertisement_unit_id'=>$this->id,
				'team_id'=>$this->active_bid->team_id,
				'amount'=>$this->active_bid->amount,
			));
			$this->auction_status = self::AUCTION_CLOSED;
			$this->save();
		}
	}

	public function getMinAllowedBidAmount() {
		if($this->auctionStarted()) {
			if($this->active_bid)
				return 100 * (ceil((1.10 * $this->active_bid->amount) / 100));
			else {
				if($this->cost < 10000)
					return 10000;
				else
					return $this->cost;
			}
		}
		else
			return NULL; //throw exception here
	}

	public function updateCurrentBidRecords($bid) {
		$this->active_bid_id = $bid->id;
		$this->update(array('active_bid_id'));
	}

	public function checkAndExtendTransfer($bid) {
		if(($this->auction_deadline - time()) <= 180) {
			$this->auction_deadline = strtotime('+3 minutes', $this->auction_deadline);
			$this->update(array('auction_deadline'));	
			DJJob::enqueue(new AdvertisementUnitJob($this->id, $this->auction_deadline),"default",date("Y-m-d H:i:s",$this->auction_deadline), 1);
		}		
	}

	public function showBidCountdown(){
		
		$end_date = new DateTime(date('Y-m-d H:i:s', $this->auction_deadline));
		$current_date = new DateTime(date('Y-m-d H:i:s',(floor(time()/60)*60)));
		$diff = $current_date->diff($end_date);
		$diff = $diff->format('%d/%h/%i');
		
        $date = explode('/',$diff);
        
        if($date[0] > 0){
            if($date[0] == 1)
                $day_str = 'day';
            else
                $day_str = 'days';
            if($date[1] == 1)
                $hour_str = 'hr';
            else
                $hour_str = 'hrs';
            $date_str = "$date[0] $day_str $date[1] $hour_str";
            return $date_str;
        }
        
        if($date[1] > 0){
            if($date[1] == 1)
                $hour_str = 'hr';
            else
                $hour_str = 'hrs';
            if($date[2] == 1)
                $min_str = 'min';
            else
                $min_str = 'mins';
            $date_str = "$date[1] $hour_str $date[2] $min_str";
        }
        else{
			if($date[2] == 0)
                $date_str = "few seconds left";
			else {
				if($date[2] == 1)
	                $min_str = 'min';
	            else if($date[2] > 1)
	                $min_str = 'mins';
	            $date_str = "less than $date[2] $min_str";
			}
        }
        
        return $date_str;
    }

    public function getCostAccordingToFrequency() {
    	if($this->high_frequency == 1)
    		return ($this->cost * 1.5);
    	else
    		return $this->cost;
    }

    public function getImpressionsCount() {
    	$impressions = $this->impressions;
    	if($this->high_frequency == 1)
    		$impressions *= 1.5;

    	if($this->index)
    		return ( $impressions * $this->index);
    	else
    		return $impressions;
    }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->with[] = 'advertisement_type';
		$criteria->compare('advertisement_type_id',$this->advertisement_type_id);
		$criteria->compare('advertisement_type.name',$this->advertisement_type_name, true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('cost',$this->cost);
		$criteria->compare('impressions',$this->impressions);
		$criteria->compare('index',$this->index);
		$criteria->compare('in_auction',$this->in_auction);
		$criteria->compare('auction_deadline',$this->auction_deadline);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('updated_at',$this->updated_at);
		$criteria->compare('high_frequency',$this->high_frequency);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
			    'defaultOrder'=>'t.created_at DESC',
	        ),
			'pagination'=>array('pageSize'=>20),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AdvertisementUnit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
