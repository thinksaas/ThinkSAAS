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
	
	/**
	 * 从数据表中查找一条记录
	 *
	 * @param conditions    查找条件，数组array("字段名"=>"查找值")或字符串，
	 * 请注意在使用字符串时将需要开发者自行使用escape来对输入值进行过滤
	 * @param sort    排序，等同于“ORDER BY ”
	 * @param fields    返回的字段范围，默认为返回全部字段的值
	 */
	public function find($table,$conditions = null, $sort = null, $fields = null)
	{
		if( $record = $this->findAll($table,$conditions, $sort, $fields, 1) ){
			return array_pop($record);
		}else{
			return FALSE;
		}
	}
	
	/**
	 * 从数据表中查找记录
	 *
	 * @param conditions    查找条件，数组array("字段名"=>"查找值")或字符串，
	 * 请注意在使用字符串时将需要开发者自行使用escape来对输入值进行过滤
	 * @param sort    排序，等同于“ORDER BY ”
	 * @param fields    返回的字段范围，默认为返回全部字段的值
	 * @param limit    返回的结果数量限制，等同于“LIMIT ”，如$limit = " 3, 5"，即是从第3条记录（从0开始计算）开始获取，共获取5条记录
	 *                 如果limit值只有一个数字，则是指代从0条记录开始。
	 */
	public function findAll($table,$conditions=null,$sort = null,$fields=null,$limit = null){
	
		$fields = empty($fields) ? "*" : $fields;
		
		$where = '';
		
		if(is_array($conditions)){
			$join = array();
			foreach( $conditions as $key => $condition ){
				$join[] = "{$key} = {$condition}";
			}
			$where = "WHERE ".join(" AND ",$join);
		}else{
			if(null != $conditions) $where = "WHERE ".$conditions;
		}
		
		$sort = empty($sort) ? "" : " ORDER BY {$sort}";
		
		$limit = empty($limit) ? "" : " LIMIT {$limit}";
	
		$sql = "SELECT {$fields} FROM ".dbprefix."{$table} {$where} {$sort}{$limit}";
	
		return $this->findSql($sql);
		
	}

	
	/**
	 * 计算符合条件的记录数量
	 *
	 * @param conditions 查找条件，数组array("字段名"=>"查找值")或字符串，
	 * 请注意在使用字符串时将需要开发者自行使用escape来对输入值进行过滤
	 */
	public function findCount($table,$conditions = null)
	{
		$where = "";
		if(is_array($conditions)){
			$join = array();
			foreach( $conditions as $key => $condition ){
				//$condition = $this->escape($condition);
				$join[] = "{$key} = {$condition}";
			}
			$where = "WHERE ".join(" AND ",$join);
		}else{
			if(null != $conditions)$where = "WHERE ".$conditions;
		}
		$sql = "SELECT COUNT(*) AS SP_COUNTER FROM ".dbprefix."{$table} {$where}";
		$result = $this->findSql($sql);
		return $result[0]['SP_COUNTER'];
	}
	
	
	/**
	 * 使用SQL语句进行查找操作，等于进行find，findAll等操作
	 *
	 * @param sql 字符串，需要进行查找的SQL语句
	 */
	public function findSql($sql)
	{
		if( ! $result = $this->query($sql) )return array();
		if( ! mysql_num_rows($result) )return array();
		$rows = array();
		while($rows[] = mysql_fetch_array($result,MYSQL_ASSOC)){}
		mysql_free_result($result);
		array_pop($rows);
		return $rows;
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
	
	/**
	 * 修改数据，该函数将根据参数中设置的条件而更新表中数据
	 * 
	 * @param conditions    数组形式，查找条件，此参数的格式用法与find/findAll的查找条件参数是相同的。
	 * @param row    数组形式，修改的数据，
	 *  此参数的格式用法与create的$row是相同的。在符合条件的记录中，将对$row设置的字段的数据进行修改。
	 */
	public function update($table,$conditions, $row)
	{
		$where = "";
		//$row = $this->__prepera_format($row);
		if(empty($row))return FALSE;
		if(is_array($conditions)){
			$join = array();
			foreach( $conditions as $key => $condition ){
				//$condition = $this->escape($condition);
				$join[] = "{$key} = {$condition}";
			}
			$where = "WHERE ".join(" AND ",$join);
		}else{
			if(null != $conditions)$where = "WHERE ".$conditions;
		}
		foreach($row as $key => $value){
			//$value = $this->escape($value);
			$vals[] = "{$key} = {$value}";
		}
		$values = join(", ",$vals);
		$sql = "UPDATE ".dbprefix."{$table} SET {$values} {$where}";
		return $this->query($sql);
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