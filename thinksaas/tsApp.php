<?php
// ///////////////////////////////////////////////////////////////////////
// ThinkSAAS中文社区, Copyright (C) 2011 - 2012 ThinkSAAS.cn //
// //////////////////////////////////////////////////////////////////////
defined('IN_TS') or die('Access Denied.');
class tsApp {

    /**
     * 数据驱动程序
     */
	public $db;


	public function __construct($dbhandle) {
		$this->db = $dbhandle;
	}

    /**
     * 在数据表中新增一行数据
     * @table 字符，数据表
     * @param row 数组形式，数组的键是数据表中的字段名，键对应的值是需要新增的数据。
     * @return bool
     */
	public function create($table, $row) {
		if (! is_array ( $row ) || empty ( $row ))
			return FALSE;
		$sql = '';
		if (empty ( $row ))
			return FALSE;
		foreach ( $row as $key => $value ) {
			$cols [] = $key;
			$vals [] = $this->escape ( $value );
		}
		$col = join ( ',', $cols );
		$val = join ( ',', $vals );
		
		$sql = "INSERT INTO " . dbprefix . $table . " ({$col}) VALUES ({$val})";
		
		if (FALSE != $this->db->query ( $sql )) { // 获取当前新增的ID
			if ($newinserid = $this->db->insert_id ()) {
				return $newinserid;
			}
		}
		return FALSE;
	}
	
	/**
	 * 替换数据，根据条件替换存在的记录，如记录不存在，则将条件与替换数据相加并新增一条记录。
	 * 
	 * @param table 数据表
	 * @param conditions 数组形式，查找条件，请注意，仅能使用数组作为该条件！
	 * @param row 数组形式，修改的数据
	 */
	public function replace($table, $conditions, $row) {
		if ($this->find ( $table, $conditions )) {
			return $this->update ( $table, $conditions, $row );
		} else {
			if (! is_array ( $conditions ))
				qiMsg ( 'replace方法的条件务必是数组形式！' );
			return $this->create ( $table, $row );
		}
	}
	
	/**
	 * 修改数据，该函数将根据参数中设置的条件而更新表中数据
	 * 
	 * @param table 数据表
	 * @param conditions 数组形式，查找条件，此参数的格式用法与find/findAll的查找条件参数是相同的。
	 * @param row 数组形式，修改的数据，此参数的格式用法与create的$row是相同的。在符合条件的记录中，将对$row设置的字段的数据进行修改。
	 */
	public function update($table, $conditions, $row) {
		$where = "";
		if (empty ( $row ))
			return FALSE;
		if (is_array ( $conditions )) {
			$join = array ();
			foreach ( $conditions as $key => $condition ) {
				$condition = $this->escape ( $condition );
				$join [] = "`{$key}` = {$condition}";
			}
			$where = "WHERE " . join ( " AND ", $join );
		} else {
			if (null != $conditions)
				$where = "WHERE " . $conditions;
		}
		foreach ( $row as $key => $value ) {
			$value = $this->escape ( $value );
			//$vals [] = "`$key` = $value";
			$vals [] = "{$key} = {$value}";
		}
		$values = join ( ", ", $vals );
		$sql = "UPDATE " . dbprefix . "{$table} SET {$values} {$where}";
		
		return $this->db->query ( $sql );
	}
	
	/**
	 * 按条件删除记录
	 * 
	 * @param table
	 * @param conditions 数组形式，查找条件，此参数的格式用法与find/findAll的查找条件参数是相同的。
	 */
	public function delete($table, $conditions) {
		$where = "";
		if (is_array ( $conditions )) {
			$join = array ();
			foreach ( $conditions as $key => $condition ) {
				$condition = $this->escape ( $condition );
				$join [] = "`{$key}` = {$condition}";
			}
			$where = "WHERE ( " . join ( " AND ", $join ) . ")";
		} else {
			if (null != $conditions)
				$where = "WHERE ( " . $conditions . ")";
		}
		$sql = "DELETE FROM " . dbprefix . "{$table} {$where}";
		return $this->db->query ( $sql );
	}
	
	/**
	 * 从数据表中查找一条记录
	 * 
	 * @param table 数据表
	 * @param conditions 查找条件，数组array("字段名"=>"查找值")或字符串，请注意在使用字符串时将需要开发者自行使用escape来对输入值进行过滤
	 * @param fields 返回的字段范围，默认为返回全部字段的值
	 * @param sort 排序，等同于“ORDER BY ”
	 */
	public function find($table, $conditions = null, $fields = null, $sort = null) {
		if ($record = $this->findAll ( $table, $conditions, $sort, $fields, 1 )) {
			return array_pop ( $record );
		} else {
			return FALSE;
		}
	}
	
