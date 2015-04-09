<?php
/**
 * @author barontang
 * @date 2015-01-04 17:00
 * @database is yitu_test
 * @table is yitu_test.scenic_spots_order_link
 */

class ScenicSpotsOrderLinkService extends BaseService {
    const STATUS_NORMAL = 0;

    const TYPE_SUB_SPOTS = 1;
    const TYPE_SUB_FULU = 2;

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

        $attri_maps = ScenicSpotsOrderLink::model()->attributeLabels();
        $attributes = array_keys($attri_maps);
        $map = array();
        foreach ($data as $key=>$val) {
            if (in_array($key, $attributes)) {
                $map[$key] = $val;
            }
        }                      
        $obj = ScenicSpotsOrderLink::model()->findByAttributes(
                    $map
                );
        if ($obj) {
            $status = -1;
            $msg = json_encode($data);
            Yii::log("Add item to ScenicSpotsOrderLink repeated, data is {$msg}", CLogger::LEVEL_ERROR);
            return $obj['id'];
        }

        $obj = new ScenicSpotsOrderLink();
        if (in_array('ctime', $attributes)) {
            $map['ctime'] = time();
        }
        if (in_array('mtime', $attributes)) {
            $map['mtime'] = time();
        }
        foreach ($map as $key=>$val) {
            $obj->$key = $val;
        }
        $res = $obj->save();
        if ($res) {
            $res = $obj->attributes['id'];
            $msg = json_encode($data);
            Yii::log("Add item to ScenicSpotsOrderLink success, data is {$msg}", CLogger::LEVEL_INFO);
        } else {
            $status = -2;
            $msg = json_encode($data);
            $error = json_encode($obj->getErrors());
            Yii::log("Add item to ScenicSpotsOrderLink failed, data is {$msg}, error info is {$error}", CLogger::LEVEL_ERROR);
        }

        return $res;
    }

    /**
     * Delete by field
     * @param field: yitu_test.scenic_spots_order_link field
     * @return true or false
     * auto function, if you use this, please delete this line
     */
    public function delDataByField($field) {
        if (!is_array($field) || $field == false) {
            return false;
        } else {
            $res = ScenicSpotsOrderLink::model()->deleteAllByAttributes($field);
            if (!$res) {
                $msg = json_encode($field);
                Yii::log("Delete item from ScenicSpotsOrderLink failed, condition is {$msg}", CLogger::LEVEL_ERROR);
            }
            return $res;
        }
    }

    /**
     * Update yitu_test.scenic_spots_order_link info
     * @param data: array()
     * @return true or false
     * auto function, if you use this, please delete this line
     */
    public function updateData($data) {
        $obj = ScenicSpotsOrderLink::model()->findByPk($data['id']);
        if (!$obj) {
            return false;
        }

        $attri_maps = ScenicSpotsOrderLink::model()->attributeLabels();
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
            $error = json_encode($obj->getErrors());
            Yii::log("Update item to ScenicSpotsOrderLink failed, data is {$msg}, error is {$error}", CLogger::LEVEL_ERROR);
        }

        return $res;
    }

    /**
     * Get info by some fields
     * @param data: array of yitu_test.scenic_spots_order_link field and value
     * @return array of yitu_test.scenic_spots_order_link info
     * auto function, if you use this, please delete this line
     */
    public function getDataByField($data) {
		if (count($data) <= 0 || !is_array($data)) {
			return array();
		}
        $attri_maps = ScenicSpotsOrderLink::model()->attributeLabels();
        $attributes = array_keys($attri_maps);
        $where = array();
        foreach ($data as $key=>$val) {
            if (in_array($key, $attributes)) {
                $where[$key] = $val;
            }
        }                      

        $objs = ScenicSpotsOrderLink::model()->findAllByAttributes(
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
     * @param ids: yitu_test.scenic_spots_order_link ids
     * @return array of yitu_test.scenic_spots_order_link info
     * auto function, if you use this, please delete this line
     */
    public function getDataByIds($ids) {
        if (!is_array($ids) || $ids == false) {
            return array();
        }
        $objs = ScenicSpotsOrderLink::model()->findAllByAttributes(
                    array(
                        'id' => $ids,
                    )
                );
        $data = array();
        foreach ($objs as $obj) {
            $data[] = $this->parseData($obj);
        }
        return $data;
    }

	/**
	 * Get yitu_test.scenic_spots_order_link info by pk
	 * @return yitu_test.scenic_spots_order_link info
     * auto function, if you use this, please delete this line
	 */
	public function getDataByPk($id) {
        $obj = ScenicSpotsOrderLink::model()->findByPk($id);
        if ($obj) {
            return $this->parseData($obj);
        } else {
            return false;
        }
	}

	/**
	 * Get all yitu_test.scenic_spots_order_link info
	 * @return all yitu_test.scenic_spots_order_link info
     * auto function, if you use this, please delete this line
	 */
	public function getAllData() {
		$objs = ScenicSpotsOrderLink::model()->findAll();
        $data = array();
        foreach ($objs as $obj) {
            $data[] = $this->parseData($obj);
        }
        return $data;
	}

	/**
	 * Search yitu_test.scenic_spots_order_link info
	 * @return matched yitu_test.scenic_spots_order_link info
     * auto function, if you use this, please delete this line
	 */
    public function searchByCondition($condition, $offset=0, $limit=10) {
        $attri_maps = ScenicSpotsOrderLink::model()->attributeLabels();
        $attributes = array_keys($attri_maps);
        $where = array();
        foreach ($condition as $key=>$val) {
            if (in_array($key, $attributes)) {
                $where[$key] = $val;
            }
        }                      

        $objs = ScenicSpotsOrderLink::model()->findAllByAttributes(
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
	 * Get the count
     * auto function, if you use this, please delete this line
	 */
    public function getCountByField($where) {
        $count = ScenicSpotsOrderLink::model()->countByAttributes($where);
        return $count;
    }

	/**
	 * Format yitu_test.scenic_spots_order_link info
	 * @return yitu_test.scenic_spots_order_link info
     * auto function, if you use this, please delete this line
	 */
	protected function parseData($obj) {
        $data = $obj->attributes;
        foreach ($data as $key=>&$val) {
            if ($val === null) {
                $val = '';
            }
        }
        unset($data['ctime']);
        unset($data['mtime']);
        unset($data['status']);
        unset($data['id']);
		return $data;
	}
}
