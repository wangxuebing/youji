<?php

/**
 * This is the model class for table "scenic_spots_content_link".
 *
 * The followings are the available columns in table 'scenic_spots_content_link':
 * @property integer $id
 * @property integer $language
 * @property integer $spots_type
 * @property integer $spots_id
 * @property integer $content_id
 * @property integer $pos
 * @property integer $ctime
 * @property integer $mtime
 * @property integer $status
 */
class ScenicSpotsContentLink extends YituActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ScenicSpotsContentLink the static model class
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
		return 'scenic_spots_content_link';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('spots_type, spots_id, content_id', 'required'),
			array('language, spots_type, spots_id, content_id, pos, ctime, mtime, status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, language, spots_type, spots_id, content_id, pos, ctime, mtime, status', 'safe', 'on'=>'search'),
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
            'content'=>array(self::BELONGS_TO, 'ContentMeta', 'content_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'language' => 'Language',
			'spots_type' => 'Spots Type',
			'spots_id' => 'Spots',
			'content_id' => 'Content',
			'pos' => 'Pos',
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
		$criteria->compare('language',$this->language);
		$criteria->compare('spots_type',$this->spots_type);
		$criteria->compare('spots_id',$this->spots_id);
		$criteria->compare('content_id',$this->content_id);
		$criteria->compare('pos',$this->pos);
		$criteria->compare('ctime',$this->ctime);
		$criteria->compare('mtime',$this->mtime);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
