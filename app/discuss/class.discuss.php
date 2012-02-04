<?php 
defined('IN_TS') or die('Access Denied.');
 
class discuss{

	private $db;

	public function discuss($dbhandle){
		$this->db = $dbhandle;
	}
	
	//获取一个讨论组
	public function getOneDiscuss($id){
		$strDiscuss = $this->db->once_fetch_assoc("select * from ".dbprefix."discuss where `id`=$id");
		return $strDiscuss;
	}
	
	//获取全部讨论组
	public function getArrDiscuss(){
		$arrDiscuss = $this->db->fetch_all_assoc("select * from ".dbprefix."discuss order by orderid desc");
		return $arrDiscuss;
	}
	
	/*
	 *获取内容评论列表
	 */
	
	function getGroupContentComment($page = 1, $prePageNum,$topicid){
		$start_limit = !empty($page) ? ($page - 1) * $prePageNum : 0;
		$limit = $prePageNum ? "LIMIT $start_limit, $prePageNum" : '';
		$arrGroupContentComment	= $this->db->fetch_all_assoc("select * from ".dbprefix."discuss_topics_comments where topicid='$topicid' order by addtime desc $limit");
		
		if(is_array($arrGroupContentComment)){
			foreach($arrGroupContentComment as $key=>$item){
				$arrGroupContentComment[$key]['user'] = aac('user')->getUserForApp($item['userid']);
				$arrGroupContentComment[$key]['recomment'] = $this->recomment($item['referid']);
			}
		}
		
		return $arrGroupContentComment;
	}
	
	//Refer二级循环，三级循环暂时免谈
	function recomment($referid){
		$strComment = $this->db->once_fetch_assoc("select * from ".dbprefix."discuss_topics_comments where commentid='$referid'");
		$strComment['user'] = aac('user')->getUserForApp($strComment['userid']);
		
		return $strComment;
	}
	
	/*
	 *获取内容评论列表数
	 */
	
	function getGroupContentCommentNum($virtue, $setvirtue){
		$where = 'where '.$virtue.'='.$setvirtue.'';
		$sql = "SELECT * FROM ".dbprefix."discuss_topics_comments $where";
		$groupContentCommentNum = $this->db->once_num_rows($sql);
		return $groupContentCommentNum;
	}
	

	
	//获取一条帖子 
	function getOneTopic($topicid){
		$isTopic = $this->db->once_num_rows("select * from ".dbprefix."discuss_topics where topicid='$topicid'");
		if($isTopic == '0'){
			header("Location: ".SITE_URL."index.php");
		}else{
			$strTopic = $this->db->once_fetch_assoc("select * from ".dbprefix."discuss_topics where topicid='$topicid'");
			return $strTopic;
		}
	}
	
	//删除帖子
	function deltopic($topicid){
		//删除帖子
		$this->db->query("delete from ".dbprefix."discuss_topics where topicid='$topicid'");
		//删除帖子收藏 
		$this->db->query("delete from ".dbprefix."discuss_topics_collects where topicid='$topicid'");
		//删除帖子评论
		$this->db->query("delete from ".dbprefix."discuss_topics_comments where topicid='$topicid'");
		//删除帖子digg
		$this->db->query("delete from ".dbprefix."discuss_topics_plugin_digg where topicid='$topicid'");
		//删除帖子tag索引 
		$this->db->query("delete from ".dbprefix."tag_topic_index where topicid='$topicid'");
		
		//删除成功
		echo '1';
		
	}

	
	//判断是否存在小组
	function isGroup($groupid){
		$isGroup = $this->db->once_fetch_assoc("select count(groupid) from ".dbprefix."discuss where groupid='$groupid'");
		if($isGroup['count(groupid)']==0){
			header("Location: ".SITE_URL);
			exit;
		}
	}
	
	//判断是否存在帖子
	function isTopic($topicid){
		$isTopic = $this->db->once_fetch_assoc("select count(topicid) from ".dbprefix."discuss_topics where topicid='$topicid'");
		if($isTopic['count(topicid)']==0){
			header("Location: ".SITE_URL);
			exit;
		}
	}
	
}