<?php
defined('IN_TS') or die('Access Denied.');
 
class MySql {

	public $queryCount = 0;
	public $conn;
	public $result;
	
	//构造函数
	//$TS_DB 数据库链接参数
	function __construct($TS_DB){
		
		if (!function_exists('mysql_connect')){
			qiMsg('服务器PHP不支持MySql数据库');
		}
		
		if($TS_DB['host'] && $TS_DB['user']) {
		
			if (!$this->conn = mysql_connect($TS_DB['host'], $TS_DB['user'], $TS_DB['pwd'])){
				qiMsg("连接数据库失败,可能是数据库用户名或密码错误");
			}
		
		}
		
		$this->query("SET NAMES 'utf8'");
		
		if($TS_DB['name']) mysql_select_db($TS_DB['name'], $this->conn) OR qiMsg("未找到指定数据库");
		
	}

	/*
	 *发送查询语句
	 */
	 
	function query($sql){
		$this->result = mysql_query($sql,$this->conn);
		$this->queryCount++;
		if (!$this->result){
			qiMsg("SQL语句执行错误：$sql <br />".mysql_error());
		}else{
			return $this->result;
		}
	}

	
	/*
	 *fetch_all_assoc
	 */
	 
	function fetch_all_assoc($sql,$max=0){
		$query = $this->query($sql);
		while($list_item = mysql_fetch_assoc($query)){
		
			$current_index ++;
			
			if($current_index > $max && $max != 0){
				break;
			}
			
			$all_array[] = $list_item;
			
		}
		
		return $all_array;
	}

	
	function once_fetch_assoc($sql){
		$list 	= $this->query($sql);
		$list_array = mysql_fetch_assoc($list);
		return $list_array;
	}
	
	function once_num_rows($sql){
		$query=$this->query($sql);
		return mysql_num_rows($query);
	}
	
	/*
	 *获得结果集中字段的数目
	 */
	 
	function num_fields($query){
		return mysql_num_fields($query);
	}

	/*
	 *取得上一步INSERT产生的ID
	 */
	
	function insert_id(){
		return mysql_insert_id($this->conn);
	}
	
	/**
	 * 在数据表中新增一行数据，并返回id
	 * @param table 要插入的数据表
	 * @param row 数组形式，数组的键是数据表中的字段名，键对应的值是需要新增的数据。
	 */
	public function create($table,$row){
	
		$Item = array();
		
		foreach($row as $key=>$value){
			$Item[] = "$key='$value'";
		}
		
		$intStr = implode(',',$Item);
		
		$this->query("insert into ".dbprefix."$table  SET $intStr");
		
		return mysql_insert_id($this->conn);
	}
	
	/*
	 * $table 数据表
	 * $where 查找条件 
	 * $row 数组数据
	 */
	function update($table,$where,$row){
	
		$Item = array();
		
		foreach($arrData as $key => $date){
		
			$Item[] = "$key='$date'";
			
		}
		
		$upStr = implode(',',$Item);
		
		$this->query("update ".dbprefix."$table set $upStr where $where");
		
		return true;
	}

	/*
	 *Get number of affected rows in previous MySQL operation
	 */
	 
	function affected_rows(){
		return mysql_affected_rows();
	}
	
	public function __destruct(){
		return mysql_close($this->conn);
	}

}