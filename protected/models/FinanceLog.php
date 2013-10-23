<?php

/**
 * This is the model class for table "finance_log".
 *
 * The followings are the available columns in table 'finance_log':
 * @property string $id
 * @property integer $team_id
 * @property integer $advertisement_unit_id
 * @property integer $amount
 * @property integer $created_at
 * @property integer $updated_at
 */
class FinanceLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'finance_log';
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
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, team_id, advertisement_unit_id, amount, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'advertisement_unit'=>array(self::BELONGS_TO, 'AdvertisementUnit', 'advertisement_unit_id'),
			'team'=>array(self::BELONGS_TO, 'Team', 'team_id'),
		);
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

	public function beforSave() {
		if($this->isNewRecord)
			$this->created_at = time();
		$this->updated_at = time();
		return parent::beforSave();
	}

	public static function create($attributes) {
		$model = new FinanceLog;
		$model->attributes = $attributes;
		$model->save();
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
	 * @return FinanceLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
