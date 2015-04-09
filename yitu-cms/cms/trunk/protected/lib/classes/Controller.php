<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {
    const STATUS_OK = 0;
    const STATUS_OPERATION_FAILED = 10001;
    const STATUS_RES_EMPTY = 10002;
    const STATUS_RES_OVERDUE = 10003;
    const STATUS_PARAMS_ERROR = 20001;
    const STATUS_OPERATION_REPEAT = 20002;
    const STATUS_NOT_LOGIN = 30001;
    const STATUS_CAPTCHA_ERROR = 30002; //验证码错误
    const STATUS_USERNAME_ERROR = 30003; //用户名不存在, 即手机号未注册
    const STATUS_PASSWORD_ERROR = 30004; //密码错误
    const STATUS_COMPETENCE_ERROR = 30005; //没有权限
    const STATUS_SERVER_ERROR = 40001; //服务器繁忙

    protected $_statusMsg = array(
                    self::STATUS_OK          =>  'Ok!',
                    self::STATUS_OPERATION_FAILED    =>  'Error: operation failed!',
                    self::STATUS_RES_EMPTY   =>  'Error: result is empty!',
                    self::STATUS_RES_OVERDUE   =>  'Error: result is overdue!',
                    self::STATUS_NOT_LOGIN    =>  'Error: user not login!',
                    self::STATUS_PARAMS_ERROR    =>  'Error: params error!',
                    self::STATUS_OPERATION_REPEAT    =>  'Error:operation repeat error!',
                    self::STATUS_CAPTCHA_ERROR    =>  'Error:captcha error!',
                    self::STATUS_USERNAME_ERROR    =>  'Error:user name error!',
                    self::STATUS_PASSWORD_ERROR    =>  'Error:password error!',
                    self::STATUS_COMPETENCE_ERROR    =>  'Error:competence error!',
                    self::STATUS_SERVER_ERROR    =>  'Error:server is busying!',
              );
    protected $_data = array();
    protected $_user = array();
    protected $_cache_map = array();
    protected $_params = array();

    protected function beforeAction($action) {
        $module = $this->getModule()->getId();
        $token = $this->getParam('token', '');
        if ($token != '' && $module == 'api') {
            $service = new PassportService();
            $data = $service->getUserByToken($token);
            if ($data['status'] == 0) {
                $this->_user = $data['user'];
            }
        }
        $this->_data['error_code'] = self::STATUS_OK;

        parent::beforeAction($action);
        return true;
    }

    protected function getStatusMsg($status) {
        if( isset( $this->_statusMsg[$status] ) ) {
            return $this->_statusMsg[$status];
        }
        return 'status code is undefined';
    }

    protected function echoJson($var = '') {
        if ($var == '' && isset($_GET['jsonVar'])) {
            $var = trim($_GET['jsonVar']);
        }
        $this->_data['error_msg'] = $this->getStatusMsg( $this->_data['error_code'] );
        header ( "Access-Control-Allow-Origin: *" ); //允许跨域的post提交
        header ( "Content-type:application/json; charset=utf-8" );
        if ($var == '') {
            echo json_encode ( $this->_data );
        } else {
            echo 'var ' . $var . '=' . json_encode ( $this->_data );
        }
    }

    //Get json content, no return the json to user
    protected function getJson($data, $var = '') {
        $data['error_msg'] = $this->getStatusMsg( $data['error_code'] );
        if ($var == '') {
            return json_encode ( $data );
        } else {
            return 'var ' . $var . '=' . json_encode ( $data );
        }
    }

    //对于一次请求中多次使用的变量，做内存缓存
    protected function getFromCacheMap($key) {
        if (isset($this->_cache_map[$key])) {
            return $this->_cache_map[$key];
        }
        return false;
    }
    protected function setToCacheMap($key, $val) {
        $this->_cache_map[$key] = $val;
    }

    /**
     * 判断token是否有效
     * @return 有效返回user信息, 无效返回false
     */
    protected function checkToken($token) {
        $passportService = new PassportService();
        $data = $passportService->getUserByToken($token);

        if ($data['status'] != 0) {
            return false;
        } else {
            return $data['user'];
        }
    }

    public function getParam($name, $default='') {
        if (isset($_REQUEST[$name])) {
            return $_REQUEST[$name];
        }
        return $default;
    }

	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = 'main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = array();
	private $_description;
	private $_keywords;

	public function getDescription() {
		if (!isset($this->_description)) {
			$this->_description = Yii::app()->c->defaultDescription;
		}
		return $this->_description;
	}

	public function setDescription($description) {
		$this->_description = htmlspecialchars($description, ENT_COMPAT, 'UTF-8', false);
	}

	public function getKeywords() {
		if (!isset($this->_keywords)) {
			$this->_keywords = Yii::app()->c->defaultKeywords;
		}
		return $this->_keywords;
	}

	public function setKeywords($keywords) {
		$this->_keywords = htmlspecialchars($keywords, ENT_COMPAT, 'UTF-8', false);
	}

	public function iGet($name, $def = 0) {
		return isset($_GET[$name]) ? intval($_GET[$name]) : $def;
	}
		
	public function iPost($name, $def = 0) {
		return isset($_POST[$name]) ? intval($_POST[$name]) : $def;
	}
		
	public function iRequest($name, $def = 0) {
		return isset($_REQUEST[$name]) ? intval($_REQUEST[$name]) : $def;
	}

	public function sGet($name, $def = '') {
		return isset($_GET[$name]) ? trim($_GET[$name]) : $def;
	}

	public function sPost($name, $def = '') {
		return isset($_POST[$name]) ? trim($_POST[$name]) : $def;
	}

	public function sRequest($name, $def = '') {
		return isset($_REQUEST[$name]) ? trim($_REQUEST[$name]) : $def;
	}

	public function aGet($name, $def = array()) {
		return isset($_GET[$name]) ? (array)$_GET[$name] : $def;
	}

	public function aPost($name, $def = array()) {
		return isset($_POST[$name]) ? (array)$_POST[$name] : $def;
	}

	public function aRequest($name, $def = array()) {
		return isset($_REQUEST[$name]) ? (array)$_REQUEST[$name] : $def;
	}

	public function render($view, $data = null, $return = false) {
		$action = $this->getAction()->getId();
		return parent::render($view, $data, $return);
	}

}
