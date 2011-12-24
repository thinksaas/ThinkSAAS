<?php
defined('IN_TS') or die('Access Denied.');
/*
 * ThinkSAAS core function
 * @copyright (c) ThinkSAAS All Rights Reserved
 * @code by QiuJun
 * @Email:thinksaas@qq.com
 * @TIME:2010-12-18
 */

//AutoAppClass
function aac($appname){
	global $db;
	$class = $appname;
	$path = THINKAPP.'/'.$appname.'/';
	if(!class_exists($class)){
		include_once  $path.'class.'.$class.'.php';
	}
	if(!class_exists($class)){
		return false;
	}
	$obj = new $class($db);
	return $obj;
	
	unset($db);
	
}

//editor Special info  to html
function editor2html($str)
{
	global $db;
	//匹配本地图片
	preg_match_all('/\[(photo)=(\d+)\]/is', $str, $photos);
	foreach ($photos[2] as $item) {
		$strPhoto = aac('photo')->getPhotoForApp($item);
		$str = str_replace("[photo={$item}]",'<a href="'.SITE_URL.'uploadfile/photo/'.$strPhoto['photourl'].'" target="_blank">
							<img class="thumbnail" src="'.SITE_URL.miniimg($strPhoto['photourl'],'photo','500','500',$strPhoto['path']).'" title="'.$strTopic['title'].$item.'" /></a>', $str);
	}

	//匹配附件
	preg_match_all('/\[(attach)=(\d+)\]/is', $str, $attachs);
	if($attachs[2]){
		foreach ($attachs[2] as $aitem) {
			$strAttach = aac('attach')->getOneAttach($aitem);
			if($strAttach['isattach'] == '1'){
				$str = str_replace("[attach={$aitem}]",'<span class="attach_down">附件下载：
									 <a href="'.SITE_URL.'index.php?app=attach&ac=ajax&ts=down&attachid='.$aitem.'" target="_blank">'.$strAttach["attachname"].'</a></span>', $str);
			}else{
				$str = str_replace("[attach={$aitem}]",'', $str);
			}
		}
	}
	
	$find = array("http://","-",'.',"/",'?','=','&');
	$replace = array("",'_','','','','','');
			
	preg_match_all('/\[(video)=(.*?)\]/is', $str, $video);
	if($video[2]){
		foreach ($video[2] as $aitem) {
			//img play title
			$arr = explode(',',$aitem);
			$id = str_replace($find,$replace,$arr[0]);
			$html = '<div id="img_'.$id.'"><a href="javascript:void(0)" onclick="showVideo(\''.$id.'\',\''.$arr[1].'\');"><img src="'.$arr[0].'"/></a></div>';
			$html .= '<div id="play_'.$id.'" style="display:none">'.$arr['2'].' <a href="javascript:void(0)" onclick="showVideo(\''.$id.'\',\''.$arr[1].'\');">收起</a>
			<div id="swf_'.$id.'"></div> </div>';
			$str = str_replace("[video={$aitem}]",$html, $str);

		}
	}
	
	preg_match_all('/\[(mp3)=(.*?)\]/is', $str, $music);
	if($music[2]){
		foreach ($music[2] as $aitem) {
			//url title
			$arr = explode(',',$aitem);
			$id = str_replace($find,$replace,$arr[0]);
			
			//$mp3flash = '<div id="mp3img_'.$id.'"><a href="javascript:void(0)" onclick="showMp3(\''.$id.'\',\''.$arr[1].'\');"><img src="'.SITE_URL.'public/flash/music.gif" /></a></div>';
			
			$mp3flash ='<div id="mp3swf_'.$id.'" class="mp3player">
			<div>'.$arr[1].' <a href="'.$aitem.'" target="_blank">下载</a> </div>
			<object height="24" width="290" data="'.SITE_URL.'public/flash/player.swf" type="application/x-shockwave-flash">
				<param value="'.SITE_URL.'public/flash/player.swf" name="movie"/>
				<param value="autostart=no&bg=0xCDDFF3&leftbg=0x357DCE&lefticon=0xF2F2F2&rightbg=0xF06A51&rightbghover=0xAF2910&righticon=0xF2F2F2&righticonhover=0xFFFFFF&text=0x357DCE&slider=0x357DCE&track=0xFFFFFF&border=0xFFFFFF&loader=0xAF2910&soundFile='.$aitem.'" name="FlashVars"/>
				<param value="high" name="quality"/>
				<param value="false" name="menu"/>
				<param value="#FFFFFF" name="bgcolor"/>
				</object></div>';
			$str = str_replace("[mp3={$aitem}]",$mp3flash, $str);


		}
	}
	return $str;	
	unset($db);
}

 
//系统消息
 
function qiMsg($msg,$button='点击返回>>',$url='javascript:history.back(-1);', $isAutoGo=false){
	echo 
<<<EOT
<html>
<head>
EOT;
	if($isAutoGo){
		echo "<meta http-equiv=\"refresh\" content=\"2;url=$url\" />";
	}
	echo 
<<<EOT
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ThinkSAAS 提示</title>
<style type="text/css">
<!--
body {
background-color:#D6E9A0;
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
	exit;
}


/*
 * 分页函数
 *
 * @param int $count 条目总数
 * @param int $perlogs 每页显示条数目
 * @param int $page 当前页码
 * @param string $url 页码的地址
 * @return unknown
 */
 
function pagination($count,$perlogs,$page,$url,$suffix=''){
	$pnums = @ceil($count / $perlogs);
	$re = '';
	for ($i = $page-5;$i <= $page+5 && $i <= $pnums; $i++){
		if ($i > 0){
			if ($i == $page){
				$re .= ' <span class="current">'.$i.'</span> ';
			} else {
				$re .= '<a href="'.$url.$i.$suffix.'">'.$i.'</a>';
			}
		}
	}
	if ($page > 6) $re = '<a href="'.$url.'1'.$suffix.'" title="首页">&laquo;</a> ...'.$re;
	if ($page + 5 < $pnums) $re .= '... <a href="'.$url.$pnums.$suffix.'" title="尾页">&raquo;</a>';
	if ($pnums <= 1) $re = '';
	return $re;
}

//验证Email

function valid_email($email){
	if (!@ereg("^[^@]{1,64}@[^@]{1,255}$", $email)){ 
		return false; 
	}
	$email_array		= explode("@", $email); 
	$local_array		= explode(".", $email_array[0]); 
	for ($i = 0; $i < sizeof($local_array); $i++){ 
		if (!@ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])){ 
			return false; 
		 } 
	}   
	if (!@ereg("^\[?[0-9\.]+\]?$", $email_array[1])){
		$domain_array = explode(".", $email_array[1]); 
		if (sizeof($domain_array) < 2){ 
			return false;
		} 
		for ($i = 0; $i < sizeof($domain_array); $i++){ 
			if (!@ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])){ 
				return false; 
			} 
		} 
	} 
	return true;
} 

