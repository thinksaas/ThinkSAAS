<?php
defined('IN_TS') or die('Access Denied.');

class user extends tsApp{

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
     * 获取用户头像
     * @param $strUser
     * @return string
     */
	function getUserFace($strUser){
        if($strUser['face']){
            $strFace = tsXimg($strUser['face'],'user',120,120,$strUser['path'],1).'?v='.$strUser['uptime'];
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
	function getHotUser($num){
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
        ),'userid,locationid,username,face,path,uptime');
        $strUser['face'] = $this->getUserFace($strUser);
        return $strUser;
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
			//$this->toEmpty($userid);
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
	
		$userid = intval($_SESSION['tsuser']['userid']);

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
				header("Location: ".tsUrl('user','login',array('ts'=>'out')));
				exit;
			}
		}else{
			header("Location: ".tsUrl('user','login',array('ts'=>'out')));
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
	
	//处理积分
	function doScore($app,$ac,$ts='',$uid=0,$mg=''){
		$userid = intval($_SESSION['tsuser']['userid']);
		if($uid) $userid=$uid;
		$strScore = $this->find('user_score',array(
			'app'=>$app,
			'action'=>$ac,
			'mg'=>$mg,
			'ts'=>$ts,
		));
		
		if($strScore && $userid){
			if($strScore['status']=='0'){
				$this->addScore($userid,$strScore['scorename'],$strScore['score']);
			}
			
			if($strScore['status']=='1'){
				$this->delScore($userid,$strScore['scorename'],$strScore['score']);
			}
			
		}
		
	}
	
	//清空用户的一切数据
	function toEmpty($userid){
	
		$strUser = $this->find('user_info',array(
			'userid'=>$userid,
		));
		
		//是否存在Email
		$isEmail = $this->findCount('anti_email',array(
			'email'=>$strUser['email'],
		));
		if($strUser['email'] && $isEmail==0){
			$this->create('anti_email',array(
				'email'=>$strUser['email'],
				'addtime'=>date('Y-m-d H:i:s'),
			));
		}
		
		//article
		$this->delete('article',array('userid'=>$userid));
		$this->delete('article_comment',array('userid'=>$userid));
		$this->delete('article_recommend',array('userid'=>$userid));
		
		//attach
		$this->delete('attach',array('userid'=>$userid));
		$this->delete('attach_album',array('userid'=>$userid));
		
		//user
		$this->delete('user',array('userid'=>$userid));
		$this->delete('user_info',array('userid'=>$userid));
		$this->delete('user_follow',array('userid'=>$userid));
		$this->delete('user_follow',array('userid_follow'=>$userid));
		$this->delete('user_gb',array('userid'=>$userid));
		$this->delete('user_gb',array('touserid'=>$userid));
		$this->delete('user_open',array('userid'=>$userid));
		$this->delete('user_scores',array('userid'=>$userid));
		$this->delete('user_score_log',array('userid'=>$userid));
		
		//group
		$this->delete('group',array('userid'=>$userid,));
		$this->delete('group_album',array('userid'=>$userid,));
		$this->delete('group_topic',array('userid'=>$userid));
		$this->delete('group_user',array('userid'=>$userid));
		$this->delete('group_topic_comment',array('userid'=>$userid));
		$this->delete('group_topic_collect',array('userid'=>$userid));
	
		
		//message
		$this->delete('message',array('userid'=>$userid));
		$this->delete('message',array('touserid'=>$userid));
		
		//photo
		$this->delete('photo',array('userid'=>$userid));
		$this->delete('photo_album',array('userid'=>$userid));
		$this->delete('photo_comment',array('userid'=>$userid));
		
		//tag
		$this->delete('tag_user_index',array('userid'=>$userid));
		
		//weibo
		$this->delete('weibo',array('userid'=>$userid));
		$this->delete('weibo_comment',array('userid'=>$userid));
		
		//活动event
		$this->delete('event',array('userid'=>$userid));
		$this->delete('event_comment',array('userid'=>$userid));
		$this->delete('event_users',array('userid'=>$userid));
		
		//问答ask
		$this->delete('ask_comment',array('userid'=>$userid));
		$this->delete('ask_comment_add',array('userid'=>$userid));
		$this->delete('ask_comment_op',array('userid'=>$userid));
		$this->delete('ask_question_add',array('userid'=>$userid));
		$this->delete('ask_topic',array('userid'=>$userid));
		$this->delete('ask_user_cate',array('userid'=>$userid));
		$this->delete('ask_user_score',array('userid'=>$userid));
		
	}
	
	//获取locationid
	function getLocationId($userid){
		$strUser = $this->find('user_info',array(
			'userid'=>$userid,
		),'locationid');
		
		return intval($strUser['locationid']);
		
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

        $userid = intval($GLOBALS['TS_USER']['userid']);

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
        $userid = intval($GLOBALS['TS_USER']['userid']);

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