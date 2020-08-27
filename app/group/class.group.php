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

    /**
     * 获取一个小组
     *
     * @param [type] $groupid
     * @return void
     */
    function getOneGroup($groupid){
        $strGroup=$this->find('group',array(
            'groupid'=>$groupid,
        ));
        if($strGroup){
            $strGroup['groupname'] = tsTitle($strGroup['groupname']);
            $strGroup['groupdesc'] = tsTitle($strGroup['groupdesc']);
            $strGroup['photo'] = $this->getGroupPhoto($strGroup);
        }
        return $strGroup;
    }

    /**
     * 获取小组头像
     *
     * @param [type] $strGroup
     * @return void
     */
    function getGroupPhoto($strGroup){
        if($strGroup['photo']){
            if($GLOBALS['TS_SITE']['file_upload_type']==1){
                #阿里云(对象云存储OSS)数据
                $strFace = tsXimg($strGroup['photo'],'group',200,200,$strGroup['path'],1).'&v='.$strGroup['uptime'];
            }else{
                #本地数据
                $strFace = tsXimg($strGroup['photo'],'group',200,200,$strGroup['path'],1).'?v='.$strGroup['uptime'];
            }
        }else{
            $strFace = SITE_URL.'public/images/group.jpg';
        }
        return $strFace;
    }

    /**
     * 删除小组
     *
     * @param [type] $strGroup
     * @return void
     */
    public function deleteGroup($strGroup){
        if($strGroup['photo']){
            if($GLOBALS['TS_SITE']['file_upload_type']==1){
                deleteAliOssFile('uploadfile/group/'.$strGroup['photo']);
            }else{
                unlink('uploadfile/group/'.$strGroup['photo']);
                tsDimg($strGroup['photo'],'group','120','120',$strGroup['path']);
            }
        }

        $this->delete('group',array(
            'groupid'=>$strGroup['groupid'],
        ));

        $this->delete('group_user',array(
            'groupid'=>$strGroup['groupid'],
        ));

        return true;

    }

    /**
     * 获取推荐的小组
     *
     * @param integer $num
     * @return void
     */
    function getRecommendGroup($num=10){
        $arrGroup = $this->findAll('group',array(
            'isrecommend'=>1,
        ),'orderid asc','groupid,groupname,groupdesc,path,photo,count_user',$num);
        foreach($arrGroup as $key=>$item){
            $arrGroup[$key]['groupname'] = tsTitle($item['groupname']);
            $arrGroup[$key]['groupdesc'] = tsTitle($item['groupdesc']);
            $arrGroup[$key]['photo'] = $this->getGroupPhoto($item);
        }
        return $arrGroup;
    }

    /**
     * 获取最新创建的小组
     *
     * @param integer $num
     * @return void
     */
    function getNewGroup($num=10){
        $arrGroup = $this->findAll('group',array(
            'isaudit'=>0,
        ),'addtime desc',null,$num);
        foreach($arrGroup as $key=>$item){
            $arrGroup[$key]['groupname'] = tsTitle($item['groupname']);
            $arrGroup[$key]['groupdesc'] = tsTitle($item['groupdesc']);
            $arrGroup[$key]['photo'] = $this->getGroupPhoto($item);
        }
        return $arrGroup;
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

    


    //析构函数
    public function __destruct(){

    }

}