<?php

/*
* 操作MySQL数据库模块
*/

class Sql{

	//主机名
	public $Host;
	//用户名
	public $Root;
	//密码
	public $Pass;
	//数据库名
	public $DB;

	//链接数据库
	function connect(){
		if(!$conn = mysql_connect($this->Host,$this->Root,$this->Pass))return;
		$this->iQ("set names 'utf8'");
		if(mysql_select_db($this->DB))return$conn;
	}

	//查询数据库
	function iQ($sql){
		if($query = mysql_query($sql))return$query;
	}

	//获取数据储存为数组
	function iR($sql){
		return mysql_fetch_row($sql);
	}

	//获得数据行数
	function iN($sql){
		return mysql_num_rows($sql);
	}

	//取得上一步INSERT 操作产生的ID
	function iD(){
		return mysql_insert_id();
	}

}