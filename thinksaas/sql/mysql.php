<?php
defined('IN_TS') or die('Access Denied.');
 
class MySql {

	public $queryCount = 0;
	public $conn;
	public $result;
	
	//构造函数
	//$DB 数据库链接参数
	function __construct($DB){
		
		if (!function_exists('mysql_connect')){
			qiMsg('服务器PHP不支持MySql数据库');
		}
		
		if($DB['host'] && $DB['user']) {
		
			if (!$this->conn = mysql_connect($DB['host'].':'.$DB['port'], $DB['user'], $DB['pwd'])){
				qiMsg("连接数据库失败,可能是数据库用户名或密码错误");
			}
		
		}
		
		$this->query("SET NAMES 'utf8'");
		
		if($DB['name']) mysql_select_db($DB['name'], $this->conn) OR qiMsg("未找到指定数据库");
		
		
	}
	
	/**
	 * 对特殊字符进行过滤
	 *
	 * @param value  值
	 */
	public function escape($value) {
		if(is_null($value))return 'NULL';
		if(is_bool($value))return $value ? 1 : 0;
		if(is_int($value))return (int)$value;
		if(is_float($value))return (float)$value;
		if(@get_magic_quotes_gpc())$value = stripslashes($value);
		return '\''.mysql_real_escape_string($value, $this->conn).'\'';
	}
	
	/**
	 * 格式化带limit的SQL语句
	 */
	public function setlimit($sql, $limit)
	{
		return $sql. " LIMIT {$limit}";
	}

	/*
	 *发送查询语句
	 */
	 
	function query($sql){
		$this->result = mysql_query($sql,$this->conn);
		$this->queryCount++;
		/*
		if (!$this->result){
			qiMsg("SQL语句执行错误：$sql <br />".mysql_error());
		}else{
			return $this->result;
		}
		*/
		//记录错误并继续执行
		if (!$this->result){
			$log = date('Y-m-d :H:i:s')." SQL ERROR：$sql ".mysql_error()."\n";
			!is_dir('logs')?mkdir('logs',0777):'';
			file_put_contents('logs/'.date('Y-m-d').'.txt',$log);
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
	

	/*
	 *获取行的数目
	 */
	
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
	
	/*
	 *数组添加
	 */
	 
	function insertArr($arrData,$table,$where=''){
		$Item = array();
		foreach($arrData as $key=>$data){
			$Item[] = "$key='$data'";
		}
		$intStr = implode(',',$Item);
		$sql = "insert into $table  SET $intStr $where";
		//echo $sql;
		$this->query("insert into $table  SET $intStr $where");
		return mysql_insert_id($this->conn);
	}
	
	/*
	 *数组更新(Update)
	 */

	function updateArr($arrData,$table,$where=''){
		$Item = array();
		foreach($arrData as $key => $date)
		{
			$Item[] = "$key='$date'";
		}
		$upStr = implode(',',$Item);
		$this->query("UPDATE $table  SET  $upStr $where");
		return true;
	}
	 
	/*
	 *获取mysql错误
	 */
	function geterror(){
		return mysql_error();
	}

	/*
	 *Get number of affected rows in previous MySQL operation
	 */
	 
	function affected_rows(){
		return mysql_affected_rows();
	}
	/*
	 *获取数据库版本信息
	 */
	 
	function getMysqlVersion(){
		return @mysql_get_server_info();
	}
	
	public function __destruct(){
		return mysql_close($this->conn);
	}

}