<?php
defined('IN_TS') or die('Access Denied.');
 
class MySql {
	
	public $query_count = 0;
	public $db = null ;
	
	//初始化
	function __construct($TS_DB)
	{
		$dsn='mysql:host='.$TS_DB['host'].';dbname='.$TS_DB['name'];

		try{
			$this->db = new pdo($dsn,$TS_DB['user'],$TS_DB['pwd'],array(PDO::ATTR_PERSISTENT => true));//持久链接
			$this->db->query("set names 'utf8'");
		}catch(PDOException $e){
			echo $e->getMessage();
			exit;
		}
	}

	/*
	进行 updata insert delete 操作
	返回行数
	*/
	function query($s,$symbols = 0){
			$result = $this->db->exec($s);
			$this->query_count += 1;
			if ($this->db->errorCode() != 00000){
				if($symbols == 0){
					$this->getError();
				}else{return "Error";}
			}else{
				return $result;
			}
	}
	
	
	/*
	查询数据 返回数组
	*/
	function fetch_all_assoc($s)
	{
		$this->db->setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER); //改写获取方式为小写字段
		$rs = $this->db->query($s);
		
		if ($this->db->errorCode() != 00000){
			$this->getError();
		}
		$this->query_count += 1;
		
		$rs->setFetchMode(PDO::FETCH_ASSOC);//是用fetch_assoc 获取方式
		return $rs->fetchAll(); //取出记录
	}
	
	/*
	返回查询结果一条
	*/
	function once_fetch_assoc($s,$symbols = 0)
	{
			
			$this->db->setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER);
			$data = $this->db->query($s);
			if ($this->db->errorCode() != 00000){
				if($symbols == 0){
					$this->getError();
				}else{return "Error";}
			}
			$this->query_count += 1;
			$data->setFetchMode(PDO::FETCH_ASSOC);//是用fetch_assoc 获取方式
			$da = '';
			while($row = $data->fetch()){
				  $da = $row;
			}
			return $da;
	}

	//统计结果集的行数
	function once_num_rows($sql){
		$rs=$this->db->query($sql);
		$num = $rs->rowCount();
		return $num;
	}

	/*
	 *取得上一步INSERT产生的ID
	 */
	
	function insert_id(){
		return	$this->db->lastInsertId();
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
		$this->db->query("insert into $table  SET $intStr $where");
		return $this->insert_id();
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
	function getError(){
		$result = $this->db->errorInfo();
		return $result[2];
	}
	
	//转义数据字符
	function escape_string($string)
	{
		return $this->db->quote($string);
	}
	
	function getMysqlVersion()
	{
		$Data = $this->once_fetch_assoc("SELECT version( ) AS version");
		return $Data['version'];
	}

}