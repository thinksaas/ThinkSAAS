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
					$this->error($this->db->errorInfo());
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
			$this->error($this->db->errorInfo());
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
					$this->error($this->db->errorInfo());
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
	function geterror(){
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
	
	/*报错*/
	function error($e)
	{
	echo'
	<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>致命错误</title>
		</head>
		<body bgcolor="#FFFFFF">
		<table cellpadding="10" cellspacing="0" border="0" width="100%" align="left" style="font-family: Verdana, Tahoma; color: #666666; font-size: 12px;border:1px solid #F00">
		  <tr>
		    <td valign="middle" align="left" bgcolor="#EBEBEB"><br>
		      <b style="font-size: 14px">致命错误</b> <br>
		      <br>出现了致命错误。导致网站非正常停止响应，您应该尽快的将本错误告知网站技术支持人员。技术支持QQ:1078700473
		      <p>
		      <b style="font-size: 14px">错误代号：'.$e[1].'  </b> </p>
			  <p>
		       '.$e[2].'</p>
		      </td>
	      </tr>
	    </table>
</body>
		</html>';
	//$this->log($e);
	exit();
	}
	
	//记录错误
	function log($err,$no=0)
	{
		$path =  'data/log/db_log.txt';
		if(!file_exists($path)){
			$hand =	fopen($path,'w+');  //如果文件不存在则使用创建方式建立文件
		}else{
			$hand =	fopen($path,'a');	//如果文件存在则直接进行添加写入
		}
		$data = '时间 :['.date("Y/m/d H:i:s").']('.$err[1].') '."\n".'脚本:'.$_SERVER["REQUEST_URI"] ."\n".'描述:'.$err[2].''."\n";
		@fwrite($hand,$data);
		@fclose($hand);

	}

}