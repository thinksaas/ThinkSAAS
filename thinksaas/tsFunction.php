<?php
defined('IN_TS') or die('Access Denied.');
/*
 * ThinkSAAS core function
 * @copyright (c) ThinkSAAS All Rights Reserved
 * @code by QiuJun
 * @Email:thinksaas@qq.com
 * @TIME:2010-12-18
 */

/**
 * 加载某一APP类
 * AutoAppClass
 * @app APP名称
 * @
 * @param $app
 * @return bool
 */
function aac($app) {

	$path = THINKAPP . '/' . $app . '/';
	if (!class_exists($app)) {
		require_once $path . 'class.' . $app . '.php';
	}
	if (!class_exists($app)) {
		return false;
	}
	$obj = new $app($GLOBALS['db']);
	return $obj;
}

/**
 * 二维数组的根据不同键值来排序。 第一个参数是二位数组名，第二个是依据键，第三个是升序还是降序，默认是升序
 * @param unknown $arr
 * @param unknown $keys
 * @param string $type
 * @return multitype:unknown
 */
function array_sort($arr, $keys, $type = 'asc') {
	$keysvalue = $new_array = array();
	foreach ($arr as $k => $v) {
		$keysvalue[$k] = $v[$keys];
	}
	if ($type == 'asc') {
		asort($keysvalue);
	} else {
		arsort($keysvalue);
	}
	reset($keysvalue);
	foreach ($keysvalue as $k => $v) {
		$new_array[$k] = $arr[$k];
	}
	return $new_array;
}

/**
 * ThinkSAAS Notice
 * @param unknown $notice
 * @param string $button
 * @param string $url
 * @param string $isAutoGo
 */
function tsNotice($notice, $button = '点击返回', $url = 'javascript:history.back(-1);', $isAutoGo = false) {
	global $runTime;
	$title = '提示：';
	include  pubTemplate('notice');
	exit();
}

/**
 * 系统消息
 * @param unknown $msg
 * @param string $button
 * @param string $url
 * @param string $isAutoGo
 */
function qiMsg($msg, $button = '点击返回>>', $url = 'javascript:history.back(-1);', $isAutoGo = false) {
    echo <<<EOT
<html>
<head>
EOT;
	if ($isAutoGo) {
		echo "<meta http-equiv=\"refresh\" content=\"2;url=$url\" />";
	}
	echo <<<EOT
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ThinkSAAS 提示</title>
<style type="text/css">
<!--
body {
font-family: Arial;
font-size: 12px;
line-height:150%;
text-align:center;
}
a{color:#555555;}
.main {
width:500px;
background-color:#FFFFFF;
font-size: 12px;
color: #666666;
margin:100px auto 0;
list-style:none;
padding:20px;
}
.main p {
line-height: 18px;
margin: 5px 20px;
font-size:14px;
}
-->
</style>
</head>
<body>
<div class="main">
<p>$msg</p>
<p><a href="$url">$button</a></p>
</div>
</body>
</html>
EOT;
	exit();
}

/**
 * 分页函数
 * @param unknown $count
 * @param unknown $perlogs
 * @param unknown $page
 * @param unknown $url
 * @param string $suffix
 * @return string
 */
function pagination($count, $perlogs, $page, $url, $suffix = '') {

	$urlset = $GLOBALS['TS_SITE']['site_urltype'];
	if ($urlset == 3) {
		$suffix = '.html';
	} elseif ($urlset == 7) {
		if($ac=='admin'){
			$suffix = '';
		}else{
			$suffix = '/';
		}
	}

	$pnums = @ceil($count / $perlogs);
	$re = '';
	for ($i = $page - 5; $i <= $page + 5 && $i <= $pnums; $i++) {
		if ($i > 0) {
			if ($i == $page) {
				$re .= ' <span class="current">' . $i . '</span> ';
			} else {
				$re .= '<a href="' . $url . $i . $suffix . '">' . $i . '</a>';
			}
		}
	}
	if ($page > 6)
		$re = '<a href="' . $url . '1' . $suffix . '" title="首页">&laquo;</a> ...' . $re;
	if ($page + 5 < $pnums)
		$re .= '... <a href="' . $url . $pnums . $suffix . '" title="尾页">&raquo;</a>';
	if ($pnums <= 1)
		$re = '';
	return $re;
}

/**
 * 验证Email
 * @param unknown $email
 * @return boolean
 */
function valid_email($email) {
	if (preg_match('/^[A-Za-z0-9]+([._\-\+]*[A-Za-z0-9]+)*@([A-Za-z0-9-]+\.)+[A-Za-z0-9]+$/', $email)) {
		return true;
	} else {
		return false;
	}
}

/**
 * 处理时间的函数
 * @param unknown $btime
 * @param unknown $etime
 * @return string
 */
function getTime($btime, $etime = null) {
	if ($etime == null)
		$etime = time();
	if ($btime < $etime) {
		$stime = $btime;
		$endtime = $etime;
	} else {
		$stime = $etime;
		$endtime = $btime;
	}
	$timec = $endtime - $stime;
	$days = intval($timec / 86400);
	$rtime = $timec % 86400;
	$hours = intval($rtime / 3600);
	$rtime = $rtime % 3600;
	$mins = intval($rtime / 60);
	$secs = $rtime % 60;
	if ($days >= 1) {
		return $days . ' 天前';
	}
	if ($hours >= 1) {
		return $hours . ' 小时前';
	}

	if ($mins >= 1) {
		return $mins . ' 分钟前';
	}
	if ($secs >= 1) {
		return $secs . ' 秒前';
	}
}

/**
 * 获取用户IP
 * @return Ambigous <string, unknown>
 */
function getIp() {
	if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$PHP_IP = getenv('HTTP_CLIENT_IP');
	} elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$PHP_IP = getenv('HTTP_X_FORWARDED_FOR');
	} elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$PHP_IP = getenv('REMOTE_ADDR');
	} elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$PHP_IP = $_SERVER['REMOTE_ADDR'];
	}
	preg_match("/[\d\.]{7,15}/", $PHP_IP, $ipmatches);
	$PHP_IP = $ipmatches[0] ? $ipmatches[0] : 'unknown';
	return $PHP_IP;
}

/**
 * 纯文本输入
 * @param unknown $text
 * @return mixed
 */
function t($text) {
	$text = tsDecode($text);
	$text = @preg_replace('/\[.*?\]/is', '', $text);
	$text = cleanJs($text);
	// 彻底过滤空格BY QINIAO
	$text = @preg_replace('/\s(?=\s)/', '', $text);
	$text = @preg_replace('/[\n\r\t]/', ' ', $text);
	$text = str_replace('  ', ' ', $text);
	// $text = str_replace ( ' ', '', $text );
	$text = str_replace('&nbsp;', '', $text);
	$text = str_replace('&', '', $text);
	$text = str_replace('=', '', $text);
	$text = str_replace('-', '', $text);
	$text = str_replace('#', '', $text);
	$text = str_replace('%', '', $text);
	$text = str_replace('!', '', $text);
	$text = str_replace('@', '', $text);
	$text = str_replace('^', '', $text);
	$text = str_replace('*', '', $text);
	$text = str_replace('amp;', '', $text);

	$text = str_replace('position', '', $text);

	$text = strip_tags($text);
	$text = htmlspecialchars($text);
	$text = str_replace("'", "", $text);
	return $text;
}

