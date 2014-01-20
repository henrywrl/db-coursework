<?php

/*
* R.php
* 集合一些常用到的方法
*/

//检测是否定义根路径
defined('ROOTPATH') or die("You don't have permission to access the path on this server.");

//包含配置文件
require_once ROOTPATH.'conf/c.php';

class R{

	//实例化Sql，使用数据库
	protected function MySQL(){
		require_once ROOTPATH.'yepo/module/Sql.php';
		$db = new Sql();
		$db->Host = MYHOST;
		$db->Root = MYROOT;
		$db->Pass = MYPASS;
		$db->DB = MYDB;
		if(!$db->connect())exit('can not connect MySQL');
		return$db;
	}

	//检测管理员在线状态
	protected function checkOnlineR(){
		if(!isset($_SESSION['admin']))return;
		$S = $_SESSION['admin'];
		if(!count($S) == 3)return;
		if(!preg_match("/^[\d]+$/", $S[0])||!preg_match("/^[\d]+$/", $S[2]))return;
		if(sha1(AUSER.APASS.PS) != $S[1])return;
		$S[2] = time();
		return true;
	}

	//检测用户在线状态
	protected function checkOnline($db){
		if(!isset($_SESSION['online']))return;
		$S = $_SESSION['online'];
		if(!count($S) == 3)return;
		if(!preg_match("/^[\d]+$/", $S[0])||!preg_match("/^[\d]+$/", $S[2]))return;
		if(!$db)$db = $this->MySQL();
		$time = time();
		$sql = $db->iQ("select `mail`,`pass`,`class`,`name` from `user` where `id` = '".$S[0]."'") or die('System error');
		if(!is_array($arr = $db->iR($sql)))return;
		if($arr[2] != 2)return;
		if(sha1($arr[0].$arr[1].PS) != $S[1])return;
		$db->iQ("update `user` set `time` = '$time' where `id` = ".$S[0]) or die('System error2');
		$S[2] = $time;
		return $arr[3];
	}

	//实例化PHPMailer，处理发件
	protected function _Send($addr,$title,$content){
		include_once(ROOTPATH.'yepo/module/phpmailer/class.phpmailer.php');
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Host = SMTPHOST;
		$mail->SMTPAuth = true;
		//$mail->Port = 465;
		//$mail->SMTPSecure = "ssl";
		$mail->Username = MYMAILER;
		$mail->Password = MYMAILERPASS;
		$mail->Charset = 'UTF-8';
		$mail->From = MYMAILER;
		$mail->FromName = FROMNAME;
		$mail->AddAddress($addr);
		$mail->IsHTML(true);
		$mail->Subject = '=?UTF-8?B?'.base64_encode($title).'?=';
		$mail->Body = $content;
		if($mail->Send())
			return;
		else
			return true;
	}

	//返回邮箱登录地址
	function returnMailFrom($mail){
		$res = '';
		$arr = explode("@",$mail);
		$mail = $arr[1];
		switch($mail){
			case 'qq.com': $res = 'mail.qq.com'; break;
			case 'foxmail.com': $res = 'mail.foxmail.com'; break;
			case '163.com': $res = 'mail.163.com'; break;
			case '126.com': $res = 'mail.126.com'; break;
			case 'yeah.net': $res = 'mail.yeah.net'; break;
			case 'gmail.com': $res = 'gmail.com'; break;
			case 'yahoo.cn': $res = 'mail.cn.yahoo.com'; break;
			case 'hotmail.com': $res = 'live.com'; break;
			case 'live.com': $res = 'live.com'; break;
			case 'sina.com': $res = 'mail.sina.com.cn'; break;
			case 'tom.com': $res = 'mail.tom.com'; break;
			case '139.com': $res = 'mail.10086.cn'; break;
			case '189.cn': $res = 'mail.189.cn'; break;
			case 'sohu.com': $res = 'mail.sohu.com'; break;
			default: $res = $mail;
		}
		return $res;
	}

	//新建或更新文件
	protected function writeDocument($filename,$mod,$data){
		if(!$hander = fopen($filename,$mod))return;
		if(!fwrite($hander,$data))return;
		fclose($hander);
		return true;
	}

