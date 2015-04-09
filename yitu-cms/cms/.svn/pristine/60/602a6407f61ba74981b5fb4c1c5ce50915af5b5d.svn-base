<?php
/**
 * @author barontang
 * @date 2014-10-11 17
 * @database is yitu
 * @table is yitu_area
 */

class AreaService extends BaseService{
    const STATUS_NORMAL = 0;

    private $_filter = array('省', '市', '县', '自治县');
    private $_pinyin_filter = array('sheng', 'shi', 'xian', 'zi zhi xian');

    /**
     * Add one item
     * @param data array()
     * @return id or false
     * @status 0 means param illegal or -1 means repeat or -2 means add error
     * auto function, if you use this, please delete this line
     */
    public function addData($data, &$status) {
        if (!is_array($data) || $data == false) {
            $status = 0;
            return false;
        } else if (count($data) == 0) { // check not empty data
            $status = 0;
            return false;
        }

        $attri_maps = Area::model()->attributeLabels();
        $attributes = array_keys($attri_maps);
        $map = array();
        foreach ($data as $key=>$val) {
            if (in_array($key, $attributes)) {
                $map[$key] = $val;
            }
        }                      
        $obj = Area::model()->findByAttributes(
                    $map
                );
        if ($obj) {
            $status = -1;
            $msg = json_encode($data);
            Yii::log("Add item to Area repeated, data is {$msg}", CLogger::LEVEL_ERROR);
            return $obj['id'];
        }

        $obj = new Area();
        if (in_array('ctime', $attributes)) {
            $map['ctime'] = time();
        }
        foreach ($map as $key=>$val) {
            $obj->$key = $val;
        }
        $res = $obj->save();
        if ($res) {
            Yii::log("Add item to Area failed, data is {$msg}", CLogger::LEVEL_ERROR);
        } else {
            Yii::log("Add item to Area success, data is {$msg}", CLogger::LEVEL_INFO);
        }

        return $res;
    }

    /**
     * Delete by field
     * @param id: yitu.area id
     * @return true or false
     * auto function, if you use this, please delete this line
     */
    public function delDataByField($field) {
        if (!is_array($field) || $field == false) {
            return false;
        } else {
            $res = Area::model()->deleteAllByAttributes($field);
            if (!$res) {
                $msg = json_encode($field);
                Yii::log("Delete item from Area failed, condition is {$msg}", CLogger::LEVEL_ERROR);
            }
            return $res;
        }
    }

    /**
     * Update yitu.area info
     * @param data: array()
     * @return true or false
     * auto function, if you use this, please delete this line
     */
    public function updateData($data) {
        $obj = Area::model()->findByPk($data['area_id']);
        if (!$obj) {
            return false;
        }

        $attri_maps = Area::model()->attributeLabels();
        $attributes = array_keys($attri_maps);
        if (in_array('mtime', $attributes)) {
            $data['mtime'] = time();
        }
        foreach ($data as $key=>$val) {
            if (in_array($key, $attributes)) {
                $obj[$key] = $val;
            }
        }                      

        $res = $obj->save();
        if (!$res) {
            $msg = json_encode($data);
            Yii::log("Update item to Area failed, data is {$msg}", CLogger::LEVEL_ERROR);
        }

        return $res;
    }

    /**
     * Get info by some fields
     * @param data: array of yitu.area field and value
     * @return array of yitu.area info
     * auto function, if you use this, please delete this line
     */
    public function getDataByField($data) {
		if (count($data) <= 0 || !is_array($data)) {
			return array();
		}
        $attri_maps = Area::model()->attributeLabels();
        $attributes = array_keys($attri_maps);
        $where = array();
        foreach ($data as $key=>$val) {
            if (in_array($key, $attributes)) {
                $where[$key] = $val;
            }
        }                      

        $objs = Area::model()->findAllByAttributes(
                    $where
                );

        $data = array();
        foreach ($objs as $obj) {
            $data[] = $this->parseData($obj);
        }
        return $data;
    }

    /**
     * Get info by ids
     * @param ids: yitu.area ids
     * @return array of yitu.area info
     * auto function, if you use this, please delete this line
     */
    public function getDataByIds($ids) {
        if (!is_array($ids) || $ids == false) {
            return array();
        }
        $objs = Area::model()->findAllByAttributes(
                    array(
                        'area_id' => $ids,
                    )
                );
        $data = array();
        foreach ($objs as $obj) {
            $data[] = $this->parseData($obj);
        }
        return $data;
    }

	/**
	 * Get all yitu.area info
	 * @return all yitu.area info
     * auto function, if you use this, please delete this line
	 */
	public function getAllData() {
		$objs = Area::model()->findAll();
        $data = array();
        foreach ($objs as $obj) {
            $data[] = $this->parseData($obj);
        }
        return $data;
	}

    public function getCountByField($where) {
        $count = Area::model()->countByAttributes($where);
        return $count;
    }

	/**
	 * Search yitu.area info
	 * @return matched yitu.area info
     * auto function, if you use this, please delete this line
	 */
    public function searchByCondition($condition, $offset=0, $limit=10) {
        $attri_maps = Area::model()->attributeLabels();
        $attributes = array_keys($attri_maps);
        $where = array();
        foreach ($condition as $key=>$val) {
            if (in_array($key, $attributes)) {
                $where[$key] = $val;
            }
        }                      

        $objs = Area::model()->findAllByAttributes(
                $where,
                array(
                    'offset' => $offset,
                    'limit' => $limit,
                    )
                );

        $data = array();
        foreach ($objs as $obj) {
            $data[] = $this->parseData($obj);
        }
        return $data;
    }

	/**
	 * Format yitu.area info
	 * @return yitu.area info
     * auto function, if you use this, please delete this line
	 */
	protected function parseData($obj) {
        $data = $obj->attributes;
        foreach ($data as $key=>&$val) {
            if ($val === null) {
                $val = '';
            }
        }
        //filter the area name
        foreach ($this->_filter as $fi) {
            $data['title'] = preg_replace('/'.$fi.'$/', '', $data['title']);
        }
        //filter the area name pinyin
        foreach ($this->_pinyin_filter as $fi) {
            $data['pinyin'] = preg_replace('/ '.$fi.'$/', '', $data['pinyin']);
        }
        //up the first letter
        $data['pinyin'] = ucwords($data['pinyin']);
        unset($data['sort']);
		return $data;
	}
}