//处理时间的函数
 
function getTime($btime, $etime){

	if ($btime < $etime) {
		$stime = $btime;
		$endtime = $etime;
	}else {
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
	if($days>=1){
		return $days.' 天前';
	}
	if($hours>=1){
		return $hours.' 小时前';
	}

	if($mins>=1){
		return $mins.' 分钟前';
	}
	if($secs>=1){
		return $secs.' 秒前';
	}
 
}

//获取用户IP
 
function getIp(){

	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')){
		$PHP_IP = getenv('HTTP_CLIENT_IP');
	}elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')){
		$PHP_IP = getenv('HTTP_X_FORWARDED_FOR');
	}elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')){
		$PHP_IP = getenv('REMOTE_ADDR');
	}elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')){
		$PHP_IP = $_SERVER['REMOTE_ADDR'];
	}
	preg_match("/[\d\.]{7,15}/", $PHP_IP, $ipmatches);
	$PHP_IP = $ipmatches[0] ? $ipmatches[0] : 'unknown';
	return $PHP_IP;
}



//过滤脚本代码
function cleanJs($text){
	$text = trim ( $text );
	$text = stripslashes ( $text );
	//完全过滤注释
	$text = preg_replace ( '/<!--?.*-->/', '', $text ); 
	//完全过滤动态代码
	
	$text = preg_replace ( '/<\?|\?>/', '', $text );
	
	//完全过滤js
	$text = preg_replace ( '/<script?.*\/script>/', '', $text );
	//过滤多余html
	$text = preg_replace ( '/<\/?(html|head|meta|link|base|body|title|style|script|form|iframe|frame|frameset)[^><]*>/i', '', $text );
	//过滤on事件lang js
	while ( preg_match ( '/(<[^><]+)(lang|onfinish|onmouse|onexit|onerror|onclick|onkey|onload|onchange|onfocus|onblur)[^><]+/i', $text, $mat ) ){
		$text = str_replace ( $mat [0], $mat [1], $text );
		}
	while ( preg_match ( '/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i', $text, $mat ) ){
		$text = str_replace ( $mat [0], $mat [1] . $mat [3], $text );
		}
	return $text;
}

