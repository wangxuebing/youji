<?php
/**
 * @author barontang
 * @date 2014-11-06 03:00
 * @database is yitu
 * @table is yitu.content_meta
 */

class ContentMetaService extends BaseService {
    const STATUS_NORMAL = 0;

    const TYPE_INTRODUCTION = 1;
    const TYPE_SUB_SPOTS = 12;

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

        if (!isset($data['language'])) {
            $data['language'] = $this->language;
        }
        $attri_maps = ContentMeta::model()->attributeLabels();
        $attributes = array_keys($attri_maps);
        $map = array();
        foreach ($data as $key=>$val) {
            if (in_array($key, $attributes)) {
                $map[$key] = $val;
            }
        }                      
        $obj = ContentMeta::model()->findByAttributes(
                    $map
                );
        if ($obj) {
            $status = -1;
            $msg = json_encode($data);
            Yii::log("Add item to ContentMeta repeated, data is {$msg}", CLogger::LEVEL_ERROR);
            return $obj['id'];
        }

        $obj = new ContentMeta();
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
            $msg = json_encode($data);
            $res = $obj->attributes['id'];
            Yii::log("Add item to ContentMeta success, data is {$msg}", CLogger::LEVEL_INFO);
        } else {
            $status = -2;
            $msg = json_encode($data);
            $error = json_encode($obj->getErrors());
            Yii::log("Add item to ResourceMeta failed, data is {$msg}, error info is {$error}", CLogger::LEVEL_ERROR);
        }

        return $res;
    }

    /**
     * Delete by field
     * @param field: yitu.content_meta field
     * @return true or false
     * auto function, if you use this, please delete this line
     */
    public function delDataByField($field) {
        if (!is_array($field) || $field == false) {
            return false;
        } else {
            if (!isset($field['language'])) {
                $field['language'] = $this->language;
            }
            $res = ContentMeta::model()->deleteAllByAttributes($field);
            if (!$res) {
                $msg = json_encode($field);
                Yii::log("Delete item from ContentMeta failed, condition is {$msg}", CLogger::LEVEL_ERROR);
            }
            return $res;
        }
    }

    /**
     * Update yitu.content_meta info
     * @param data: array()
     * @return true or false
     * auto function, if you use this, please delete this line
     */
    public function updateData($data) {
        if (!isset($data['language'])) {
            $data['language'] = $this->language;
        }
        $where = array('id'=>$data['id'], 'language'=>$data['language']);
        $obj = ContentMeta::model()->findByAttributes($where);
        if (!$obj) {
            return false;
        }

        $attri_maps = ContentMeta::model()->attributeLabels();
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
            Yii::log("Update item to ContentMeta failed, data is {$msg}", CLogger::LEVEL_ERROR);
        }

        return $res;
    }

    /**
     * Get info by some fields
     * @param data: array of yitu.content_meta field and value
     * @return array of yitu.content_meta info
     * auto function, if you use this, please delete this line
     */
    public function getDataByField($data) {
		if (count($data) <= 0 || !is_array($data)) {
			return array();
		}
        if (!isset($data['language'])) {
            $data['language'] = $this->language;
        }
        $attri_maps = ContentMeta::model()->attributeLabels();
        $attributes = array_keys($attri_maps);
        $where = array();
        foreach ($data as $key=>$val) {
            if (in_array($key, $attributes)) {
                $where[$key] = $val;
            }
        }                      

        $objs = ContentMeta::model()->findAllByAttributes(
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
     * @param ids: yitu.content_meta ids
     * @return array of yitu.content_meta info
     * auto function, if you use this, please delete this line
     */
    public function getDataByIds($ids) {
        if (!is_array($ids) || $ids == false) {
            return array();
        }
        $objs = ContentMeta::model()->findAllByAttributes(
                    array(
                        'id' => $ids,
                        'language' => $this->language,
                    )
                );
        $data = array();
        foreach ($objs as $obj) {
            $data[] = $this->parseData($obj);
        }
        return $data;
    }

	/**
	 * Get yitu.content_meta info by pk
	 * @return yitu.content_meta info
     * auto function, if you use this, please delete this line
	 */
	public function getDataByPk($id) {
        $where = array('id'=>$id, 'language'=>$this->language);
        $obj = ContentMeta::model()->findByAttributes($where);
        if ($obj) {
            return $this->parseData($obj);
        } else {
            return false;
        }
	}

	/**
	 * Get all yitu.content_meta info
	 * @return all yitu.content_meta info
     * auto function, if you use this, please delete this line
	 */
	public function getAllData() {
		$objs = ContentMeta::model()->findAll(array('language'=>$this->language));
        $data = array();
        foreach ($objs as $obj) {
            $data[] = $this->parseData($obj);
        }
        return $data;
	}

	/**
	 * Search yitu.content_meta info
	 * @return matched yitu.content_meta info
     * auto function, if you use this, please delete this line
	 */
    public function searchByCondition($condition, $offset=0, $limit=10) {
        if (!isset($condition['language'])) {
            $condition['language'] = $this->language;
        }
        $attri_maps = ContentMeta::model()->attributeLabels();
        $attributes = array_keys($attri_maps);
        $where = array();
        foreach ($condition as $key=>$val) {
            if (in_array($key, $attributes)) {
                $where[$key] = $val;
            }
        }                      

        $objs = ContentMeta::model()->findAllByAttributes(
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

    public function searchByKw($kw, $offset=0, $limit=10, $where=array()) {
        $lower = strtolower($kw);
        $condition = '`name` LIKE "%' . $kw . '%" OR `title` LIKE "%' . $kw . '%"';
        $where['status'] = self::STATUS_NORMAL;
        if (!isset($where['language'])) {
            $where['language'] = $this->language;
        }

        $conditions = array('offset'=>$offset, 'limit'=>$limit);
        $conditions['condition'] = $condition;
        $count = ContentMeta::model()->countByAttributes(
                $where,
                $condition
                );
        $objs = ContentMeta::model()->findAllByAttributes(
                $where,
                $conditions
                );

        $data = array();
        foreach ($objs as $obj) {
            $data[] = $this->parseData($obj);
        }

        return array('count'=>$count, 'data'=>$data);
    }

	/**
	 * Get the count
     * auto function, if you use this, please delete this line
	 */
    public function getCountByField($where) {
        if (!isset($where['language'])) {
            $where['language'] = $this->language;
        }
        $count = ContentMeta::model()->countByAttributes($where);
        return $count;
    }

	/**
	 * Format yitu.content_meta info
	 * @return yitu.content_meta info
     * auto function, if you use this, please delete this line
	 */
	public function parseData($obj) {
        $data = $obj->attributes;
        foreach ($data as $key=>&$val) {
            if ($val === null) {
                $val = '';
            }
        }
        //内容的类型说明
        //0--文本, 1--图片, 2--视频, 3--音频
        $resource_map = Yii::app()->c->resource_map_type;
        $data['content'] = json_decode($data['content'], true);
        $service = new ResourceService();
        if ($data['content'] != null) {
            foreach ($data['content'] as &$item) {
                if ($item['type'] == 0) { //text
                    $item['face'] = '';
                } else if ($item['type'] == 1) { //image
                    $type = $resource_map[$item['type']];
                    $md = $item['content'];
                    $item['content'] = ParseUtil::getResourceUrlByTypeAndMd($type, $item['content']);
                    $item['face'] = '';
                } else if ($item['type'] == 2) { //video
                    $type = $resource_map[$item['type']];
                    $md = $item['content'];
                    $item['content'] = ParseUtil::getResourceUrlByTypeAndMd($type, $item['content']);
                    $item['face'] = ParseUtil::getResourceUrlByTypeAndMd('image', $item['face_md']);;
                } else if ($item['type'] == 3) { //audio
                    $type = $resource_map[$item['type']];
                    $md = $item['content'];
                    $item['content'] = ParseUtil::getResourceUrlByTypeAndMd($type, $item['content']);
                    $item['face'] = '';
                }
                unset($item['face_md']);
            }
        } else {
            $data['content'] = array();
        }
        if ($data['face'] != '') {
            $data['face'] = ParseUtil::getResourceUrlByTypeAndMd('image', $data['face']);
        }

        //get the name of the type
        $service = new ContentTypeService();
        $type = $service->getDataByPk($data['type_id']);
        if ($type) {
            $data['type_name'] = $type['name'];
        } else {
            $data['type_name'] = '';
        }

        unset($data['ctime']);
        unset($data['mtime']);
        unset($data['status']);
        unset($data['from_spots_type']);
        unset($data['from_spots_id']);
		return $data;
	}
}
