<?php
defined('IN_TS') or die('Access Denied.');

	switch($ts){
	
		//用户列表
		case "list":
		
			$page = isset($_GET['page']) ? tsIntval($_GET['page']) : 1;
			
			$userid = tsIntval($_GET['userid']);
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
		
			$userid = tsIntval($_GET['userid']);

			if($userid==1) qiMsg('无法停用该用户！');

			$page = tsIntval($_GET['page']);

			$strUser = $new['user']->find('user_info',array(
				'userid'=>$userid,
			));
			
			if($strUser['isadmin']==1) qiMsg('管理员不能停用！');
			
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
			
			#qiMsg('操作成功！');

            header('Location: '.SITE_URL.'index.php?app=user&ac=admin&mg=user&ts=list&page='.$page);
			
			break;
		
		//修改密码
		case "pwd":
			
			$userid = tsIntval($_GET['userid']);
			
			$strUser = $new['user']->find('user',array(
				'userid'=>$userid,
			));
			
			include template('admin/user_pwd');
			break;
			
		//执行修改密码
		case "pwddo":
			
			$userid = tsIntval($_POST['userid']);
			
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
			$userid = tsIntval($_GET['userid']);

			if($userid==1) qiMsg('该用户数据无法清空！');

			aac('user')->toEmpty($userid);
			qiMsg('清空数据成功！');
			
			break;
			
		//管理员 
		case "admin":
			
			$userid = tsIntval($_GET['userid']);

			if($userid==1) qiMsg('该用户无法取消管理员！');

			$strUser = $new['user']->find('user_info',array(
				'userid'=>$userid,
			));
			
			if($strUser['isadmin']==1){
				$new['user']->update('user_info',array(
					'userid'=>$userid,
				),array(
					'isadmin'=>'0',
					'isverify'=>'0',
					'isverifyphone'=>'0',
					'isrenzheng'=>'0',
				));
			}elseif($strUser['isadmin']==0){
				$new['user']->update('user_info',array(
					'userid'=>$userid,
				),array(
					'isadmin'=>'1',//系统管理员
					'isverify'=>'1',//Email验证
					'isverifyphone'=>'1',//手机号验证
					'isrenzheng'=>'1',//人工认证
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
			$userid = tsIntval($_GET['userid']);
			
			$new['user']->update('user_info',array(
				'userid'=>$userid,
			),array(
				'path'=>'',
				'face'=>'',
			));
			
			qiMsg('操作成功！');

            break;

        //是否手工认证
        case "isrenzheng":
			$userid = tsIntval($_GET['userid']);
			
			if($userid==1) qiMsg('该用户无法操作！');

            $strUser = $new['user']->find('user_info',array(
                'userid'=>$userid,
            ));


            //开通认证
            if($strUser['isrenzheng']==0){
                $new['user']->update('user_info',array(
                    'userid'=>$userid,
                ),array(
                    'isrenzheng'=>1,
                ));


                //发系统消息
                $msg_userid = '0';
                $msg_touserid = $userid;
                $msg_content = '恭喜你，系统已经通过你的个人信息认证！';
                aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);


            }


            //取消认证
            if($strUser['isrenzheng']==1){

                $new['user']->update('user_info',array(
                    'userid'=>$userid,
                ),array(
                    'isrenzheng'=>0,
                ));

                //发系统消息
                $msg_userid = '0';
                $msg_touserid = $userid;
                $msg_content = '很抱歉，系统取消了你的个人信息认证！';
                aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);

            }

            qiMsg('操作成功！');
			break;
			
		case "isverify":

			$userid = tsIntval($_GET['userid']);
			$strUser = $new['user']->find('user_info',array(
				'userid'=>$userid,
			));
			
			if($strUser['isverify']==0){
				$isverify = 1;
			}else{
				$isverify = 0;
			}

			$new['user']->update('user_info',array(
				'userid'=>$userid,
			),array(
				'isverify'=>$isverify,
			));

			qiMsg('操作成功！');

			break;

		case "isverifyphone":

			$userid = tsIntval($_GET['userid']);
			$strUser = $new['user']->find('user_info',array(
				'userid'=>$userid,
			));
			
			if($strUser['isverifyphone']==0){
				$isverifyphone = 1;
			}else{
				$isverifyphone = 0;
			}

			$new['user']->update('user_info',array(
				'userid'=>$userid,
			),array(
				'isverifyphone'=>$isverifyphone,
			));

			qiMsg('操作成功！');

			break;
		
	}