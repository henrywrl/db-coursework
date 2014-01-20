<?php


/*
*
*  定义系统常量
*
*/

//检测配置文件是否存在，该文件为 txt 后缀 文本文件，储存的为 JSON 数据
if(!file_exists($file = dirname(__FILE__).'/setting.txt'))exit('system error 0001');

//获得数据
$txt = file($file);

//对 JSON 格式的字符串进行编码
$dat = json_decode($txt[0],true);

//遍历 dat JSON 数据数组
foreach($dat as $key => $value){
	//定义常量
	define($key,$value);
}

//设置时间区域为 中华人民共和国
date_default_timezone_set('PRC');