<?php
/**
 * @author barontang
 * @date 2014-12-18 18:00
 * @database is cms
 * @table is cms.user
 */

class CmsUserService extends BaseService {
    const STATUS_NORMAL = 0;

    /**
     * Add one item
     * @param data array(login, name, nick, password, type, ctime, mtime, status)
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

        $attri_maps = CmsUser::model()->attributeLabels();
        $attributes = array_keys($attri_maps);
        $map = array();
        foreach ($data as $key=>$val) {
            if (in_array($key, $attributes)) {
                $map[$key] = $val;
            }
        }                      
        $obj = CmsUser::model()->findByAttributes(
                    $map
                );
        if ($obj) {
            $status = -1;
            $msg = json_encode($data);
            Yii::log("Add item to CmsUser repeated, data is {$msg}", CLogger::LEVEL_ERROR);
            return $obj['id'];
        }

        $obj = new CmsUser();
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
            Yii::log("Add item to CmsUser success, data is {$msg}", CLogger::LEVEL_INFO);
        } else {
            $status = -2;
            $msg = json_encode($data);
            $error = json_encode($obj->getErrors());
            Yii::log("Add item to CmsUser failed, data is {$msg}, error info is {$error}", CLogger::LEVEL_ERROR);
        }

        return $res;
    }

    /**
     * Delete by field
     * @param field: cms.user field
     * @return true or false
     * auto function, if you use this, please delete this line
     */
    public function delDataByField($field) {
        if (!is_array($field) || $field == false) {
            return false;
        } else {
            $res = CmsUser::model()->deleteAllByAttributes($field);
            if (!$res) {
                $msg = json_encode($field);
                Yii::log("Delete item from CmsUser failed, condition is {$msg}", CLogger::LEVEL_ERROR);
            }
            return $res;
        }
    }

    /**
     * Update cms.user info
     * @param data: array(login, name, nick, password, type, ctime, mtime, status)
     * @return true or false
     * auto function, if you use this, please delete this line
     */
    public function updateData($data) {
        $obj = CmsUser::model()->findByPk($data['id']);
        if (!$obj) {
            return false;
        }

        $attri_maps = CmsUser::model()->attributeLabels();
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
            Yii::log("Update item to CmsUser failed, data is {$msg}, error is {$error}", CLogger::LEVEL_ERROR);
        }

        return $res;
    }

    /**
     * Get info by some fields
     * @param data: array of cms.user field and value
     * @return array of cms.user info
     * auto function, if you use this, please delete this line
     */
    public function getDataByField($data) {
		if (count($data) <= 0 || !is_array($data)) {
			return array();
		}
        $attri_maps = CmsUser::model()->attributeLabels();
        $attributes = array_keys($attri_maps);
        $where = array();
        foreach ($data as $key=>$val) {
            if (in_array($key, $attributes)) {
                $where[$key] = $val;
            }
        }                      

        $objs = CmsUser::model()->findAllByAttributes(
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
     * @param ids: cms.user ids
     * @return array of cms.user info
     * auto function, if you use this, please delete this line
     */
    public function getDataByIds($ids) {
        if (!is_array($ids) || $ids == false) {
            return array();
        }
        $objs = CmsUser::model()->findAllByAttributes(
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
	 * Get cms.user info by pk
	 * @return cms.user info
     * auto function, if you use this, please delete this line
	 */
	public function getDataByPk($id) {
        $obj = CmsUser::model()->findByPk($id);
        if ($obj) {
            return $this->parseData($obj);
        } else {
            return false;
        }
	}

	/**
	 * Get all cms.user info
	 * @return all cms.user info
     * auto function, if you use this, please delete this line
	 */
	public function getAllData() {
		$objs = CmsUser::model()->findAll();
        $data = array();
        foreach ($objs as $obj) {
            $data[] = $this->parseData($obj);
        }
        return $data;
	}

	/**
	 * Search cms.user info
	 * @return matched cms.user info
     * auto function, if you use this, please delete this line
	 */
    public function searchByCondition($condition, $offset=0, $limit=10) {
        $attri_maps = CmsUser::model()->attributeLabels();
        $attributes = array_keys($attri_maps);
        $where = array();
        foreach ($condition as $key=>$val) {
            if (in_array($key, $attributes)) {
                $where[$key] = $val;
            }
        }                      

        $objs = CmsUser::model()->findAllByAttributes(
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
        $count = CmsUser::model()->countByAttributes($where);
        return $count;
    }

	/**
	 * Format cms.user info
	 * @return cms.user info
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
		return $data;
	}
}
