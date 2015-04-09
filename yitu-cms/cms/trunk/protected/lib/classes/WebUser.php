<?php
/*
 * 用户类，用于用户登录、记录用户信息等等
 */

class WebUser extends CWebUser {

	public function init() {
        if (isset($_POST['PHPSESSID'])) {
            $session = Yii::app()->getSession();
            $session->close();
            $session->sessionID = $_POST['PHPSESSID'];
            $session->open();
        }
		parent::init();
	}

	public function setState($key, $value, $defaultValue=null) {
		parent::setState($key, $value, $defaultValue);
		if ($value !== $defaultValue && in_array($key, array('_user'), true)) {
			$this->saveToCookie(Yii::app()->c->loginExpire);
		}
	}

	protected function afterLogout() {
		$cookie = Yii::app()->getRequest()->getCookies()->itemAt(Yii::app()->c->cookieKey);
		$cookie->domain = Yii::app()->c->cookieDomain;
		Yii::app()->getRequest()->getCookies()->remove(Yii::app()->c->cookieKey);
	}

	protected function afterLogin($fromCookie) {
		//如果不是从cookie登录的，那么记录一下最后登录时间和IP
		if (!$fromCookie) {
		}
		$this->setLoginTryCount(null);
	}

	protected function restoreFromCookie() {
		parent::restoreFromCookie();
		if ($this->isGuest && Yii::app()->getRequest()->getCookies()->itemAt(Yii::app()->c->cookieKey) !== null) {
		}
	}

	public function getLoginTryCount() {
		return $this->getState('loginTryCount');
	}

	public function setLoginTryCount($loginTryCount) {
		$this->setState('loginTryCount', $loginTryCount);
	}

	public function getReturnUrl($defaultUrl = array('/')) {
		return CHtml::normalizeUrl($defaultUrl);
	}

	public function getIsGuest() {
		if ($this->getState('_user') !== null) {
			return false;
		}
        return true;
	}
}
