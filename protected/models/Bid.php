<?php

/**
 * This is the model class for table "bid".
 *
 * The followings are the available columns in table 'bid':
 * @property string $id
 * @property integer $team_id
 * @property integer $advertisement_unit_id
 * @property integer $amount
 * @property integer $created_at
 * @property integer $updated_at
 */
class Bid extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bid';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('team_id, advertisement_unit_id, amount', 'required'),
			array('team_id, advertisement_unit_id, amount, created_at, updated_at', 'numerical', 'integerOnly'=>true),
			array('amount','sufficent_balance', 'skipOnError'=>true),
			array('amount','greater_than_ten_thousand', 'skipOnError'=>true),
			array('amount','two_percent_more_than_previous', 'skipOnError'=>true),			
			array('amount','less_than_ten_times_of_previous', 'skipOnError'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, team_id, advertisement_unit_id, amount, created_at, updated_at', 'safe', 'on'=>'search'),
		);
	}

	public function sufficent_balance($attribute,$params) {
		if($this->isNewRecord) {
			$left_balance = $this->team->leftBalance;
			if($this->amount >= $left_balance)
				$this->addError($attribute, "You do not have sufficent balance.");
		}
	}

	public function greater_than_ten_thousand($attribute, $params) {
		if($this->amount < 10000)
			$this->addError($attribute, "Minimum bid amount is Rs. 10,000");
	}

	public function two_percent_more_than_previous($attribute, $params) {
		if($this->isNewRecord) {
			if($this->amount < $this->advertisement_unit->minAllowedBidAmount)
				$this->addError($attribute, "Your bid must be atleast 10% more than the previous bid.");
		}		
	}

	public function less_than_ten_times_of_previous($attribute, $params) {
		if($this->isNewRecord) {
			if($this->advertisement_unit->active_bid_id)
				$prev_amount = $this->advertisement_unit->active_bid->amount;
			else
				$prev_amount = $this->advertisement_unit->cost;

			if($prev_amount >= 10000) {
				if($this->amount >= (10*$prev_amount))
					$this->addError($attribute,  "Your bid must be less than 10 times the previous bid, don't get too generous.");
			}
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'advertisement_unit'=>array(self::BELONGS_TO, 'AdvertisementUnit', 'advertisement_unit_id'),
			'team'=>array(self::BELONGS_TO, 'Team', 'team_id'),
			'active_on_advertisement_unit'=>array(self::HAS_ONE, 'AdvertisementUnit', 'active_bid_id'),
		);
	}
	
	public function scopes() {
		return array(
			'active' => array(
				'with'=>'active_on_advertisement_unit',
				'condition'=>"active_on_advertisement_unit.id IS NOT NULL"
			),
			'order_recent' => array(
				'order'=>"$this->tableAlias.created_at DESC"
			),
		);
	}
	
	public function filter_team($team_id) {
		$this->getDbCriteria()->mergeWith(array('condition'=>"$this->tableAlias.team_id = $team_id"));
		return $this;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'team_id' => 'Team',
			'advertisement_unit_id' => 'Advertisement Unit',
			'amount' => 'Amount',
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
		if($this->isNewRecord) {
			$this->advertisement_unit->updateCurrentBidRecords($this);
			$this->advertisement_unit->checkAndExtendTransfer($this);
			$this->notifyPreviousBidTeam();
		}
		return parent::afterSave();
	}
	
	public function notifyPreviousBidTeam() {
		$previous_bid = Bid::model()->find(array(
			'condition'=>"advertisement_unit_id = :advertisement_unit_id and id < :this_id", 'order'=>'id DESC',
			'params'=>array('advertisement_unit_id'=>$this->advertisement_unit_id, 'this_id'=>$this->id)
		));
		if($previous_bid)
			Notification::create(array('team_id'=>$previous_bid->team_id, 'action_item_type'=>'Bid', 'action_id'=>$previous_bid->id));
	}

	public static function build($attributes) {
		$model = new Bid;
		$model->attributes = $attributes;
		return $model;
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
		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('advertisement_unit_id',$this->advertisement_unit_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('updated_at',$this->updated_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Bid the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
