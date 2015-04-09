<?php

/**
 * This is the model class for table "cms_user".
 *
 * The followings are the available columns in table 'cms_user':
 * @property integer $id
 * @property string $login
 * @property string $name
 * @property string $nick
 * @property string $password
 * @property integer $type
 * @property integer $ctime
 * @property integer $mtime
 * @property integer $status
 */
class CmsUser extends CmsActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CmsUser the static model class
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
		return 'cms_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, login, password, type', 'required'),
			array('id, type, ctime, mtime, status', 'numerical', 'integerOnly'=>true),
			array('login, name, nick', 'length', 'max'=>24),
			array('password', 'length', 'max'=>36),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, login, name, nick, password, type, ctime, mtime, status', 'safe', 'on'=>'search'),
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
			'login' => 'Login',
			'name' => 'Name',
			'nick' => 'Nick',
			'password' => 'Password',
			'type' => 'Type',
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
		$criteria->compare('login',$this->login,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('nick',$this->nick,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('ctime',$this->ctime);
		$criteria->compare('mtime',$this->mtime);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}