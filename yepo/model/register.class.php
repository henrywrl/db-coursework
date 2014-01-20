<?php

/*
* 注册模型
*/

//检测是否定义根路径
defined('ROOTPATH') or die("You don't have permission to access the path on this server.");
//包含R文件
require_once ROOTPATH.'yepo/R.php';

class register extends R{

	/*
	* 显示注册页面和记录注册资料
	*/

	function __construct(){
		//执行exec方法
		$this->exec();
	}

	private function exec(){

		//检测是否已在线，成立则跳转
		if($this->checkOnline(0)){
			header("Location: /user");
			exit;
		}

		/*
		* 检测是否设置由POST提交的变量
		* 用户姓名、登录密码、确认密码、电邮地址、年龄、性别、年级、验证码
		* 若成立则进行登录步骤
		*/
		if(isset($_POST['user'])&&isset($_POST['pass'])&&isset($_POST['repass'])&&isset($_POST['mail'])&&isset($_POST['code'])&&isset($_POST['age'])&&isset($_POST['gender'])&&isset($_POST['grade'])){

			/*
			* 检测系统路径
			* 用户资料存放路径
			* 注册轮候册路径
			* 由生产验证码图片程序设置  $_SESSION['verifyC']
			* 若有1项异常则停止下行
			*/
			if(!isset($_SESSION['verifyC'])||!is_dir($listDir = ROOTPATH.'user/list/'))exit('System error');

			//去除两端空格并重新声明变量、赋值
			$user = trim($_POST['user']);
			$pass = trim($_POST['pass']);
			$repass = trim($_POST['repass']);
			$mail = trim($_POST['mail']);
			$code = trim($_POST['code']);
			$age = trim($_POST['age']);
			$gender = trim($_POST['gender']);
			$grade = trim($_POST['grade']);
			
			//声明error数组，储存各步骤的检测情况
			$error = array();


			//检测姓名是否空值
			if($user == ''){
				$error['user'] = 1;
			}else{
				//替换特殊符号
				$reg[0] = "\n";
				$reg[1] = "<";
				$reg[2] = ">";
				$reg[3] = "\'";
				$reg[4] = "\\\"";
				$exc[4] = "";
				$exc[3] = "&lt;";
				$exc[2] = "&gt;";
				$exc[1] = "";
				$exc[0] = "";
				$user = str_replace($reg,$exc,$user);
				if($user == '')$error['user'] = 1;

				//检测姓名长度
				if(mb_strlen($user) < 2)$error['user'] = 2;
				//截取
				$user = mb_substr($user,0,16,'utf-8');
				
				//重置 user POST值
				$_POST['user'] = $user;
				
			}

			//检测密码是否空值
			if($pass == ''){
				$error['pass'] = 1;
			}else{
				//检测密码长度
				if(isset($pass{7})&&!isset($pass{16})){
					//检测密码强度
					$safe = 0;
					if(preg_match("/[\d]/",$pass))$safe++;
					if(preg_match("/[a-z]/",$pass))$safe++;
					if(preg_match("/[A-Z]/",$pass))$safe++;
					if($safe < 2)$error['pass'] = 3;
				}else $error['pass'] = 2;
			}

			//检测确认密码是否空值
			if($repass == ''){
				$error['repass'] = 1;
			}else{
				//检测密码输入是否一致
				if($repass != $pass)$error['repass'] = 2;
			}
			
			//检测邮箱是否空值
			if($mail == ''){
				$error['mail'] = 1;
			}else{
				//检测邮箱格式
				if(!preg_match("/^[0-9a-z_\.]+\@([a-z0-9]+\.)+[a-z]{2,3}$/",$mail))$error['mail'] = 2;
			}

			//检测年龄是否空值
			if($age == ''){
				$error['age'] = 1;
			}else{
				//检测年龄格式
				if(!preg_match("/^[\d]+$/",$age))exit('System error 3');
				//检测年龄范围
				if($age > 11 && $age < 81){}else exit('System error 4');
			}

			//检测性别是否空值
			if($gender == ''){
				$error['gender'] = 1;
			}else{
				//检测性别
				if($gender == 'male'){
					$gender = 1;
				}elseif($gender == 'female'){
					$gender = 2;
				}else exit('System erorr 5');
			}

			//检测年级是否空值
			if($grade == ''){
				$error['grade'] = 1;
			}else{
				//检测年级
				switch($grade){
					case 'first year': $grade = 1; break;
					case 'second year': $grade = 2; break;
					case 'third year': $grade = 3; break;
					case 'fouth year': $grade = 4; break;
					default:exit('System error 6');
				}
			}

			//检测验证码是否空值
			if($code == ''){
				$error['code'] = 1;
			}else{
				//检测验证码
				if($code != $_SESSION['verifyC'])$error['code'] = 2;
			}

			//检测error数组单元是否不=0，成立则停止下行并显示反馈
			if(count($error))self::show($error);

			//使用数据库
			$db = $this->MySQL();

			//检测查询数据库是否出错，成立则报错并停止下行
			$sql = $db->iQ("select `mail` from `user` where `mail` = '$mail'") or die('System error');

			//检测电邮地址是否存在，成立则停止下行并显示反馈
			if(is_array($arr = $db->iR($sql))){
				$error['mail'] = 3;
				self::show($error);
			}

			//获得时间戳
			$time = time();

			/*
			* 插入新用户
			* 邮箱，
			* 密码(密码+常量PS组合，再使用sha1加密)
			* 姓名
			* 年龄
			* 性别
			* 年级
			* 阶级，
			更新时间
			*/
			$db->iQ("insert into `user`(`id`,`mail`,`pass`,`name`,`age`,`gender`,`grade`,`class`,`time`)values(NULL,'$mail','".sha1($pass.PS)."','".mysql_real_escape_string($user)."','$age','$gender','$grade','1','$time')") or die('System error 2');

			//获得新用户的ID
			$id = $db->iD();

			//设置激活识别码
			$tid = sha1($mail.PS);

			//新建注册轮候册文件名
			$listfilename = $listDir.$tid.'.txt';

			//设置轮候册文件数据
			$listFileData = $mail;

			//创建轮候册文件
			if(!$this->writeDocument($listfilename,'w+',$listFileData)){
				$this->deleteData($id);
				exit('System error 8');
			}

			//设置激活url
			$ReceiptURL = 'http://'.DOMAIN.'/queryverify/register/'.$tid;

			//设置激活邮件正文
			$mailContent = $this->returnMailContent($user,$ReceiptURL,$mail,'R');

			//设置一个session标记，给再次发送邮件准备
			$_SESSION['sendFlag'] = $mail;

			//判断发件，若返回为空则发送成功，不为空则失败
			if($this->_Send($mail,'activate account',$mailContent)){
				unlink($listfilename);
				$this->deleteData($id);
				exit('System error 9');
			}

			//跳转到显示发送激活邮件页面
			exit('<html><body onload="document.getElementsByTagName(\'form\')[0].submit()"><form action="/sendmail" method="POST"><input type="hidden" value="'.$grade.'" name="grade"><input type="hidden" value="'.$user.'" name="user"></form></body></html>');

		}

		self::show(0,'');
		
	}

	//显示页面
	static function show($userError){
		//包含registerPage.php文件
		require_once ROOTPATH.'static/page/registerPage.php';
		//执行registerPage方法
		registerPage($userError);
		//停止下行
		exit;
	}

	//后续处理出错时，删除之前插入的数据
	private static function deleteData($id){
		$db->iQ("delete from `user` where `id` = '$id'");
	}

}

$new = new register();