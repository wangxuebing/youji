<?php

/**
 * This is the model class for table "scenic_spots_resource_link".
 *
 * The followings are the available columns in table 'scenic_spots_resource_link':
 * @property integer $id
 * @property integer $spots_type
 * @property integer $spots_id
 * @property integer $resource_type
 * @property integer $resource_id
 * @property string $md
 * @property integer $ctime
 * @property integer $mtime
 * @property integer $status
 */
class ScenicSpotsResourceLink extends YituActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ScenicSpotsResourceLink the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'scenic_spots_resource_link';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('spots_id, resource_id, md', 'required'),
			array('spots_type, spots_id, resource_type, resource_id, ctime, mtime, status', 'numerical', 'integerOnly'=>true),
			array('md', 'length', 'max'=>40),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, spots_type, spots_id, resource_type, resource_id, md, ctime, mtime, status', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'spots_type' => 'Spots Type',
			'spots_id' => 'Spots',
			'resource_type' => 'Resource Type',
			'resource_id' => 'Resource',
			'md' => 'Md',
			'ctime' => 'Ctime',
			'mtime' => 'Mtime',
			'status' => 'Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('spots_type',$this->spots_type);
		$criteria->compare('spots_id',$this->spots_id);
		$criteria->compare('resource_type',$this->resource_type);
		$criteria->compare('resource_id',$this->resource_id);
		$criteria->compare('md',$this->md,true);
		$criteria->compare('ctime',$this->ctime);
		$criteria->compare('mtime',$this->mtime);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
