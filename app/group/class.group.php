<?php 
defined('IN_TS') or die('Access Denied.');
/*
 *模型：小组
 *class.group.php
 *By QINIAO
 */
 
class group{

	var $db;

	function group($dbhandle){
		$this->db = $dbhandle;
	}

	//显示所有小组带分页
	function getArrGroup($page='1',$prePageNum,$where=''){
		$start_limit = !empty($page) ? ($page - 1) * $prePageNum : 0;
		$limit = $prePageNum ? "LIMIT $start_limit, $prePageNum" : '';
		$groups	= $this->db->fetch_all_assoc("select * from ".dbprefix."group ".$where." ".$limit."");
		return $groups;
	}
	
	//显示所有小组分类带分页
	function getArrCate($page='1',$prePageNum,$where=''){
		$start_limit = !empty($page) ? ($page - 1) * $prePageNum : 0;
		$limit = $prePageNum ? "LIMIT $start_limit, $prePageNum" : '';
		$cates	= $this->db->fetch_all_assoc("select * from ".dbprefix."group_cates ".$where." ".$limit."");
		if($cates){
		foreach($cates as $item){
			$topCate = $this->getOneCateById($item['catereferid']);
			$arrCate[] = array(
				'cateid'			=> $item['cateid'],
				'catename'	=> $item['catename'],
				'topcateid'		=> $topCate['cateid'],
				'topcatename'		=> $topCate['catename'],
			);
		}}
		
		return $arrCate;
	}
	
	//获取一条分类的名字BY cateid
	function getOneCateById($cateid){
		$strCate = $this->db->once_fetch_assoc("select * from ".dbprefix."group_cates where cateid='$cateid'");
		return $strCate;
	}
	
	//获取一个小组
	function getOneGroup($groupid){
		$strGroup = $this->db->once_fetch_assoc("select * from ".dbprefix."group where groupid=$groupid");
		if($strGroup['groupicon'] == ''){
			$strGroup['icon_48'] = SITE_URL.'public/images/group.gif';
			$strGroup['icon_16'] = SITE_URL.'public/images/group.gif';
		}else{
			$strGroup['icon_48'] = SITE_URL.miniimg($strGroup['groupicon'],'group',48,48,$strGroup['path'],1);
			$strGroup['icon_16'] = SITE_URL.miniimg($strGroup['groupicon'],'group',16,16,$strGroup['path'],1);
		}
		return $strGroup;
	}
	
	//或者小组ID和小组名字
	function getSimpleGroup($groupid){
		$strGroup = $this->db->once_fetch_assoc("select groupid,groupname,path,groupicon from ".dbprefix."group where groupid=$groupid");
		if($strGroup['groupicon'] == ''){
			$strGroup['icon_48'] = SITE_URL.'public/images/group.gif';
			$strGroup['icon_16'] = SITE_URL.'public/images/group.gif';
		}else{
			$strGroup['icon_48'] = SITE_URL.miniimg($strGroup['groupicon'],'group',48,48,$strGroup['path'],1);
			$strGroup['icon_16'] = SITE_URL.miniimg($strGroup['groupicon'],'group',16,16,$strGroup['path'],1);
		}
		return $strGroup;
	}
	
	//获取小组分类数 
	function getCateNum($where=''){
		$sql = "SELECT * FROM ".dbprefix."group_cates ".$where."";
		$cateNum = $this->db->once_num_rows($sql);
		return $cateNum;
	}
	
	/*
	 *获取小组全部内容列表
	 */

	function getGroupContent($page = 1, $prePageNum,$groupid){
		$start_limit = !empty($page) ? ($page - 1) * $prePageNum : 0;
		$limit = $prePageNum ? "LIMIT $start_limit, $prePageNum" : '';
		$arrGroupContent	= $this->db->fetch_all_assoc("select * from ".dbprefix."group_topics where groupid='$groupid' order by addtime desc $limit");
		return $arrGroupContent;
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
		$arrNewGroups = $this->db->fetch_all_assoc("select groupid from ".dbprefix."group where isshow='0' order by addtime desc limit $num");
		if(is_array($arrNewGroups)){
			foreach($arrNewGroups as $item){
				$arrNewGroup[] = $this->getOneGroup($item['groupid']);
			}
		}
		return $arrNewGroup;
	}
	
	//获取小组的所有分类 
	function getCates(){
		$ArrTopCates = $this->db->fetch_all_assoc("select * from ".dbprefix."group_cates where catereferid='0'");
		
		$arrCate = array();
		
		if(is_array($ArrTopCates)){
			foreach($ArrTopCates as $item){
				$arrCate[] = array(
					'cateid'	=> $item['cateid'],
					'catename'	=> $item['catename'],
					'count_group'	=> $item['count_group'],
					'cates'	=> $this->db->fetch_all_assoc("select * from ".dbprefix."group_cates where catereferid='".$item['cateid']."'"),
				);
				
			}
		}
		
		return $arrCate;
		
	}
	
	//我的小组（加入的和管理的）
	function getMyGroup(){
		
	}
	
	
	
	/*
	 *获取小组全部内容数
	 */
	
	function getGroupContentNum($virtue, $setvirtue){
		$where = 'where '.$virtue.'='.$setvirtue.'';
		$sql = "SELECT * FROM ".dbprefix."group_topics $where";
		$groupContentNum = $this->db->once_num_rows($sql);
		return $groupContentNum;
	}
	
