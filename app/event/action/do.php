<?php
defined('IN_TS') or die('Access Denied.');
	switch($ts){
	
		//发布活动
		case "add":
			$userid = intval($TS_USER['user']['userid']);
			$title = h($_POST['title']);
			$typeid = $_POST['typeid'];
			$time_start = $_POST['time_start'];
			$time_end = $_POST['time_end'];
			$address = $_POST['address'];
			$content = trim($_POST['content']);
			
			$oneid = intval($_POST['oneid']);
			$twoid = intval($_POST['twoid']);
			$threeid = intval($_POST['threeid']);
			
			if($oneid != 0 && $twoid==0 && $threeid==0){
				$areaid = $oneid;
			}elseif($oneid!=0 && $twoid !=0 && $threeid==0){
				$areaid = $twoid;
			}elseif($oneid!=0 && $twoid !=0 && $threeid!=0){
				$areaid = $threeid;
			}else{
				$areaid = 0;
			}
			
			if($userid == '0' || $title=='' || $time_end <=$time_start || $address=='' || $content==''){
				qiMsg("活动信息不符合要求！");
			}
			
			$eventData = array(
				'userid'	=> $userid,
				'title'	=> $title,
				'typeid' => $typeid,
				'time_start'	=> $time_start,
				'time_end'	=> $time_end,
				'areaid'	=> $areaid,
				'address'	=> $address,
				'content' => $content,
				'addtime'	=> time(),
			);
			
			$eventid = $db->insertArr($eventData,dbprefix.'event');
			
			//更新统计活动分类缓存
			$arrTypess = $db->fetch_all_assoc("select * from ".dbprefix."event_type");
			foreach($arrTypess as $key=>$item){
				$event = $db->once_fetch_assoc("select count(eventid) from ".dbprefix."event where typeid='".$item['typeid']."'");
				$arrTypes['list'][] = array(
					'typeid'	=> $item['typeid'],
					'typename'	=> $item['typename'],
					'count_event'	=> $event['count(eventid)'],
				);
			}
			
			$eventNum = $db->once_fetch_assoc("select count(eventid) from ".dbprefix."event");
			$arrTypes['count'] = $eventNum['count(eventid)'];
			
			//生成缓存文件
			fileWrite('event_types.php','data',$arrTypes);
			
			//上传海报图片 
			if (!empty($_FILES)) {
			
				$uptypes = array('jpg','gif','png');
				
				//1000个文件一个目录 
				$menu2=intval($eventid/1000);
				$menu1=intval($menu2/1000);
				$menu = $menu1.'/'.$menu2;
				
				$newdir = $menu;
				$dest_dir='uploadfile/event/'.$newdir;

				createFolders($dest_dir);
				
				$photoname = $_FILES["photo"]['name'];
				
				$arrType = explode('.',$photoname);
				$phototype = $arrType[1];
				
				if (in_array($phototype,$uptypes)) {
					//上传图片
					$fileInfo=pathinfo($photoname);
					$extension=$fileInfo['extension'];
					$newphotoname = $eventid.'.'.$extension;
					$dest=$dest_dir.'/'.$newphotoname;
					move_uploaded_file($_FILES['photo']['tmp_name'], mb_convert_encoding($dest,"gb2312","UTF-8"));
					chmod($dest, 0777); 
					
					$poster = $newdir.'/'.$newphotoname;
					
					$db->query("update ".dbprefix."event set `path`='$menu',`poster`='$poster' where eventid='$eventid'");
					
				}
			}
			
			header("Location: ".SITE_URL."index.php?app=event&ac=show&eventid=".$eventid);
			
			break;
	
		//参加或者感兴趣活动
		case "dowish":
			$userid = $TS_USER['user']['userid'];
			$eventid = $_POST['eventid'];
			
			//0加入1感兴趣
			$status = intval($_POST['status']);
			
			$userNum = $db->once_num_rows("select * from ".dbprefix."event_users where eventid='$eventid' and userid='$userid'");
			
			if($userid == ''){
				echo '0';return false;
			}elseif($userNum > '0'){
				echo '1';
			}else{
				$db->query("insert into ".dbprefix."event_users (`eventid`,`userid`,`status`,`addtime`) values ('$eventid','$userid','$status','".time()."')");
				//统计一下参加的
				$userDoNum = $db->once_num_rows("select * from ".dbprefix."event_users where eventid='$eventid' and status='0'");
				//统计感兴趣的
				$userWishNum = $db->once_num_rows("select * from ".dbprefix."event_users where eventid='$eventid' and status='1'");
				
				$db->query("update ".dbprefix."event set `count_userdo`='$userDoNum',`count_userwish`='$userWishNum' where eventid='$eventid'");
				
				//event
				$strEvent = $db->once_fetch_assoc("select title,time_start,path,poster,address,count_userdo from ".dbprefix."event where `eventid`='$eventid'");
				
				//feed开始
				if($status == 1){
					$feed_template = '<a href="{link}" title=""><img alt="{title}" src="{photo}" class="broadimg"></a><span class="pl">对<a  href="{link}">《{title}》</a>活动感兴趣</span><div class="broadsmr">{content}</div>';
				}else{
					$feed_template = '<a href="{link}" title="{title}"><img alt="{title}" src="{photo}" class="broadimg"></a><span class="pl">要参加<a  href="{link}">《{title}》</a>活动</span><div class="broadsmr">{content}</div>';
				}
				
				$feed_data = array(
					'link'	=> SITE_URL.tsurl('event','show',array('eventid'=>$eventid)),
					'title'	=> $strEvent['title'],
					'content'	=> '开始时间：'.$strEvent['time_start'].' / 地点：'.$strEvent['address'].' / 参加人数：'.$strEvent['count_userdo'],
				);
				
				if($strEvent['poster'] != ''){
					$feed_data['photo']	= SITE_URL.miniimg($strEvent['poster'],'event',85,85,$strEvent['path']);
				}else{
					$feed_data['photo']	= SITE_URL.'public/images/event_dft.jpg';
				}
				
				aac('feed')->addFeed($userid,$feed_template,serialize($feed_data));
				//feed结束
				
				echo '2';
			}
			
			break;
			
		//取消参加活动
		case "cancel":
			
			$eventid = $_POST['eventid'];
			$userid	= $_POST['userid'];
			
			$db->query("delete from ".dbprefix."event_users where eventid='$eventid' and userid='$userid'");
			//统计一下参加的
			$userDoNum = $db->once_num_rows("select * from ".dbprefix."event_users where eventid='$eventid' and status='0'");
			//统计感兴趣的
			$userWishNum = $db->once_num_rows("select * from ".dbprefix."event_users where eventid='$eventid' and status='1'");
			
			$db->query("update ".dbprefix."event set `count_userdo`='$userDoNum',`count_userwish`='$userWishNum' where eventid='$eventid'");
			
			echo '1';
			
			break;
			
		//参加活动
		case "do":
			$eventid = $_POST['eventid'];
			$userid	= $_POST['userid'];
			$db->query("update ".dbprefix."event_users set `status`='0' where eventid='$eventid' and userid='$userid'");
			
			//统计一下参加的
			$userDoNum = $db->once_num_rows("select * from ".dbprefix."event_users where eventid='$eventid' and status='0'");
			//统计感兴趣的
			$userWishNum = $db->once_num_rows("select * from ".dbprefix."event_users where eventid='$eventid' and status='1'");
			
			$db->query("update ".dbprefix."event set `count_userdo`='$userDoNum',`count_userwish`='$userWishNum' where eventid='$eventid'");
			
			echo '1';
			
			break;
			
		//编辑执行
		case "edit":
			//发布
			$eventid = $_POST['eventid'];
			$title = $_POST['title'];
			$typeid = $_POST['typeid'];
			$time_start = $_POST['time_start'];
			$time_end = $_POST['time_end'];
			$address = trim($_POST['address']);
			$content = trim($_POST['content']);
			
			$eventData = array(
				'typeid' => $typeid,
				'title'	=> $title,
				'time_start'	=> $time_start,
				'time_end'	=> $time_end,
				'address'	=> $address,
				'content'	=> $content,
			);
			
			$db->updateArr($eventData,dbprefix."event","where eventid='$eventid'");
			
			//上传海报图片 
			if (!empty($_FILES)) {
			
				$uptypes = array('jpg','gif','png');
				
				//1000个文件一个目录 
				$menu2=intval($eventid/1000);
				$menu1=intval($menu2/1000);
				$menu = $menu1.'/'.$menu2;
				
				$newdir = $menu;
				$dest_dir='uploadfile/event/'.$newdir;

				createFolders($dest_dir);
				
				$photoname = $_FILES["photo"]['name'];
				
				$arrType = explode('.',$photoname);
				$phototype = $arrType[1];
				
				if (in_array($phototype,$uptypes)) {
					//上传图片
					$fileInfo=pathinfo($photoname);
					$extension=$fileInfo['extension'];
					$newphotoname = $eventid.'.'.$extension;
					$dest=$dest_dir.'/'.$newphotoname;
					move_uploaded_file($_FILES['photo']['tmp_name'], mb_convert_encoding($dest,"gb2312","UTF-8"));
					chmod($dest, 0777); 
					
					$poster = $newdir.'/'.$newphotoname;
					
					$db->query("update ".dbprefix."event set `path`='$menu',`poster`='$poster' where eventid='$eventid'");
					
				}
			}
			
			header("Location: ".SITE_URL."index.php?app=event&ac=show&eventid=".$eventid);
			
			break;
			
		//添加评论 
		case "comment":
			$eventid	= intval($_POST['eventid']);
			$content	= trim($_POST['content']);
			
			if($TS_USER['user'] == ''){
				qiMsg('请登陆后再发表内容^_^','点击登陆','index.php/user/login');
			}elseif(empty($content)){
				qiMsg('没有任何内容是不允许你通过滴^_^');
			}else{
				$arrData	= array(
					'eventid'			=> $eventid,
					'userid'			=> $TS_USER['user']['userid'],
					'content'	=> $content,
					'addtime'		=> time()
				);
				
				$commentid = $db->insertArr($arrData,dbprefix.'event_comment');
				
				
				//发送系统消息(通知活动组织者有人回复他的活动啦)
				$strEvent = $db->once_fetch_assoc("select * from ".dbprefix."event where eventid='$eventid'");
				if($strEvent['userid'] != $TS_USER['user']['userid']){

					$msg_userid = '0';
					$msg_touserid = $strEvent['userid'];
					$msg_content = '你的活动：《'.$strEvent['title'].'》新增一条评论，快去看看给个回复吧^_^ <br />'
												.SITE_URL.'index.php/event/show/eventid-'.$eventid;
					aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
				
				}
				
				header("Location: ".SITE_URL."index.php/event/show/eventid-".$eventid);
				
			}	
			break;
			
		//推荐活动,取消推荐 
		case "recommend":
			$eventid = intval($_GET['eventid']);
			$isrecommend = intval($_GET['isrecommend']);
			
			$db->query("update ".dbprefix."event set `isrecommend`='$isrecommend' where `eventid`='$eventid'");
			
			qiMsg("操作成功！");
			
			break;
			
	}
