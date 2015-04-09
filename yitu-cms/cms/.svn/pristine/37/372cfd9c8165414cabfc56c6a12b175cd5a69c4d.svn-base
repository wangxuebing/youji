<?php

/**
 * This is the model class for table "area".
 *
 * The followings are the available columns in table 'area':
 * @property integer $area_id
 * @property string $title
 * @property integer $pid
 * @property integer $sort
 * @property string $pinin
 */
class Area extends YituActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Area the static model class
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
		return 'area';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, sort', 'required'),
			array('pid, sort', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('pinin', 'length', 'max'=>64),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('area_id, title, pid, sort, pinin', 'safe', 'on'=>'search'),
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
			'area_id' => 'Area',
			'title' => 'Title',
			'pid' => 'Pid',
			'sort' => 'Sort',
			'pinin' => 'Pinin',
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

		$criteria->compare('area_id',$this->area_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('pid',$this->pid);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('pinin',$this->pinin,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}