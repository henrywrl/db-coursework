<?php

/*
* 程序入口文件
*/

//启动Session
session_start();

//定义根目录路径常量
define('ROOTPATH',dirname(__FILE__).DIRECTORY_SEPARATOR);

/*
* 获得动作符
* 三元算法
* 若为空即为首页
* 此行用@仰制错误，因为在启动报错时，若 $_GET['action'] 未设置的情况下，PHP或报错；若关闭报错，可去除@
*/

@$action = $_GET['action'] == '' ? 'index' : $_GET['action'];

if($action == 'getquestion' || $action == 'underway' || $action == 'answer'){}else{
	unset($_SESSION['list_underway']);
	unset($_SESSION['surplustime']);
	unset($_SESSION['underwayend']);
}

if($action == 'underwaya' || $action == 'getquestiona' || $action == 'answera'){}else{
	unset($_SESSION['list_anonymous']);
	unset($_SESSION['anonymousend']);
}

//向模型存放区寻找相应模型
file_exists($file = ROOTPATH.'yepo/model/'.$action.'.class.php') or die('Class '.$action.' is not found');

//包含该文件
include $file;
