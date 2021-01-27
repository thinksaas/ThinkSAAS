<?php
defined('IN_TS') or die('Access Denied.');

class topic extends tsApp {

    //构造函数
    public function __construct($db) {
        $tsAppDb = array();
        include 'app/topic/config.php';
        //判断APP是否采用独立数据库
        if ($tsAppDb) {
            $db = new MySql($tsAppDb);
        }

        parent::__construct($db);
    }

    
    /**
     * 获取一条帖子信息
     *
     * @param [type] $topicid
     * @return void
     */
    public function getOneTopic($topicid){
        $strTopic = $this->find('topic',array(
            'topicid'=>$topicid,
        ));
        return $strTopic;
    }

    //是否存在帖子
    public function isTopic($topicid){
        $isTopic = $this->findCount('topic',array(
            'topicid'=>$topicid,
        ));
        if($isTopic > 0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 删除帖子
     *
     * @param array $strTopic
     * @return void
     */
    public function deleteTopic($strTopic=array()){

        $this->delete('topic',array('topicid'=>$strTopic['topicid']));
        $this->delete('tag_topic_index',array('topicid'=>$strTopic['topicid']));
        

        #删除评论ts_comment
        aac('pubs')->delComment('topic','topicid',$strTopic['topicid']);

        #删除点赞ts_love
        aac('pubs')->delLove('topic','topicid',$strTopic['topicid']);


        #删除图片ts_topic_photo
        $arrPhoto = $this->findAll('topic_photo',array(
            'topicid'=>$strTopic['topicid'],
        ));

        if($arrPhoto){
            foreach($arrPhoto as $key=>$item){
                unlink('uploadfile/group/topic/photo/'.$item['photo']);
                tsDimg($item['photo'],'group/topic/photo','320','320',$item['path'],1);
                tsDimg($item['photo'],'group/topic/photo','640','',$item['path']);
            }
            $this->delete('topic_photo',array('topicid'=>$strTopic['topicid']));
        }

        $this->countTopic($strTopic['groupid']);

        return true;

    }

        /*
     * 统计小组里的话题并更新到小组
     */
    public function countTopic($groupid){
        $count_topic = $this->findCount('topic',array(
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

        $arr = "`addtime`>'$startTime' and `count_view`>'0' and `addtime`<'$endTime' and `isaudit`='0'";

        $arrTopic = $this->findAll('topic',$arr,'addtime desc','topicid,title,count_view,count_comment',10);
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
                'isaudit'=>0,
            );
        }else{
            $arr = array(
                'isrecommend'=>1,
                'isaudit'=>0,
            );
        }
        $arrTopic = $this->findAll('topic',$arr,'addtime desc','topicid,title',$num);

        foreach($arrTopic as $key=>$item){
            $arrTopic[$key]['title']=tsTitle($item['title']);
        }

        return $arrTopic;

    }


    /**
     * 获取帖子图片，处理通过小程序或者客户端发的图片
     */
    public function getTopicPhoto($topicid,$num=null){
        $arrPhotos = $this->findAll('topic_photo',array(
            'topicid'=>$topicid,
        ),'orderid asc',null,$num);
        foreach($arrPhotos as $key=>$item){
            if($num){
                $arrPhoto[$key] = tsXimg($item['photo'],'group/topic/photo','320','320',$item['path'],1);
            }else{
                $arrPhoto[$key] = tsXimg($item['photo'],'group/topic/photo','640','',$item['path']);
            }
        }
        return $arrPhoto;
    }


    public function getProject($ptable,$pkey,$pid){
        if($ptable && $pkey && $pid){

            $strProject = $this->find($ptable,array(
                $pkey=>$pid,
            ));

            if($ptable=='article'){
                ########文章########
                $strProject['title'] = tsTitle($strProject['title']);
                $strProject['content'] = tsDecode($strProject['content']);
                #处理正文样式和图片
                $strProject['content'] = mobileHtml($strProject['content']);
                if($strProject['photo']){
                    $strProject['photo'] = tsXimg($strProject['photo'],'article',640,360,$strProject['path'],'1');
                }
                $topicInfo['article'] = $strProject;

            }elseif($ptable=='video'){
                ########视频########
                $topicInfo['video'] = SITE_URL.'uploadfile/video/'.$strProject['video'];

            }

            return $topicInfo;

        }
    }


}