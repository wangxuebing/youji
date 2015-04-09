<?php
/**
 * @author barontang
 * @date 2014-12-18 18:00
 * @database is cms
 * @table is cms.task
 */

class CmsTaskService extends BaseService {
    const STATUS_NOT_START = 0; //未开始
    const STATUS_DOING = 1; //进行中
    const STATUS_COMPLETED = 2; //已完成
    const STATUS_ABORT = 3; //中途停止

    /**
     * Add one item
     * @param data array(spots_name, spots_id, create_user_id, last_operation_user_id, province_id, city_id, area_id, stime, etime, ctime, mtime, status)
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

        $attri_maps = CmsTask::model()->attributeLabels();
        $attributes = array_keys($attri_maps);
        $map = array();
        foreach ($data as $key=>$val) {
            if (in_array($key, $attributes)) {
                $map[$key] = $val;
            }
        }                      
        $obj = CmsTask::model()->findByAttributes(
                    $map
                );
        if ($obj) {
            $status = -1;
            $msg = json_encode($data);
            Yii::log("Add item to CmsTask repeated, data is {$msg}", CLogger::LEVEL_ERROR);
            return $obj['id'];
        }

        $obj = new CmsTask();
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
            Yii::log("Add item to CmsTask success, data is {$msg}", CLogger::LEVEL_INFO);
        } else {
            $status = -2;
            $msg = json_encode($data);
            $error = json_encode($obj->getErrors());
            Yii::log("Add item to CmsTask failed, data is {$msg}, error info is {$error}", CLogger::LEVEL_ERROR);
        }

        return $res;
    }

    /**
     * Delete by field
     * @param field: cms.task field
     * @return true or false
     * auto function, if you use this, please delete this line
     */
    public function delDataByField($field) {
        if (!is_array($field) || $field == false) {
            return false;
        } else {
            $res = CmsTask::model()->deleteAllByAttributes($field);
            if (!$res) {
                $msg = json_encode($field);
                Yii::log("Delete item from CmsTask failed, condition is {$msg}", CLogger::LEVEL_ERROR);
            }
            return $res;
        }
    }

    /**
     * Update cms.task info
     * @param data: array(spots_name, spots_id, create_user_id, last_operation_user_id, province_id, city_id, area_id, stime, etime, ctime, mtime, status)
     * @return true or false
     * auto function, if you use this, please delete this line
     */
    public function updateData($data) {
        $obj = CmsTask::model()->findByPk($data['id']);
        if (!$obj) {
            return false;
        }

        $attri_maps = CmsTask::model()->attributeLabels();
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
            Yii::log("Update item to CmsTask failed, data is {$msg}, error is {$error}", CLogger::LEVEL_ERROR);
        }

        return $res;
    }

    /**
     * Get info by some fields
     * @param data: array of cms.task field and value
     * @return array of cms.task info
     * auto function, if you use this, please delete this line
     */
    public function getDataByField($data) {
		if (count($data) <= 0 || !is_array($data)) {
			return array();
		}
        $attri_maps = CmsTask::model()->attributeLabels();
        $attributes = array_keys($attri_maps);
        $where = array();
        foreach ($data as $key=>$val) {
            if (in_array($key, $attributes)) {
                $where[$key] = $val;
            }
        }                      

        $objs = CmsTask::model()->findAllByAttributes(
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
     * @param ids: cms.task ids
     * @return array of cms.task info
     * auto function, if you use this, please delete this line
     */
    public function getDataByIds($ids) {
        if (!is_array($ids) || $ids == false) {
            return array();
        }
        $objs = CmsTask::model()->findAllByAttributes(
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
	 * Get cms.task info by pk
	 * @return cms.task info
     * auto function, if you use this, please delete this line
	 */
	public function getDataByPk($id) {
        $obj = CmsTask::model()->findByPk($id);
        if ($obj) {
            return $this->parseData($obj);
        } else {
            return false;
        }
	}

	/**
	 * Get all cms.task info
	 * @return all cms.task info
     * auto function, if you use this, please delete this line
	 */
	public function getAllData() {
		$objs = CmsTask::model()->findAll();
        $data = array();
        foreach ($objs as $obj) {
            $data[] = $this->parseData($obj);
        }
        return $data;
	}

    public function searchByKw($kw, $offset=0, $limit=10, $where=array()) {
        $lower = strtolower($kw);
        $condition = '`spots_name` LIKE "%' . $kw . '%"';

        $conditions = array('offset'=>$offset, 'limit'=>$limit, 'order'=>'id DESC');
        $conditions['condition'] = $condition;
        $count = CmsTask::model()->countByAttributes(
                $where,
                $condition
                );
        $spots = CmsTask::model()->findAllByAttributes(
                $where,
                $conditions
                );

        $data = array();
        foreach ($spots as $spot) {
            $data[] = $this->parseData($spot);
        }

        return array('count'=>$count, 'data'=>$data);
    }

	/**
	 * Search cms.task info
	 * @return matched cms.task info
     * auto function, if you use this, please delete this line
	 */
    public function searchByCondition($condition, $offset=0, $limit=10) {
        $attri_maps = CmsTask::model()->attributeLabels();
        $attributes = array_keys($attri_maps);
        $where = array();
        foreach ($condition as $key=>$val) {
            if (in_array($key, $attributes)) {
                $where[$key] = $val;
            }
        }                      

        $objs = CmsTask::model()->findAllByAttributes(
                $where,
                array(
                    'offset' => $offset,
                    'limit' => $limit,
                    'order' => 'id DESC',
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
        $count = CmsTask::model()->countByAttributes($where);
        return $count;
    }

	/**
	 * Format cms.task info
	 * @return cms.task info
     * auto function, if you use this, please delete this line
	 */
	protected function parseData($obj) {
        $data = $obj->attributes;
        foreach ($data as $key=>&$val) {
            if ($val === null) {
                $val = '';
            }
        }

        //get the province, city and area
        $area_service = new AreaService();
        $data['province'] = '';
        $data['city'] = '';
        $data['area'] = '';
        if ($obj['province_id'] != 0) {
            $where = array('area_id'=>$obj['province_id']);
            $areas = $area_service->getDataByField($where);
            if (count($areas) > 0) {
                $data['province'] = $areas[0]['title'];
            }
        }
        if ($obj['city_id'] != 0) {
            $where = array('area_id'=>$obj['city_id']);
            $areas = $area_service->getDataByField($where);
            if (count($areas) > 0) {
                $data['city'] = $areas[0]['title'];
            }
        }
        if ($obj['area_id'] != 0) {
            $where = array('area_id'=>$obj['area_id']);
            $areas = $area_service->getDataByField($where);
            if (count($areas) > 0) {
                $data['area'] = $areas[0]['title'];
            }
        }
		return $data;
	}
}
