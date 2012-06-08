<?php
defined('IN_TS') or die('Access Denied.');
//js绑定事件
function verify_html(){
	    global $TS_USER,$db;
	    include('verifydata.php');
	    
		//我的积分
		$count_score=$db -> once_fetch_assoc("select count_score from " . dbprefix . "user_info where userid='".$TS_USER['user']['userid']."'");
		//是否验证
		if(($needcredit >0) && ( $count_score['count_score'] < $needcredit )){
			$kid=rand(0,(count($q)-1));
			$getdo=$_GET['ts'];
			if($_GET['ac']=='add' && $_GET['ts']=='speed') $istype=1;
			else if($_GET['ac']=='add'  && $_GET['ts']!='speed') $istype=2;
			else if($_GET['ac']=='topic') $istype=3;
			include template('verify','verify');
				
		}else{
		   return false;	
		}
	
}

function action(){
	global $_POST;
	if(isset($_POST['q'])){
			 include('verifydata.php');
			 if($a[$_POST['q']]!=$_POST['a']){
				tsNotice('验证回答错误');
			}
		 }
	}
//小组发帖
addAction('group_add_footer','verify_html');
//小组发帖验证
addAction('group_topic_add','action');
//评论验证
addAction('group_comment_add','action');
//小组快速发帖
addAction('group_add_speed_post','verify_html');
//评论
addAction('group_topic_right_footer','verify_html');