<?php

/*
* 验证帐户激活模型
*/

//检测是否定义根路径
defined('ROOTPATH') or die("You don't have permission to access the path on this server.");
//包含R文件
require_once ROOTPATH.'yepo/R.php';

class queryverify extends R{

	/*
	* 处理帐户激活
	*/

	function __construct(){
		//执行exec方法
		$this->exec();
	}

	private function exec(){

		//获得激活识别码
		if(!isset($_GET['opera2']))exit("You don't have permission to access the path on this server.");

		//检测轮候册是否存在
		if(!file_exists($filename = ROOTPATH.'user/list/'.$_GET['opera2'].'.txt')){
			self::show('抱歉，您的激活请求已过期或已激活(qv01)。');
		}

		//获得轮候册数据，数据为注册邮箱地址
		$mail = file($filename);
		$mail = $mail[0];

		//检测邮箱格式
		if(!$mail||!$this->checkMail($mail)){
			self::show('抱歉，发生内部错误(qv02)。');
		}

		//使用数据库
		$db = $this->MySQL();

		//检测查询数据库是否出错，成立则报错并停止下行
		if(!$sql = $db->iQ("select `id`,`class`,`name` from `user` where `mail` = '$mail'")){
			self::show('抱歉，发生内部错误(qv03)。');
		}

		//检测电邮地址是否存在
		if(!is_array($arr = $db->iR($sql))){
			unlink($filename);
			self::show('抱歉，没有找到帐户资料(qv04)。');
		}

		//获得用户ID
		$id = $arr[0];

		//获得用户姓名
		$name = $arr[2];

		//检测用户阶级是否为1
		if($arr[1] != 1){
			unlink($filename);
			self::show('抱歉，您的激活请求已过期或已激活(qv05)。');
		}

		//更新用户阶级为2
		if(!$db->iQ("update `user` set `class` = 2 where `id` = $id")){
			self::show('抱歉，发生内部错误(qv06)。');
		}

		//删除轮候册文件
		unlink($filename);

		//跳转到显示验证成功页面
		require_once ROOTPATH.'static/page/verifySuccessPage.php';
		verifySuccessPage($name);

	}

	//显示页面
	static function show($con){
		//包含queryverifyPage.php文件
		require_once ROOTPATH.'static/page/queryverifyPage.php';
		//执行queryverifyPage方法
		queryverifyPage($con);
		//stop
		exit;
	}


}

$new = new queryverify();