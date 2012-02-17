<?php 

class tsApp{
	
	public $db;
	
	public function __construct($dbhandle){
	
		$this->db = $dbhandle;
	
	}
	
	public function create(){
		
	}
	
	public function replace(){
		
	}
	
	public function update(){
		
	}
	
	public function delete(){
		
	}
	
	public function find(){
		
	}
	
	public function findAll(){
		
	}
	
	/**
	 * 过滤转义字符
	 *
	 * @param value 需要进行过滤的值
	 */
	public function escape($value)
	{
		return $this->db->escape($value);
	}
	
	/**
	 * 计算符合条件的记录数量
	 *	@param table 数据表
	 * @param conditions 查找条件，数组array("字段名"=>"查找值")或字符串，
	 * 请注意在使用字符串时将需要开发者自行使用escape来对输入值进行过滤
	 */
	public function findCount($table,$conditions = null)
	{
		$where = "";
		if(is_array($conditions)){
			$join = array();
			foreach( $conditions as $key => $condition ){
				$condition = $this->escape($condition);
				$join[] = "{$key} = {$condition}";
			}
			$where = "WHERE ".join(" AND ",$join);
		}else{
			if(null != $conditions)$where = "WHERE ".$conditions;
		}
		$sql = "SELECT COUNT(*) AS TS_COUNTER FROM ".dbprefix."{$table} {$where}";
		$result = $this->db->once_fetch_assoc($sql);
		return $result['TS_COUNTER'];
	}
	
}