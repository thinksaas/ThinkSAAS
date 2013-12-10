<?php
defined('IN_TS') or die('Access Denied.');  

class mobile extends tsApp{
	
	//构造函数
	public function __construct($db){
		parent::__construct($db);
	}
	
	//用户是否存在
	public function isUser($userid){
		$isUser = $this->findCount('user',array('userid'=>$userid));
		if($isUser == 0){
			return false;
		}else{
			return true;
		}
	}
	
	//是否登录 
	public function isLogin(){
		$userid = intval($_SESSION['tsuser']['userid']);
		if($userid>0){
			if($this->isUser($userid)){
				return $userid;
			}else{
				header("Location: ".tsUrl('mobile','login',array('ts'=>'out')));
				exit;
			}
		}else{
			header("Location: ".tsUrl('mobile','login',array('ts'=>'out')));
			exit;
		}
	}
	
	public function mNotice($msg,$button='点击返回>>',$url='javascript:history.back(-1);', $isAutoGo=false){
	echo 
<<<EOT
<html>
<head>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
font-family: arial,helvetica,simhei,sans-serif;font-size:18px;
text-align:center;
}
a{text-decoration:none;color:#666666;border:none;}
.main {
background-color:#FFFFFF;
font-size: 12px;
color: #666666;
margin:60px auto 0;
list-style:none;
padding:20px;
}
.main p {
line-height: 18px;
padding:20px 0;
font-size:14px;
}
.btn{height:50px;line-height:50px;display:block;background:#F5F5F5;}
-->
</style>
</head>
<body>
<div class="main">
<p>$msg</p>
<p><a class="btn" href="$url">$button</a></p>
</div>
</body>
</html>
EOT;
	exit;
	}
	
	
}
