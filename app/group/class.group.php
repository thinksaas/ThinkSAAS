<?php 
defined('IN_TS') or die('Access Denied.');

class group extends tsApp{
	
	//构造函数
	public function __construct($db){
		parent::__construct($db);
	}
	
	//获取一个小组
	function getOneGroup($groupid){	
		$strGroup=$this->find('group',array(
			'groupid'=>$groupid,
		));
		
		$strGroup['groupname'] = htmlspecialchars($strGroup['groupname']);
		
		if($strGroup['photo'] == ''){
			$strGroup['icon_48'] = SITE_URL.'public/images/group.jpg';
			$strGroup['icon_16'] = SITE_URL.'public/images/group.jpg';
		}else{
			$strGroup['icon_48'] = tsXimg($strGroup['photo'],'group',60,60,$strGroup['path'],1);
			$strGroup['icon_16'] = tsXimg($strGroup['photo'],'group',16,16,$strGroup['path'],1);
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
		$strComment = $this->db->once_fetch_assoc("select * from ".dbprefix."group_topic_comment where commentid='$referid'");
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
	

	//获取话题补贴
	public function topicAfter($topicid){
	
		$arrAfter = null;
		
		$arrAfters = $this->findAll('group_topic_add',array(
			'topicid'=>$topicid,
		));
		
		foreach($arrAfters as $key=>$item){
			$arrAfter[] = $item;
			$arrAfter[$key]['user'] = aac('user')->getOneUser($item['userid']);
			$arrAfter[$key]['title']=htmlspecialchars(stripslashes($item['title']));

		}
		
		return $arrAfter;
	
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
		
		//删除话题补贴
		$this->delTopicAfter($topicid);
		
		$this->delTopicComment($topicid);
		
		$this->countTopic($strTopic['groupid']);
		
		return true;
		
	}
	
	//删除话题补贴
	public function delTopicAfter($topicid){
		$arrAfter = $this->findAll('group_topic_add',array(
			'topicid'=>$topicid,
		));
		
		foreach($arrAfter as $item){
			if($item['photo']){
				unlink('uploadfile/after/'.$item['photo']);
			}
			if($item['attach']){
				unlink('uploadfile/after/'.$item['attach']);
			}
		}
		
		$this->delete('group_topic_add',array(
			'topicid'=>$topicid,
		));
		
		return true;
		
	}
	
	//删除补贴
	public function delAfter($id){
		$strAfter = $this->find('group_topic_add',array(
			'id'=>$id,
		));
		
		//删除图片
		if($strAfter['photo']){
			unlink('uploadfile/after/'.$strAfter['photo']);
		}
		//删除文件
		if($strAfter['attach']){
			unlink('uploadfile/after/'.$strAfter['attach']);
		}
		
		$this->delete('group_topic_add',array(
			'id'=>$id,
		));
		
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
			$arrTopic[$key]['title']=htmlspecialchars($item['title']);
		}
		
		return $arrTopic;
		
	}
	
	
	//析构函数
	public function __destruct(){
		
	}
	
}