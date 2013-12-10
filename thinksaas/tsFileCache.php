<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );
class tsFileCache {
	private $cache_path; // path for the cache
	private $cache_expire; // seconds that the cache expires
	                       
	// cache constructor, optional expiring time and cache path
	public function tsFileCache($exp_time = 3600, $path = "data/") {
		$this->cache_expire = $exp_time;
		$this->cache_path = $path;
	}
	
	// returns the filename for the cache
	private function fileName($key) {
		return $this->cache_path . md5 ( $key );
	}
	
	// creates new cache files with the given data, $key== name of the cache, data the info/values to store
	public function put($key, $data) {
		$values = serialize ( $data );
		$filename = $this->fileName ( $key );
		$file = fopen ( $filename, 'w' );
		if ($file) { // able to create the file
			fwrite ( $file, $values );
			fclose ( $file );
		} else
			return false;
	}
	
	// returns cache for the given key
	public function get($key) {
		$filename = $this->fileName ( $key );
		if (! file_exists ( $filename ) || ! is_readable ( $filename )) { // can't read the cache
			return false;
		}
		if (time () < (filemtime ( $filename ) + $this->cache_expire)) { // cache for the key not expired
			$file = fopen ( $filename, "r" ); // read data file
			if ($file) { // able to open the file
				$data = fread ( $file, filesize ( $filename ) );
				fclose ( $file );
				return unserialize ( $data ); // return the values
			} else
				return false;
		} else
			return false; // was expired you need to create new
	}
}


/*

使用说明：

1、实例化

$cache = new tsFileCache();
2、设置缓存时间和缓存目录

$cache = new tsFileCache(60, '/any_other_path/');
第一个参数是缓存秒数，第二个参数是缓存路径，根据需要配置。
默认情况下，缓存时间是 3600 秒，缓存目录是 cache/

3、读取缓存

$value = $cache->get('data_key');
4、写入缓存

$value = $cache->put('data_key', 'data_value');
完整实例：

$cache = new tsCache();

//从缓存从读取键值 $key 的数据
$values = $cache->get($key);

//如果没有缓存数据
if ($values == false) {
	//insert code here...
	//写入键值 $key 的数据
	$cache->put($key, $values);
} else {
	//insert code here...
}

*/