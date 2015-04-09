<?php

/**
 * This is the model class for table "cms_task".
 *
 * The followings are the available columns in table 'cms_task':
 * @property integer $id
 * @property string $spots_name
 * @property integer $spots_id
 * @property integer $create_user_id
 * @property integer $last_operation_user_id
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $area_id
 * @property string $stime
 * @property string $etime
 * @property integer $ctime
 * @property integer $mtime
 * @property integer $status
 */
class CmsTask extends CmsActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CmsTask the static model class
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
		return 'cms_task';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('spots_name, stime, etime', 'required'),
			array('spots_id, create_user_id, last_operation_user_id, province_id, city_id, area_id, ctime, mtime, status', 'numerical', 'integerOnly'=>true),
			array('spots_name', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, spots_name, spots_id, create_user_id, last_operation_user_id, province_id, city_id, area_id, stime, etime, ctime, mtime, status', 'safe', 'on'=>'search'),
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
			'spots_name' => 'Spots Name',
			'spots_id' => 'Spots',
			'create_user_id' => 'Create User',
			'last_operation_user_id' => 'Last Operation User',
			'province_id' => 'Province',
			'city_id' => 'City',
			'area_id' => 'Area',
			'stime' => 'Stime',
			'etime' => 'Etime',
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
		$criteria->compare('spots_name',$this->spots_name,true);
		$criteria->compare('spots_id',$this->spots_id);
		$criteria->compare('create_user_id',$this->create_user_id);
		$criteria->compare('last_operation_user_id',$this->last_operation_user_id);
		$criteria->compare('province_id',$this->province_id);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('area_id',$this->area_id);
		$criteria->compare('stime',$this->stime,true);
		$criteria->compare('etime',$this->etime,true);
		$criteria->compare('ctime',$this->ctime);
		$criteria->compare('mtime',$this->mtime);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}