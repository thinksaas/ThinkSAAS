<?php
defined('IN_TS') or die('Access Denied.');

class user extends tsApp {

	//构造函数
	public function __construct($db){
        $tsAppDb = array();
		include 'app/user/config.php';
		//判断APP是否采用独立数据库
		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}
		
		parent::__construct($db);
    }
    
    /**
     * 用户登录
     *
     * @param [type] $userid
     * @param string $phone
     * @return void
     */
    public function login($userid,$phone=''){
        
        $strUserInfo = $this->find('user_info',array(
            'userid'=>$userid,
        ),'userid,username,path,face,isadmin,signin,uptime');

        $this->update('user_info',array(
            'userid'=>$strUserInfo['userid'],
        ),array(
            'uptime'=>time(),
        ));

        #清空验证码
        if($phone){
            $this->update('phone_code',array(
                'phone'=>$phone,
            ),array(
                'code'=>'',
            ));
        }
        
        //用户session信息
        $sessionData = array(
            'userid' => $strUserInfo['userid'],
            'username' => $strUserInfo['username'],
            'path' => $strUserInfo['path'],
            'face' => $strUserInfo['face'],
            'isadmin' => $strUserInfo['isadmin'],
            'signin' =>$strUserInfo['signin'],
            'uptime' => $strUserInfo['uptime'],
        );

        $_SESSION['tsuser']	= $sessionData;
        
        //更新登录时间，用作自动登录
        $autologin = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->update('user_info',array(
            'userid'=>$strUserInfo['userid'],
        ),array(
            'ip'=>getIp(),  //更新登录ip
            'autologin'=>$autologin,
            'uptime'=>time(),   //更新登录时间
        ));
        
        //记住登录Cookie，根据用户Email和最后登录时间
        setcookie("ts_email", $strUserInfo['email'], time()+2592000,'/');
        setcookie("ts_autologin", $autologin, time()+2592000,'/');
        
    }

    /**
     * 用户注册
     *
     * @param [type] $email
     * @param string $username
     * @param string $pwd
     * @param integer $fuserid
     * @return void
     */
    public function register($email,$username='',$pwd='',$fuserid=0,$invitecode=''){

        $salt = md5(rand());
        
        if($pwd=='') $pwd = random(6);
        
		$userid = $this->create('user',array(
			'pwd'=>md5($salt.$pwd),
			'salt'=>$salt,
			'email'=>$email,
			'phone'=>$email,
        ));

        if($username=='') $username = 'TS'.$userid;

        $isverifyphone = 0;

        if(isPhone($email)==true){

            $isverifyphone = 1;

            #清空验证码
            $this->update('phone_code',array(
                'phone'=>$email,
            ),array(
                'code'=>'',
            ));

        }
		
		//插入用户信息			
		$this->create('user_info',array(
			'userid'	=> $userid,
			'fuserid'	=> $fuserid,
			'username' 	=> $username,
            'email'		=> $email,
            'phone'		=> $email,
            'ip'		=> getIp(),
            'isverifyphone'=>$isverifyphone,
			'addtime'	=> time(),
			'uptime'	=> time(),
		));
		
		//默认加入小组
		if($GLOBALS['TS_APP']['isgroup']){
			$arrGroup = explode(',',$GLOBALS['TS_APP']['isgroup']);
			if($arrGroup){
				foreach($arrGroup as $key=>$item){
					$groupUserNum = $this->findCount('group_user',array(
						'userid'=>$userid,
						'groupid'=>$item,
					));
					if($groupUserNum == 0){
						$this->create('group_user',array(
							'userid'=>$userid,
							'groupid'=>$item,
							'addtime'=>time(),
						));
						//统计更新
						$count_user = $this->findCount('group_user',array(
							'groupid'=>$item,
						));
						
						$this->update('group',array(
							'groupid'=>$item,
						),array(
							'count_user'=>$count_user,
						));
					}
				}
			}
		}
		
		//用户信息
		$userData = $this->find('user_info',array(
			'userid'=>$userid,
		),'userid,username,path,face,isadmin,signin,uptime');
		
		//用户session信息
		$_SESSION['tsuser']	= $userData;
		
		//发送消息
		aac('message')->sendmsg(0,$userid,'亲爱的 '.$username.' ：您成功加入了 '.$GLOBALS['TS_SITE']['site_title'].'。在遵守本站的规定的同时，享受您的愉快之旅吧!');
		
		//注销邀请码并将关注邀请用户
		if($GLOBALS['TS_SITE']['isinvite']=='1' && $invitecode){
			
			//邀请码信息
			$strInviteCode = $this->find('user_invites',array(
				'invitecode'=>$invitecode,
			));
			
			$this->create('user_follow',array(
				'userid'=>$userid,
				'userid_follow'=>$strInviteCode['userid'],
			));
			
			//注销
			$this->update('user_invites',array(
				'invitecode'=>$invitecode,
			),array(
				'isused'=>'1',
			));
        }
        

        return $userid;

    }


    /**
     * 获取用户头像
     * @param $strUser
     * @return string
     */
	function getUserFace($strUser){
        if($strUser['face']){
            if($GLOBALS['TS_SITE']['file_upload_type']==1){
                #阿里云(对象云存储OSS)数据
                $strFace = tsXimg($strUser['face'],'user',120,120,$strUser['path'],1).'&v='.$strUser['uptime'];
            }else{
                #本地数据
                $strFace = tsXimg($strUser['face'],'user',120,120,$strUser['path'],1).'?v='.$strUser['uptime'];
            }
        }else{
            $strFace = SITE_URL.'public/images/user_large.jpg';
        }
        return $strFace;
    }
	
	/**
     * 获取最新会员
     */
	function getNewUser($num){
		$arrUser = $this->findAll('user_info',null,'addtime desc','userid,username,face,path,addtime,uptime',$num);
		foreach($arrUser as $key=>$item){
            $arrUser[$key]['face'] = $this->getUserFace($item);
		}
		return $arrUser;
	}
	
	//获取活跃会员
	public function getHotUser($num){
        $arrUser = $this->findAll('user_info',null,'uptime desc','userid,username,face,path,addtime,uptime',$num);
        foreach($arrUser as $key=>$item){
            $arrUser[$key]['face'] = $this->getUserFace($item);
        }
        return $arrUser;
	}
	
	//最多关注的用户
	public function getFollowUser($num){
        $arrUser = $this->findAll('user_info',null,'count_followed desc','userid,username,face,path,count_followed,addtime,uptime',$num);
        foreach($arrUser as $key=>$item){
            $arrUser[$key]['face'] = $this->getUserFace($item);
        }
        return $arrUser;
	}
	
	//最多积分的用户
	public function getScoreUser($num){
        $arrUser = $this->findAll('user_info',null,'count_score desc','userid,username,face,path,count_score,addtime,uptime',$num);
        foreach($arrUser as $key=>$item){
            $arrUser[$key]['face'] = $this->getUserFace($item);
        }
        return $arrUser;
	}

    #获取简单的用户信息
    function getSimpleUser($userid){
        $strUser = $this->find('user_info',array(
            'userid'=>$userid,
        ),'userid,username,face,path,uptime');
        if($strUser){
            $strUser['face'] = $this->getUserFace($strUser);
            return $strUser;
        }else{
            return '';
        } 
    }
	
	//获取一个用户的信息
	function getOneUser($userid){
			
        $strUser = $this->find('user_info',array(
            'userid'=>$userid,
        ));

        if($strUser){

            $strUser['username'] = tsTitle($strUser['username']);
            $strUser['email'] = tsTitle($strUser['email']);
            $strUser['phone'] = tsTitle($strUser['phone']);
            $strUser['province'] = tsTitle($strUser['province']);
            $strUser['city'] = tsTitle($strUser['city']);
            $strUser['signed'] = tsTitle($strUser['signed']);
            $strUser['about'] = tsTitle($strUser['about']);
            $strUser['address'] = tsTitle($strUser['address']);

            $strUser['face'] = $this->getUserFace($strUser);

            $strUser['rolename'] = $this->getRole($strUser['allscore']);

        }else{
            $strUser = '';
        }

        return $strUser;
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


    /**
     * @param int $js
     * @param string $userkey
     * @return int
     */
    public function isLogin($js=0, $userkey=''){
	
		$userid = tsIntval($_SESSION['tsuser']['userid']);

        if($js && $userid==0 && $userkey==''){
            getJson('你还没有登录',$js);
        }

        #通过userkey返回userid
        if($js && $userid==0 && $userkey){
            $userid = $this->getUserIdByUserKey($userkey);
            return $userid;
        }

		if($userid>0){
			if($this->isUser($userid)){
				return $userid;
			}else{
				header("Location: ".tsUrl('user','logout'));
				exit;
			}
		}else{
			header("Location: ".tsUrl('user','logout'));
			exit;
		}
	}
	

	
	//根据用户积分获取用户角色
	public function getRole($score){
		global $tsMySqlCache;
		$arrRole = fileRead('data/user_role.php');
		
		if($arrRole==''){
			$arrRole = $tsMySqlCache->get('user_role');
		}
		
		foreach($arrRole as $key=>$item){
			if($score > $item['score_start'] && $score <= $item['score_end'] || $score > $item['score_start'] && $item['score_end']==0 || $score >=0 && $score <= $item['score_end']){
				return $item['rolename'];
			}
		}
	}
	
	/**
	 * 增加积分
	 * $userid 用户ID
	 * $scorename 积分名字 
	 * $score 积分
	 * @issx 上线限制0限制1不限制
	 */
	public function addScore($userid,$scorename,$score,$issx=0){
		if($userid && $scorename && $score){


		    #计算当天已经获得的积分
            $starttime = strtotime(date('Y-m-d 00:00:01'));
            $endtime = strtotime(date('Y-m-d 23:59:59'));
            $strDayScore = $this->db->once_fetch_assoc("select SUM(score) as dayscore from ".dbprefix."user_score_log where `userid`='$userid' and  `status`='0' and `addtime`>='$starttime' and `addtime`<='$endtime'");

            #用户每日获得积分上限
            if($strDayScore['dayscore']<$GLOBALS['TS_SITE']['dayscoretop'] || $issx==1){

                //添加积分记录
                $this->create('user_score_log',array(
                    'userid'=>$userid,
                    'scorename'=>$scorename,
                    'score'=>$score,
                    'status'=>0,
                    'addtime'=>time(),
                ));

                //计算总积分
                $strUser = $this->find('user_info',array(
                    'userid'=>$userid,
                ));

                $strAllScore = $this->db->once_fetch_assoc("select SUM(score) as allscore from ".dbprefix."user_score_log where `userid`='$userid' and  `status`='0'");

                $this->update('user_info',array(
                    'userid'=>$userid,
                ),array(
                    'allscore'=>$strAllScore['allscore'],
                    'count_score'=>$strUser['count_score']+$score,
                ));


            }


		}
	}
	
	/*
	 * 减去积分
	 */
	public function delScore($userid,$scorename,$score){
		if($userid && $scorename && $score){

			//计算总积分
			$strUser = $this->find('user_info',array(
				'userid'=>$userid,
			));

			if($strUser['count_score']>$score){

                //添加积分记录
                $this->create('user_score_log',array(
                    'userid'=>$userid,
                    'scorename'=>$scorename,
                    'score'=>$score,
                    'status'=>1,
                    'addtime'=>time(),
                ));

                $this->update('user_info',array(
                    'userid'=>$userid,
                ),array(
                    'count_score'=>$strUser['count_score']-$score,
                ));

                return true;

            }else{

			    return false;

            }


		}
	}
	
	/**
     * 处理积分
     *
     * @param [type] $app
     * @param [type] $ac
     * @param string $ts
     * @param integer $uid     指定用户ID
     * @param string $mg       指向后台管理文件(用于后台操作得积分处理)
     * @param integer $isday   是否一天只给一次积分，默认0否1是
     * @return void
     */
	function doScore($app,$ac,$ts='',$uid=0,$mg='',$isday=0){
		$userid = tsIntval($_SESSION['tsuser']['userid']);
		if($uid) $userid=$uid;
		$strScore = $this->find('user_score',array(
			'app'=>$app,
			'action'=>$ac,
			'mg'=>$mg,
			'ts'=>$ts,
		));
		if($strScore && $userid){
			if($strScore['status']=='0'){
                if($isday==1){
                    //获取最新得一条积分记录
                    $strScoreLog = $this->find('user_score_log',array(
                        'userid'=>$userid,
                        'scorename'=>$strScore['scorename'],
                    ),null,'addtime desc');
                    if(($strScoreLog && date('Y-m-d H:i:s',$strScoreLog['addtime'])<date('Y-m-d 00:00:01')) || $strScoreLog==''){
                        $this->addScore($userid,$strScore['scorename'],$strScore['score']);
                    }
                }else{
                    //0加积分
                    $this->addScore($userid,$strScore['scorename'],$strScore['score']);
                }
			}elseif($strScore['status']=='1'){
                //1减积分
				$this->delScore($userid,$strScore['scorename'],$strScore['score']);
			}
		}
    }
	
	//删除用户一切数据
	function toEmpty($userid){
	
		$strUser = $this->find('user_info',array(
			'userid'=>$userid,
		),'userid,email,phone,face');
		
		#禁用用户Email账号
        $this->replace('anti_email',array(
            'email'=>$strUser['email'],
        ),array(
            'email'=>$strUser['email'],
			'addtime'=>date('Y-m-d H:i:s'),
        ));

        #禁用用户手机号
        $this->replace('anti_phone',array(
            'phone'=>$strUser['phone'],
        ),array(
            'phone'=>$strUser['phone'],
			'addtime'=>date('Y-m-d H:i:s'),
        ));

        #用户头像
        if($strUser['face']){

            if($GLOBALS['TS_SITE']['file_upload_type']==1){
                deleteAliOssFile('uploadfile/user/'.$strUser['face']);
            }else{
                unlink('uploadfile/user/'.$strUser['photo']);
                tsDimg($strUser['face'],'user','120','120',$strUser['path']);
            }

        }
        
        #用户相关数据
		$this->delete('user',array('userid'=>$userid));
		$this->delete('user_info',array('userid'=>$userid));
		$this->delete('user_follow',array('userid'=>$userid));
		$this->delete('user_follow',array('userid_follow'=>$userid));
		$this->delete('user_gb',array('userid'=>$userid));
		$this->delete('user_gb',array('touserid'=>$userid));
		$this->delete('user_open',array('userid'=>$userid));
		$this->delete('user_score_log',array('userid'=>$userid));
		
        #文章
        $arrArticle = $this->findAll('article',array(
            'userid'=>$userid,
        ));
        foreach($arrArticle as $key=>$item){
            aac('article')->deleteArticle($item);
        }
        $this->delete('article_user',array('userid'=>$userid));

        #草稿箱
        $this->delete('draft',array('userid'=>$userid));

        #编辑器上传的文件
        $arrEditor = $this->findAll('editor',array(
            'userid'=>$userid,
        ));
        foreach($arrEditor as $key=>$item){
            unlink('uploadfile/editor/'.$item['url']);
        }
        $this->delete('editor',array('userid'=>$userid));

        #小组
		$this->delete('topic',array('userid'=>$userid));
		$this->delete('group_user',array('userid'=>$userid));
		
		//attach
		$this->delete('attach',array('userid'=>$userid));
		$this->delete('attach_album',array('userid'=>$userid));
		
		//message
		$this->delete('message',array('userid'=>$userid));
		$this->delete('message',array('touserid'=>$userid));
		
		//photo
		$this->delete('photo',array('userid'=>$userid));
		$this->delete('photo_album',array('userid'=>$userid));
		
		//tag
		$this->delete('tag_user_index',array('userid'=>$userid));
		
		//weibo
		$this->delete('weibo',array('userid'=>$userid));
		
		//活动ts_event
		$this->delete('event',array('userid'=>$userid));
		$this->delete('event_users',array('userid'=>$userid));
		
        //问答ts_ask
        $this->delete('ask',array('userid'=>$userid));
		$this->delete('ask_comment',array('userid'=>$userid));
		$this->delete('ask_comment_op',array('userid'=>$userid));
        
        #删除评论ts_comment
        $this->delete('comment',array('userid'=>$userid));
        #删除点赞ts_love
        $this->delete('love',array('userid'=>$userid));
		
	}
	
	
	//销毁前台session退出登陆
	function logout(){
		unset($_SESSION['tsuser']);
		session_destroy();
		setcookie("ts_email", '', time()+3600,'/');   
		setcookie("ts_autologin", '', time()+3600,'/');
	}

    //用户签到
    function signin(){

        $userid = tsIntval($GLOBALS['TS_USER']['userid']);

        $zuotian = date('Y-m-d',strtotime("-1 day"));

        $jintian = date('Y-m-d');


        $zuotianSign = $this->find('sign',array(
            'userid'=>$userid,
            'addtime'=>$zuotian,
        ));

        $jintianSign = $this->find('sign',array(
            'userid'=>$userid,
            'addtime'=>$jintian,
        ));

        if($jintianSign==''){

            if($zuotianSign==''){
                $this->create('sign',array(
                    'userid'=>$userid,
                    'num'=>1,
                    'addtime'=>$jintian,
                ));
            }else{

                $this->create('sign',array(
                    'userid'=>$userid,
                    'num'=>$zuotianSign['num']+1,
                    'addtime'=>$jintian,
                ));

            }

            //加积分
            $this->doScore('user','signin');

            return true;

        }else{
            return false;
        }
    }


    /*
     * 判断是否允许用户发布内容
     */
    public function isPublisher(){
        $publisher = $GLOBALS['TS_SITE']['publisher'];
        $userid = tsIntval($GLOBALS['TS_USER']['userid']);

        if($publisher){

            $ispublisher = $this->findCount('user_info',array(
                'userid'=>$userid,
                $publisher=>1,
            ));

            if($ispublisher){
                return true;
            }else{
                return false;
            }

        }else{
            return true;
        }

    }


    /**
     * 通过 userid 获取 userkey
     * @param $userid
     * @return bool|string
     */
    public function getUserKeyByUserId($userid){
        include 'thinksaas/class.crypt.php';
        $crypt= new crypt();
        return $crypt->encrypt($userid,$GLOBALS['TS_SITE']['site_pkey']);
    }

    /**
     * 通过userkey获取userid
     * @param $userkey
     */
    public function getUserIdByUserKey($userkey){
        include 'thinksaas/class.crypt.php';
        $crypt= new crypt();
        $userid = $crypt->decrypt($userkey,$GLOBALS['TS_SITE']['site_pkey']);

        $isUser = $this->findCount('user',array(
            'userid'=>$userid,
        ));

        if($isUser == 0){

            echo json_encode(array(

                'status'=> 0,
                'msg'=> '非法操作',
                'data'=> '',

            ));
            exit;

        }else{

            return $userid;

        }
    }


	
	//析构函数
	public function __destruct(){
		
	}
					
}