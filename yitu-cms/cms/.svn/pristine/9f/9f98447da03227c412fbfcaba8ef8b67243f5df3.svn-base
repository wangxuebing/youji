<?php
class LineController extends Controller {
    /**
     * Get the sub scenic spots by scenic spots id
     */
    public function actionGetSubScenicSpotsByPid() {
        $scenic_spots_id = $this->getParam('spots_id', 0);

        if ($scenic_spots_id == 0) {
            $this->_data['error_code'] = self::STATUS_PARAMS_ERROR;
        } else {
            $service = new SubScenicSpotsService();
            $where = array('scenic_spots_id'=>$scenic_spots_id, 'status'=>SubScenicSpotsService::STATUS_NORMAL);
            $data = $service->getDataByField($where);
            if (count($data) <= 0) {
                $this->_data['error_code'] = self::STATUS_RES_EMPTY;
            } else {
                $this->_data['data'] = $data;
            }
        }

        $this->echoJson();
    }

    /**
     * Create line
     * @param, $spots_id
     * @param, $name
     * @param, $description
     * @param, $time
     * @param, $line, longitude,latitude,sub_id|longitude,latitude,sub_id|longitude,latitude,sub_id
     */
    public function actionCreateLine() {
        $spots_id = $this->getParam('spots_id', 0);
        $name = $this->getParam('name', '');
        $description = $this->getParam('description', '');
        $time = $this->getParam('time', '');
        $line = $this->getParam('line', '');

        if ($spots_id == 0 || $name == '' || $description =='' || $time == '' || $line == '') {
            $this->_data['error_code'] = self::STATUS_PARAMS_ERROR;
        } else {
            $service = new LineService();
            $obj = array();
            $obj['type'] = LineService::TYPE_SPOTS;
            $obj['type_id'] = $spots_id;
            $obj['name'] = $name;
            $obj['time'] = $time;
            $obj['description'] = $description;

            //get lines
            $parts = explode('|', $line);
            $points = array();
            $false_flag = false;
            $sub_ids = array();
            foreach ($parts as $part) {
                $info = explode(',', $part);
                if (count($info) == 3) {
                    $tmp = array();
                    $tmp['longitude'] = $info[0];
                    $tmp['latitude'] = $info[1];
                    $tmp['spots_id'] = $info[2];
                    $points[] = $tmp;
                    if ($info[2] != 0) {
                        $sub_ids[] = $info[2];
                    }
                } else {
                    $false_flag = true;
                    break;
                }
            }

            if ($false_flag) { //lines param has error
                $this->_data['error_code'] = self::STATUS_PARAMS_ERROR;
            } else {
                $sub_service = new SubScenicSpotsService();
                $sub_spots = $sub_service->getDataByIds($sub_ids);
                foreach ($points as &$pt) {
                    $pt['name'] = '';
                    foreach ($sub_spots as $sub) {
                        if ($pt['spots_id'] == $sub['id']) {
                            $pt['name'] = $sub['name'];
                        }
                    }
                }
                $obj['line'] = json_encode($points);
                $status = 0;
                $res = $service->addData($obj, $status);
                if ($status == -1) {
                    $this->_data['error_code'] = self::STATUS_OPERATION_REPEAT;
                } else if ($status == -2) {
                    $this->_data['error_code'] = self::STATUS_OPERATION_FAILED;
                }
            }
        }

        $this->echoJson();
    }

    /**
     * Update line
     * @param, $id
     * @param, $name
     * @param, $description
     * @param, $time
     * @param, $line, longitude,latitude,sub_id|longitude,latitude,sub_id|longitude,latitude,sub_id
     */
    public function actionUpdateLine() {
        $id = $this->getParam('id', 0);
        $name = $this->getParam('name', '');
        $description = $this->getParam('description', '');
        $time = $this->getParam('time', '');
        $line = $this->getParam('line', '');

        if ($id == 0 || $name == '' || $description =='' || $time == '' || $line == '') {
            $this->_data['error_code'] = self::STATUS_PARAMS_ERROR;
        } else {
            $service = new LineService();
            $obj = array();
            $obj['id'] = $id;
            $obj['name'] = $name;
            $obj['time'] = $time;
            $obj['description'] = $description;

            //get lines
            $parts = explode('|', $line);
            $points = array();
            $false_flag = false;
            $sub_ids = array();
            foreach ($parts as $part) {
                $info = explode(',', $part);
                if (count($info) == 3) {
                    $tmp = array();
                    $tmp['longitude'] = $info[0];
                    $tmp['latitude'] = $info[1];
                    $tmp['spots_id'] = $info[2];
                    $points[] = $tmp;
                    if ($info[2] != 0) {
                        $sub_ids[] = $info[2];
                    }
                } else {
                    $false_flag = true;
                    break;
                }
            }

            if ($false_flag) { //lines param has error
                $this->_data['error_code'] = self::STATUS_PARAMS_ERROR;
            } else {
                $sub_service = new SubScenicSpotsService();
                $sub_spots = $sub_service->getDataByIds($sub_ids);
                foreach ($points as &$pt) {
                    $pt['name'] = '';
                    foreach ($sub_spots as $sub) {
                        if ($pt['spots_id'] == $sub['id']) {
                            $pt['name'] = $sub['name'];
                        }
                    }
                }
                $obj['line'] = json_encode($points);
                $res = $service->updateData($obj);
                if (!$res) {
                    $this->_data['error_code'] = self::STATUS_OPERATION_FAILED;
                }
            }
        }

        $this->echoJson();
    }

    /**
     * Get line detail info
     * @param, $id
     */
    public function actionGetLineDetail() {
        $id = $this->getParam('id', 0);
        if ($id == 0) {
            $this->_data['error_code'] = self::STATUS_PARAMS_ERROR;
        } else {
            $service = new LineService();
            $line = $service->getDataByPk($id);
            if ($line) {
                $this->_data['data'] = $line;
            } else {
                $this->_data['error_code'] = self::STATUS_RES_EMPTY;
            }
        }

        $this->echoJson();
    }

    /**
     * Get lines by spots id
     * @param, $spots_id
     */
    public function actionGetLinesBySpotsId() {
        $spots_id = $this->getParam('spots_id', 0);
        if ($spots_id == 0) {
            $this->_data['error_code'] = self::STATUS_PARAMS_ERROR;
        } else {
            $service = new LineService();
            $where = array('type'=>LineService::TYPE_SPOTS, 'type_id'=>$spots_id);
            $lines = $service->getDataByField($where);
            if (count($lines) > 0) {
                $this->_data['data'] = $lines;
            } else {
                $this->_data['error_code'] = self::STATUS_RES_EMPTY;
            }
        }

        $this->echoJson();
    }

}