/**
 * 输入安全的html，针对存入数据库中的数据进行的过滤和转义
 * @param unknown $text
 * @return string
 */
function h($text) {
	$text = trim($text);
	$text = htmlspecialchars($text);
	$text = addslashes($text);
	return $text;
}

/**
 * utf-8截取
 * @param unknown $string
 * @param number $start
 * @param unknown $sublen
 * @param string $append
 * @return string
 */
function cututf8($string, $start = 0, $sublen, $append = true) {
	$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
	preg_match_all($pa, $string, $t_string);
	if (count($t_string[0]) - $start > $sublen && $append == true) {
		return join('', array_slice($t_string[0], $start, $sublen)) . "...";
	} else {
		return join('', array_slice($t_string[0], $start, $sublen));
	}
}

/**
 * 计算时间
 * @return number
 */
function getmicrotime() {
	list($usec, $sec) = explode(" ", microtime());
	return (( float )$usec + ( float )$sec);
}

/**
 * 写入文件，支持Memcache缓存
 * @param unknown $file 缓存文件
 * @param unknown $dir 缓存目录
 * @param unknown $data	缓存内容
 * @param number $isphp
 * @return boolean
 */
function fileWrite($file, $dir, $data, $isphp = 1) {

	$dfile = $dir . '/' . $file;

	// 支持memcache
	if ($GLOBALS['TS_CF']['memcache'] && extension_loaded('memcache')) {
		$GLOBALS['TS_MC'] -> delete(md5($dfile));
        $GLOBALS['TS_MC'] -> set(md5($dfile), $data, 0, 172800);
	}

	// 同时保存文件
	!is_dir($dir) ? mkdir($dir, 0777) : '';
	if (is_file($dfile))
		unlink($dfile);
	if ($isphp == 1) {
		$data = "<?php\ndefined('IN_TS') or die('Access Denied.');\nreturn " . var_export($data, true) . ";";
	}

	file_put_contents($dfile, $data);

	return true;
}

/**
 * 读取文件 支持Memcache缓存 $dfile 文件
 * @param unknown $dfile
 */
function fileRead($dfile) {

	// 支持memcache
	if ($GLOBALS['TS_CF']['memcache'] && extension_loaded('memcache')) {

		if ($GLOBALS['TS_MC'] -> get(md5($dfile))) {
			return $GLOBALS['TS_MC'] -> get(md5($dfile));
		} else {
			if (is_file($dfile))
				return
				include $dfile;
		}
	} else {

		if (is_file($dfile))
			return
			include $dfile;
	}
}

/**
 * 把数组转换为,号分割的字符串
 * @param unknown $arr
 * @return Ambigous <string, unknown>
 */
function arr2str($arr) {
	$str = '';
	$count = 1;
	if (is_array($arr)) {
		foreach ($arr as $a) {
			if ($count == 1) {
				$str .= $a;
			} else {
				$str .= ',' . $a;
			}
			$count++;
		}
	}
	return $str;
}

/**
 * 生成随机数(1数字,0字母数字组合)
 * @param unknown $length
 * @param number $numeric
 * @return string
 */
function random($length, $numeric = 0) {
	PHP_VERSION < '4.2.0' ? mt_srand(( double ) microtime() * 1000000) : mt_srand();
	$seed = base_convert(md5(print_r($_SERVER, 1) . microtime()), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
	$hash = '';
	$max = strlen($seed) - 1;
	for ($i = 0; $i < $length; $i++) {
		$hash .= $seed[mt_rand(0, $max)];
	}
	return $hash;
}

/**
 * 封装一个采集函数
 * @param unknown $url	网址
 * @param unknown $proxy	代理
 * @param unknown $timeout	跳出时间
 * @return mixed
 */
function getHtmlByCurl($url, $proxy, $timeout) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_PROXY, $proxy);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$file_contents = curl_exec($ch);
	return $file_contents;
}

/**
 * 计算文件大小
 * @param unknown $size
 * @return string
 */
function format_bytes($size) {
	$units = array(' B', ' KB', ' MB', ' GB', ' TB');
	for ($i = 0; $size >= 1024 && $i < 4; $i++)
		$size /= 1024;
	return round($size, 2) . $units[$i];
}

/**
 * 对象转数组
 * @param unknown $array
 * @return array
 */
function object2array($array) {
	if (is_object($array)) {
		$array = ( array )$array;
	}
	if (is_array($array)) {
		foreach ($array as $key => $value) {
			$array[$key] = object2array($value);
		}
	}
	return $array;
}

/**
 * 写文件
 *
 * @param string $file
 *        	- 需要写入的文件，系统的绝对路径加文件名
 * @param string $content
 *        	- 需要写入的内容
 * @param string $mod
 *        	- 写入模式，默认为w
 * @param boolean $exit
 *        	- 不能写入是否中断程序，默认为中断
 * @return boolean 返回是否写入成功
 *
 */
function isWriteFile($file, $content, $mod = 'w', $exit = TRUE) {
	if (!@$fp = @fopen($file, $mod)) {
		if ($exit) {
			exit('ThinkSAAS File :<br>' . $file . '<br>Have no access to write!');
		} else {
			return false;
		}
	} else {
		@flock($fp, 2);
		@fwrite($fp, $content);
		@fclose($fp);
		return true;
	}
}

/**
 * 创建目录
 * @param unknown $dir
 * @return boolean
 */
function makedir($dir) {
	return is_dir($dir) or (makedir(dirname($dir)) and mkdir($dir, 0777));
}

/**
 * 加载模板
 * @param string $file
 *        	- 模板文件名
 * @return string 返回编译后模板的系统绝对路径
 * @param array $self
 *        	自定义路径，必须是数组格式
 *
 */
function template($file, $plugin = '', $self = '') {

	if ($plugin) {
		$tplfile = 'plugins/' . $GLOBALS['TS_URL']['app'] . '/' . $plugin . '/' . $file . '.html';
		if (!is_file($tplfile)) {
			$tplfile = 'plugins/pubs/' . $plugin . '/' . $file . '.html';
		}
		$objfile = 'cache/template/' . $plugin . '.' . $file . '.tpl.php';
	} else if ($self) {
        $tplfile ='';
		foreach ($self as $v) {
            $tplfile .= $v . '/';
			$cache = $v . '_';
		}
		$tplfile = $tplfile . $file . '.html';
		$objfile = 'cache/template/self/' . $self[3] . '.' . $file . '.tpl.php';
	} else {
		$tplfile = 'app/' . $GLOBALS['TS_URL']['app'] . '/html/' . $file . '.html';
		$objfile = 'cache/template/' . $GLOBALS['TS_URL']['app'] . '.' . $file . '.tpl.php';
	}

	if (@filemtime($tplfile) > @filemtime($objfile)) {
		// note 加载模板类文件
		require_once 'thinksaas/tsTemplate.php';
		$T = new tsTemplate();

		$T -> complie($tplfile, $objfile);
	}

	return $objfile;
}

