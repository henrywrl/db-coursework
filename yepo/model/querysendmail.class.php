<?php

/*
* 二次发件模型
*/

//检测是否定义根路径
defined('ROOTPATH') or die("You don't have permission to access the path on this server.");
//包含R文件
require_once ROOTPATH.'yepo/R.php';

class querysendmail extends R{

	/*
	* 处理注册时二次发送激活邮件
	*/

	function __construct(){
		//执行exec方法
		$this->exec();
	}

	private function exec(){
		
		//检测是否设置二次发件标记和用户姓名
		if(!isset($_SESSION['sendFlag'])||!isset($_POST['user']))exit('1');

		//检测二次发件标记是否为正确的电子邮箱地址
		if(!$this->checkMail($_SESSION['sendFlag']))exit('2');

		//设置激活识别码
		$tid = sha1($_SESSION['sendFlag'].PS);

		//检测轮候册文件是否存在
		file_exists(ROOTPATH."user/list/".$tid.'.txt') or die('3');

		//设置激活url
		$ReceiptURL = 'http://'.DOMAIN.'/queryverify/register/'.$tid;

		//设置激活邮件正文
		$mailContent = $this->returnMailContent($_POST['user'],$ReceiptURL,$_SESSION['sendFlag'],$_GET['opera']);

		//判断发件，若返回0则发送成功，不为0则失败
		if($this->_Send($_SESSION['sendFlag'],'帐户激活',$mailContent)){
			echo'4';
		}else{
			echo'0';
		}

	}


}

$new = new querysendmail();