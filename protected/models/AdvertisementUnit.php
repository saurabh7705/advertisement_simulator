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
			array('advertisement_type_id, cost, impressions, in_auction, auction_deadline, created_at, updated_at', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, advertisement_type_id, title, description, cost, impressions, in_auction, auction_deadline, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'advertisement_type'=>array(self::BELONGS_TO, 'AdvertisementType', 'advertisement_type_id'),
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

	public function beforSave() {
		if($this->isNewRecord)
			$this->created_at = time();
		$this->updated_at = time();
		return parent::beforSave();
	}

	public static function create($attributes) {
		$model = new AdvertisementUnit;
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
		$criteria->compare('advertisement_type_id',$this->advertisement_type_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('cost',$this->cost);
		$criteria->compare('impressions',$this->impressions);
		$criteria->compare('in_auction',$this->in_auction);
		$criteria->compare('auction_deadline',$this->auction_deadline);
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
	 * @return AdvertisementUnit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