/**
 * 加载公用html模板文件
 * @param unknown $file
 * @return string
 */
function pubTemplate($file) {
	$tplfile = 'public/html/' . $file . '.html';
	$objfile = 'cache/template/public.' . $file . '.tpl.php';

	if (@filemtime($tplfile) > @filemtime($objfile)) {
		// note 加载模板类文件

		require_once 'thinksaas/tsTemplate.php';
		$T = new tsTemplate();

		$T -> complie($tplfile, $objfile);
	}

	return $objfile;
}

/**
 * 该函数在插件中调用,挂载插件函数到预留的钩子上,针对app各个的插件部分，修改自Emlog
 *
 * @param string $hook
 * @param string $actionFunc
 * @return boolearn
 *
 */
function addAction($hook, $actionFunc) {
	global $tsHooks;
	if (!@in_array($actionFunc, $tsHooks[$hook])) {
		$tsHooks[$hook][] = $actionFunc;
	}

	return true;
}

/**
 * 执行挂在钩子上的函数,支持多参数 eg:doAction('post_comment', $author, $email, $url, $comment);
 * @param string $hook
 */
function doAction($hook) {
	global $tsHooks;
	$args = array_slice(func_get_args(), 1);
	if (isset($tsHooks[$hook])) {
		foreach ($tsHooks [$hook] as $function) {
			$string = call_user_func_array($function, $args);
		}
	}

	if ($GLOBALS['TS_CF']['hook'])
		var_dump($hook);
}

function createFolders($path) {
	// 递归创建
	if (!file_exists($path)) {
		createFolders(dirname($path));
		// 取得最后一个文件夹的全路径返回开始的地方
		mkdir($path, 0777);
	}
}

/**
 * 删除文件夹和文件夹下所有的文件
 * @param string $dir
 * @return boolean
 */
function delDir($dir = '') {
	if (empty($dir)) {
		$dir = rtrim(RUNTIME_PATH, '/');
	}
	if (substr($dir, -1) == '/') {
		$dir = rtrim($dir, '/');
	}
	if (!file_exists($dir))
		return true;
	if (!is_dir($dir) || is_link($dir))
		return @unlink($dir);
	foreach (scandir ( $dir ) as $item) {
		if ($item == '.' || $item == '..')
			continue;
		if (!delDir($dir . "/" . $item)) {
			@chmod($dir . "/" . $item, 0777);
			if (!delDir($dir . "/" . $item))
				return false;
		};
	}
	return @rmdir($dir);
}

/**
 * 获取带http的网站域名
 * @return string
 */
function getHttpUrl() {
	$arrUri = explode('index.php', $_SERVER['REQUEST_URI']);
	$site_url = 'http://' . $_SERVER['HTTP_HOST'] . $arrUri[0];
	return $site_url;
}

/**
 * 10位MD5值
 * @param string $str
 * @return string
 */
function md10($str = '') {
	return substr(md5($str), 10, 10);
}

/**
 * ThinkSAAS专用图片截图函数
 * @param unknown $file	数据库里的图片url
 * @param unknown $app	app名称
 * @param unknown $w	缩略图片宽度
 * @param unknown $h	缩略图片高度
 * @param string $path
 * @param string $c	1裁切,0不裁切
 * @return void|string
 */
function tsXimg($file, $app, $w, $h, $path = '', $c = '0') {

	if (!$file) {
		return false;
	} else {

		//$info = explode ( '.', $file );
		//$name = md10 ( $file ) . '_' . $w . '_' . $h . '.' . $info [1];

		$info = explode('/', $file);
		$name = $info[2];

		if ($path == '') {
			$cpath = 'cache/' . $app . '/' . $w . '/' . $name;
		} else {
			$cpath = 'cache/' . $app . '/' . $path . '/' . $w . '/' . $name;
		}

		if (!is_file($cpath)) {
			createFolders('cache/' . $app . '/' . $path . '/' . $w);
			$dest = 'uploadfile/' . $app . '/' . $file;
			$arrImg = getimagesize($dest);
			if ($arrImg[0] <= $w) {
				copy($dest, $cpath);

			} else {
				require_once 'thinksaas/tsImage.php';
				$resizeimage = new tsImage("$dest", $w, $h, $c, "$cpath");
			}
		}

		return SITE_URL . $cpath;

	}
}

/**
 * TS专用删除缓存图片
 * @param unknown $file 数据库里的图片url
 * @param unknown $app app名称
 * @param unknown $w 缩略图片宽度
 * @param unknown $h 缩略图片高度
 * @param unknown $path
 * @return boolean
 */
function tsDimg($file, $app, $w, $h, $path) {

	$info = explode('/', $file);
	$name = $info[2];

	unlink('cache/' . $app . '/' . $path . '/' . $w . '/' . $name);
	return true;
}

/**
 * gzip压缩输出
 * @param unknown $content
 * @return string
 */
