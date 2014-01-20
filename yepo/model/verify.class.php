<?php

/*
* 生产验证码图片模型
*/

//检测是否定义根路径
defined('ROOTPATH') or die('No permission resources');

class verify{

	/*

	* 生产验证码图片,注册验证码session

	*/

	static function exec(){
		
		//新建真色彩图帧
		$im = imagecreatetruecolor(180,41);
		//定义背景颜色
		$bg = imagecolorallocate($im,255,255,255);
		//填充背景
		imagefill($im,0,0,$bg);
		//验证码字符左间距
		$x = 30;
		//单个字符
		$code = '';
		//验证码结果字符串
		$result = '';

		//循环4次
		for($_i=0;$_i<4;$_i++){
			//单个字符
			$code = dechex(mt_rand(1,15));
			//水平给图帧写入字符
			imagestring($im, 13, $x, mt_rand(0,25), $code,imagecolorallocate($im, mt_rand(10,250), mt_rand(100,250), mt_rand(100,250)));
			//增加验证码字符左间距
			$x += 39;
			//验证码拼接
			$result .= $code;
		}

		//输入png格式
		header("content-type:image/png");
		//输入图像
		imagepng($im);
		//销毁图片
		imagedestroy($im);

		//注册验证码session
		$_SESSION['verifyC'] = $result;
	}

}

verify::exec();