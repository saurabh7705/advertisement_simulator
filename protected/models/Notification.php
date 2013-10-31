<?php

/**
 * This is the model class for table "notification".
 *
 * The followings are the available columns in table 'notification':
 * @property string $id
 * @property integer $team_id
 * @property string $action_item_type
 * @property integer $action_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Notification extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'notification';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('team_id, action_item_type, action_id', 'required'),
			array('team_id, action_id, created_at, updated_at', 'numerical', 'integerOnly'=>true),
			array('action_item_type', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, team_id, action_item_type, action_id, created_at, updated_at', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'team'=>array(self::BELONGS_TO, 'Team', 'team_id'),
		);
	}

	public function beforeSave() {
		if($this->isNewRecord) {
			$this->created_at = time();
		}
		$this->updated_at = time();
		return parent::beforeSave();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'team_id' => 'Team',
			'action_item_type' => 'Action Item Type',
			'action_id' => 'Action',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
		);
	}
	
	public function getActionItem() {
		$action_item_type = $this->action_item_type;
		return $action_item_type::model()->findByPk($this->action_id);
	}
	
	public function getContent() {
		$action_item = $this->actionItem;
		switch($this->action_item_type) {			
			case 'Bid':
				$content = "Your bid on ".CHtml::link($action_item->advertisement_unit->title, array('/advertisementUnit/view', 'id'=>$action_item->advertisement_unit_id), array('target'=>'_blank'))." has been surpassed.";
			break;			
		}
		return $content;
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
		$criteria->compare('action_item_type',$this->action_item_type,true);
		$criteria->compare('action_id',$this->action_id);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('updated_at',$this->updated_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public static function create($attributes) {
		$notification = new Notification;
		$notification->attributes = $attributes;
		$notification->save();
		return $notification;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Notification the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