function ob_gzip($content) {
	if (!headers_sent() && extension_loaded("zlib") && strstr($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip")) {
		// $content = gzencode($content." \n//此页已压缩",9);
		$content = gzencode($content, 9);
		header("Content-Encoding: gzip");
		header("Vary: Accept-Encoding");
		header("Content-Length: " . strlen($content));
	}
	return $content;
}

/**
 * tsUrl提供至少4种的url展示方式
 * (1)index.php?app=group&ac=topic&topicid=1 //标准默认模式
 * (2)index.php/group/topic/topicid-1 //path_info模式
 * (3)group-topic-topicid-1.html //rewrite模式1
 * (4)group/topic/topicid-1 //rewrite模式2
 * @param unknown $app
 * @param string $ac
 * @param unknown $params
 * @return string
 */
function tsUrl($app, $ac = '', $params = array()) {

	$urlset = $GLOBALS['TS_SITE']['site_urltype'];
    $str ='';
	if ($urlset == 1) {
		foreach ($params as $k => $v) {
			$str .= '&' . $k . '=' . $v;
		}
		if ($ac == '') {
			$ac = '';
		} else {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				$ac = '?ac=' . $ac;
			} else {
				$ac = '&ac=' . $ac;
			}
		}
		if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
			$url = 'index.php' . $ac . $str;
		} else {
			$url = 'index.php?app=' . $app . $ac . $str;
		}
	} elseif ($urlset == 2) {

		foreach ($params as $k => $v) {
            $str .= '/' . $k . '-' . $v;
		}
		if ($ac == '') {
			$ac = '';
		} else {
			$ac = '/' . $ac;
		}

		if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
			$url = 'index.php' . $ac . $str;
		} else {
			$url = 'index.php/' . $app . $ac . $str;
		}
	} elseif ($urlset == 3) {
		foreach ($params as $k => $v) {
			$str .= '-' . $k . '-' . $v;
		}

		if ($ac == '') {
			$ac = '';
		} else {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				$ac = $ac;
			} else {
				$ac = '-' . $ac;
			}
		}

		$page = strpos($str, 'page');

		if ($page) {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				$url = $ac . $str;
			} else {
				$url = $app . $ac . $str;
			}
		} else {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				$url = $ac . $str . '.html';
			} else {
				$url = $app . $ac . $str . '.html';
			}
		}
	} elseif ($urlset == 4) {
		foreach ($params as $k => $v) {
			$str .= '/' . $k . '-' . $v;
		}
		if ($ac == '') {
			$ac = '';
		} else {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				$ac = $ac;
			} else {
				$ac = '/' . $ac;
			}
		}
		if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
			$url = $ac . $str;
		} else {
			$url = $app . $ac . $str;
		}
	} elseif ($urlset == 5) {
		foreach ($params as $k => $v) {
			$str .= '/' . $k . '/' . $v;
		}
		$str = str_replace('/id', '', $str);
		if ($ac == '') {
			$ac = '';
		} else {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				$ac = $ac;
			} else {
				$ac = '/' . $ac;
			}
		}
		if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
			$url = $ac . $str;
		} else {
			$url = $app . $ac . $str;
		}
	} elseif ($urlset == 6) {
		foreach ($params as $k => $v) {
			$str .= '/' . $k . '/' . $v;
		}

		if ($ac == '') {
			$ac = '';
		} else {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				$ac = $ac;
			} else {
				$ac = '/' . $ac;
			}
		}
		if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
			$url = $ac . $str;
		} else {
			$url = $app . $ac . $str;
		}
	} elseif ($urlset == 7) {
		foreach ($params as $k => $v) {
			$str .= '/' . $k . '/' . $v;
		}
		$str = str_replace('/id', '', $str);
		if ($ac == '') {
			$ac = '';
		} else {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				$ac = $ac;
			} else {
				$ac = '/' . $ac;
			}
		}

		$page = strpos($str, 'page');

		if ($page) {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				$url = $ac . $str;
			} else {
				$url = $app . $ac . $str;
			}
		} else {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				if ($ac == '') {
					$url = '';
				} else {
					$url = $ac . $str . '/';
				}
			} else {
				$url = $app . $ac . $str . '/';
			}
		}
	}
	if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app'])) {
		return 'http://' . $app . '.' . $GLOBALS['TS_CF']['subdomain']['domain'] . '/' . $url;
	} elseif ($GLOBALS['TS_CF']['appdomain'][$app]) {
		return 'http://' . $GLOBALS['TS_CF']['appdomain'][$app] . '/' . $url;
	} else {
		return SITE_URL . $url;
	}
}

/**
 * reurl
 */
function reurl() {
	global $tsMySqlCache;
	$options = fileRead('data/system_options.php');

	if ($options == '') {
		$options = $tsMySqlCache -> get('system_options');
	}

	$scriptName = explode('index.php', $_SERVER['SCRIPT_NAME']);

	//获取到网站目录

	$rurl = substr($_SERVER['REQUEST_URI'], strlen($scriptName[0]));
	//过滤掉网站目录剩下的就是URL部分

	// 针对QQ开放平台做一下修改strpos($rurl,'?openid=')==true
	if (strpos($rurl, '?') == false || strpos($rurl, '?openid=') == true) {

		if (preg_match('/index.php/i', $rurl)) {
			$rurl = str_replace('index.php', '', $rurl);
			$rurl = substr($rurl, 1);
			$params = $rurl;
		} else {
			$params = $rurl;
		}

		if ($rurl) {

			if ($options['site_urltype'] == 3) {
				// http://localhost/group-topic-id-1.html
				$params = explode('.', $params);

				$params = explode('-', $params[0]);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['app'] = $v;
							break;
						case 1 :
							$_GET['ac'] = $v;
							break;
						default :
							if ($v)
								$kv[] = $v;

							break;
					}
				}

				$ck = count($kv) / 2;

				if ($ck >= 2) {
					$arrKv = array_chunk($kv, $ck);
					foreach ($arrKv as $key => $item) {
						$_GET[$item[0]] = $item[1];
					}
				} elseif ($ck == 1) {
					$_GET[$kv[0]] = $kv[1];
				} else {
				}
			} elseif ($options['site_urltype'] == 4) {
				// http://localhost/group/topic/id-1
				$params = explode('/', $params);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['app'] = $v;
							break;
						case 1 :
							$_GET['ac'] = $v;
							break;
						default :
							$kv = explode('-', $v);

							if (count($kv) > 1) {
								$_GET[$kv[0]] = $kv[1];
							} else {
								$_GET['params' . $p] = $kv[0];
							}
							break;
					}
				}
			} elseif ($options['site_urltype'] == 5) {
				// http://localhost/group/topic/1
				$params = explode('/', $params);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['app'] = $v;
							break;
						case 1 :
							$_GET['ac'] = $v;
							if (empty($_GET['ac']))
								$_GET['ac'] = 'index';
							break;
						case 2 :
							if ((( int )$v) > 0) {
								$_GET['id'] = $v;
								break;
							}
							// 处理TAG
							if ($_GET['ac'] == 'tag') {
								$_GET['id'] = $v;
								break;
							}

						default :
							$_GET[$v] = $params[$p + 1];
							break;
					}
				}
			} elseif ($options['site_urltype'] == 6) {
				// http://localhost/group/topic/id/1
				$params = explode('/', $params);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['app'] = $v;
							break;
						case 1 :
							$_GET['ac'] = $v;
							break;
						default :
							$_GET[$v] = $params[$p + 1];
							break;
					}
				}
			} elseif ($options['site_urltype'] == 7) {
				// http://localhost/group/topic/1/
				$params = explode('/', $params);
				//var_dump($params);
				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['app'] = $v;
							break;
						case 1 :
							$_GET['ac'] = $v;
							if (empty($_GET['ac']))
								$_GET['ac'] = 'index';
							break;
						case 2 :
							if ((( int )$v) > 0) {
								$_GET['id'] = $v;
								break;
							}
							// 处理TAG
							if ($_GET['ac'] == 'tag') {
								$_GET['id'] = $v;
								break;
							}

						default :
							$_GET[$v] = $params[$p + 1];

							break;
					}
				}
			} else {

				$params = explode('/', $params);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['app'] = $v;
							break;
						case 1 :
							$_GET['ac'] = $v;
							break;

							// 处理TAG
							if ($_GET['ac'] == 'tag') {
								$_GET['id'] = $v;
								break;
							}

						default :
							$kv = explode('-', $v);
							if (count($kv) > 1) {
								$_GET[$kv[0]] = $kv[1];
							} else {
								$_GET['params' . $p] = $kv[0];
							}

							break;
					}
				}
			}
		}
	}

	//规划化URL规则，对跳转到首页不符合规则的进行404处理
	if ($_GET['app'] == '' && $_GET['ac'] == '' && $rurl) {
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		echo '404 page by <a href="http://www.thinksaas.cn/">www.thinksaas.cn</a>';
		exit ;
	}

}

