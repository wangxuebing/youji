<?php
class PassportService {
    const TOURIST_USER = 0; //游客用户
    const LOGIN_USER = 1; //登录用户

    private $_cache = null;

    public function __construct() {
        $cacheConf = Yii::app()->c->cache_server;
        $this->_cache = new Cache($cacheConf);
    }

    /**
     * 登录
     * @param $login, 用户注册名
     * @param $pwd, 用户密码
     * @param $status, 返回操作状态
     * @return array
     */
    public function login($login, $pwd, &$status) {
        $where = array('login' => $login, 'status'=>CmsUserService::STATUS_NORMAL);
        $service = new CmsUserService();
        $users = $service->getDataByField($where); 

        if (count($users) <= 0) { //用户不存在
            $status = Controller::STATUS_USERNAME_ERROR;
            return array();
        }
        $info = $users[0];

        if (md5($pwd) != $info['password']) { //密码不正确
            $status = Controller::STATUS_PASSWORD_ERROR;
            return array();
        }
        unset($info['password']);

        //生成token
        $data['expire_time'] = Yii::app()->c->user_cache_expire;
        $data['time'] = time();
        $data['user'] = $info;
        $data['token'] = $this->generateToken($login, $pwd);
        //存储token和用户信息的对应关系
        $this->_cache->set($data['token'], json_encode($data), Yii::app()->c->user_cache_expire);

        $status = Controller::STATUS_OK;
        return $data;
    }

    /**  
     * 根据token从db存储中获取登录用户的信息
     * @param $token
     * @return $data=array('status'=>0|30001,'user'=>array())
     *          0表示登录态有效, 30001表示登录过期需要重新登录
     */
    public function getUserByToken($token) {
        $data = array('status'=>0, 'user'=>array());
        if (empty($token)) {
            $data['status'] = Controller::STATUS_NOT_LOGIN;
        } else {
            $res = $this->_cache->get($token);
            if (empty($res)) {
                $data['status'] = Controller::STATUS_NOT_LOGIN;
            } else {
                $info = json_decode($res, true);
                if (time()-$info['time'] > $info['expire_time']) { //token已过期
                    $data['status'] = 30001;
                    //删除过期的token记录
                    $this->_cache->delete($token);
                } else {
                    $data['user'] = $info['user'];
                }    
            }    
        }

        return $data;
    }

    /**
     * 根据传入的两个字符串, 进行组合生成用户token
     * @return string, token串
     */
    private function generateToken($str1, $str2) {
        $tmp = $str1 . $str2 . time();
        return md5($tmp);
    }
}
