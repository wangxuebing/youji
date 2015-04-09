<?php

/**
 * This is the model class for table "scenic_spots".
 *
 * The followings are the available columns in table 'scenic_spots':
 * @property integer $id
 * @property string $name
 * @property integer $language
 * @property string $pinyin
 * @property integer $province_id
 * @property string $province
 * @property integer $city_id
 * @property string $city
 * @property integer $area_id
 * @property string $area
 * @property double $longitude
 * @property double $latitude
 * @property string $address
 * @property string $face
 * @property string $detail_face
 * @property integer $detail_face_type
 * @property string $content_face
 * @property string $panorama
 * @property string $audio
 * @property string $around_coordinates
 * @property string $ticket_price
 * @property string $open_time
 * @property double $level
 * @property string $description
 * @property string $service
 * @property string $traffic
 * @property string $tips
 * @property string $telephone
 * @property integer $ctime
 * @property integer $mtime
 * @property integer $status
 * @property string $manage_url
 */
class ScenicSpots extends YituActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ScenicSpots the static model class
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
		return 'scenic_spots';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('language, province_id, city_id, area_id, detail_face_type, ctime, mtime, status', 'numerical', 'integerOnly'=>true),
			array('longitude, latitude, level', 'numerical'),
			array('name, pinyin, province, city, area', 'length', 'max'=>64),
			array('address, telephone, manage_url', 'length', 'max'=>128),
			array('face, detail_face, content_face, audio', 'length', 'max'=>40),
			array('panorama, description', 'length', 'max'=>1024),
			array('around_coordinates, ticket_price, open_time', 'length', 'max'=>256),
			array('service, traffic, tips', 'length', 'max'=>512),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, language, pinyin, province_id, province, city_id, city, area_id, area, longitude, latitude, address, face, detail_face, detail_face_type, content_face, panorama, audio, around_coordinates, ticket_price, open_time, level, description, service, traffic, tips, telephone, ctime, mtime, status, manage_url', 'safe', 'on'=>'search'),
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
			'language' => 'Language',
			'pinyin' => 'Pinyin',
			'province_id' => 'Province',
			'province' => 'Province',
			'city_id' => 'City',
			'city' => 'City',
			'area_id' => 'Area',
			'area' => 'Area',
			'longitude' => 'Longitude',
			'latitude' => 'Latitude',
			'address' => 'Address',
			'face' => 'Face',
			'detail_face' => 'Detail Face',
			'detail_face_type' => 'Detail Face Type',
			'content_face' => 'Content Face',
			'panorama' => 'Panorama',
			'audio' => 'Audio',
			'around_coordinates' => 'Around Coordinates',
			'ticket_price' => 'Ticket Price',
			'open_time' => 'Open Time',
			'level' => 'Level',
			'description' => 'Description',
			'service' => 'Service',
			'traffic' => 'Traffic',
			'tips' => 'Tips',
			'telephone' => 'Telephone',
			'ctime' => 'Ctime',
			'mtime' => 'Mtime',
			'status' => 'Status',
			'manage_url' => 'Manage Url',
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
		$criteria->compare('language',$this->language);
		$criteria->compare('pinyin',$this->pinyin,true);
		$criteria->compare('province_id',$this->province_id);
		$criteria->compare('province',$this->province,true);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('area_id',$this->area_id);
		$criteria->compare('area',$this->area,true);
		$criteria->compare('longitude',$this->longitude);
		$criteria->compare('latitude',$this->latitude);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('face',$this->face,true);
		$criteria->compare('detail_face',$this->detail_face,true);
		$criteria->compare('detail_face_type',$this->detail_face_type);
		$criteria->compare('content_face',$this->content_face,true);
		$criteria->compare('panorama',$this->panorama,true);
		$criteria->compare('audio',$this->audio,true);
		$criteria->compare('around_coordinates',$this->around_coordinates,true);
		$criteria->compare('ticket_price',$this->ticket_price,true);
		$criteria->compare('open_time',$this->open_time,true);
		$criteria->compare('level',$this->level);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('service',$this->service,true);
		$criteria->compare('traffic',$this->traffic,true);
		$criteria->compare('tips',$this->tips,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('ctime',$this->ctime);
		$criteria->compare('mtime',$this->mtime);
		$criteria->compare('status',$this->status);
		$criteria->compare('manage_url',$this->manage_url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}