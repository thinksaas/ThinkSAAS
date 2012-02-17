<?php 

class tsApp{
	
	public $db;
	
	public function __construct($dbhandle){
	
		$this->db = $dbhandle;
	
	}
	
	/**
	 * 在数据表中新增一行数据
	 * @param table 数据表
	 * @param row 数组形式，数组的键是数据表中的字段名，键对应的值是需要新增的数据。
	 */
	public function create($table,$row)
	{

	}
	
	public function replace(){
		
	}
	
	/**
	 * 修改数据，该函数将根据参数中设置的条件而更新表中数据
	 * @param table 数据表
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
				$condition = $this->escape($condition);
				$join[] = "{$key} = {$condition}";
			}
			$where = "WHERE ".join(" AND ",$join);
		}else{
			if(null != $conditions)$where = "WHERE ".$conditions;
		}
		foreach($row as $key => $value){
			$value = $this->escape($value);
			$vals[] = "{$key} = {$value}";
		}
		$values = join(", ",$vals);
		$sql = "UPDATE ".dbprefix."{$table} SET {$values} {$where}";
		return $this->db->query($sql);
	}
	
	public function delete(){
		
	}
	
	/**
	 * 从数据表中查找一条记录
	 * @param table 数据表
	 * @param conditions    查找条件，数组array("字段名"=>"查找值")或字符串，
	 * 请注意在使用字符串时将需要开发者自行使用escape来对输入值进行过滤
	 * @param fields    返回的字段范围，默认为返回全部字段的值
	 * @param sort    排序，等同于“ORDER BY ”
	 */
	public function find($table,$conditions = null, $fields = null, $sort = null)
	{
		if( $record = $this->findAll($table,$conditions, $sort, $fields, 1) ){
			return array_pop($record);
		}else{
			return FALSE;
		}
	}
	
	/**
	 * 从数据表中查找记录
	 *	@param table 数据表
	 * @param conditions    查找条件，数组array("字段名"=>"查找值")或字符串，
	 * 请注意在使用字符串时将需要开发者自行使用escape来对输入值进行过滤
	 * @param sort    排序，等同于“ORDER BY ”
	 * @param fields    返回的字段范围，默认为返回全部字段的值
	 * @param limit    返回的结果数量限制，等同于“LIMIT ”，如$limit = " 3, 5"，即是从第3条记录（从0开始计算）开始获取，共获取5条记录
	 *                 如果limit值只有一个数字，则是指代从0条记录开始。
	 */
	public function findAll($table,$conditions = null, $sort = null, $fields = null, $limit = null)
	{
		$where = "";
		$fields = empty($fields) ? "*" : $fields;
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
		if(null != $sort){
			$sort = "ORDER BY {$sort}";
		}else{
			$sort = "";
		}
		$sql = "SELECT {$fields} FROM ".dbprefix."{$table} {$where} {$sort}";
		if(null != $limit)$sql = $this->db->setlimit($sql, $limit);
		return $this->db->fetch_all_assoc($sql);
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