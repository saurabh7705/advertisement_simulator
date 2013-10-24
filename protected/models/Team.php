<?php

/**
 * This is the model class for table "team".
 *
 * The followings are the available columns in table 'team':
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $product_name
 * @property string $product_description
 * @property integer $finance_amount
 * @property integer $created_at
 * @property integer $updated_at
 */
class Team extends CActiveRecord
{
	public $password_repeat;
	const DEFAULT_FINANCE_AMOUNT = 1000000;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'team';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, email, password, product_name', 'required'),
			array('email','email'),
			array('email','unique'),
			array('password_repeat', 'compare', 'compareAttribute'=>'password','message'=>'Passwords do not match.', 'on'=>'create_or_update'),
			array('finance_amount, created_at, updated_at', 'numerical', 'integerOnly'=>true),
			array('name, email, password, product_name', 'length', 'max'=>255),
			array('product_description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, email, password, product_name, product_description, finance_amount, created_at, updated_at', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'unit_logs'=>array(self::HAS_MANY, 'FinanceLog', 'team_id'),
			'unit_log'=>array(self::HAS_ONE, 'FinanceLog', 'team_id', 'condition'=>'advertisement_unit_id = :unit_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'email' => 'Email',
			'password' => 'Password',
			'product_name' => 'Product Name',
			'product_description' => 'Product Description',
			'finance_amount' => 'Finance Amount',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
		);
	}

	public function scopes() {
        return array(
			'non_admin'=>array('condition'=>'id != 1'),
			'admin' => array('condition' => 'id = 1'),
        );
	}

	public function beforeSave() {
		if($this->isNewRecord) {
			$this->created_at = time();
			$this->finance_amount = self::DEFAULT_FINANCE_AMOUNT;
		}
		$this->updated_at = time();
		return parent::beforeSave();
	}

	public static function create($attributes) {
		$model = new Team;
		$model->attributes = $attributes;
		$model->save();
		return $model;
	}

	public function getTotalImpressions() {
		$total = 0;
		foreach($this->unit_logs as $log)
			$total += $log->advertisement_unit->impressions; 
		return $total;
	}

	public function hasBalance($cost) {
		return ($this->leftBalance > $cost);
	}

	public function getLeftBalance() {
		$left_balance = $this->finance_amount;
		$bid_query = "Select sum(bid.amount) as bid_amount from advertisement_unit JOIN bid on bid.id=advertisement_unit.active_bid_id where advertisement_unit.auction_status=1 and bid.team_id=$this->id and advertisement_unit.active_bid_id is NOT NULL";
		$bid_amount = Yii::app()->db->createCommand($bid_query)->queryScalar();
		if($bid_amount)
			$left_balance -= $bid_amount;
		return $left_balance;
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
		$criteria->addCondition('id != 1');

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('product_name',$this->product_name,true);
		$criteria->compare('product_description',$this->product_description,true);
		$criteria->compare('finance_amount',$this->finance_amount);
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
	 * @return Team the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
