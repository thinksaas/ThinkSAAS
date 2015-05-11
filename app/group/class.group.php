<?php 
defined('IN_TS') or die('Access Denied.');

class group extends tsApp{
	
	//构造函数
	public function __construct($db){
        $tsAppDb = array();
		include 'app/group/config.php';
		//判断APP是否采用独立数据库
		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}
	
		parent::__construct($db);
	}
	
	//获取一个小组
	function getOneGroup($groupid){	
		$strGroup=$this->find('group',array(
			'groupid'=>$groupid,
		));
		
		if($strGroup){
			$strGroup['groupname'] = tsTitle($strGroup['groupname']);
			$strGroup['groupdesc'] = tsDecode($strGroup['groupdesc']);
			
			if($strGroup['photo']){
				$strGroup['photo'] = tsXimg($strGroup['photo'],'group',120,120,$strGroup['path'],1);
			}else{
				$strGroup['photo'] = SITE_URL.'public/images/group.jpg';
			}
		}
		
		return $strGroup;

	}
	
	//获取推荐的小组
	function getRecommendGroup($num){
		$arrRecommendGroups = $this->db->fetch_all_assoc("select groupid from ".dbprefix."group where isrecommend='1' limit $num");
		
		$arrRecommendGroup = array();
		
		if(is_array($arrRecommendGroups)){
			foreach($arrRecommendGroups as $item){
				$arrRecommendGroup[] = $this->getOneGroup($item['groupid']);
			}
		}
		return $arrRecommendGroup;
	}
	
	//获取最新创建的小组
	function getNewGroup($num){
		$arrNewGroups = $this->db->fetch_all_assoc("select groupid from ".dbprefix."group where `isaudit`='0' order by addtime desc limit $num");
		if(is_array($arrNewGroups)){
			foreach($arrNewGroups as $item){
				$arrNewGroup[] = $this->getOneGroup($item['groupid']);
			}
		}
		return $arrNewGroup;
	}
	

	
	
	//Refer二级循环，三级循环暂时免谈
	function recomment($referid){
		$strComment = $this->find('group_topic_comment',array(
			'commentid'=>$referid,
		));
		
		$strComment['content'] = tsDecode($strComment['content']);
		
		$strComment['user'] = aac('user')->getOneUser($strComment['userid']);
		return $strComment;
	}

	
	//是否存在帖子 
	public function isTopic($topicid){
		
		$isTopic = $this->findCount('group_topic',array(
			'topicid'=>$topicid,
		));
		
		if($isTopic > 0){
		
			return true;
		
		}else{
			
			return false;
			
		}
		
	}
	
	//判断是否存在小组
	function isGroup($groupid){
		
		$isGroup = $this->findCount('group',array(
			'groupid'=>$groupid,
		));
		
		if($isGroup > 0){
			return true;
		}else{
			return false;
		}
	}
	


	
	//删除帖子
	public function delTopic($topicid){

		$strTopic = $this->find('group_topic',array(
			'topicid'=>$topicid,
		));

		$this->delete('group_topic',array('topicid'=>$topicid));
		$this->delete('group_topic_edit',array('topicid'=>$topicid));
		$this->delete('group_topic_comment',array('topicid'=>$topicid));
		$this->delete('tag_topic_index',array('topicid'=>$topicid));
		$this->delete('group_topic_collect',array('topicid'=>$topicid));
		
		//删除图片
		if($strTopic['photo']){
			unlink('uploadfile/topic/'.$strTopic['photo']);
		}
		//删除文件
		if($strTopic['attach']){
			unlink('uploadfile/topic/'.$strTopic['attach']);
		}
		
		$this->delTopicComment($topicid);
		
		$this->countTopic($strTopic['groupid']);
		
		return true;
		
	}
	

	
	//删除话题评论
	public function delTopicComment($topicid){
		$arrComment = $this->findAll('group_topic_comment',array(
			'topicid'=>$topicid,
		));
		
		foreach($arrComment as $item){
			$this->delComment($item['commentid']);
		}
		
		return true;
		
	}
	
	//删除评论
	public function delComment($commentid){
		$strComment = $this->find('group_topic_comment',array(
			'commentid'=>$commentid,
		));
		
		//删除图片
		if($strComment['photo']){
			unlink('uploadfile/comment/'.$strComment['photo']);
		}
		//删除文件
		if($strComment['attach']){
			unlink('uploadfile/comment/'.$strComment['attach']);
		}
		
		$this->delete('group_topic_comment',array(
			'commentid'=>$commentid,
		));
		
		return true;
		
	}
	
	//热门帖子：一天1
	public function hotTopics($day,$num=10){
	
		$startTime = time()-($day*3600*60);
		$endTime = time();
		
		$arrTopic = $this->findAll('group_topic',"`addtime`>'$startTime' and `addtime` < '$endTime' and and `isaudit`='0'",'count_comment desc',null,$num);
		
		return $arrTopic;
		
	}
	
	//推荐喜欢的帖子
	public function loveTopic($topicId,$userNum){
		$strLike['num'] = $this->findCount('group_topic_collect',array(
			'topicid'=>$topicId,
		));
		
		$strLike['topic']=$this->find('group_topic',array(
			'topicid'=>$topicId,
		));
		
		$likeUsers = $this->findAll('group_topic_collect',array(
			'topicid'=>$topicId,
		),'addtime desc',null,$userNum);
		
		foreach($likeUsers as $key=>$item){
			$strLike['user'][]=aac('user')->getOneUser($item['userid']);
		}
		
		return $strLike;
	}

	/*
	 * 统计小组里的话题并更新到小组
	 */
	public function countTopic($groupid){
		$count_topic = $this->findCount('group_topic',array(
			'groupid'=>$groupid,
		));
		
		$this->update('group',array(
			'groupid'=>$groupid,
		),array(
			'count_topic'=>$count_topic,
		));
		
	}
	
	//热门帖子,1天，7天，30天
	public function getHotTopic($day){
		$startTime = time()-($day*3600*60);
		
		$endTime = time();
		
		$arr = "`addtime`>'$startTime' and `count_view`>'0' and `addtime`<'$endTime'";
		
		$arrTopic = $this->findAll('group_topic',$arr,'addtime desc','topicid,title,count_view,count_comment',10);
		foreach($arrTopic as $key=>$item){
			$arrTopic[$key]['title']=tsTitle($item['title']);
			$arrTopic[$key]['content']=tsDecode($item['content']);
		}
		
		return $arrTopic;
		
	}
	
	//获取推荐的帖子(全部推荐和小组推荐)
	public function getRecommendTopic($groupid=null,$num=20){
		if($groupid){
			$arr = array(
				'groupid'=>$groupid,
				'isrecommend'=>1,
			);
		}else{
			$arr = array(
				'isrecommend'=>1,
			);
		}
		$arrTopic = $this->findAll('group_topic',$arr,'addtime desc','topicid,title',$num);
		
		foreach($arrTopic as $key=>$item){
			$arrTopic[$key]['title']=tsTitle($item['title']);
			$arrTopic[$key]['content']=tsDecode($item['content']);
		}
		
		return $arrTopic;
		
	}
	
	
	//析构函数
	public function __destruct(){
		
	}
	
}