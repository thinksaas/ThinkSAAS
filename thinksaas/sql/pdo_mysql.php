<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );
class MySql {
	public $query_count = 0;
	public $conn;
	public $arrSql;
	private $num_rows;
	
	/**
	 * 初始化
	 * @param unknown $DB
	 */
	function __construct($DB) {
		$dsn = 'mysql:host=' . $DB ['host'] . ';dbname=' . $DB ['name'];
		
		try {
			$this->conn = new pdo ( $dsn, $DB ['user'], $DB ['pwd'], array (
					PDO::ATTR_PERSISTENT => true 
			) ); // 持久链接
			$this->query ( "set names 'utf8'" );
		} catch ( PDOException $e ) {
			echo $e->getMessage ();
			exit ();
		}
	}
	
	/**
	 * 转义数据字符
	 * @param unknown $string
	 * @return string
	 */
	function escape($string) {
		return $this->conn->quote ( $string );
	}
	
	/**
	 * 格式化带limit的SQL语句
	 */
	public function setlimit($sql, $limit) {
		return $sql . " LIMIT {$limit}";
	}
	
	/**
	 * 进行 updata insert delete 操作,返回行数
	 * @param unknown $sql
	 * @return number
	 */
	function query($sql) {
		$this->arrSql [] = $sql;
		$result = $this->conn->exec ( $sql );
		if (FALSE !== $result) {
			$this->num_rows = $result;
			return $result;
		} else {
			
			$poderror = $this->conn->errorInfo ();
			//记录SQL错误日志
			$log = "TIME:" . date ( 'Y-m-d :H:i:s' ) . "\n";
			$log .= "SQL:" . $sql . "\n";
			$log .= "ERROR:" . $this->conn->errorInfo ( $poderror [2] ) . "\n";
			$log .= "REQUEST_URI:" . $_SERVER['REQUEST_URI'] . "\n";
			$log .= "--------------------------------------\n";
			logging ( date ( 'Ymd' ) . '-mysql-error.txt', $log );
			
			
		}
	}
	
	/**
	 * 查询数据 返回数组
	 * @param unknown $sql
	 * @return multitype:
	 */
	function fetch_all_assoc($sql) {
		$this->conn->setAttribute ( PDO::ATTR_CASE, PDO::CASE_LOWER ); // 改写获取方式为小写字段
		$rows = $this->conn->prepare ( $sql );
		
		if ($this->conn->errorCode () != 00000) {
			
			$poderror = $this->conn->errorInfo ();
			//记录SQL错误日志
			$log = "TIME:" . date ( 'Y-m-d :H:i:s' ) . "\n";
			$log .= "SQL:" . $sql . "\n";
			$log .= "ERROR:" . $this->conn->errorInfo ( $poderror [2] ) . "\n";
			$log .= "REQUEST_URI:" . $_SERVER['REQUEST_URI'] . "\n";
			$log .= "--------------------------------------\n";
			logging ( date ( 'Ymd' ) . '-mysql-error.txt', $log );
			
		}
		
		$rows->execute ();
		
		$this->query_count += 1;
		
		$rows->setFetchMode ( PDO::FETCH_ASSOC ); // 是用fetch_assoc 获取方式
		return $rows->fetchAll (); // 取出记录
	}
	
	/**
	 * 返回查询结果一条
	 * @param unknown $sql
	 * @param number $symbols
	 * @return string|Ambigous <string, mixed>
	 */
	function once_fetch_assoc($sql, $symbols = 0) {
		$this->conn->setAttribute ( PDO::ATTR_CASE, PDO::CASE_LOWER );
		$rows = $this->conn->prepare ( $sql );
		if ($this->conn->errorCode () != 00000) {
			if ($symbols == 0) {
				
				$poderror = $this->conn->errorInfo ();
				//记录SQL错误日志
				$log = "TIME:" . date ( 'Y-m-d :H:i:s' ) . "\n";
				$log .= "SQL:" . $sql . "\n";
				$log .= "ERROR:" . $this->conn->errorInfo ( $poderror [2] ) . "\n";
				$log .= "REQUEST_URI:" . $_SERVER['REQUEST_URI'] . "\n";
				$log .= "--------------------------------------\n";
				logging ( date ( 'Ymd' ) . '-mysql-error.txt', $log );
				
			} else {
				return "Error";
			}
		}
		
		$rows->execute ();
		
		$this->query_count += 1;
		$rows->setFetchMode ( PDO::FETCH_ASSOC ); // 是用fetch_assoc 获取方式
		$da = '';
		while ( $row = $rows->fetch () ) {
			$da = $row;
		}
		
		return $da;
	}
	
	/**
	 * 统计结果集的行数
	 * @param unknown $sql
	 * @return number
	 */
	function once_num_rows($sql) {
		$rows = $this->conn->prepare ( $sql );
		$rows->execute ();
		$num = $rows->rowCount ();
		return $num;
	}
	
	/**
	 * 取得上一步INSERT产生的ID
	 * @return string
	 */
	function insert_id() {
		return $this->conn->lastInsertId ();
	}
	
	/**
	 * 数组添加
	 * @param unknown $arrData
	 * @param unknown $table
	 * @param string $where
	 * @return string
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
		return $this->insert_id ();
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
	 * @return Ambigous <>
	 */
	function geterror() {
		$result = $this->conn->errorInfo ();
		return $result [2];
	}
	function getMysqlVersion() {
		$Data = $this->once_fetch_assoc ( "SELECT version( ) AS version" );
		return $Data ['version'];
	}
	
	/**
	 * 报错
	 * @param unknown $err
	 */
	function error($err) {
		$log = "TIME:" . date ( 'Y-m-d :H:i:s' ) . "\n";
		$log .= "SQL:" . $err . "\n";
		$log .= "REQUEST_URI:" . $_SERVER['REQUEST_URI'] . "\n";
		$log .= "--------------------------------------\n";
		logging ( date ( 'Ymd' ) . '-mysql-error.txt', $log );
	}
}