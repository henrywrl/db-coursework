<?php

/*
* 退出模型
*/

//检测是否定义根路径
defined('ROOTPATH') or die("You don't have permission to access the path on this server.");
//包含R文件
require_once ROOTPATH.'yepo/R.php';

class logout extends R{

	/*
	* 退出系统
	*/

	static function show(){
		//销毁在线识别session 数组
		unset($_SESSION['online']);
		//回到首页
		header("Location: /");
	}

}

logout::show();