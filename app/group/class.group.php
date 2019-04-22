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
            $strGroup['groupdesc'] = tsTitle($strGroup['groupdesc']);

            if($strGroup['photo']){
                $strGroup['photo'] = tsXimg($strGroup['photo'],'group',200,200,$strGroup['path'],1);
            }else{
                $strGroup['photo'] = SITE_URL.'public/images/group.jpg';
            }
        }

        return $strGroup;

    }

    //获取推荐的小组
    function getRecommendGroup($num){


        $arrGroup = $this->findAll('group',array(
            'isrecommend'=>1,
        ),'orderid asc','groupid,groupname,groupdesc,path,photo,count_user',$num);

        foreach($arrGroup as $key=>$item){
            $arrGroup[$key]['groupname'] = tsTitle($item['groupname']);
            $arrGroup[$key]['groupdesc'] = tsTitle($item['groupdesc']);

            if($item['photo']){
                $arrGroup[$key]['photo'] = tsXimg($item['photo'],'group',200,200,$item['path'],1);
            }else{
                $arrGroup[$key]['photo'] = SITE_URL.'public/images/group.jpg';
            }
        }

        return $arrGroup;

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

        $strComment['user'] = aac('user')->getSimpleUser($strComment['userid']);
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
    public function delTopic($topicid,$groupid){

        $this->delete('group_topic',array('topicid'=>$topicid));
        $this->delete('group_topic_edit',array('topicid'=>$topicid));
        $this->delete('group_topic_comment',array('topicid'=>$topicid));
        $this->delete('tag_topic_index',array('topicid'=>$topicid));
        $this->delete('group_topic_collect',array('topicid'=>$topicid));

        #删除帖子附件
        $this->delete('group_topic_attach',array('topicid'=>$topicid));

        $this->delTopicComment($topicid);

        #删除ts_group_topic_photo
        $arrPhoto = $this->findAll('group_topic_photo',array(
            'topicid'=>$topicid,
        ));

        if($arrPhoto){
            foreach($arrPhoto as $key=>$item){
                unlink('uploadfile/group/topic/photo/'.$item['photo']);
                tsDimg($item['photo'],'group/topic/photo','320','320',$item['path'],1);
                tsDimg($item['photo'],'group/topic/photo','640','',$item['path']);
            }
            $this->delete('group_topic_photo',array('topicid'=>$topicid));
        }

        $this->countTopic($groupid);

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

        $this->delete('group_topic_comment',array(
            'commentid'=>$commentid,
        ));

        #删除评论附件
        $this->delete('group_comment_attach',array(
            'commentid'=>$commentid,
        ));

        return true;

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
            $strLike['user'][]=aac('user')->getSimpleUser($item['userid']);
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
        $startTime = time()-($day*3600*24);

        $endTime = time();

        $arr = "`addtime`>'$startTime' and `count_view`>'0' and `addtime`<'$endTime'";

        $arrTopic = $this->findAll('group_topic',$arr,'addtime desc','topicid,title,count_view,count_comment',10);
        foreach($arrTopic as $key=>$item){
            $arrTopic[$key]['title']=tsTitle($item['title']);
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
        }

        return $arrTopic;

    }

    /*
     * 是否小组组长
     */
    public function isGroupCreater($groupid,$userid){
        $isCreater = $this->findCount('group',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
        ));

        if($isCreater){
            return true;
        }else{
            return false;
        }
    }

    /*
 * 是否小组管理员，仅次于小组组长
 */
    public function isGroupAdmin($groupid,$userid){
        $isAdmin = $this->findCount('group_user',array(
            'userid'=>$userid,
            'groupid'=>$groupid,
            'isadmin'=>1,
        ));
        if($isAdmin){
            return true;
        }else{
            return false;
        }
    }

    /*
     * 是否小组成员，被统治阶级
     */
    public function isGroupUser($groupid,$userid){
        $countGroupUser = $this->findCount('group_user',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
        ));
        if($countGroupUser){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取帖子附件
     */
    public function getTopicAttach($topicid){
        $arrAttachId = $this->findAll('group_topic_attach',array(
            'topicid'=>$topicid,
        ));
        if($arrAttachId){
            foreach ($arrAttachId as $key=>$item){
                $arrIds[] = $item['attachid'];
            }
            $attachids = arr2str($arrIds);
            $arrAttach = $this->findAll('attach',"`attachid` in ($attachids)",'addtime desc');
        }else{
            $arrAttach = '';
        }
        return $arrAttach;
    }

    /**
     * 获取评论关联附件
     */
    public function getCommentAttach($commentid){
        $arrAttachId = $this->findAll('group_comment_attach',array(
            'commentid'=>$commentid,
        ));
        if($arrAttachId){
            foreach ($arrAttachId as $key=>$item){
                $arrIds[] = $item['attachid'];
            }
            $attachids = arr2str($arrIds);
            $arrAttach = $this->findAll('attach',"`attachid` in ($attachids)",'addtime desc');
        }else{
            $arrAttach = '';
        }
        return $arrAttach;
    }

    /**
     * 获取帖子关联视频
     * @param $topicid
     * @return mixed
     */
    public function getTopicVideo($topicid){

        $arrVideoId = $this->findAll('group_topic_video',array(
            'topicid'=>$topicid,
        ));

        $arrVideo = array();

        if($arrVideoId){
            foreach($arrVideoId as $key=>$item){
                $arrId[] = $item['videoid'];
            }

            $videoid = arr2str($arrId);

            $arrVideo = $this->findAll('video',"`videoid` in ($videoid)");

            foreach($arrVideo as $key=>$item){
                if($item['siteid']==1){
                    $arrVideo[$key]['iframe'] = "//v.qq.com/txp/iframe/player.html?vid=".$item['vid'];
                }elseif($item['siteid']==2){
                    $arrVideo[$key]['iframe'] = "//player.youku.com/embed/".$item['vid']."==";
                }elseif($item['siteid']==3){
                    $arrVideo[$key]['iframe'] = "//player.bilibili.com/player.html?aid=".$item['vid']."&page=1";
                }else{

                }
            }

        }

        return $arrVideo;

    }

    /**
     * 获取帖子图片，处理通过小程序或者客户端发的图片
     */
    public function getTopicPhoto($topicid,$num=null){
        $arrPhotos = $this->findAll('group_topic_photo',array(
            'topicid'=>$topicid,
        ),'addtime desc',null,$num);

        foreach($arrPhotos as $key=>$item){
            if($num){
                $arrPhoto[$key] = tsXimg($item['photo'],'group/topic/photo','320','320',$item['path'],1);
            }else{
                $arrPhoto[$key] = tsXimg($item['photo'],'group/topic/photo','640','',$item['path']);
            }
        }

        return $arrPhoto;

    }


    //析构函数
    public function __destruct(){

    }

}