<?php
defined('IN_TS') or die('Access Denied.');

class comment extends tsApp {

    //构造函数
    public function __construct($db) {
        $tsAppDb = array();
        include 'app/comment/config.php';
        //判断APP是否采用独立数据库
        if ($tsAppDb) {
            $db = new MySql($tsAppDb);
        }

        parent::__construct($db);
    }


    /**
     * 获取评论列表
     *
     * @param [type] $ptable
     * @param [type] $pkey
     * @param [type] $pid
     * @param [type] $page      当前页码
     * @param [type] $lstart    每页显示条数
     * @param [type] $puid      当前项目的用户ID
     * @param integer $uid      当前登录的用户ID
     * @param integer $ismb     是否手机浏览
     * @return void
     */
    public function getCommentList($ptable,$pkey,$pid,$page,$lstart,$puid,$uid=0,$ismb=0){
        $arrComment = $this->findAll('comment',array(
            'ptable'=>$ptable,
            'pkey'=>$pkey,
            'pid'=>$pid,
            'referid'=>0,
        ),'addtime desc',null,$lstart.',15');

        foreach($arrComment as $key => $item){
            $arrComment[$key]['l'] = (($page-1) * 15) + $key + 1;
            //$arrComment[$key]['l'] = (($page-1) * 15) - $key + $commentNum;//盖楼
            $arrComment[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
            $arrComment[$key]['content'] = tsDecode($item['content']);
            if($ismb){
                $arrComment[$key]['content'] = mobileHtml($arrComment[$key]['content']);
            }
            $arrComment[$key]['recomment'] = $this->recomment($item['commentid'],$puid,3,$uid,$ismb);
            $arrComment[$key]['recomment_num'] = $this->recommentNum($item['commentid']);

            $arrComment[$key]['zzuid'] = $puid;//作者ID

            $arrComment[$key]['iszz'] = 0;#作者1是0否
            if($item['userid']==$puid){
                $arrComment[$key]['iszz'] = 1;#作者1是0否
            }

            $arrComment[$key]['isdelete'] = 0;#删除权限1有0无
            if($uid && $uid==$item['userid']){
                $arrComment[$key]['isdelete'] = 1;#删除权限1有0无
            }

            $arrComment[$key]['iscomment'] = 0;#回复权限1有0无
            if($uid && $uid!=$item['userid']){
                $arrComment[$key]['iscomment'] = 1;#回复权限1有0无
            }


            $arrComment[$key]['datetime'] = date('m-d H:i',$item['addtime']);

        }

        return $arrComment;
    }

    /**
     * 获取评论数
     *
     * @param [type] $ptable
     * @param [type] $pkey
     * @param [type] $pid
     * @return void
     */
    public function getCommentNum($ptable,$pkey,$pid){
        $commentNum = $this->findCount('comment',array(
            'ptable'=>$ptable,
            'pkey'=>$pkey,
            'pid'=>$pid,
            'referid'=>0,
        ));
        return $commentNum;
    }


    /**
     * 获取评论下的回复列表
     *
     * @param [type] $referid   上级评论ID
     * @param [type] $puid      当前项目用户ID
     * @param integer $num      调用条数
     * @param integer $uid      当前登录的用户ID
     * @param integer $ismb     是否手机浏览
     * @return void
     */
    function recomment($referid,$puid,$num=0,$uid=0,$ismb=0){

        if($num){
            $limit = $num;
        }else{
            $limit = null;
        }

        $arrComment = $this->findAll('comment',array(
            'referid'=>$referid,
        ),'addtime desc',null,$limit);

        foreach($arrComment as $key=>$item){
            $html = tsDecode($item['content']);
            if($ismb==1){
                $html = mobileHtml($html);
            }

            $arrComment[$key]['content'] = $html;

            $arrComment[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
            $arrComment[$key]['datetime'] = date('m-d H:i',$item['addtime']);

            if($item['touserid']){
                $arrComment[$key]['touser'] = aac('user')->getSimpleUser($item['touserid']);
            }

            $arrComment[$key]['iszz'] = 0;#作者1是0否
            if($item['userid']==$puid){
                $arrComment[$key]['iszz'] = 1;#作者1是0否
            }

            $arrComment[$key]['isdelete'] = 0;#删除权限1有0无
            if($uid && $uid==$item['userid']){
                $arrComment[$key]['isdelete'] = 1;#删除权限1有0无
            }

            $arrComment[$key]['iscomment'] = 0;#回复权限1有0无
            if($uid && $uid!=$item['userid']){
                $arrComment[$key]['iscomment'] = 1;#回复权限1有0无
            }


        }

        return $arrComment;
    }

    function recommentNum($referid){
        $num = $this->findCount('comment',array(
            'referid'=>$referid,
        ));

        return $num;
    }


    /**
     * 删除评论
     *
     * @param [type] $ptable
     * @param [type] $pkey
     * @param [type] $pid
     * @param integer $commentid
     * @return void
     */
    public function delComment($ptable,$pkey,$pid,$commentid=0){

        if($commentid){
            $this->delete('comment',array(
                'commentid'=>$commentid,
            ));
            #删除回复
            $this->delete('comment',array(
                'referid'=>$commentid,
            ));
        }else{
            $this->delete('comment',array(
                'ptable'=>$ptable,
                'pkey'=>$pkey,
                'pid'=>$pid,
            ));
        }

        #统计评论数
        $count_comment = $this->findCount('comment',array(
            'ptable'=>$ptable,
            'pkey'=>$pkey,
            'pid'=>$pid,
        ));

        //更新评论数
        $this->update($ptable,array(
            $pkey=>$pid,
        ),array(
            'count_comment'=>$count_comment,
        ));

        return true;
    }


}