<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );
/*
 * MySQL缓存 默认为ts_cache表
 */
class tsMySqlCache extends tsApp {
	public function get($name) {
		$result = $this->find ( 'cache', array (
				'cachename' => $name 
		), 'cachevalue', 'cacheid DESC' );
		
		if (! $result = array_pop ( $result ))
			return FALSE;
			// if( substr($result, 0, 10) < time() ){$this->del($name);return FALSE;}
		return unserialize ( substr ( $result, 10 ) );
	}
	public function set($name, $value, $life_time) {
		$value = (time () + $life_time) . serialize ( $value );
		if ($this->findCount ( 'cache', array (
				'cachename' => $name 
		) ) > 0) {
			return $this->updateField ( 'cache', array (
					'cachename' => $name 
			), 'cachevalue', $value );
		} else {
			return $this->create ( 'cache', array (
					'cachename' => $name,
					'cachevalue' => $value 
			) );
		}
	}
	public function del($name) {
		return $this->delete ( 'cache', array (
				'cachename' => $name 
		) );
	}
	/**
	 * 数据库缓存到本地文件
	 * @return unknown
	 */
	public function file(){
		 $arrCache = $this->findAll('cache');
		 foreach($arrCache as $key=>$item){
		 	fileWrite($item['cachename'].'.php','data',$this->get($item['cachename']));
		 }
	}
}