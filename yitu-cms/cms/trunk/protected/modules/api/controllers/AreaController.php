<?php
class AreaController extends Controller {

    public function actionGetAreaList() {
        $pid = $this->getParam('pid', 0);

        $service = new AreaService();
        $this->_data['data'] = $service->getDataByFieldFromCache(array('pid'=>$pid));

        $this->echoJson();
    }
}
