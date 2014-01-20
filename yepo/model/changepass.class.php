<?php

/*
* 修改密码模型
*/

//检测是否定义根路径
defined('ROOTPATH') or die("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

class changepass extends R{

	/*
	* 显示修改密码页面和处理修改密码
	*/

	function __construct(){
		//执行exec方法
		$this->exec();
	}

	private function exec(){
		
		//检测是否已离线，成立则跳转
		if(!$this->checkOnline(0)){
			exit('<meta charset="utf-8"><script>alert("You are offline, please login");location.href="/";</script>');
		}

		//声明error数组，储存各步骤的检测情况
		$error = array();

		//声明变量id并赋值，该变量为会员ID号
		$id = $_SESSION['online'][0];

		/*
		* 检测是否设置由POST提交的变量
		* 当前密码  $_POST['pass']
		* 新密码  $_POST['repass']
		* 若成立则进行修改步骤
		*/
		if(isset($_POST['pass'])&&isset($_POST['repass'])&&isset($_POST['repass2'])){

			//去除两端空格并重新声明变量、赋值
			$pass = trim($_POST['pass']);
			$repass = trim($_POST['repass']);
			$repass2 = trim($_POST['repass2']);

			//检测当前密码是否为空值
			if($pass == ''){

				$error['pass'] = 1;

			}

			//检测新密码是否为空值
			if($repass == ''){

				$error['repass'] = 1;

			}else{

				//检测新密码长度是否正确
				if(isset($repass{7})&&!isset($repass{16})){

					//设置安全度
					$safe = 0;

					//若符合检测则安全度递增
					if(preg_match("/[\d]/",$repass))$safe++;
					if(preg_match("/[a-z]/",$repass))$safe++;
					if(preg_match("/[A-Z]/",$repass))$safe++;

					//检测安全度是否小于2
					if($safe < 2){

						$error['repass'] = 3;

					}else{

						//检测新密码是否与当前密码相等
						if($pass == $repass)$error['repass'] = 4;

					}

				}else $error['repass'] = 2;

			}

			//检测确认密码是否为空值
			if($repass2 == ''){

				$error['repass2'] = 1;

			}else{

				//检测新密码输入是否一样
				if($repass2 != $repass)$error['repass2'] = 2;

			}

			//检测error数组单元是否不=0，成立则停止下行并显示反馈
			if(count($error) > 0)self::show($error);

			//使用数据库
			$db = $this->MySQL();
			
			//检测查询数据库是否出错，成立则报错并停止下行
			$sql = $db->iQ("select `id` from `user` where `id` = '$id' and `pass` = '".sha1($pass.PS)."'") or die("System error 2");
			
			//检测电邮地址是否为不存在，成立则停止下行并显示反馈
			if(!is_array($arr = $db->iR($sql))){
				$error['pass'] = 2;
				self::show($error);
			}

			//更新该ID的密码
			if(!$db->iQ("update `user` set `pass` = '".sha1($repass.PS)."' where `id` = '$id'"))exit('System error 4');
			
			//这里设置的数组单元为提示处理成功
			$error['suc'] = 1;
			self::show($error);

		}

		self::show('');

	}

	//显示页面
	static function show($userError){

		//包含changepassPage.php文件
		require_once ROOTPATH.'static/page/changepassPage.php';
		//执行changepassPage方法
		changepassPage($userError);
		//停止下行
		exit;
		
	}


}

$new = new changepass();