//纯文本输入
function t($text){
	$text = cleanJs ( $text );
	//彻底过滤空格BY QINIAO
	$text = preg_replace('/\s(?=\s)/', '', $text);
	$text = preg_replace('/[\n\r\t]/', ' ', $text);
	$text = str_replace ( '  ', ' ', $text );
	$text = str_replace ( ' ', '', $text );
	$text = str_replace ( '&nbsp;', '', $text );
	$text = str_replace ( '&', '', $text );
	$text = str_replace ( '=', '', $text );
	$text = str_replace ( '-', '', $text );
	$text = str_replace ( '#', '', $text );
	$text = str_replace ( '%', '', $text );
	$text = str_replace ( '!', '', $text );
	$text = str_replace ( '@', '', $text );
	$text = str_replace ( '^', '', $text );
	$text = str_replace ( '*', '', $text );
	$text = str_replace ( 'amp;', '', $text );
	
	$text = strip_tags ( $text );
	$text = htmlspecialchars ( $text );
	$text = str_replace ( "'", "", $text );
	return $text;
}

//输入安全的html，针对存入数据库中的数据进行的过滤和转义
 
function h($text){
	$text = trim ( $text );
	$text = htmlspecialchars ( $text );
	$text = addslashes ( $text );
	return $text;
}

//主要针对输出的内容，对动态脚本，静态html，动态语言全部通吃
function hview($text){
	$text = stripslashes($text);
	$text = nl2br($text);
	return $text;
}

//反序列化为UTF-8
function mb_unserialize($serial_str) {
	$serial_str= preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str );
	$serial_str= str_replace("\r", "", $serial_str);      
	return unserialize($serial_str);
}

//反序列化为ASC
function asc_unserialize($serial_str) {
	$serial_str = preg_replace('!s:(\d+):"(.*?)";!se', '"s:".strlen("$2").":\"$2\";"', $serial_str );
	$serial_str= str_replace("\r", "", $serial_str);      
	return unserialize($serial_str);
}

//utf-8截取
function getsubstrutf8($string, $start = 0,$sublen,$append=true){
	$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
	preg_match_all($pa, $string, $t_string);
	if(count($t_string[0]) - $start > $sublen && $append==true){
		return join('', array_slice($t_string[0], $start, $sublen))."...";
	}else{
		return join('', array_slice($t_string[0], $start, $sublen));
	}
}

//读取目录列表函数dirs,files
function dirList($dir, $bool = "dirs"){

   $truedir = $dir; //注意：确定路劲是否带有'/'
   
   $dir = scandir($dir);
   if($bool == "files"){
		$direct = 'is_dir';
   }elseif($bool == "dirs"){
		$direct = 'is_file';
   }
   
   foreach($dir as $k => $v){
		if(($direct($truedir.'/'.$dir[$k])) || $dir[$k] == '.' || $dir[$k] == '..'  || $dir[$k] == '.svn'){
			unset($dir[$k]);
		}
   }
   $dir = array_values($dir);
   return $dir;
}

//计算时间
function getmicrotime(){ 
	list($usec, $sec) = explode(" ",microtime()); 
	return ((float)$usec + (float)$sec); 
}