	/*
	 *获取内容
	 */
	 
	function getOneGroupContent($topicid){
		$strGroupContent = $this->db->once_fetch_assoc("select * from ".dbprefix."group_topics where topicid=$topicid");
		return $strGroupContent;
	}
	
	/*
	 *获取内容评论列表
	 */
	
	function getGroupContentComment($page = 1, $prePageNum,$topicid){
		$start_limit = !empty($page) ? ($page - 1) * $prePageNum : 0;
		$limit = $prePageNum ? "LIMIT $start_limit, $prePageNum" : '';
		$arrGroupContentComment	= $this->db->fetch_all_assoc("select * from ".dbprefix."group_topics_comments where topicid='$topicid' order by addtime desc $limit");
		
		if(is_array($arrGroupContentComment)){
			foreach($arrGroupContentComment as $key=>$item){
				$arrGroupContentComment[$key]['user'] = aac('user')->getUserForApp($item['userid']);
				$arrGroupContentComment[$key]['content'] = editor2html($item['content']);
				$arrGroupContentComment[$key]['recomment'] = $this->recomment($item['referid']);
			}
		}
		
		return $arrGroupContentComment;
	}
	
	//Refer二级循环，三级循环暂时免谈
	function recomment($referid){
		$strComment = $this->db->once_fetch_assoc("select * from ".dbprefix."group_topics_comments where commentid='$referid'");
		$strComment['user'] = aac('user')->getUserForApp($strComment['userid']);
		$strComment['content'] = editor2html($strComment['content']);
		
		return $strComment;
	}
	
	/*
	 *获取内容评论列表数
	 */
	
	function getGroupContentCommentNum($virtue, $setvirtue){
		$where = 'where '.$virtue.'='.$setvirtue.'';
		$sql = "SELECT * FROM ".dbprefix."group_topics_comments $where";
		$groupContentCommentNum = $this->db->once_num_rows($sql);
		return $groupContentCommentNum;
	}
	
	/*
	 *获取指定小组的名字
	 */
	 
	function getGroupName($groupid){
		$groupDate = $this->db->once_fetch_array("select groupname from ".dbprefix."group where groupid=$groupid");
		$groupName = $groupDate['groupname'];
		return $groupName;
	}
	
	//获取一条帖子 
	function getOneTopic($topicid){
		$isTopic = $this->db->once_num_rows("select * from ".dbprefix."group_topics where topicid='$topicid'");
		if($isTopic == '0'){
			header("Location: ".SITE_URL."index.php");
		}else{
			$strTopic = $this->db->once_fetch_assoc("select * from ".dbprefix."group_topics where topicid='$topicid'");
			return $strTopic;
		}
	}
	
	//删除帖子
	function deltopic($topicid){
		//删除帖子
		$this->db->query("delete from ".dbprefix."group_topics where topicid='$topicid'");
		//删除帖子收藏 
		$this->db->query("delete from ".dbprefix."group_topics_collects where topicid='$topicid'");
		//删除帖子评论
		$this->db->query("delete from ".dbprefix."group_topics_comments where topicid='$topicid'");
		//删除帖子digg
		$this->db->query("delete from ".dbprefix."group_topics_plugin_digg where topicid='$topicid'");
		//删除帖子tag索引 
		$this->db->query("delete from ".dbprefix."tag_topic_index where topicid='$topicid'");
		
		//删除成功
		echo '1';
		
	}
	
	//匹配内容中的附件 
	function matchAttach($content){
		preg_match_all('/\[(attach)=(\d+)\]/is', $content, $attachs);
		if($attachs[2]){
			foreach ($attachs[2] as $aitem) {
				$content = str_replace("[attach={$aitem}]",'附件：', $content);
			}
		}
	}
	//为内容准备的附件格式
	function attachForContent($attachid,$userid){
		$strAttach = acc('attach')->getOneAttach($attachid);
		$userScore = acc('user')->getUserScore($userid);
		if($strAttach['score'] > '0' && $userScore< $strAttach['score']) {
			$result = "下载附件：需要".$strAttach['score']."积分";
		}else{
			$result = '下载附件：<a href="'.SITE_URL.'index.php?app=attach&ajax&ts=down&attachid='.$strAttach["attachid"].'">'.$strAttach["attachname"].'</a>';
		}
	}
	
	//判断是否存在小组
	function isGroup($groupid){
		$isGroup = $this->db->once_fetch_assoc("select count(groupid) from ".dbprefix."group where groupid='$groupid'");
		if($isGroup['count(groupid)']==0){
			header("Location: ".SITE_URL);
			exit;
		}
	}
	
	//判断是否存在帖子
	function isTopic($topicid){
		$isTopic = $this->db->once_fetch_assoc("select count(topicid) from ".dbprefix."group_topics where topicid='$topicid'");
		if($isTopic['count(topicid)']==0){
			header("Location: ".SITE_URL);
			exit;
		}
	}
	
	//获取帖子第一张图片
	function getOnePhoto($topicid){
		$strTopic = $this->db->once_fetch_assoc("select content,isphoto from ".dbprefix."group_topics where `topicid`='$topicid'");
		if($strTopic['isphoto']=='1'){
			preg_match_all('/\[(photo)=(\d+)\]/is', $strTopic['content'], $photos);
			$photoid = $photos[2][0];
			
			$strPhoto = aac('photo')->getSamplePhoto($photoid);
			
			return $strPhoto;
			
		}
	}
	
}

