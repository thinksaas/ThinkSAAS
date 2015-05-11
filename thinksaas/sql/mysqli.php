<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );
class MySql {
	public $queryCount = 0;
	public $conn;
	public $result;

    /**
     * 执行的SQL语句记录
     */
    public $arrSql;
	
	/**
	 * @param unknown $DB	数据库链接参数	
	 */
	function __construct($DB) {


		if (! function_exists ( 'mysqli_connect' )) {
			qiMsg ( '服务器PHP不支持MySQLi数据库' );
		}

		$this->conn = mysqli_connect ( $DB ['host'],$DB ['user'], $DB ['pwd'] ,$DB ['name'],$DB ['port'] );
		
		if(mysqli_connect_errno())qiMsg('数据库链接错误/无法找到数据库 : '. mysqli_connect_error());
		$this->query("SET NAMES UTF8");
		
	}
	
	/**
	 * 对特殊字符进行过滤
	 * @param unknown $value
	 * @return string|number
	 */
	public function escape($value) {
		if (is_null ( $value ))
			return 'NULL';
		if (is_bool ( $value ))
			return $value ? 1 : 0;
		if (is_int ( $value ))
			return ( int ) $value;
		if (is_float ( $value ))
			return ( float ) $value;
		/*
		if (get_magic_quotes_gpc ()){
			$value = stripslashes ( $value );
		}
		*/
		return '\'' . mysqli_real_escape_string ($this->conn,$value ) . '\'';
	}
	
	/**
	 * 格式化带limit的SQL语句
	 * @param unknown $sql
	 * @param unknown $limit
	 * @return string
	 */
	public function setlimit($sql, $limit) {
		return $sql . " LIMIT {$limit}";
	}
	
	/**
	 * 发送查询语句
	 * @param unknown $sql
	 * @return resource
	 */
	function query($sql) {

        $this->arrSql = $sql;

		$this->result = mysqli_query ( $this->conn,$sql );
		$this->queryCount ++;
		
		// 记录SQL错误日志并继续执行
		if (! $this->result) {
			$log = "TIME:" . date ( 'Y-m-d :H:i:s' ) . "\n";
			$log .= "SQL:" . $sql . "\n";
			$log .= "ERROR:" . mysqli_error ($this->conn) . "\n";
			$log .= "REQUEST_URI:" . $_SERVER['REQUEST_URI'] . "\n";
			$log .= "--------------------------------------\n";
			logging ( date ( 'Ymd' ) . '-mysqli-error.txt', $log );
		}
		
		// 记录SQL日志
		if ($GLOBALS['TS_CF'] ['logs']) {
			
			$log = "TIME:" . date ( 'Y-m-d :H:i:s' ) . "\n";
			$log .= "SQL:" . $sql . "\n";
			$log .= "--------------------------------------\n";
			logging ( date ( 'Ymd' ) . '-mysqli.txt', $log );
		}
		
		return $this->result;
	}
	
	/**
	 * @param unknown $sql
	 * @param number $max
	 * @return multitype:
	 */
	function fetch_all_assoc($sql, $max = 0) {
		$query = $this->query ( $sql );
		while ( $list_item = mysqli_fetch_assoc ( $query ) ) {
			
			$current_index ++;
			
			if ($current_index > $max && $max != 0) {
				break;
			}
			
			$all_array [] = $list_item;
		}
		
		return $all_array;
	}
	function once_fetch_assoc($sql) {
		$list = $this->query ( $sql );
		$list_array = mysqli_fetch_assoc ( $list );
		return $list_array;
	}
	
	/**
	 * 获取行的数目
	 * @param unknown $sql
	 * @return number
	 */
	function once_num_rows($sql) {
		$query = $this->query ( $sql );
		return mysqli_num_rows ( $query );
	}
	
	/**
	 * 获得结果集中字段的数目
	 * @param unknown $query
	 * @return number
	 */
	function num_fields($query) {
		return mysqli_num_fields ( $query );
	}
	
	/**
	 * 取得上一步INSERT产生的ID
	 * @return number
	 */
	function insert_id() {
		return mysqli_insert_id ( $this->conn );
	}
	
	/**
	 * 数组添加
	 * @param unknown $arrData
	 * @param unknown $table
	 * @param string $where
	 * @return number
	 */
	function insertArr($arrData, $table, $where = '') {
		$Item = array ();
		foreach ( $arrData as $key => $data ) {
			$Item [] = "$key='$data'";
		}
		$intStr = implode ( ',', $Item );
		$sql = "insert into $table  SET $intStr $where";
		// echo $sql;
		$this->query ( "insert into $table  SET $intStr $where" );
		return mysqli_insert_id ( $this->conn );
	}
	
	/**
	 * 数组更新(Update)
	 * @param unknown $arrData
	 * @param unknown $table
	 * @param string $where
	 * @return boolean
	 */
	function updateArr($arrData, $table, $where = '') {
		$Item = array ();
		foreach ( $arrData as $key => $date ) {
			$Item [] = "$key='$date'";
		}
		$upStr = implode ( ',', $Item );
		$this->query ( "UPDATE $table  SET  $upStr $where" );
		return true;
	}
	
	/**
	 * 获取mysql错误
	 * @return string
	 */
	function geterror() {
		return mysqli_error ($this->conn);
	}
	
	/**
	 * Get number of affected rows in previous MySQL operation
	 * @return number
	 */
	function affected_rows() {
        return mysqli_affected_rows($this->conn);
	}
	/**
	 * 获取数据库版本信息
	 */
	function getMysqlVersion() {
		return mysqli_get_server_info ($this->conn);
	}
	public function __destruct() {
		return mysqli_close ( $this->conn );
	}
}