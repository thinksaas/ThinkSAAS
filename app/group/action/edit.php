<?php
//编辑小组信息
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();

$groupid = intval($_GET['groupid']);

$strGroup = $new['group']->find('group',array(
	'groupid'=>$groupid,
));

if($strGroup['userid']!=$userid && $TS_USER['isadmin']==0){
    tsNotice('非法操作！');
}

$strGroup['groupname'] = tsDecode($strGroup['groupname']);
$strGroup['groupdesc'] = tsDecode($strGroup['groupdesc']);

switch($ts){

    //编辑小组基本信息
    case "base":


        //小组标签
        $arrTags = aac ( 'tag' )->getObjTagByObjid ( 'group', 'groupid', $groupid );
        foreach ( $arrTags as $key => $item ) {
            $arrTag [] = $item ['tagname'];
        }
        $strGroup ['tag'] = arr2str ( $arrTag );

        $title = '编辑小组基本信息';
        include template("edit_base");

        break;

    //编辑小组基本信息
    case "basedo":

        $groupname = trim($_POST['groupname']);
        $groupdesc = trim($_POST['groupdesc']);

        if($groupname=='' || $groupdesc=='') tsNotice("小组名称和介绍都不能为空！");

        //过滤内容开始
        if($TS_USER['isadmin']!=1){
            aac('system')->antiWord($groupname);
            aac('system')->antiWord($groupdesc);
        }

        //过滤内容结束

        $isgroupname = $new['group']->findCount('group',array(
            'groupname'=>$groupname,
        ));

        if($isgroupname > 0 && $strGroup['groupname']!=$groupname) tsNotice('小组名称已经存在！');


        $new['group']->update('group',array(
            'groupid'=>$groupid,
        ),array(
            'groupname'	=> $groupname,
            'groupdesc'	=> $groupdesc,
            'joinway'		=> intval($_POST['joinway']),
            'price'		=> intval($_POST['price']),
            'ispost'	=> intval($_POST['ispost']),
            'isopen'		=> intval($_POST['isopen']),
            'ispostaudit'		=> intval($_POST['ispostaudit']),
        ));

        // 处理标签
        if ($_POST ['tag']) {
            aac ( 'tag' )->delIndextag ( 'group', 'groupid', $groupid );
            aac ( 'tag' )->addTag ( 'group', 'groupid', $groupid, $_POST ['tag'] );
        }

        tsNotice('基本信息修改成功！');



        break;


    //编辑小组头像
    case "icon":

        $title = '修改小组头像';
        include template("edit_icon");

        break;

    //上传小组头像

    case "icondo":

        //上传
        $arrUpload = tsUpload($_FILES['photo'],$groupid,'group',array('jpg','gif','png','jpeg'));

        if($arrUpload){

            $new['group']->update('group',array(
                'groupid'=>$groupid,
            ),array(
                'path'=>$arrUpload['path'],
                'photo'=>$arrUpload['url'],
            ));

            tsDimg($arrUpload['url'],'group','200','200',$arrUpload['path']);
            tsXimg($arrUpload['url'],'group','200','200',$arrUpload['path'],1);

            tsNotice("小组图标修改成功！");

        }else{
            tsNotice("上传出问题啦！");
        }

        break;


    //修改访问权限
    case "privacy":

        $title = '编辑小组权限';
        include template("edit_privacy");

        break;




    //帖子分类
    case "type":
        //调出类型
        $arrGroupType = $new['group']->findAll('group_topic_type',array(
            'groupid'=>$strGroup['groupid'],
        ));

        $title = '编辑帖子分类';
        include template("edit_type");

        break;


    //添加帖子分类
    case "typeadd":

        $typename = trim($_POST['typename']);
        if($typename){
            $new['group']->create('group_topic_type',array(
                'groupid'=>$groupid,
                'typename'=>$typename,
            ));
        }

        header("Location: ".tsUrl('group','edit',array('ts'=>'type','groupid'=>$groupid)));
        break;

    //修改帖子分类
    case "typeedit":
        $typeid = intval($_POST['typeid']);
        $typename = trim($_POST['typename']);
        if($typeid && $typename){
            $new['group']->update('group_topic_type',array(
                'typeid'=>$typeid,
                'groupid'=>$groupid,
            ),array(
                'typename'=>$typename,
            ));
        }
        header("Location: ".tsUrl('group','edit',array('ts'=>'type','groupid'=>$groupid)));
        break;


    //删除帖子分类
    case "typedelete":

        $typeid = intval($_GET['typeid']);

        $new['group']->delete('group_topic_type',array(
            'typeid'=>$typeid,
            'groupid'=>$groupid,
        ));

        $new['group']->update('group_topic',array(
            'groupid'=>$groupid,
            'typeid'=>$typeid,
        ),array(
            'typeid'=>0,
        ));

        header("Location: ".tsUrl('group','edit',array('ts'=>'type','groupid'=>$groupid)));
        break;






    //小组分类
    case "cate":

        $arrCate = $new['group']->findAll('group_cate',array(

            'referid'=>0,

        ));

        //一级分类
        $strCate = $new['group']->find('group_cate',array(
            'cateid'=>$strGroup['cateid'],
        ));
        //二级分类
        $strCate2 = $new['group']->find('group_cate',array(
            'cateid'=>$strGroup['cateid2'],
        ));
        //三级分类
        $strCate3 = $new['group']->find('group_cate',array(
            'cateid'=>$strGroup['cateid3'],
        ));

        $title = '编辑小组分类';
        include template("edit_cate");

        break;


    //成员审核
    case "useraudit":

        $arrUserId = $new['group']->findAll('group_user_isaudit',array(
            'groupid'=>$groupid,
        ));
        foreach($arrUserId as $key=>$item){
            $arrUser[] = aac('user')->getSimpleUser($item['userid']);
        }

        $title = '成员申请加入审核';
        include template('edit_useraudit');
        break;

    //成员审核执行
    case "userauditdo":

        $userid = intval($_GET['userid']);
        $status = intval($_GET['status']);


        //0加入1删除
        if($status==0 && $userid){

            $new['group']->create('group_user',array(
                'userid'=>$userid,
                'groupid'=>$groupid,
                'addtime'=>time(),
            ));

            //更新
            $count_group = $new['group']->findCount('group_user',array(
                'userid'=>$userid,
            ));
            $new['group']->update('user_info',array(
                'userid'=>$userid,
            ),array(
                'count_group'=>$count_group,
            ));

            //计算小组会员数
            $count_user = $new['group']->findCount('group_user',array(
                'groupid'=>$groupid,
            ));

            //更新小组成员统计
            $new['group']->update('group',array(
                'groupid'=>$groupid,
            ),array(
                'count_user'=>$count_user,
            ));

        }

        $new['group']->delete('group_user_isaudit',array(
            'userid'=>$userid,
            'groupid'=>$groupid,
        ));

        header('Location: '.tsUrl('group','edit',array('groupid'=>$groupid,'ts'=>'useraudit')));

        break;


    //小组转让
    case "transfer":


        $title = '小组转让';
        include template('edit_transfer');
        break;

    case "transferdo":


        $touserid = intval($_POST['touserid']);

        $strTouser = $new['group']->find('group_user',array(
            'userid'=>$touserid,
            'groupid'=>$groupid,
        ));

        if($strTouser==''){
            tsNotice('用户还没有加入本小组，只能转让给本小组成员');
        }

        $new['group']->update('group',array(
            'groupid'=>$groupid,
        ),array(
            'userid'=>$touserid,
        ));

        tsNotice('小组转让成功！');

        break;

    #添加用户
    case "adduser":

        $js = intval($_GET['js']);


        $userid = intval($_POST['userid']);

        if($userid==0){
            getJson('用户ID输入有误！',$js);
        }

        $isGroupUser = $new['group']->findCount('group_user',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
        ));

        if($isGroupUser>0){
            getJson('用户已经加入小组！',$js);
        }

        $new['group']->create('group_user',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
            'addtime'=>time(),
        ));


        //发送系统消息
        $msg_userid = '0';
        $msg_touserid = $userid;
        $msg_content = '恭喜你，你成为了小组《'.$strGroup['groupname'].'》的成员！快去看看吧';
        $msg_tourl = tsUrl('group','show',array('id'=>$groupid));
        aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_tourl);


        getJson('操作成功！',$js,1);

        break;


    #小组管理员
    case "isadmin":

        $arrAdmin = $new['group']->findAll('group_user',array(
            'groupid'=>$groupid,
            'isadmin'=>1,
        ));

        $arrAdminUser = array();
        if($arrAdmin){
            foreach($arrAdmin as $key=>$item){
                $arrUserId[] = $item['userid'];
            }
            $userids = arr2str($arrUserId);

            $arrAdminUser = $new['group']->findAll('user_info',"`userid` in ($userids)",'addtime desc','userid,username');

        }


        $title = '小组管理员';
        include template('edit_isadmin');

        break;

    case "isadmindo":

        $js = intval($_GET['js']);


        $userid = intval($_POST['userid']);

        if($userid==0){
            getJson('用户ID输入有误！',$js);
        }

        if($userid==$strGroup['userid']){
            getJson('用户ID不可以是组长ID！',$js);
        }

        $isGroupUser = $new['group']->findCount('group_user',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
        ));

        if($isGroupUser==0){
            getJson('输入用户ID不属于该小组用户！',$js);
        }

        $new['group']->update('group_user',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
        ),array(
            'isadmin'=>1,
        ));


        //发送系统消息
        $msg_userid = '0';
        $msg_touserid = $userid;
        $msg_content = '恭喜你，你成为了小组《'.$strGroup['groupname'].'》的管理员！快去看看吧';
        $msg_tourl = tsUrl('group','show',array('id'=>$groupid));
        aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_tourl);


        getJson('操作成功！',$js,1);

        break;

    #取消管理员
    case "isadmindel":

        $js = intval($_GET['js']);


        $userid = intval($_POST['userid']);

        if($userid==0){
            getJson('用户ID输入有误！',$js);
        }

        if($userid==$strGroup['userid']){
            getJson('用户ID不可以是组长ID！',$js);
        }

        $isGroupUser = $new['group']->findCount('group_user',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
        ));

        if($isGroupUser==0){
            getJson('输入用户ID不属于该小组用户！',$js);
        }

        $new['group']->update('group_user',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
        ),array(
            'isadmin'=>0,
        ));


        //发送系统消息
        $msg_userid = '0';
        $msg_touserid = $userid;
        $msg_content = '不好意思，你在小组《'.$strGroup['groupname'].'》的管理员身份被撤销了！快去看看吧';
        $msg_tourl = tsUrl('group','show',array('id'=>$groupid));
        aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_tourl);


        getJson('操作成功！',$js,1);

        break;


    case "user":


        $guserid = intval($_GET['guserid']);


        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

        $url = tsUrl('group','edit',array('ts'=>'user','groupid'=>$groupid,'page'=>''));


        $lstart = $page*40-40;

        $arr = array(
            'groupid'=>$groupid,
            'isadmin'=>0,
            'isfounder'=>0,
        );

        if($guserid){

            $arr = array(
                'userid'=>$guserid,
                'groupid'=>$groupid,
                'isadmin'=>0,
                'isfounder'=>0,
            );

        }

        //普通用户
        $groupUserNum = $new['group']->findCount('group_user',$arr);

        $groupUser = $new['group']->findAll('group_user',$arr,'userid desc',null,$lstart.',40');

        if(is_array($groupUser)){
            foreach($groupUser as $key=>$item){
                $arrGroupUser[$key] = aac('user')->getSimpleUser($item['userid']);
                $arrGroupUser[$key]['endtime'] = $item['endtime'];
                $arrGroupUser[$key]['price'] = $item['price'];
            }
        }

        $pageUrl = pagination($groupUserNum, 40, $page, $url);

        $title = '用户管理';
        include template('edit_user');
        break;

    case "xuqi":

        $js = intval($_GET['js']);


        $userid = intval($_POST['userid']);
        $endtime = trim($_POST['endtime']);

        if($userid==0){
            getJson('用户ID输入有误！',$js);
        }


        if($endtime==''){
            getJson('续期时间不能为空！',$js);
        }

        if($endtime<date('Y-m-d')){
            getJson('续期时间必须大于今天！',$js);
        }


        $isGroupUser = $new['group']->findCount('group_user',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
        ));

        if($isGroupUser==0){
            getJson('续期用户不属于该小组用户！',$js);
        }

        $new['group']->update('group_user',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
        ),array(
            'endtime'=>$endtime,
        ));

        getJson('操作成功！',$js,1);

        break;





}
