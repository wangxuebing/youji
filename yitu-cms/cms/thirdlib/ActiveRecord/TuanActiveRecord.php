<?php
class TuanActiveRecord extends CActiveRecord {

    public function getDbConnection()
    {
        //强制使用新的db链接
        self::$db = Yii::app()->getComponent('db_tuan');
        if(self::$db instanceof CDbConnection)
            return self::$db;
        else
            throw new CDbException(Yii::t('yii','Active Record requires a "db_tuan" CDbConnection application component.'));
    }
}
