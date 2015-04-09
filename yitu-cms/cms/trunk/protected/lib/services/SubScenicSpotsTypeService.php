<?php
class SubScenicSpotsTypeService extends BaseService {
    const STATUS_NORMAL = 0;
    
    public function getAllData() {
        $objs = SubScenicSpotsType::model()->findAll();
        $data = array();
        foreach ($objs as $obj) {
            $data[] = $this->parseData($obj);
        }
        return $data;
    }

    public function getDataByPk($id) {
        $obj = SubScenicSpotsType::model()->findByPk($id);
        if ($obj) {
            return $this->parseData($obj);
        } else {
            return false;
        }
    }

    protected function parseData($obj) {
        $data = $obj->attributes;
        unset($data['ctime']);
        unset($data['mtime']);
        unset($data['status']);
        return $data;
    }
}
