<?php

/*
* 后台管理程序入口文件
*/

//启动Session
session_start();

//定义后台管理目录路径常量
define('ADPATH',dirname(__FILE__).'/');

//定义根目录路径常量
define('ROOTPATH',dirname(dirname(__FILE__)).'/');


/*
* 获得动作符
* 三元算法
* 若为空即为首页
* 此行用@仰制错误，因为在启动报错时，若 $_GET['action'] 未设置的情况下，PHP或报错；若关闭报错，可去除@
*/

@$action = $_GET['action'] == '' ? 'index' : $_GET['action'];

//向模型存放区寻找相应模型
file_exists($file = ADPATH.'model/'.$action.'.class.php') or die('Class '.$action.' is not found');

//包含该文件
include $file;