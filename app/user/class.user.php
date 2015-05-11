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
	
	//获取最新会员
	function getNewUser($num){
		$arrNewUserId = $this->findAll('user_info',null,'addtime desc','userid',$num);
		foreach($arrNewUserId as $item){
			$arrNewUser[] = $this->getOneUser($item['userid']);
		}
		return $arrNewUser;
	}
	
	//获取活跃会员
	function getHotUser($num){
		$arrNewUserId = $this->findAll('user_info',null,'uptime desc','userid',$num);
		foreach($arrNewUserId as $item){
			$arrHotUser[] = $this->getOneUser($item['userid']);
		}
		return $arrHotUser;
	}
	
	//最多关注的用户
	public function getFollowUser($num){
		$arrUserId = $this->findAll('user_info',null,'count_followed desc','userid',$num);
		foreach($arrUserId as $item){
			$arrFollowUser[] = $this->getOneUser($item['userid']);
		}
		return $arrFollowUser;
	}
	
	//最多积分的用户
	public function getScoreUser($num){
		$arrUserId = $this->findAll('user_info',null,'count_score desc','userid',$num);
		foreach($arrUserId as $item){
			$arrScoreUser[] = $this->getOneUser($item['userid']);
		}
		return $arrScoreUser;
	}
	
	//获取一个用户的信息
	function getOneUser($userid){
			
			$strUser = $this->find('user_info',array(
				'userid'=>$userid,
			));
			
			if($strUser){
			
				$strUser['username'] = tsTitle($strUser['username']);
			
				if($strUser['face'] && $strUser['path']){
					$strUser['face'] = tsXimg($strUser['face'],'user',120,120,$strUser['path'],1);
				}elseif($strUser['face'] && $strUser['path']==''){
					$strUser['face']	= SITE_URL.'public/images/'.$strUser['face'];
				}else{
					//没有头像
					$strUser['face']	= SITE_URL.'public/images/user_large.jpg';
				}
			}else{
				$strUser = '';
			}
			
			return $strUser;
	}
	
	//用户是否存在
	public function isUser($userid){
		
		$isUser = $this->findCount('user',array('userid'=>$userid));
		
		if($isUser == 0){
			$this->toEmpty($userid);
			return false;
		}else{
			return true;
		}
		
	}
	
	//是否登录 
	public function isLogin($type=''){
	
		$userid = intval($_SESSION['tsuser']['userid']);

        if($type && $userid==0){
            getJson('你还没有登录',1);
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
	
	public function getOneArea($areaid){
	
		$strArea = $this->find('area',array('areaid'=>$areaid));
		return $strArea;
	
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
	
	/*
	 * 增加积分
	 * $userid 用户ID
	 * $scorename 积分名字 
	 * $score 积分
	 */
	public function addScore($userid,$scorename,$score){
		if($userid && $scorename && $score){
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
			$this->update('user_info',array(
				'userid'=>$userid,
			),array(
				'count_score'=>$strUser['count_score']+$score,
			));
		}
	}
	
	/*
	 * 减去积分
	 */
	public function delScore($userid,$scorename,$score){
		if($userid && $scorename && $score){
			//添加积分记录
			$this->create('user_score_log',array(
				'userid'=>$userid,
				'scorename'=>$scorename,
				'score'=>$score,
				'status'=>1,
				'addtime'=>time(),
			));
			//计算总积分
			$strUser = $this->find('user_info',array(
				'userid'=>$userid,
			));
			$this->update('user_info',array(
				'userid'=>$userid,
			),array(
				'count_score'=>$strUser['count_score']-$score,
			));
		}
	}
	
	//处理积分
	function doScore($app,$ac,$ts='',$uid=0){
		$userid = intval($_SESSION['tsuser']['userid']);
		if($uid) $userid=$uid;
		$strScore = $this->find('user_score',array(
			'app'=>$app,
			'action'=>$ac,
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
		$userid = intval($_SESSION['tsuser']['userid']);
		$signin = intval($_SESSION['tsuser']['signin']);
		if($userid && $signin<strtotime(date('Y-m-d 00:00:00'))){
			$this->update('user_info',array(
				'userid'=>$userid,
			),array(
				'signin'=>time(),
			));
			
			//加积分
			$this->doScore('user','signin');
			
			//更新signin
			$_SESSION['tsuser']['signin'] = time();
			
			return true;
			
		}else{
			return false;
		}
	}
	
	//析构函数
	public function __destruct(){
		
	}
					
}