/**
 * 辅助APP二级域名
 */
function reurlsubdomain() {
	global $tsMySqlCache;
	$options = fileRead('data/system_options.php');
	if ($options == '') {
		$options = $tsMySqlCache -> get('system_options');
	}

	$scriptName = explode('index.php', $_SERVER['SCRIPT_NAME']);
	$rurl = substr($_SERVER['REQUEST_URI'], strlen($scriptName[0]));

	if (strpos($rurl, '?') == false) {

		if (preg_match('/index.php/i', $rurl)) {
			$rurl = str_replace('index.php', '', $rurl);
			$rurl = substr($rurl, 1);
			$params = $rurl;
		} else {
			$params = $rurl;
		}

		if ($rurl) {

			if ($options['site_urltype'] == 3) {
				// http://group.thinksaas.cn/topic-id-1.html
				$params = explode('.', $params);

				$params = explode('-', $params[0]);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['ac'] = $v;
							break;
						default :
							if ($v)
								$kv[] = $v;

							break;
					}
				}

				$ck = count($kv) / 2;

				if ($ck >= 2) {
					$arrKv = array_chunk($kv, $ck);
					foreach ($arrKv as $key => $item) {
						$_GET[$item[0]] = $item[1];
					}
				} elseif ($ck == 1) {
					$_GET[$kv[0]] = $kv[1];
				} else {
				}
			} elseif ($options['site_urltype'] == 4) {
				// http://group.thinksaas.cn/topic/id-1
				$params = explode('/', $params);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['ac'] = $v;
							break;
						default :
							$kv = explode('-', $v);

							if (count($kv) > 1) {
								$_GET[$kv[0]] = $kv[1];
							} else {
								$_GET['params' . $p] = $kv[0];
							}
							break;
					}
				}
			} elseif ($options['site_urltype'] == 5) {
				// http://group.thinksaas.cn/topic/1
				$params = explode('/', $params);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['ac'] = $v;
							if (empty($_GET['ac']))
								$_GET['ac'] = 'index';
							break;
						case 1 :
							if ((( int )$v) > 0) {
								$_GET['id'] = $v;
								break;
							}
						default :
							$_GET[$v] = $params[$p + 1];
							break;
					}
				}
			} elseif ($options['site_urltype'] == 6) {
				// http://group.thinksaas.cn/topic/id/1
				$params = explode('/', $params);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['ac'] = $v;
							break;
						default :
							$_GET[$v] = $params[$p + 1];
							break;
					}
				}
			} elseif ($options['site_urltype'] == 7) {
				// http://group.thinksaas.cn/topic/1/
				$params = explode('/', $params);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['ac'] = $v;
							if (empty($_GET['ac']))
								$_GET['ac'] = 'index';
							break;
						case 1 :
							if ((( int )$v) > 0) {
								$_GET['id'] = $v;
								break;
							}
						default :
							$_GET[$v] = $params[$p + 1];
							break;
					}
				}
			} else {

				$params = explode('/', $params);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['ac'] = $v;
							break;
						default :
							$kv = explode('-', $v);
							if (count($kv) > 1) {
								$_GET[$kv[0]] = $kv[1];
							} else {
								$_GET['params' . $p] = $kv[0];
							}
							break;
					}
				}
			}
		}
	}
}

/**
 * 检测目录是否可写1可写，0不可写
 * @param unknown $file
 * @return number
 */
function iswriteable($file) {
	if (is_dir($file)) {
		$dir = $file;
		if ($fp = fopen("$dir/test.txt", 'w')) {
			fclose($fp);
			unlink("$dir/test.txt");
			$writeable = 1;
		} else {
			$writeable = 0;
		}
	} else {
		if ($fp = fopen($file, 'a+')) {
			fclose($fp);
			$writeable = 1;
		} else {
			$writeable = 0;
		}
	}
	return $writeable;
}

/**
 * 删除目录下文件
 * @param unknown $dir
 */
function delDirFile($dir) {
	$arrFiles = dirList($dir, 'files');
	foreach ($arrFiles as $item) {
		unlink($dir . '/' . $item);
	}
}

/**
 * ThinkSAAS专用上传函数
 * @param unknown $files	要上传的文件 如$_FILES['photo']
 * @param unknown $projectid	上传针对的项目id 如$userid
 * @param unknown $dir	上传到目录 如 user
 * @param unknown $uptypes	上传类型，数组 array('jpg','png','gif')
 * @return multitype:string unknown mixed |boolean	返回数组：array('name'=>'','path'=>'','url'=>'','path'=>'','size'=>'')
 */
function tsUpload($files, $projectid, $dir, $uptypes) {
	
	if ($files['size'] > 0) {
	
	
		//上传图片大小控制
		if(in_array('png',$uptypes) || in_array('jpg',$uptypes) || in_array('gif',$uptypes) || in_array('jpeg',$uptypes)){
		
			if($GLOBALS['TS_SITE']['photo_size']){
				$upsize = $GLOBALS['TS_SITE']['photo_size']*1048576;
				
				if($files ['size']>$upsize){
					tsNotice('上传图片不能超过'.$GLOBALS['TS_SITE']['photo_size'].'M，请修改小点后再上传！');
				}
				
			}
		
		}
		

		$menu2 = intval($projectid / 1000);

		$menu1 = intval($menu2 / 1000);

		$path = $menu1 . '/' . $menu2;

		$dest_dir = 'uploadfile/' . $dir . '/' . $path;

		createFolders($dest_dir);

		//$ext = pathinfo($files['name'],PATHINFO_EXTENSION);

		$arrType = explode('.', strtolower($files['name']));
		// 转小写一下

		$type = array_pop($arrType);

		if (in_array($type, $uptypes)) {

			$name = $projectid . '.' . $type;

			$dest = $dest_dir . '/' . $name;

			// 先删除
			unlink($dest);
			// 后上传
			move_uploaded_file($files['tmp_name'], mb_convert_encoding($dest, "gb2312", "UTF-8"));

			chmod($dest, 0777);

			$filesize = filesize($dest);
			if (intval($filesize) > 0) {
				return array('name' => tsFilter($files['name']), 'path' => $path, 'url' => $path . '/' . $name, 'type' => $type, 'size' => tsFilter($files['size']));
			} else {
				return false;
			}
		} else {
			return false;
		}

	}
}

