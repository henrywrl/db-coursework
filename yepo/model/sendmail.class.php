<?php

/*
* 显示发送激活邮件模型
*/

defined('ROOTPATH') or die("You don't have permission to access the path on this server.");
require_once ROOTPATH.'yepo/R.php';

class sendmail extends R{

	/*
	* 显示发送激活邮件页面
	*/

	function __construct(){
		//执行exec方法
		$this->exec();
	}


	private function exec(){

		//检测是否已设置二次发件session标记
		if(!isset($_SESSION['sendFlag']))exit("You don't have permission to access the path on this server.");

		//获得该邮箱地址的登录地址
		$emailGo = $this->returnMailFrom($_SESSION['sendFlag']);

		//包含sendRegiPage.php文件，显示页面
		require_once ROOTPATH.'static/page/sendRegiPage.php';
		sendRegiPage($emailGo);
		

	}


}

$new = new sendmail();