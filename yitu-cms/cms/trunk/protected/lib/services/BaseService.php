<?php
/**
 * @author barontang
 * @date 2014-11-03 19
 * @the base service
 */

class BaseService extends CommonBaseService{
    const STATUS_NORMAL = 0;
    protected $language = 1;

    public function __construct() {
        if (isset($_REQUEST['language'])) {
            $language = intval($_REQUEST['language']);
            $allow_languages = Yii::app()->c->spots['language'];
            if (in_array($language, array_keys($allow_languages))) {
                $this->language = $language;
            }
        }
        $cache_server = Yii::app()->c->data_cache_server;
        parent::__construct($cache_server);
    }

}