//网络上传
function tsUploadUrl($fileurl, $projectid, $dir, $uptypes) {
	$menu2 = intval($projectid / 1000);
	$menu1 = intval($menu2 / 1000);
	$path = $menu1 . '/' . $menu2;
	$dest_dir = 'uploadfile/' . $dir . '/' . $path;
	createFolders($dest_dir);
	$arrType = explode('.', $fileurl);
	//转小写一下
	$type = array_pop($arrType);
	if (in_array($type, $uptypes)) {
		$name = $projectid . '.' . $type;
		$dest = $dest_dir . '/' . $name;
		//先删除
		unlink($dest);
		//后上传
		$img = file_get_contents($fileurl);
		file_put_contents($dest, $img);
		chmod($dest, 0777);
		$filesize = filesize($dest);
		if (intval($filesize) > 0) {
			return array('name' => $name, 'path' => $path, 'url' => $path . '/' . $name, 'type' => $type, 'size' => $filesize, );
		} else {
			return false;
		}
	} else {
		return false;
	}
}

/**
 * 扫描目录
 * @param unknown $dir
 * @param string $isDir
 * @return mixed
 */
function tsScanDir($dir, $isDir = null) {
	if ($isDir == null) {
		$dirs = array_filter(glob($dir . '/' . '*'), 'is_dir');
	} else {
		$dirs = array_filter(glob($dir . '/' . '*'), 'is_file');
	}

	foreach ($dirs as $key => $item) {
		$y = explode('/', $item);
		$arrDirs[] = array_pop($y);
	}

	return $arrDirs;
}

/**
 * 删除目录下所有文件
 * @param unknown $dir
 */
function rmrf($dir) {
	foreach (glob ( $dir ) as $file) {
		if (is_dir($file)) {
			rmrf("$file/*");
			rmdir($file);
		} else {
			unlink($file);
		}
	}
}

/**
 * 内容url解析
 * @param unknown $content
 * @return mixed
 */
function urlcontent($content) {
	$pattern = '/(http:\/\/|https:\/\/|ftp:\/\/)([\w:\/\.\?=&-_#]+)/is';
	$content = @preg_replace($pattern, '<a rel="nofollow" target="_blank" href="\1\2">\1\2</a>', $content);
	return $content;
}

/**
 * 反序列化为UTF-8
 * @param unknown $serial_str
 * @return mixed
 */
function mb_unserialize($serial_str) {
	$serial_str = @preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str);
	$serial_str = str_replace("\r", "", $serial_str);
	return unserialize($serial_str);
}

/**
 * 反序列化为ASC
 * @param unknown $serial_str
 * @return mixed
 */
function asc_unserialize($serial_str) {
	$serial_str = @preg_replace('!s:(\d+):"(.*?)";!se', '"s:".strlen("$2").":\"$2\";"', $serial_str);
	$serial_str = str_replace("\r", "", $serial_str);
	return unserialize($serial_str);
}

/**
 * 二进制上传
 * @param unknown $projectid
 * @param unknown $dir
 * @param unknown $type
 * @return multitype:string unknown
 */
function tsXupload($projectid, $dir, $type) {
	$menu2 = intval($projectid / 1000);
	$menu1 = intval($menu2 / 1000);
	$path = $menu1 . '/' . $menu2;
	$dest_dir = 'uploadfile/' . $dir . '/' . $path;
	createFolders($dest_dir);

	$name = $projectid . '.' . $type;
	// 要生成的图片名字

	$dest = $dest_dir . '/' . $name;

	// 先删除
	unlink($dest);

	$xmlstr = $GLOBALS[HTTP_RAW_POST_DATA];
	if (empty($xmlstr))
		$xmlstr = file_get_contents('php://input');
	$jpg = $xmlstr;
	// 得到post过来的二进制原始数据
	$file = fopen($dest, "w");
	// 打开文件准备写入
	fwrite($file, $jpg);
	// 写入
	fclose($file);
	// 关闭

	chmod($dest, 0777);

	return array('name' => $name, 'path' => $path, 'url' => $path . '/' . $name, 'type' => $type);
}

/**
 * 记录日志
 * @param unknown $file
 * @param unknown $data
 */
function logging($file, $data) {
	!is_dir('tslogs') ? mkdir('tslogs', 0777) : '';
	$dfile = 'tslogs/' . $file;

	$filesize = abs(filesize($dfile));

	// 文件重命名
	if ($filesize > 1024000) {
		rename($dfile, $dfile . date('His'));
	}

	$fd = fopen($dfile, "a+");
	fputs($fd, $data);
	fclose($fd);
}

/**
 * 记录用户日志
 * @param unknown $array
 * @param unknown $userid
 */
function userlog(&$array, $userid) {
	if (is_array($array)) {
		foreach ($array as $key => $value) {
			if (!is_array($value)) {
				$data = "UserId:" . $userid . "\n";
				$data .= "IP:" . getIp() . "\n";
				$data .= "TIME:" . date('Y-m-d H:i:s') . "\n";
				$data .= "URL:" . $_SERVER['REQUEST_URI'] . "\n";
				$data .= "DATA:" . $data . "\n";
				$data .= "--------------------------------------\n";
				logging(date('Ymd') . '-' . $userid . '.txt', $data);
			} else {
                userlog($array[$key],$userid);
			}
		}
	}
}

/**
 * 过滤脚本代码
 * @param unknown $text
 * @return mixed
 */
function cleanJs($text) {
	$text = trim($text);
	//$text = stripslashes ( $text );
	// 完全过滤注释
	$text = @preg_replace('/<!--?.*-->/', '', $text);
	// 完全过滤动态代码
	$text = @preg_replace('/<\?|\?>/', '', $text);
	// 完全过滤js
	$text = @preg_replace('/<script?.*\/script>/', '', $text);
	// 过滤多余html
	$text = @preg_replace('/<\/?(html|head|meta|link|base|body|title|style|script|form|iframe|frame|frameset|math|maction|marquee)[^><]*>/i', '', $text);
	// 过滤on事件lang js
	while (preg_match('/(<[^><]+)(data|onmouse|onexit|onclick|onkey|onsuspend|onabort|onactivate|onafterprint|onafterupdate|onbeforeactivate|onbeforecopy|onbeforecut|onbeforedeactivate|onbeforeeditfocus|onbeforepaste|onbeforeprint|onbeforeunload|onbeforeupdate|onblur|onbounce|oncellchange|onchange|onclick|oncontextmenu|oncontrolselect|oncopy|oncut|ondataavailable|ondatasetchanged|ondatasetcomplete|ondblclick|ondeactivate|ondrag|ondragend|ondragenter|ondragleave|ondragover|ondragstart|ondrop|onerror|onerrorupdate|onfilterchange|onfinish|onfocus|onfocusin|onfocusout|onhelp|onkeydown|onkeypress|onkeyup|onlayoutcomplete|onload|onlosecapture|onmousedown|onmouseenter|onmouseleave|onmousemove|onmouseout|onmouseover|onmouseup|onmousewheel|onmove|onmoveend|onmovestart|onpaste|onpropertychange|onreadystatechange|onreset|onresize|onresizeend|onresizestart|onrowenter|onrowexit|onrowsdelete|onrowsinserted|onscroll|onselect|onselectionchange|onselectstart|onstart|onstop|onsubmit|onunload)[^><]+/i', $text, $mat)) {
		$text = str_replace($mat[0], $mat[1], $text);
	}
	while (preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i', $text, $mat)) {
		$text = str_replace($mat[0], $mat[1] . $mat[3], $text);
	}
	return $text;
}

