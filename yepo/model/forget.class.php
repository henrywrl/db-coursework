<?php

/*
* 找回密码模型
*/

//检测是否定义根路径
defined('ROOTPATH') or die("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

class forget extends R{

	/*
	* 显示找回密码页面和处理找回密码
	*/

	function __construct(){
		//执行exec方法
		$this->exec();
	}


	private function exec(){

		/*
		* 检测是否设置由POST提交的变量
		* 邮箱  $_POST['mail']
		* 验证码  $_POST['code']
		* 这个session是由生产验证码图片程序设置  $_SESSION['verifyC']
		* 若成立则进行重置步骤
		*/
		if(isset($_POST['mail'])&&isset($_POST['code'])&&isset($_SESSION['verifyC'])){

			//去除两端空格并重新声明变量、赋值
			$mail = trim($_POST['mail']);
			$code = trim($_POST['code']);

			//声明error数组，储存各步骤的检测情况
			$error = array();

			//检测电邮地址是否为空值
			if($mail == ''){

				$error['mail'] = 1;

			}else{

				//检测电邮地址格式是否正确
				if(!$this->checkMail($mail))$error['mail'] = 2;

			}

			//检测验证码是否为空值
			if($code == ''){

				$error['code'] = 1;

			}else{

				//检测验证码是否正确
				if($code != $_SESSION['verifyC'])$error['code'] = 2;

			}

			//检测error数组单元是否不=0，成立则停止下行并显示反馈
			if(count($error)){
				self::show($error);
			}

			//使用数据库
			$db = $this->MySQL();

			//检测查询数据库是否出错，成立则报错并停止下行
			if(!$sql = $db->iQ("select `id`,`name`,`class` from `user` where `mail` = '$mail'")){
				exit('System error ');
			}

			//检测电邮地址是否为不存在，成立则停止下行并显示反馈
			if(!is_array($arr = $db->iR($sql))){
				$error['mail'] = 3;
				self::show($error);
			}

			//检查用户阶级，2为正常，1为未激活，0为冻结
			if($arr[2] != 2){
				if($arr[2] == 0){
					$error['mail'] = 4;
					self::show($error);
				}elseif($arr[2] == 1){
					$error['mail'] = 5;
					self::show($error);
				}else{
					exit('System error 2');
				}
			}

			//生成一个8位数字符串的密码
			$newPass = $this->get_rand(8);

			//加入常量混熬和使用sha1加密
			$_newPass = sha1($newPass.PS);

			//声明变量id并赋值，该变量为会员ID号
			$id = $arr[0];
			//获得会员姓名
			$name = $arr[1];

			//更新该ID的密码
			if(!$db->iQ("update `user` set `pass` = '$_newPass' where `id` = $id")){
				exit('System error 3');
			}

			//设置邮件正文
			$mailContent = $this->returnMailContent($name,$newPass,$mail,'F');

			//判断发件，若返回为空则发送成功，不为空则失败
			if($this->_Send($mail,'密码找回',$mailContent)){
				exit('System error 4');
			}

			//这里设置的数组单元为提示处理成功
			$error['mail'] = 6;
			$error['suc'] = '"'.$this->returnMailFrom($mail).'"';

			self::show($error);

		}
		
		self::show('');

	}

	//显示页面
	static function show($error){

		//包含forgetPage.php文件
		require_once ROOTPATH.'static/page/forgetPage.php';
		//执行forgetPage方法
		forgetPage($error);
		//停止下行
		exit;

	}


}

$new = new forget(); //实例化类forget