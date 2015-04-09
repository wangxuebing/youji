<?php
class PassportController extends Controller {

    /**
     * 登录接口
     */
    public function actionLogin() {
        $login = $this->getParam('login', '');
        $pwd = $this->getParam('password', '');

        if ($login =='' || $pwd=='') {
            $this->_data['error_code'] = self::STATUS_PARAMS_ERROR;
        } else {
            $service = new PassportService();
            $data = $service->login($login, $pwd, $status);
            if ($status == self::STATUS_OK) {
                $this->_data['data'] = $data;
            } else {
                $this->_data['error_code'] = $status;
            }
        }
        
        $this->echoJson();
    }

}
