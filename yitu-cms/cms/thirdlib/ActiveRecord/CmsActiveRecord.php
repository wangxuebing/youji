<?php
class CmsActiveRecord extends CActiveRecord {

    public function getDbConnection()
    {
        //强制使用新的db链接
        self::$db = Yii::app()->getComponent('db_cms');
        if(self::$db instanceof CDbConnection)
            return self::$db;
        else
            throw new CDbException(Yii::t('yii','Active Record requires a "db_cms" CDbConnection application component.'));
    }
}
