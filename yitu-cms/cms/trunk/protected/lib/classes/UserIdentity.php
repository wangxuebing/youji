<?php

/*
 * 用户登录验证
 */

class UserIdentity extends CUserIdentity {
    public $checkPassword;
    public $status;

    public function setCheckPassword() {
        $this->checkPassword = true;
    }

	public function getId() {
		$user = $this->getState('_user');
		return $user ? $user['schoolId'] : null;
	}

	//验证登录
	public function authenticate() {


	}

    public function getStatus() {
        return $this->status;
    }
}