	/**
	 * 从数据表中查找记录
	 * 
	 * @param table 数据表
	 * @param conditions 查找条件，数组array("字段名"=>"查找值")或字符串，请注意在使用字符串时将需要开发者自行使用escape来对输入值进行过滤
	 * @param sort 排序，等同于“ORDER BY ”
	 * @param fields 返回的字段范围，默认为返回全部字段的值
	 * @param limit 返回的结果数量限制，等同于“LIMIT ”，如$limit = " 3, 5"，即是从第3条记录（从0开始计算）开始获取，共获取5条记录。如果limit值只有一个数字，则是指代从0条记录开始。
	 */
	public function findAll($table, $conditions = null, $sort = null, $fields = null, $limit = null) {
		$where = "";
		$fields = empty ( $fields ) ? "*" : $fields;
		if (is_array ( $conditions )) {
			$join = array ();
			foreach ( $conditions as $key => $condition ) {
				$condition = $this->escape ( $condition );
				$join [] = "`{$key}` = {$condition}";
			}
			$where = "WHERE " . join ( " AND ", $join );
		} else {
			if (null != $conditions)
				$where = "WHERE " . $conditions;
		}
		if (null != $sort) {
			$sort = "ORDER BY {$sort}";
		} else {
			$sort = "";
		}
		$sql = "SELECT {$fields} FROM " . dbprefix . "{$table} {$where} {$sort}";
		if (null != $limit)
			$sql = $this->db->setlimit ( $sql, $limit );
		return $this->db->fetch_all_assoc ( $sql );
	}
	
	/**
	 * 过滤转义字符
	 *
	 * @param value 需要进行过滤的值
	 */
	public function escape($value) {
		return $this->db->escape ( $value );
	}
	
	/**
	 * 计算符合条件的记录数量
	 * 
	 * @param table 数据表
	 * @param conditions 查找条件，数组array("字段名"=>"查找值")或字符串，请注意在使用字符串时将需要开发者自行使用escape来对输入值进行过滤
	 */
	public function findCount($table, $conditions = null) {
		$where = "";
		if (is_array ( $conditions )) {
			$join = array ();
			foreach ( $conditions as $key => $condition ) {
				$condition = $this->escape ( $condition );
				$join [] = "`{$key}` = {$condition}";
			}
			$where = "WHERE " . join ( " AND ", $join );
		} else {
			if (null != $conditions)
				$where = "WHERE " . $conditions;
		}
		$sql = "SELECT COUNT(*) AS ts_counter FROM " . dbprefix . "{$table} {$where}";
		$result = $this->db->once_fetch_assoc ( $sql );
		
		return $result ['ts_counter'];
	}
	
	/**
	 * 按字段值修改一条记录
	 *
	 * @param conditions 数组形式，查找条件，此参数的格式用法与find/findAll的查找条件参数是相同的。
	 * @param field 字符串，对应数据表中的需要修改的字段名
	 * @param value 字符串，新值
	 */
	public function updateField($table, $conditions, $field, $value) {
		return $this->update ( $table, $conditions, array (
				$field => $value 
		) );
	}

    /**
     * 执行SQL语句，相等于执行新增，修改，删除等操作。
     *
     * @param sql 字符串，需要执行的SQL语句
     */
    public function doSql($sql){
        return $this->db->query($sql);
    }

    /**
     * 在数据表中新增多条记录
     * @param table 数据表
     * @param rows 数组形式，每项均为create的$row的一个数组
     */
    public function createAll($table,$rows)
    {
        foreach($rows as $row)$this->create($table,$row);
    }

    /**
     * 按字段值查找一条记录
     * @param table 数据表
     * @param field 字符串，对应数据表中的字段名
     * @param value 字符串，对应的值
     */
    public function findBy($table,$field, $value)
    {
        return $this->find($table,array($field=>$value));
    }

    /**
     * 返回最后执行的SQL语句供分析
     */
    public function dumpSql()
    {
        return end( $this->db->arrSql );
    }

    /**
     * 返回上次执行update,create,delete,exec的影响行数
     */
    public function affectedRows()
    {
        return $this->db->affected_rows();
    }


    /**
     * 为设定的字段值增加
     * @param table 数据库表
     * @param conditions    数组形式，查找条件，此参数的格式用法与find/findAll的查找条件参数是相同的。
     * @param field    字符串，需要增加的字段名称，该字段务必是数值类型
     * @param optval    增加的值
     */
    public function incrField($table,$conditions, $field, $optval = 1)
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
        $values = "{$field} = {$field} + {$optval}";
        $sql = "UPDATE ".dbprefix."{$table} SET {$values} {$where}";
        return $this->db->query($sql);
    }

    /**
     * 为设定的字段值减少
     * @param table 数据表
     * @param conditions    数组形式，查找条件，此参数的格式用法与find/findAll的查找条件参数是相同的。
     * @param field    字符串，需要减少的字段名称，该字段务必是数值类型
     * @param optval    减少的值
     */
    public function decrField($table,$conditions, $field, $optval = 1)
    {
        return $this->incrField($table,$conditions, $field, - $optval);
    }

}