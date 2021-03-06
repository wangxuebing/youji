<?php

/**
 * This is the model class for table "content_meta".
 *
 * The followings are the available columns in table 'content_meta':
 * @property integer $id
 * @property integer $language
 * @property integer $type_id
 * @property string $name
 * @property string $title
 * @property string $sub_title
 * @property string $content
 * @property string $face
 * @property integer $from_spots_type
 * @property integer $from_spots_id
 * @property integer $ctime
 * @property integer $mtime
 * @property integer $status
 */
class ContentMeta extends YituActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ContentMeta the static model class
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
		return 'content_meta';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type_id, content', 'required'),
			array('language, type_id, from_spots_type, from_spots_id, ctime, mtime, status', 'numerical', 'integerOnly'=>true),
			array('name, title, sub_title', 'length', 'max'=>128),
			array('face', 'length', 'max'=>40),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, language, type_id, name, title, sub_title, content, face, from_spots_type, from_spots_id, ctime, mtime, status', 'safe', 'on'=>'search'),
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
			'language' => 'Language',
			'type_id' => 'Type',
			'name' => 'Name',
			'title' => 'Title',
			'sub_title' => 'Sub Title',
			'content' => 'Content',
			'face' => 'Face',
			'from_spots_type' => 'From Spots Type',
			'from_spots_id' => 'From Spots',
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
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('sub_title',$this->sub_title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('face',$this->face,true);
		$criteria->compare('from_spots_type',$this->from_spots_type);
		$criteria->compare('from_spots_id',$this->from_spots_id);
		$criteria->compare('ctime',$this->ctime);
		$criteria->compare('mtime',$this->mtime);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}