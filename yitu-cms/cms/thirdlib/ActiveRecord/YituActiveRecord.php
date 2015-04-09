<?php
class YituActiveRecord extends CActiveRecord {

    public function getDbConnection()
    {
        //强制使用新的db链接
        self::$db = Yii::app()->getComponent('db');
        if(self::$db instanceof CDbConnection)
            return self::$db;
        else
            throw new CDbException(Yii::t('yii','Active Record requires a "db_yitu" CDbConnection application component.'));
    }
}