/**
 * 输入安全过滤
 * @param unknown $text
 * @return mixed
 */
function tsClean($text) {
	$text = stripslashes(trim($text));
	//去除前后空格，并去除反斜杠
	//$text = br2nl($text); //将br转换成/n

	///////XSS start
	require_once 'thinksaas/xsshtml.class.php';
	$xss = new XssHtml($text);
	$text = $xss -> getHtml();
	//$text = substr ($text, 4);//去除左边<p>标签
	//$text = substr ($text, 0,-5);//去除右边</p>标签
	///////XSS end

	//$text = html_entity_decode($text,ENT_NOQUOTES,"utf-8");//把 HTML 实体转换为字符
	//$text = strip_tags($text); //去掉HTML及PHP标记
	//$text = cleanJs ( $text );

	$text = htmlentities($text, ENT_NOQUOTES, "utf-8");
	//把字符转换为 HTML 实体

	return $text;
}

/*
 * @text 内容
 * @tp 内容分页
 * @url URL
 */
function tsDecode($text, $tp = 1) {
	$text = html_entity_decode(stripslashes($text), ENT_NOQUOTES, "utf-8");
	$text = str_replace('<br /><br />', '<br />', $text);

	//分页处理
	$arrText = explode('_ueditor_page_break_tag_', $text);

	if ($arrText) {
		$tp = $tp - 1;
		$text = $arrText[$tp];
	}

	return $text;
}

/*
 * 输出标题处理
 */
function tsTitle($title) {
	$title = stripslashes($title);
	$title = htmlspecialchars($title);
	return $title;
}

/*
 * 输出内容截取
 */
function tsCutContent($text, $length = 50) {
	$text = cututf8(t(tsDecode($text)), 0, $length);
	return $text;
}

/*
 * tpCount()
 */
function tpCount($text) {
	$arrText = explode('_ueditor_page_break_tag_', $text);
	return count($arrText);
}

/*
 * 内容分页
 */
function tpPage($text, $app, $ac, $arr) {
	$tpCount = tpCount($text);
	$tpUrl = '';
	if ($tpCount > 1) {
		$tpUrl = '<div class="page">';
		for ($i = 1; $i <= $tpCount; $i++) {
			$arr['tp'] = $i;
			$tpUrl .= '<a rel="nofollow" href="' . tsUrl($app, $ac, $arr) . '">' . $i . '</a>';
		}
		$tpUrl .= '</div>';
	}
	return $tpUrl;
}

/**
 * 统计字符长度
 * @param unknown $str
 * @return number
 */
function count_string_len($str) {
	// return (strlen($str)+mb_strlen($str,'utf-8'))/2; //开启了php_mbstring.dll扩展
	$name_len = strlen($str);
	$temp_len = 0;
	for ($i = 0; $i < $name_len; ) {
		if (strpos('abcdefghijklmnopqrstvuwxyz0123456789', $str[$i]) === false) {
			$i = $i + 3;
			$temp_len += 2;
		} else {
			$i = $i + 1;
			$temp_len += 1;
		}
	}
	return $temp_len;
}

/**
 * 针对特殊字符或者内容的特殊过滤
 * @param unknown $value
 * @return Ambigous <string, mixed>
 */
function tsFilter($value) {
	$value = trim($value);
	//定义不允许提交的SQl命令和关键字
	$words = array();
	$words[] = "add ";
	$words[] = "and ";
	$words[] = "count ";
	$words[] = "order ";
	$words[] = "table ";
	$words[] = "by ";
	$words[] = "create ";
	$words[] = "delete ";
	$words[] = "drop ";
	$words[] = "from ";
	$words[] = "grant ";
	$words[] = "insert ";
	$words[] = "select ";
	$words[] = "truncate ";
	$words[] = "update ";
	$words[] = "use ";
	$words[] = "--";
	$words[] = "#";
	$words[] = "group_concat";
	$words[] = "column_name";
	$words[] = "information_schema.columns";
	$words[] = "table_schema";
	$words[] = "union ";
	$words[] = "where ";
	$words[] = "alert";
	$value = strtolower($value);
	//转换为小写
	foreach ($words as $word) {
		if (strstr($value, $word)) {
			$value = str_replace($word, '', $value);
		}
	}

	return $value;
}

function tsgpc(&$array) {
	//如果是数组，遍历数组，递归调用
	if (is_array($array)) {
		foreach ($array as $k => $v) {
			$array[$k] = tsgpc($v);
		}
	} else if (is_string($array)) {
		//使用addslashes函数来处理
		//$array = addslashes ( closetags($array) );
		$array = addslashes($array);
	} else if (is_numeric($array)) {
		$array = intval($array);
	}
	return $array;
}

/**
 * 检查标签是否闭合
 */
function closetags($html) {
	preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
	$openedtags = $result[1];
	preg_match_all('#</([a-z]+)>#iU', $html, $result);
	$closedtags = $result[1];
	$len_opened = count($openedtags);
	$len_closed = count($closedtags);
	if ($len_closed == $len_opened) {
		return $html;
	}
	$openedtags = array_reverse($openedtags);
	for ($i = 0; $i < $len_opened; $i++) {
		if (!in_array($openedtags[$i], $closedtags)) {
			$html .= '</' . $openedtags[$i] . '>';
		} else {
			unset($closedtags[array_search($openedtags[$i], $closedtags)]);
		}
	}
	return $html;
}

/*
 * url参数检测过滤
 * @parameter  例如$app,$ac,$ts等的参数
 */
function tsUrlCheck($parameter) {
	
	$parameter = trim($parameter);
	$arrStr = str_split($parameter);
	$strOk = '%-_1234567890abcdefghijklmnopqrstuvwxyz';
	foreach ($arrStr as $key => $item) {
		if (stripos($strOk, $item) === false) {
			//qiMsg('非法URL参数！');
			header('Location: /');
			exit;
		}
	}
	return $parameter;
}

function ludou_width_height($content) {
	$images = array();
	preg_match_all('/<img (.*?)\/>/', $content, $images);
	if (!empty($images)) {
		foreach ($images[1] as $index => $value) {
			$img = array();
			preg_match_all('/(width)=("[^"]*")/i', $images[1][$index], $img);

			if (!empty($img[2])) {
				$width = trim($img[2][0], '"');
				if ($width > 630) {
					$new_img = @preg_replace('/(width)=("[^"]*")/i', 'width="630"', $images[0][$index]);
					$content = str_replace($images[0][$index], $new_img, $content);

					$new_img2 = @preg_replace('/(height)=("[^"]*")/i', 'height="100%"', $new_img);
					$content = str_replace($new_img, $new_img2, $content);
				}
			}
		}
	}
	return $content;
}

