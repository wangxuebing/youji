<?php
class ApiModule extends CWebModule {
    public function init() {
//        $this->setViewPath ( APP_DIR . "/views/api/" );
//        $this->setLayoutPath ( APP_DIR . "/views/api/layout/" );
    }
    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction ( $controller, $action )) {
            return true;
        } else {
            return false;
        }
    }
}
