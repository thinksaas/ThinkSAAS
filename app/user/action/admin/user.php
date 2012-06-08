<?php
defined('IN_TS') or die('Access Denied.');
	/* 
	 * 用户管理
	 */
	
	switch($ts){
	
		//用户列表
		case "list":
		
			$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
			
			$userid = intval($_GET['userid']);
			$username = trim($_GET['username']);
			
			$arrData = null;
			if($userid > 0 && $username==''){
				$arrData = array('userid'=>$userid);
			}elseif($userid==0 && $username != ''){
				$arrData = array('username'=>$username);
			}elseif($userid>0 && $username != ''){
				$arrData = array('userid'=>$userid,'username'=>$username);
			}
			
			$lstart = $page*20-20;
			
			$url = SITE_URL.'index.php?app=user&ac=admin&mg=user&ts=list&userid='.$userid.'&username='.$username.'&page=';
			
			$arrAllUser	= $new['user']->findAll('user_info',$arrData,'userid desc',null,$lstart.',20');
			
			$userNum = $new['user']->findCount('user_info');
			
			$pageUrl = pagination($userNum, 20, $page, $url);

			include template("admin/user_list");
			
			break;
		
		//用户编辑
		case "edit":
			$userid = $_GET['userid'];
			$strUser = $new['user']->getOneUser($userid);
			
			include template("admin/user_edit");
			break;
		
		//用户查看 
		case "view":
			$userid = $_GET['userid'];
			
			$strUser = $new['user']->getOneUser($userid);
			
			include template("admin/user_view");
			break;
		
		//用户停用启用
		case "isenable":
		
			$userid = intval($_GET['userid']);
			
			$isenable = $_GET['isenable'];
			
			
			$new['user']->update('user_info',array(
			
				'userid'=>$userid,
			
			),array(
			
				'isenable'=>$isenable,
			
			));
			
			//更新数据
			$arrUsers = $new['user']->findAll('user_info',array(
				'isenable'=>'1',
			));
			
			$user_isenable = '';
			$count = 1;
			if(is_array($arrUsers)){
				foreach ($arrUsers as $item) {
					if ($count==1) {
						$user_isenable .= $item['userid'];
					} else {
						$user_isenable .= '|'.$item['userid'];
					}
						$count++;
				}
			}
			
			foreach($arrUsers as $item){
				if($item['ip']){
					$arrIp[] = $item['ip'];
				}
			}
		
			$user_ip = '';
			$counts = 1;
			if(is_array($arrIp)){
				foreach ($arrIp as $item) {
					if ($counts==1) {
						$user_ip .= $item;
					} else {
						$user_ip .= '|'.$item;
					}
						$counts++;
				}
			}
			
			fileWrite('user_isenable.php','data',$user_isenable);
			fileWrite('user_ip.php','data',$user_ip);
			
			qiMsg('用户停用成功！');
			
			break;
		
		//修改密码
		case "pwd":
			
			$userid = intval($_GET['userid']);
			
			$strUser = $new['user']->find('user',array(
				'userid'=>$userid,
			));
			
			include template('admin/user_pwd');
			break;
			
		//执行修改密码
		case "pwddo":
			
			$userid = intval($_POST['userid']);
			
			$pwd = trim($_POST['pwd']);
			
			if($pwd == '') qiMsg('密码不能为空！');
			
			$strUser = $new['user']->find('user',array(
				'userid'=>$userid,
			));
			
			$salt = md5(rand());
			
			$new['user']->update('user',array(
				'userid'=>$userid,
			),array(
				'pwd'=>md5($salt.$pwd),
				'salt'=>$salt,
			));
			
			qiMsg('密码修改成功：'.$pwd);
			
			break;
			
		//清空用户数据
		case "deldata":
			$userid = intval($_GET['userid']);
			
			//删除小组
			$new['user']->delete('group',array(
				'userid'=>$userid,
			));
			
			//删除帖子
			$new['user']->delete('group_topics',array(
				'userid'=>$userid,
			));
			
			//删除加入的小组
			$new['user']->delete('group_users',array(
				'userid'=>$userid,
			));
			
			//删除帖子评论
			$new['user']->delete('group_topics_comments',array(
				'userid'=>$userid,
			));
			
			//删除动态
			$new['user']->delete('feed',array(
				'userid'=>$userid,
			));
			
			//删除收藏
			$new['user']->delete('group_topics_collects',array(
				'userid'=>$userid,
			));
			
			qiMsg('清空数据成功！');
			
			break;
			
		//封IP
		case "kip":
			
			
			
			break;
		
	}