/**
 * DZ在线中文分词
 * @param $title string 进行分词的标题
 * @param $content string 进行分词的内容
 * @param $encode string API返回的数据编码
 * @return  array 得到的关键词数组
 */
function dz_segment($title = '', $content = '', $encode = 'utf-8') {
	if ($title == '') {
		return false;
	}
	$title = rawurlencode(strip_tags($title));
	$content = strip_tags($content);
	if (strlen($content) > 2400) {//在线分词服务有长度限制
		$content = mb_substr($content, 0, 800, $encode);
	}
	$content = rawurlencode($content);
	$url = 'http://keyword.discuz.com/related_kw.html?title=' . $title . '&content=' . $content . '&ics=' . $encode . '&ocs=' . $encode;
	$xml_array = simplexml_load_file($url);
	//将XML中的数据,读取到数组对象中
	$result = $xml_array -> keyword -> result;
	$data = array();
	foreach ($result->item as $key => $value) {
		array_push($data, (string)$value -> kw);
	}
	if (count($data) > 0) {
		return $data;
	} else {
		return false;
	}
}

/**
 * Convert BR tags to nl
 *
 * @param string The string to convert
 * @return string The converted string
 */
function br2nl($string) {
	return @preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
}

/*
 * 判断手机访问
 */
function isMobile() {
	// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
	if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
		return true;
	}
	// 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
	if (isset($_SERVER['HTTP_VIA'])) {
		// 找不到为flase,否则为true
		return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
	}
	// 脑残法，判断手机发送的客户端标志,兼容性有待提高
	if (isset($_SERVER['HTTP_USER_AGENT'])) {
		$clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile');
		// 从HTTP_USER_AGENT中查找手机浏览器的关键字
		if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
			return true;
		}
	}
	// 协议法，因为有可能不准确，放到最后判断
	if (isset($_SERVER['HTTP_ACCEPT'])) {
		// 如果只支持wml并且不支持html那一定是移动设备
		// 如果支持wml和html但是wml在html之前则是移动设备
		if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
			return true;
		}
	}
	return false;
}

/*
 * @data 返回的数据或者提示语
 * @status 0提示、1刷新、2跳转
 * @js判断是否是通过js方式处理的
 * @url 当status=2要跳转的url页面
 */
function getJson($data, $js = 1, $status = 0, $url = '') {
	if ($js) {
		//header("Content-type: application/json;charset=utf-8");
		if ($status == 2 && $url) {
			echo json_encode(array('status' => $status, 'data' => $data, 'url' => $url, ));
		} else {
			echo json_encode(array('status' => $status, 'data' => $data, ));
		}
		exit ;
	} elseif ($js == 0 && $status == 2 && $url) {
		header('Location: ' . $url);
		exit ;
	} else {
		tsNotice($data);
	}
}

/*
 * 为项目上传绝对路径的图片
 * @photourl  图片绝对路径http
 * @projectid  项目ID
 * @dir	存储目录，都在uploadfile下，一般根据app名称命名
 */
function tsUploadPhotoUrl($photourl, $projectid, $dir) {
	$menu2 = intval($projectid / 1000);
	$menu1 = intval($menu2 / 1000);
	$path = $menu1 . '/' . $menu2;
	$dest_dir = 'uploadfile/' . $dir . '/' . $path;
	createFolders($dest_dir);

	$arrType = explode('.', strtolower($photourl));
	// 转小写一下
	$type = array_pop($arrType);

	$name = $projectid . '.' . $type;

	$dest = $dest_dir . '/' . $name;

	$img = file_get_contents($photourl);

	unlink($dest);
	file_put_contents($dest, $img);

	return array('path' => $path, 'url' => $path . '/' . $name, 'type' => $type, );

}


function getdomain($url) { 
$host = strtolower ( $url ); 
if (strpos ( $host, '/' ) !== false) { 
$parse = @parse_url ( $host ); 
$host = $parse ['host']; 
} 
$topleveldomaindb = array ('com', 'edu', 'gov', 'int', 'mil', 'net', 'org', 'biz', 'info', 'pro', 'name', 'museum', 'coop', 'aero', 'xxx', 'idv', 'mobi', 'cc', 'me','in','io','gg','co' ); 
$str = ''; 
foreach ( $topleveldomaindb as $v ) { 
$str .= ($str ? '|' : '') . $v; 
} 

$matchstr = "[^\.]+\.(?:(" . $str . ")|\w{2}|((" . $str . ")\.\w{2}))$"; 
if (preg_match ( "/" . $matchstr . "/ies", $host, $matchs )) { 
$domain = $matchs ['0']; 
} else { 
$domain = $host; 
} 
return $domain; 
} 

/*
 * 验证手机号，支持13、15、18、17号段
 * 
 */
function isPhone($phone){
	if(preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$/",$phone)){    
		return true;
	}else{
		return false;
	}
}


/**
 * 将字符串转换为数组
 *
 * @param string $data        	
 * @return array
 */
function string2array($data) {
	if ($data == '') {
		return array ();
	}
	if (is_array ( $data )) {
		return $data;
	}
	if (strpos ( $data, 'array' ) !== false && strpos ( $data, 'array' ) === 0) {
		@eval ( "\$array = $data;" );
		return $array;
	}
	return unserialize ( ($data) ); //unserialize ( new_stripslashes ( $data ) );
}

/**
 * 将数组转换为字符串
 *
 * @param array $data        	
 * @param bool $isformdata        	
 * @return string
 *
 */
function array2string($data, $isformdata = 1) {
	if ($data == '') {
		return '';
	}
	if ($isformdata) {
		$data = ($data); //new_stripslashes ( $data );
	}
	return serialize ( $data );
}

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo = true, $label = null, $strict = true) {
	$label = ($label === null) ? '' : rtrim ( $label ) . ' ';
	if (! $strict) {
		if (ini_get ( 'html_errors' )) {
			$output = print_r ( $var, true );
			$output = '<pre>' . $label . htmlspecialchars ( $output, ENT_QUOTES ) . '</pre>';
		} else {
			$output = $label . print_r ( $var, true );
		}
	} else {
		ob_start ();
		var_dump ( $var );
		$output = ob_get_clean ();
		if (! extension_loaded ( 'xdebug' )) {
			$output = preg_replace ( '/\]\=\>\n(\s+)/m', '] => ', $output );
			$output = '<pre>' . $label . htmlspecialchars ( $output, ENT_QUOTES ) . '</pre>';
		}
	}
	if ($echo) {
		echo ($output);
		return null;
	} else
		return $output;
}

//404
function ts404(){
    header ( "HTTP/1.1 404 Not Found" );
    header ( "Status: 404 Not Found" );
    $title = '404';
    include pubTemplate ( "404" );
    exit ();
}

//跳转
function tsHeaderUrl($url){
	header('Location: '.$url);
	exit;
}