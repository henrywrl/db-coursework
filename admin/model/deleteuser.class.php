<?php

/*
* 删除用户模型
*/

//检测是否定义管理目录路径、根路径
if(!defined('ADPATH')||!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class deleteuser extends R{

	/*
	* 删除用户
	*/

	function __construct(){
		//执行exec
		$this->exec();
	}

	private function exec(){
		
		//检测是否登录
		if(!$this->checkOnlineR())$this->json(1);

		//检测必须值 $_POST['id']是否设置
		if(!isset($_POST['id']))$this->json(2);

		//去除两边空格，重新赋值
		$id = trim($_POST['id']);

		//检测tid是否为数字
		if(!preg_match("/^[\d]+$/", $id))$this->json(3);

		//使用数据库
		$db = $this->MySQL();

		//执行删除
		if(!$db->iQ("delete from `user` where `id` = $id"))$this->json(4);

		$this->json(0);

	}

}

$new = new deleteuser();