	//返回邮件正文
	function returnMailContent($name,$url,$mail,$type){
		$str1 = '<div style="font-size:14px">亲爱的学员('.$name.')您好！<br><br>请点击下面的链接即可完成激活：<br><br><a href="'.$url.'">'.$url.'</a><br><br><span style="color:#aaa">(如果链接无法点击，请将它复制并粘贴到浏览器的地址栏中访问)</span><br><br>该邮件发给&lt;'.$mail.'&gt;,若不是本人操作请忽略<br><br>本邮件是系统自动发送的，请勿直接回复！感谢您的访问，祝您使用愉快！<br><br>'.MYNAME.'<br><a href="http://'.DOMAIN.'/">'.DOMAIN.'</a></div>';
		$str2 = '<div style="font-size:14px">亲爱的学员('.$name.')您好！<br><br>系统已为您重置了登录密码：<br><br><label style="font-size:20px;font-weight:bold;">'.$url.'</label><br><br>该邮件发给&lt;'.$mail.'&gt;,若不是本人操作请忽略<br><br>本邮件是系统自动发送的，请勿直接回复！感谢您的访问，祝您使用愉快！<br><br>'.MYNAME.'<br><a href="http://'.DOMAIN.'/">'.DOMAIN.'</a></div>';
		return $type == 'R' ? $str1 : $str2;
	}

	//随机返回一个若干位数的字符串
	function get_rand($limit){
		$str[0] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$str[1] = 'abcdefghijklmnopqrstuvwxyz';
		$str[2] = '0123456789';
		$res = '';
		$obj = '';
		for($i=0;$i<$limit;$i++){
			$obj = $str[mt_rand(0,2)];
			$res .= substr($obj,mt_rand(0,strlen($obj)),1);
		}
		return$res;
	}

	//JSON
	function json($word){
		exit('{"status":"'.$word.'"}');
	}

	//返回用户性别
	function get_gender($gender){
		return $gender == 1 ? 'Male' : 'Female';
	}

	//返回用户年级
	function get_grade($grade){
		$res = '';
		switch($grade){
			case 1: $res .= 'One'; break;
			case 2: $res .= 'Two'; break;
			case 3: $res .= 'Three'; break;
			case 4: $res .= 'Four'; break;
			default:return;
		}
		return$res;
	}

	protected function get_subject($db,$nid){
		if(!$sql = $db->iQ("select `value` from `subject` where `nid`"))return;
		if(!is_array($res = $db->iR($sql)))return;
		return $res[0];
	}

	function get_own($n){
		return $n == 0 ? 'Private' : 'Public';
	}

	//替换特殊符号
	function escapeStr($str){
		//替换特殊符号
		$reg[0] = "\n";
		$reg[1] = "<";
		$reg[2] = ">";
		$reg[3] = "'";
		$reg[4] = "\"";
		$exc[4] = "";
		$exc[3] = "&lt;";
		$exc[2] = "&gt;";
		$exc[1] = "";
		$exc[0] = "";
		return str_replace($reg,$exc,$str);
	}

	//检查邮箱格式
	function checkMail($mail){
		if(preg_match("/^[0-9a-z_\.]+\@([a-z0-9]+\.)+[a-z]{2,3}$/",$mail))return true;
	}


	//返回后台“会员管理”的一些列表数据
	function get_opera_admin($odr,$type){
		//设置操作
		$symbol = $type == 0 ? '\'' : '\"';
		$html = array();

		switch($odr){
			case 0:
			$html[] = $this->return_html_0($symbol,'dis',$odr);
			$html[] = $this->return_html_1($symbol,0,'enable');
			break;
			case 1:
			$html[] = $this->return_html_0($symbol,'unc',$odr);
			$html[] = $this->return_html_1($symbol,1,'activating');
			break;
			case 2:
			$html[] = $this->return_html_0($symbol,'nor',$odr);
			$html[] = $this->return_html_1($symbol,2,'disabled');
			break;
			default:;
		}
		return array($html[0],$html[1]);
	}
	function return_html_0($symbol,$class,$date){
			return '<i class='.$symbol.$class.$symbol.'>'.$date.'</i>';
	}
	function return_html_1($symbol,$opera,$class){
			return '<a href='.$symbol.'javascript:;'.$symbol.' class='.$symbol.$class.$symbol.' title='.$symbol.$class.$symbol.' onclick='.$symbol.'changeClass($(this))'.$symbol.' opera='.$opera.'></a>';
	}



	//获得用户IP地址
	function ip(){
		if(getenv('HTTP_CLIENT_IP')) { $ip = getenv('HTTP_CLIENT_IP');} 
		elseif(getenv('HTTP_X_FORWARDED_FOR')) { $ip = getenv('HTTP_X_FORWARDED_FOR');} 
		elseif(getenv('REMOTE_ADDR')) { $ip = getenv('REMOTE_ADDR');} else { $ip = $_SERVER['REMOTE_ADDR'];}
		return $ip;
	}


	protected function get_log($flag,$id,$db){
		if(!$_sql = $db->iQ("select `result` from `examlog` where `$flag` = $id"))return;
		$res = 0;
		$num = 0;
		$percent = 0;
		while($_arr = $db->iR($_sql)){
			if($_arr[0] == 1)$res++;
			$num++;
		}
		if($num){
			$percent = number_format(($res / $num),2) * 100;
			if(!$percent)$percent = 0;
		}
		return array($num,$percent);
	}


}