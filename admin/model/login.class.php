<?php

/*
* 管理首页模型
*/

//检测是否定义管理目录路径、根路径
if(!defined('ADPATH')||!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class login extends R{

	/*
	*
	* 处理管理员登录
	* 该类使用R类的json方法，返回JSON数据
	*
	*/

	function __construct(){
		//执行exec方法
		$this->exec();
	}

	private function exec(){

		/*
		* 检测提交的超级变量
		* 用户名 $_POST['user']
		* 密码 $_POST['pass']
		* 验证码 $_POST['code']
		* 由生产验证码图片程序生成的验证码session $_SESSION['verifyC']
		*/
		if(!isset($_POST['user'])||!isset($_POST['pass'])||!isset($_POST['code'])||!isset($_SESSION['verifyC']))$this->json(1);
		
		//去除两端空格并重新声明变量、赋值
		$user = trim($_POST['user']);
		$pass = trim($_POST['pass']);
		$code = trim($_POST['code']);
		
		//检测三项是否为空值
		if($user == '' || $pass == '' || $code == '')$this->json(2);

		//检测验证码是否正确
		if($code != $_SESSION['verifyC'])$this->json(3);

		//检测登录名和密码
		if($user!=AUSER||sha1($pass.PS)!=APASS)$this->json(4);

		/*
			* 声明session 数组 online ，在线识别
			* 记录用户ID、凭证符、最后刷新时间
			* 凭证符，由用户名、密码+混熬常量组合，再使用sha1加密的一个字符串，预防恶意用户伪造ID
			* 
			*/
		$_SESSION['admin'] = array(1,sha1(AUSER.APASS.PS),time());
		
		//销毁session
		unset($_SESSION['adminpre']);
		unset($_SESSION['verifyC']);

		//返回成功，0
		$this->json(0);

	}

}

$new = new login();