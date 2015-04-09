<?php
class TaskController extends Controller {

    /**
     * Add task
     *
     */
    public function actionAddTask() {
        $user = $this->_user;
        $spots_name = $this->getParam('spots_name', '');
        $stime = $this->getParam('stime', '');
        $etime = $this->getParam('etime', '');

        if (count($user) <= 0) {
            $this->_data['error_code'] = self::STATUS_NOT_LOGIN;
        } else if ($spots_name == '' || $stime == '' || $etime == '') {
            $this->_data['error_code'] = self::STATUS_PARAMS_ERROR;
        } else {
            //check the spots wheather existed
            $where = array('name'=>$spots_name);
            $spots_service = new ScenicSpotsService();
            $items = $spots_service->getDataByFieldWithoutLanguage($where);

            $data = array();
            $data['spots_name'] = $spots_name;
            $data['create_user_id'] = $user['id'];
            $data['stime'] = $stime;
            $data['etime'] = $etime;
            $data['province_id'] = 0;
            $data['city_id'] = 0;
            $data['area_id'] = 0;
            if (count($items) > 0) {
                $spots = $items[0];
                $data['province_id'] = $spots['province_id'];
                $data['city_id'] = $spots['city_id'];
                $data['area_id'] = $spots['area_id'];
                $data['spots_id'] = $spots['id'];

                $task_service = new CmsTaskService();
                $status = 0;
                $id = $task_service->addData($data, $status);
                if ($status == -1) {
                    $this->_data['error_code'] = self::STATUS_OPERATION_REPEAT;
                } else if (!$id) {
                    $this->_data['error_code'] = self::STATUS_OPERATION_FAILED;
                } else {
                    $data['id'] = $id;
                    $this->_data['data'] = $data;
                }

                //add all languages spots
                $languages = array_keys(Yii::app()->c->spots['language']);
                $existed = array();
                foreach ($items as $item) {
                    $existed[] = $item['language'];
                }
                foreach ($languages as $l) {
                    if (!in_array($l, $existed)) {//add the language spots
                        $obj = array();
                        $obj['id'] = $spots['id'];
                        $obj['name'] = $spots_name;
                        $obj['language'] = $l;
                        $status = 0;
                        $spots_id = $spots_service->addData($obj, $status);
                    }
                }
            } else {
                $spots = array();
                $spots['name'] = $spots_name;
                $spots['language'] = isset($_REQUEST['language']) ? $_REQUEST['language'] : 1; //chinese for default
                $status = 0;
                $spots_id = $spots_service->addData($spots, $status);
                if ($spots_id) {
                    $data['spots_id'] = $spots_id;

                    $task_service = new CmsTaskService();
                    $status = 0;
                    $id = $task_service->addData($data, $status);
                    if ($status == -1) {
                        $this->_data['error_code'] = self::STATUS_OPERATION_REPEAT;
                    } else if (!$id) {
                        $this->_data['error_code'] = self::STATUS_OPERATION_FAILED;
                    } else {
                        $data['id'] = $id;
                        $this->_data['data'] = $data;
                    }

                    //add all languages spots
                    $languages = array_keys(Yii::app()->c->spots['language']);
                    foreach ($languages as $l) {
                        if ($l != $spots_id) {//add the language spots
                            $obj = array();
                            $obj['id'] = $spots_id;
                            $obj['name'] = $spots_name;
                            $obj['language'] = $l;
                            $status = 0;
                            $spots_id = $spots_service->addData($obj, $status);
                        }
                    }
                } else {
                    $this->_data['error_code'] = self::STATUS_OPERATION_FAILED;
                }
            }
        }

        $this->echoJson();
    }

    /**
     * Get the list of my tasks
     */
    public function actionGetMyTask() {
        $user = $this->_user;
        $sn = $this->getParam('sn', 0);
        $nu = $this->getParam('nu', 10);

        if (count($user) <= 0) {
            $this->_data['error_code'] = self::STATUS_NOT_LOGIN;
        } else {
            $where = array('create_user_id'=>$user['id']);
            $service = new CmsTaskService();
            $tasks = $service->searchByCondition($where, $sn, $nu);

            if (count($tasks) > 0) {
                $this->_data['count'] = $service->getCountByField($where);
                $this->_data['data'] = $tasks;
            } else {
                $this->_data['error_code'] = self::STATUS_RES_EMPTY;
            }
        }

        $this->echoJson();
    }

    /**
     * Search the tasks
     */
    public function actionSearchByKeyword() {
        $kw = $this->getParam('kw', '');
        $sn = intval($this->getParam('sn', 0));
        $nu = intval($this->getParam('nu', 10));
        $status = intval($this->getParam('status', -1));
        $province_id = intval($this->getParam('province_id', 0));
        $city_id = intval($this->getParam('city_id', 0));
        $stime = $this->getParam('stime', '');
        $etime = $this->getParam('etime', '');

        if ($kw == '' && $status == -1 && $province_id == 0 && $city_id == 0 && $stime == '' && $etime == '') {
            $this->_data['error_code'] = self::STATUS_PARAMS_ERROR;
        } else {
            $where = array();
            if ($status != -1) {
                $where['status'] = $status;
            }
            if ($province_id != 0) {
                $where['province_id'] = $province_id;
            }
            if ($city_id != 0) {
                $where['city_id'] = $city_id;
            }
            if ($stime != '') {
                $where['stime'] = $stime;
            }
            if ($etime != '') {
                $where['etime'] = $etime;
            }

            $service = new CmsTaskService();
            $res = $service->searchByKw($kw, $sn, $nu, $where);
            $data = $res['data'];
            $count = $res['count'];

            if (count($data) > 0) {
                $this->_data['data'] = $data;
                $this->_data['count'] = $count;
            } else {
                $this->_data['error_code'] = self::STATUS_RES_EMPTY;
            }
        }

        $this->echoJson();
    }
}
