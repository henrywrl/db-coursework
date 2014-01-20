<?php

/*
* 选择动向模型
*/

//检测是否定义根路径
defined('ROOTPATH') or die("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

class choose extends R{

	/*
	* 选择动向
	*/

	function __construct(){
		//执行exec方法
		$this->exec();
	}

	private function exec(){

		//检测是否已在线并获得用户名称
		if(!$name = $this->checkOnline(0)){
			exit('<meta charset="utf-8"><script>alert("You are offline, please login");location.href="/";</script>');
		}print_r($_SESSION);

		//包含indexPage.php文件
		require_once ROOTPATH.'static/page/choosePage.php';
		//执行indexPage方法
		choosePage($name);
		
	}

}

$new = new choose();