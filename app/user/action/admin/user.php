<?php
defined('IN_TS') or die('Access Denied.');

	switch($ts){
	
		//用户列表
		case "list":
		
			$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
			
			$userid = intval($_GET['userid']);
			$username = tsFilter($_GET['username']);
			
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
			$strUser = $new['user']->find('user_info',array(
				'userid'=>$userid,
			));
			
			
			//禁用
			if($strUser['isenable']==0){
			
				$new['user']->update('user_info',array(
					'userid'=>$userid,
				),array(
					'isenable'=>1,
				));
				
				//封用户Id
				$isuser = $new['user']->findCount('anti_user',array(
					'userid'=>$userid,
				));
				if($isuser==0){
					$new['user']->create('anti_user',array(
						'userid'=>$userid,
						'addtime'=>date('Y-m-d H:i:s'),
					));
				}
				
				//封IP
				$isip = $new['user']->findCount('anti_ip',array(
					'ip'=>$strUser['ip']
				));
				if($isip==0 && $strUser['ip']){
					$new['user']->create('anti_ip',array(
						'ip'=>$strUser['ip'],
						'addtime'=>date('Y-m-d H:i:s'),
					));
				}
			
			}
			
			
			//启用
			if($strUser['isenable']==1){
			
				$new['user']->update('user_info',array(
					'userid'=>$userid,
				),array(
					'isenable'=>0,
				));
				
				$new['user']->delete('anti_user',array(
					'userid'=>$userid,
				));
				$new['user']->delete('anti_ip',array(
					'ip'=>$strUser['ip'],
				));
			}
			
			qiMsg('操作成功！');
			
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
			aac('user')->toEmpty($userid);
			qiMsg('清空数据成功！');
			
			break;
			
		//管理员 
		case "admin":
			
			$userid = intval($_GET['userid']);
			$strUser = $new['user']->find('user_info',array(
				'userid'=>$userid,
			));
			
			if($strUser['isadmin']==1){
				$new['user']->update('user_info',array(
					'userid'=>$userid,
				),array(
					'isadmin'=>'0',
				));
			}elseif($strUser['isadmin']==0){
				$new['user']->update('user_info',array(
					'userid'=>$userid,
				),array(
					'isadmin'=>'1',
				));
			}
			
			qiMsg('操作成功！');
			
			break;
			
		//清空全部被禁用的用户数据并保存垃圾Email
		case "clean":
		
			$arrUser = $new['user']->findAll('user_info',array(
				'isenable'=>1,
			));
			foreach($arrUser as $key=>$item){
				//执行删除用户数据
				aac('user')->toEmpty($item['userid']);
			}
			
			qiMsg('垃圾用户清空完毕！');
		
			break;
			
		case "face":
			$userid = intval($_GET['userid']);
			
			$new['user']->update('user_info',array(
				'userid'=>$userid,
			),array(
				'path'=>'',
				'face'=>'',
			));
			
			qiMsg('操作成功！');
		
	}