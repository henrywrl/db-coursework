<?php

/*
* 管理首页模型
*/

//检测是否定义管理目录路径、根路径
if(!defined('ADPATH')||!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class index extends R{

	function __construct(){

		//检测是否登录
		if(!$this->checkOnlineR()){
			
			//注册adminpre session，相对防止恶意用户绕过登录前台，直接向登录处理提交数据
			$_SESSION['adminpre'] = 1;

			if(isset($_POST['user'])&&isset($_POST['pass'])&&isset($_POST['code'])){

				//去除两端空格并重新声明变量、赋值
				$user = trim($_POST['user']);
				$pass = trim($_POST['pass']);
				$code = trim($_POST['code']);

				//检测三项是否为空值

				if($user == '')self::show('loginPage',1);
				if($pass == '')self::show('loginPage',2);
				if($code == '')self::show('loginPage',3);

				//检测验证码是否正确
				if($code != $_SESSION['verifyC'])self::show('loginPage',4);

				//检测登录名和密码

				if($user!=AUSER||sha1($pass.PS)!=APASS)self::show('loginPage',5);

	
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

				//显示后台管理前台
				self::show('frontPage',0);


			}

			//显示登录页面
			self::show('loginPage',0);

		}else{
			
			//显示后台管理前台
			self::show('frontPage',0);

		}

	}

	static function show($opera,$feedback){
		include ADPATH.'static/page/'.$opera.'.php';
		$opera($feedback);
		exit;
	}

}

$new = new index();