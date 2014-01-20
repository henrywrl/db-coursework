<?php

/*
* 首页登录模型
*/

//检测是否定义根路径
defined('ROOTPATH') or die("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

class index extends R{

	/*
	* 显示登录页面和处理登录
	*/

	function __construct(){
		//执行exec方法
		$this->exec();
	}

	private function exec(){

		//检测是否已在线，成立则跳转
		if($this->checkOnline(0)){
			header("Location: /choose");
			exit;
		}

		/*
		* 检测是否设置由POST提交的变量
		* 当前帐号  $_POST['mail']
		* 密码  $_POST['pass']
		* 若成立则进行登录步骤
		*/
		if(isset($_POST['mail'])&&isset($_POST['pass'])){

			//去除两端空格并重新声明变量、赋值
			$mail = trim($_POST['mail']);
			$pass = trim($_POST['pass']);

			//检测帐号是否为空值
			if($mail == '帐号' || $mail == ''){
				self::show(1,'');
			}else{
				//检测帐户是否为正确的电子邮箱地址
				if(!$this->checkMail($mail))self::show(2,$mail);
			}

			//检测密码是否为空值
			if($pass == ''){
				self::show(3,$mail);
			}

			//使用数据库
			$db = $this->MySQL();

			//获得时间戳
			$time = time();

			//检测查询数据库是否出错，成立则报错并停止下行
			$sql = $db->iQ("select `id`,`pass`,`class` from `user` where `mail` = '$mail' and `pass` = '".sha1($pass.PS)."'") or die('System error');

			//检测电邮地址是否为不存在，成立则停止下行并显示反馈
			if(!is_array($arr = $db->iR($sql))){
				self::show(4,$mail);
			}

			//检查用户阶级，2为正常，1为未激活，0为冻结
			if($arr[2] != 2){
				if($arr[2] == 0){
					self::show(5,$mail);
				}elseif($arr[2] == 1){
					self::show(6,$mail);
				}else{
					exit('System error 2');
				}
			}

			//声明变量id并赋值，该变量为会员ID号
			$id = $arr[0];

			//更新用户最后在线时间
			$db->iQ("update `user` set `time` = '$time' where `id` = $id") or die('System error 3');

			/*
			* 声明session 数组 online ，在线识别
			* 记录用户ID、凭证符、最后刷新时间
			* 凭证符，由用户名、密码+混熬常量组合，再使用sha1加密的一个字符串，预防恶意用户伪造ID
			* 
			*/
			$_SESSION['online'] = array($id,sha1($mail.$arr[1].PS),time());

			/*
			* 设置Cookie
			* 记住用户的帐号,有效期1个星期
			*/
			setcookie('MyMail',$mail,time()+604800);

			//销毁验证码session
			unset($_SESSION['verifyC']);

			//跳转到用户中心
			header("Location: /choose");
			exit;

		}

		//检测是否设置了Cookie,判断可用性
		$cookie = '';
		if(isset($_COOKIE['MyMail'])&&$this->checkMail($_COOKIE['MyMail']))$cookie = $_COOKIE['MyMail'];
		self::show(0,$cookie);
		
		
	}

	//显示页面
	static function show($userError,$mail){
		//包含indexPage.php文件
		require_once ROOTPATH.'static/page/indexPage.php';
		//执行indexPage方法
		indexPage($userError,$mail);
		//stop
		exit;
	}

}

$new = new index();