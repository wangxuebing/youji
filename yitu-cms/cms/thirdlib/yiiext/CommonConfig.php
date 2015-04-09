<?php
class CommonConfig {
    public $data;
    public function init() {
    }
    public function __get($name) {
        return $this->data [$name];
    }
    public function __set($name, $value) {
        $this->data [$name] = $value;
    }
    public function __isset($name) {
        return isset ( $this->data [$name] );
    }
}