/*写入文件
 @By QiuJun 2011-12-10
 @$file 缓存文件
 @$dir 缓存目录
 @$data 内容
 */
function fileWrite($file,$dir,$data){

	!is_dir($dir)?mkdir($dir,0777):'';
	
	$dfile = $dir.'/'.$file;
	
	if($dfile) unlink($dfile);
	
	$data = "<?php\ndefined('IN_TS') or die('Access Denied.');\nreturn ".var_export($data,true).";";
	
	file_put_contents($dfile,$data);
	
	return true;
	
}

/*读取文件，过渡期ThinkSAAS1.6，到1.8的时间可以全面进行精简到fileRead($dfile)
 @$file 文件
 @$dir 目录
 @$app 过渡一下app
*/
function fileRead($file,$dir,$app,$plugin=''){

	if($plugin!=''){
		
		//plugins
		if(is_file($dir.'/'.$app.'/'.$plugin.'/'.$file)){
			$data = include $dir.'/'.$app.'/'.$plugin.'/'.$file;
			return $data;
		}
		
	}else{
		
		//app
		if(is_file($dir.'/'.$app.'_'.$file)){
			$data = include $dir.'/'.$app.'_'.$file;
			return $data;
		}elseif(is_file($dir.'/cache/'.$app.'/'.$file)){
			$data = include $dir.'/cache/'.$app.'/'.$file;
			return $data;
		}
	
	}


	
	
	
}

//把数组转换为,号分割的字符串
function array_to_str($arr) {
	$str = '';
	$count = 1;
	if(is_array($arr)){
		foreach ($arr as $a) {
			if ($count==1) {
				$str .= $a;
			} else {
				$str .= ','.$a;
			}
				$count++;
		}
	}
	return $str;
}


//生成随机数(1数字,0字母数字组合)
function random($length, $numeric = 0) {
	PHP_VERSION < '4.2.0' ? mt_srand((double)microtime() * 1000000) : mt_srand();
	$seed = base_convert(md5(print_r($_SERVER, 1).microtime()), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
	$hash = '';
	$max = strlen($seed) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $seed[mt_rand(0, $max)];
	}
	return $hash;
}

/*
 *封装一个采集函数
 *@ $url 网址
 *@ $proxy 代理
 *@ $timeout 跳出时间
 */

function getHtmlByCurl($url,$proxy,$timeout){
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_PROXY, $proxy);
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$file_contents = curl_exec($ch);
	return $file_contents;
}

//计算文件大小
function format_bytes($size) {    
	$units = array(' B', ' KB', ' MB', ' GB', ' TB');    
	for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;    
	return round($size, 2).$units[$i];
}

/*
 * 功能:               判断是否是手机访问
 * 参数:               无
 * 返回值:            返回1为是手机访问,返回0时为不是
 */
function is_wap() {
	$http_via = isset($_SERVER['HTTP_VIA']) ? strtolower($_SERVER['HTTP_VIA']) : '';
	return !empty($http_via) && strstr($http_via, 'wap') ? 1 : 0;       
}

//object_array 对象转数组
function object_array($array){
	if(is_object($array)){
		$array = (array)$array;
	}
	if(is_array($array)){
		foreach($array as $key=>$value){
			$array[$key] = object_array($value);
		}
	}
	return $array;
}

/*此处开始借用moophp的模板代码*/	

