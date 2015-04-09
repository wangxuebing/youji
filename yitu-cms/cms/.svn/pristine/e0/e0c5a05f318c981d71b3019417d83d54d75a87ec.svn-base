<?php
/**
 * @author barontang
 * @date 2015-01-26 15:00
 * @database is system
 * @table is system.language
 */

class SystemLanguageService extends BaseService {
    const STATUS_NORMAL = 0;
    private $_attributes = array();

    public function __construct() {
        $attri_maps = SystemLanguage::model()->attributeLabels();
        $this->_attributes = array_keys($attri_maps);
        parent::__construct();
    }

    /**
     * Add one item
     * @param data array(name, english_name, abbreviation, parent_id, ctime, mtime, status)
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

        $data['language'] = $this->language;
        $map = array();
        foreach ($data as $key=>$val) {
            if (in_array($key, $this->_attributes)) {
                $map[$key] = $val;
            }
        }                      
        $obj = SystemLanguage::model()->findByAttributes(
                    $map
                );
        if ($obj) {
            $status = -1;
            $msg = json_encode($data);
            Yii::log("Add item to SystemLanguage repeated, data is {$msg}", CLogger::LEVEL_ERROR);
            return $obj['id'];
        }

        $obj = new SystemLanguage();
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
            Yii::log("Add item to SystemLanguage success, data is {$msg}", CLogger::LEVEL_INFO);
        } else {
            $status = -2;
            $msg = json_encode($data);
            $error = json_encode($obj->getErrors());
            Yii::log("Add item to SystemLanguage failed, data is {$msg}, error info is {$error}", CLogger::LEVEL_ERROR);
        }

        return $res;
    }

    /**
     * Delete by field
     * @param field: system.language field
     * @return true or false
     * auto function, if you use this, please delete this line
     */
    public function delDataByField($field) {
        if (!is_array($field) || $field == false) {
            return false;
        } else {
            if (in_array('language', $this->_attributes)) {
                $field['language'] = $this->language;
            }
            $res = SystemLanguage::model()->deleteAllByAttributes($field);
            if (!$res) {
                $msg = json_encode($field);
                Yii::log("Delete item from SystemLanguage failed, condition is {$msg}", CLogger::LEVEL_ERROR);
            }
            return $res;
        }
    }

    /**
     * Update system.language info
     * @param data: array(name, english_name, abbreviation, parent_id, ctime, mtime, status)
     * @return true or false
     * auto function, if you use this, please delete this line
     */
    public function updateData($data) {
        $obj = null;
        if (in_array('language', $this->_attributes)) {
            $where = array('id'=>$data['id'], 'language'=>$this->language);
            $obj = SystemLanguage::model()->findByAttributes($where);
        } else {
            $obj = SystemLanguage::model()->findByPk($data['id']);
        }

        if (!$obj) {
            return false;
        }

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
            Yii::log("Update item to SystemLanguage failed, data is {$msg}, error is {$error}", CLogger::LEVEL_ERROR);
        }

        return $res;
    }

    /**
     * Get info by some fields
     * @param data: array of system.language field and value
     * @return array of system.language info
     * auto function, if you use this, please delete this line
     */
    public function getDataByField($data) {
		if (count($data) <= 0 || !is_array($data)) {
			return array();
		}
        $data['language'] = $this->language;
        $where = array();
        foreach ($data as $key=>$val) {
            if (in_array($key, $this->_attributes)) {
                $where[$key] = $val;
            }
        }                      

        $objs = SystemLanguage::model()->findAllByAttributes(
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
     * @param ids: system.language ids
     * @return array of system.language info
     * auto function, if you use this, please delete this line
     */
    public function getDataByIds($ids) {
        if (!is_array($ids) || $ids == false) {
            return array();
        }
        $where = array('id'=>$ids);
        if (in_array('language', $this->_attributes)) {
            $where['language'] = $this->language;
        }

        $objs = SystemLanguage::model()->findAllByAttributes(
                    $where
                );
        $data = array();
        foreach ($objs as $obj) {
            $data[] = $this->parseData($obj);
        }
        return $data;
    }

	/**
	 * Get system.language info by pk
	 * @return system.language info
     * auto function, if you use this, please delete this line
	 */
	public function getDataByPk($id) {
        $obj = null;
        if (in_array('language', $this->_attributes)) {
            $where = array('id'=>$id, 'language'=>$this->language);
            $obj = SystemLanguage::model()->findByAttributes($where);
        } else {
            $obj = SystemLanguage::model()->findByPk($id);
        }
        if ($obj) {
            return $this->parseData($obj);
        } else {
            return false;
        }
	}

	/**
	 * Get all system.language info
	 * @return all system.language info
     * auto function, if you use this, please delete this line
	 */
	public function getAllData() {
        if (in_array('language', $this->_attributes)) {
		    $objs = SystemLanguage::model()->findAll(array('language'=>$this->language));
        } else {
		    $objs = SystemLanguage::model()->findAll();
        }

        $data = array();
        foreach ($objs as $obj) {
            $data[] = $this->parseData($obj);
        }
        return $data;
	}

	/**
	 * Search system.language info
	 * @return matched system.language info
     * auto function, if you use this, please delete this line
	 */
    public function searchByCondition($condition, $offset=0, $limit=10) {
        $condition['language'] = $this->language;
        $where = array();
        foreach ($condition as $key=>$val) {
            if (in_array($key, $this->_attributes)) {
                $where[$key] = $val;
            }
        }                      

        $objs = SystemLanguage::model()->findAllByAttributes(
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
        if (in_array('language', $this->_attributes)) {
            $where['language'] = $this->language;
        }

        $count = SystemLanguage::model()->countByAttributes($where);
        return $count;
    }

	/**
	 * Format system.language info
	 * @return system.language info
     * auto function, if you use this, please delete this line
	 */
	protected function parseData($obj) {
        $data = $obj->attributes;
        foreach ($data as $key=>&$val) {
            if ($val === null) {
                $val = '';
            }
        }
        unset($data['status']);
        unset($data['parent_id']);
        unset($data['ctime']);
        unset($data['mtime']);
		return $data;
	}
}
