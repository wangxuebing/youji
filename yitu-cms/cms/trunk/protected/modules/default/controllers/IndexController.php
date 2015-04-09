<?php
class IndexController extends Controller {
    public $layout = "main";


    //首页
    public function actionIndex() {
        $this->render('index', array(
        ));
    }

    //任务列表页
    public function actionTask() {
        $this->render('task', array(
        ));
    }

    //登录页面
    public function actionLogin() {
        $this->render('login', array(
        ));
    }

    //编辑页面
    public function actionEditor() {
        $this->render('editor', array(
        ));
    }

    //编辑页面
    public function actionMember() {
        $this->render('data_member', array(
        ));
    }

    public function actionDemo() {
        $this->render('demo', array(
        ));
    }

}
