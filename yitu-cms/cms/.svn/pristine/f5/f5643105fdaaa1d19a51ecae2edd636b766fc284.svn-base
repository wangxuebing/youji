<?php
class IndexController extends Controller {

    public function actionIndex() {
		echo "this is api index";
    }

    /**
     * upload the file
     * image, video, audio
     */
	public function actionUploadFile() {
        set_time_limit(0);
        $type_array = array('image', 'video', 'audio', 'panorama');
        $type = $this->getParam('type', '');
        $device_id = $this->getParam('device_id', 'cms_upload');
        $file_name = $this->getParam('file_name', 'files');

        $spots_type = $this->getParam('spots_type', 0);
        $spots_id = $this->getParam('spots_id', 0);

        if (!isset($_FILES[$file_name]) || !in_array($type, $type_array) || $device_id == '' || $spots_type == 0 || $spots_id == 0 || !in_array($spots_type, array(1,2))) {
            $msg = json_encode($_FILES);
            $this->_data['error_code'] = self::STATUS_PARAMS_ERROR;
        } else {
            $service = new ResourceService();

            $file = $_FILES[$file_name]['tmp_name'][0];
            $name = $_FILES[$file_name]['name'][0];
            $ext = strval(pathinfo($name, PATHINFO_EXTENSION));
            $md = $service->setResource($type, $file, $ext, $device_id, 0, $name);
            if ($md == false) {
                Yii::log("{$type} file {$file} saved failed!", CLogger::LEVEL_ERROR);
                $this->_data['error_code'] = self::STATUS_OPERATION_FAILED;
            } else {
                //add link to spots resource link
                $link = array();
                $link['spots_type'] = $spots_type;
                $link['spots_id'] = $spots_id;
                $link['md'] = $md;
                $link['resource_type'] = 0;
                $type_map = Yii::app()->c->resource_map_type;
                foreach ($type_map as $k=>$v) {
                    if ($type == $v) {
                        $link['resource_type'] = $k;
                        break;
                    }
                }
                //get resource id
                $resource_meta_service = new ResourceMetaService();
                $where = array('md'=>$md);
                $metas = $resource_meta_service->getDataByField($where);
                if (count($metas) > 0) {
                    $link['resource_id'] = $metas[0]['id'];
                }
                $link_service = new ScenicSpotsResourceLinkService();
                $status = 0;
                $res = $link_service->addData($link, $status);
                if (!$res) {
                    $this->_data['error_code'] = self::STATUS_OPERATION_FAILED;
                } else {
                    //add panorama to spots info
                    $res = $this->_updatePanorama($spots_type, $spots_id, $md);
                    if (!$res) {
                        $this->_data['error_code'] = self::STATUS_OPERATION_FAILED;
                    } else {
                        $data = array();
                        $data['md'] = $md;
                        $data['name'] = $name;
                        $data['url'] = $service->getResourceUrl($type, $md);
                        $this->_data['data'] = $data;
                    }
                }
            }
        }

        $this->echoJson();
	}
    private function _updatePanorama($spots_type, $spots_id, $md, $op='add') {
        if ($spots_type == 1) {
            $service = new ScenicSpotsService();
        } else {
            $service = new SubScenicSpotsService();
        }

        $spots = $service->getDataByPk($spots_id);
        if ($spots) {
            $panorama = $spots['panorama'];
            $arr = array();

            if ($op == 'add') {
                if (count($panorama) > 0) {
                    foreach ($panorama as $pa) {
                        $arr[] = $pa['md'];
                    }
                }
                if (!in_array($md, $arr)) {
                    $arr[] = $md;
                }
            } else if ($op == 'delete') {
                if (count($panorama) > 0) {
                    foreach ($panorama as $pa) {
                        if ($pa['md'] != $md) {
                            $arr[] = $pa['md'];
                        }
                    }
                }
            }

            $obj = array();
            $obj['id'] = $spots_id;
            $obj['panorama'] = implode('|', $arr);
            $res = $service->updateData($obj);
            if ($res === false) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }

    }

    /**
     * delete the file
     */
	public function actionDeleteResource() {
        $md = $this->getParam('md', '');
        $spots_type = $this->getParam('type', 0);
        $spots_id = $this->getParam('id', 0);

        if ($md == '' || $spots_type == 0 || $spots_id == 0) {
            $this->_data['error_code'] = self::STATUS_PARAMS_ERROR;
        } else {
            //delete link
            $service = new ScenicSpotsResourceLinkService();
            $where = array('spots_type'=>$spots_type, 'spots_id'=>$spots_id, 'md'=>$md);
            $links = $service->getDataByField($where);

            $opt_flag = true; //表示全景图操作是否成功
            if (count($links) > 0) {
                $link = $links[0];
                if ($link['resource_type'] == 7) { //全景图，需要直接修改spots info
                    $result = $this->_updatePanorama($spots_type, $spots_id, $md, 'delete');
                    if (!$result) {
                        $opt_flag = false;
                    }
                }
            }

            if ($opt_flag) {
                $res = $service->delDataByField($where);

                if ($res) {
                    //delete resource meta and file
                    $count = $service->getCountByField(array('md'=>$md));
                    if ($count == 0) {
                        $meta_service = new ResourceMetaService();
                        $where = array('md'=>$md);
                        $meta = $meta_service->getDataByField($where);
                        if (count($meta) > 0) {
                            $meta = $meta[0];
                            //delete resource meta
                            $res = $meta_service->delDataByField($where);
                            if ($res) {
                                $map = Yii::app()->c->resource_map_type;
                                $type = '';
                                foreach ($map as $k=>$v) {
                                    if ($k == $meta['type_id']) {
                                        $type = $v;
                                    }
                                }
                                if ($type != '') {
                                    //delete file
                                    $resource_service = new ResourceService();
                                    $file = $resource_service->getFilePath($type, $md);
                                    unlink($file);

                                    //delete the different files
                                    $info = pathinfo($file);
                                    $info = pathinfo($md);
                                    $levels = array(1,2,3,4);
                                    if (isset($info['extension'])) {
                                        $tmp = rtrim($file, '.' . $info['extension']);
                                        foreach ($levels as $dev_level) {
                                            $tmp_file = "{$tmp}_{$dev_level}.{$info['extension']}";
                                            unlink($tmp_file);
                                        }
                                    } else {
                                        foreach ($levels as $dev_level) {
                                            $tmp_file = "{$tmp}_{$dev_level}";
                                            unlink($tmp_file);
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $this->_data['error_code'] = self::STATUS_OPERATION_FAILED;
                }
            } else {
                $this->_data['error_code'] = self::STATUS_OPERATION_FAILED;
            }
        }
        $this->echoJson();
    }

    public function actionGetLanguages() {
        $service = new SystemLanguageService();
        $data = $service->getAllData();

        if (count($data) > 0) {
            $this->_data['data'] = $data;
        } else {
            $this->_data['error_code'] = self::STATUS_RES_EMPTY;
        }

        $this->echoJson();
    }
    

    /**
     * Test the file upload
     */
    public function actionPage() {
        $this->render('index');
    }

    /**
     * Test the share
     */
    public function actionShare() {
        $this->render('share');
    }

}