/**
* 写文件
* @param string $file - 需要写入的文件，系统的绝对路径加文件名
* @param string $content - 需要写入的内容
* @param string $mod - 写入模式，默认为w
* @param boolean $exit - 不能写入是否中断程序，默认为中断
* @return boolean 返回是否写入成功
*/
function isWriteFile($file, $content, $mod = 'w', $exit = TRUE) {
if(!@$fp = @fopen($file, $mod)) {
	if($exit) {
		exit('ThinkSAAS File :<br>'.$file.'<br>Have no access to write!');
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

//创建目录
function makedir($dir) {
return is_dir($dir) or (makedir(dirname($dir)) and mkdir($dir, 0777)); 
}

/**
* 加载模板
* @param string $file - 模板文件名
* @return string 返回编译后模板的系统绝对路径
*/
function template($file) {
	global $app;
	$tplfile = 'app/'.$app.'/html/'.$file.'.html';
	$objfile = 'cache/template/'.$app.'.'.$file.'.tpl.php';
	if(@filemtime($tplfile) > @filemtime($objfile)) {
		//note 加载模板类文件
		require_once 'thinksaas/class.template.php';
		$T = new template();
		
		$T->complie($tplfile, $objfile);
		
	}
	
	return $objfile;
	unset($app);
}

//加载公用html模板文件 
function pubTemplate($file) {
$tplfile = 'public/html/'.$file.'.html';
$objfile = 'cache/template/public.'.$file.'.tpl.php';

if(@filemtime($tplfile) > @filemtime($objfile)) {
	//note 加载模板类文件
	
	require_once 'thinksaas/class.template.php';
	$T = new template();
	
	$T->complie($tplfile, $objfile);
}

return $objfile;
}

//针对app各个的插件部分，修改自Emlog
/**
* 该函数在插件中调用,挂载插件函数到预留的钩子上
*
* @param string $hook
* @param string $actionFunc
* @return boolearn
*/
function addAction($hook, $actionFunc){
	global $tsHooks;
	if (!@in_array($actionFunc, $tsHooks[$hook])){
		$tsHooks[$hook][] = $actionFunc;
	}

	return true;
}

/**
* 执行挂在钩子上的函数,支持多参数 eg:doAction('post_comment', $author, $email, $url, $comment);
*
* @param string $hook
*/
function doAction($hook){
	global $tsHooks;
	$args = array_slice(func_get_args(), 1);
	if (isset($tsHooks[$hook])){
		foreach ($tsHooks[$hook] as $function){
			$string = call_user_func_array($function, $args);
		}
	}
}

function createFolders($path)  {  
	//递归创建  
	if (!file_exists($path)){  
		createFolders(dirname($path));//取得最后一个文件夹的全路径返回开始的地方  
		mkdir($path, 0777);  
	}  
}

//删除文件夹和文件夹下所有的文件
function delDir($dir=''){
	if (empty($dir)){
		$dir = rtrim(RUNTIME_PATH,'/');
	}
	if (substr($dir,-1) == '/'){
		$dir = rtrim($dir,'/');
	}
	if (!file_exists($dir)) return true;
	if (!is_dir($dir) || is_link($dir)) return @unlink($dir);
	foreach (scandir($dir) as $item) {
		if ($item == '.' || $item == '..') continue;
		if (!delDir($dir . "/" . $item)) {
			@chmod($dir . "/" . $item, 0777);
			if (!delDir($dir . "/" . $item)) return false;
		};
	}
	return @rmdir($dir);
}

//获取带http的网站域名 BY QIUJUN
function getHttpUrl(){
	$arrUri = explode('index.php',$_SERVER['REQUEST_URI']);
	$site_url = 'http://'.$_SERVER['HTTP_HOST'].$arrUri[0];
	return $site_url;
}

// 10位MD5值
function md10($str=''){
	return substr(md5($str),10,10);
}

/*BY QIUJUN
 * # 缩略图 (图片SRC, 宽度, 高度)
 * $file：数据库里的图片url
 * $app：app名称
 * $w：缩略图片宽度
 * $h：缩略图片高度
 * $c:1裁切,0不裁切
 */
function miniimg($file, $app , $w, $h,$path='',$c='0'){
	if(!$file) { return;}
	
	$info = explode('.',$file);
	
	$name = md10($file).'_'.$w.'_'.$h.'.'.$info[1];
	
	if($path==''){
		$cpath = 'cache/'.$app.'/'.$w.'/'.$name;
	}else{
		$cpath = 'cache/'.$app.'/'.$path.'/'.$w.'/'.$name;
	}
	
	
	if(!is_file($cpath)){
		createFolders('cache/'.$app.'/'.$path.'/'.$w);
		$dest = 'uploadfile/'.$app.'/'.$file;
		$arrImg = getimagesize($dest);
		if($arrImg[0] <= $w){
			copy($dest,$cpath);
		}else{
			require_once 'thinksaas/class.image.php';
			$resizeimage = new tsImg("$dest", $w, $h, $c,"$cpath");
		}
	}
	return $cpath;
}

//gzip压缩输出
function ob_gzip($content) { 
	if( !headers_sent() && extension_loaded("zlib") && strstr($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip")) {
		//$content = gzencode($content." \n//此页已压缩",9); 
		$content = gzencode($content,9); 
		header("Content-Encoding: gzip"); 
		header("Vary: Accept-Encoding");
		header("Content-Length: ".strlen($content));
	}
	return $content; 
}


/* tsUrl()  By QIUJUN
 * tsUrl提供至少4种的url展示方式
 * (1)index.php?app=group&ac=topic&topicid=1 //标准默认模式
 * (2)index.php/group/topic/topicid-1   //path_info模式
 * (3)group-topic-topicid-1.html   //rewrite模式1
 * (4)group/topic/topicid-1   //rewrite模式2
 */
function tsurl($app,$ac='',$params=array()){
	
	if(is_file('data/system_options.php')){
		$options = include 'data/system_options.php';
	}else{
		$options = include 'data/cache/system/options.php';
	}
	
	$urlset = $options['site_urltype'];
	
	if($urlset==1){
		foreach($params as $k=>$v){
			$str .= '&'.$k.'='.$v;
		}
		if($ac==''){
			$ac = '';
		}else{
			$ac='&ac='.$ac;
		}
		$url = 'index.php?app='.$app.$ac.$str;
	}elseif($urlset == 2){
		foreach($params as $k=>$v){
			$str .= '/'.$k.'-'.$v;
		}
		if($ac==''){
			$ac='';
		}else{
			$ac='/'.$ac;
		}
		$url = 'index.php/'.$app.$ac.$str;
	}elseif($urlset == 3){
		foreach($params as $k=>$v){
			$str .= '-'.$k.'-'.$v;
		}
		$url = $app.'-'.$ac.$str.'.html';
	}elseif($urlset == 4){
		foreach($params as $k=>$v){
			$str .= '/'.$k.'-'.$v;
		}
		
		if($ac==''){
			$ac='';
		}else{
			$ac='/'.$ac;
		}
		
		$url = $app.$ac.$str;
	}
	return $url;
}

//reurl BY QIUJUN 2011-10-23

function reurl(){

	$scriptName = explode('index.php',$_SERVER['SCRIPT_NAME']);

	$rurl = substr($_SERVER['REQUEST_URI'], strlen($scriptName[0]));

	if(strpos($rurl,'?')==false){

		if(preg_match('/index.php/i',$rurl)){
			$rurl = str_replace('index.php','',$rurl);
			$rurl = substr($rurl, 1);
			$params = $rurl;
		}else{
			$params = $rurl;
		}

		if($rurl){
			
			$params = explode('/', $params);
			
			foreach( $params as $p => $v )
			{
				switch($p)
				{
					case 0:$_GET['app']=$v;break;
					case 1:$_GET['ac']=$v;break;
					default:
						$kv = explode('-', $v);
						if(count($kv)>1)
						{
							$_GET[$kv[0]] = $kv[1];
						}
						else
						{
							$_GET['params'.$p] = $kv[0];
						}
						break;
				}
			}
		}
	}
}

//检测目录是否可写1可写，0不可写
function iswriteable($file){
	if(is_dir($file)){
		$dir=$file;
		if($fp = @fopen("$dir/test.txt", 'w')) {
			@fclose($fp);
			@unlink("$dir/test.txt");
			$writeable = 1;
		} else {
			$writeable = 0;
		}
	}else{
		if($fp = @fopen($file, 'a+')) {
			@fclose($fp);
			$writeable = 1;
		}else {
			$writeable = 0;
		}
	}
	return $writeable;
}

//删除目录下文件
function delDirFile($dir){
	$arrFiles = dirList($dir,'files');
	foreach($arrFiles as $item){
		unlink($dir.'/'.$item);
	}
}
