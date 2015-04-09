<?php

/**
 * This is the model class for table "system_language".
 *
 * The followings are the available columns in table 'system_language':
 * @property integer $id
 * @property string $name
 * @property string $english_name
 * @property string $abbreviation
 * @property integer $parent_id
 * @property integer $ctime
 * @property integer $mtime
 * @property integer $status
 */
class SystemLanguage extends SystemActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SystemLanguage the static model class
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
		return 'system_language';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, name, english_name, abbreviation', 'required'),
			array('id, parent_id, ctime, mtime, status', 'numerical', 'integerOnly'=>true),
			array('name, english_name', 'length', 'max'=>24),
			array('abbreviation', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, english_name, abbreviation, parent_id, ctime, mtime, status', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'english_name' => 'English Name',
			'abbreviation' => 'Abbreviation',
			'parent_id' => 'Parent',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('english_name',$this->english_name,true);
		$criteria->compare('abbreviation',$this->abbreviation,true);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('ctime',$this->ctime);
		$criteria->compare('mtime',$this->mtime);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
