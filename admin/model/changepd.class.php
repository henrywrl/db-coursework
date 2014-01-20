<?php

/*
* 修改密码模型
*/

//检测是否定义管理目录路径、根路径
if(!defined('ADPATH')||!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class changepd extends R{

	/*
	* 修改管理员密码
	*/

	function __construct(){
		//执行exec
		$this->exec();
	}

	private function exec(){

		//检测是否登录
		if(!$this->checkOnlineR())exit('<meta charset="utf-8"><script>window.parent.out(1);</script>');

		//检查是否提交
		if(isset($_POST['old'])&&isset($_POST['pass'])&&isset($_POST['repass'])){

			//除去两边空格，重新赋值
			$old = trim($_POST['old']);
			$pass = trim($_POST['pass']);
			$repass = trim($_POST['repass']);

			//声明error数组，储存各步骤的检测情况
			$error = array();

			//检查旧密码
			if($old == ''){
				$error['old'] = 1;//空值
			}elseif(sha1($old.PS) != APASS){
				$error['old'] = 2;//输入旧密码错误
			}
			//除去引号
			$_POST['old'] = self::replaceSym($old);

			//检查新密码
			if($pass == ''){
				$error['pass'] = 1;//空值
			}
			//除去引号
			$_POST['pass'] = self::replaceSym($pass);

			//检查确认的密码
			if($repass == ''){
				$error['repass'] = 1;//空值
			}elseif($repass != $pass){
				$error['repass'] = 2;//密码输入不一
			}elseif($repass == $old){
				$error['repass'] = 3;//与旧密码相同
			}
			//除去引号
			$_POST['repass'] = self::replaceSym($repass);

			//检查之前是否已经有错误
			if(count($error))self::show($error);

			//检查配置文件是否存在
			file_exists($filename = ROOTPATH.'conf/setting.txt') or die("System error 1");
			//读取配置文件
			$arr = file($filename);
			//解码JSON
			$json = json_decode($arr[0]);
			//重新拼接
			$result = '{';
			foreach($json as $key => $value){
				//修改APASS项
				if($key == 'APASS'){
					$result .= '"'.$key.'":"'.sha1($repass.PS).'",';
				}else{
					$result .= '"'.$key.'":"'.$value.'",';
				}
			}

			//除去多余的逗号
			$result = substr($result,0,strlen($result)-1).'}';
			
			//覆盖数据
			if(!$this->writeDocument($filename,'w+',$result))exit('System error 2');

			//成功
			$error['suc'] = 1;

			//销毁session
			session_destroy();

			self::show($error);

		}

		self::show('');
		
	}

	static function replaceSym($str){
		$reg[0] = "\'";
		$reg[1] = "\"";
		$exc[1] = "\\\'";
		$exc[0] = "\\\"";
		return str_replace($reg, $exc, $str);
	}

	static function show($error){
		include ADPATH.'static/page/changepdPage.php';
		changepdPage($error);
		exit;
	}

}

$new = new changepd();
