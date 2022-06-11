<?php 
defined('IN_TS') or die('Access Denied.');
class pubs extends tsApp{

	//构造函数
	public function __construct($db){
        $tsAppDb = array();
		include 'app/pubs/config.php';
		//判断APP是否采用独立数据库
		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}
		parent::__construct($db);
	}

    /**
     * 解密userkey,加验证userid
     * @param $userkey
     * @return string
     */
    public function getUserId($userkey){
        include 'thinksaas/class.crypt.php';
        $crypt= new crypt();
        $userid = $crypt->decrypt($userkey,$GLOBALS['TS_SITE']['site_pkey']);
        $isUser = $this->findCount('user',array(
            'userid'=>$userid,
        ));
        if($isUser == 0){
            echo json_encode(array(
                'status'=> 0,
                'msg'=> '非法操作',
                'data'=> '',
            ));
            exit;
        }else{
            return $userid;
        }
    }

    /**
     * @param $string
     * @param string $action
     * @return string
     */
    public function strCode($string, $action = 'ENCODE'){
        $action != 'ENCODE' && $string = base64_decode($string);
        $code = '';
        $key = $GLOBALS['TS_SITE']['site_pkey'];
        $keyLen = strlen($key);
        $strLen = strlen($string);
        for ($i = 0; $i < $strLen; $i++) {
            $k = $i % $keyLen;
            $code .= $string[$i] ^ $key[$k];
        }
        return ($action != 'DECODE' ? base64_encode($code) : $code);
    }

    /**
     * @param $phone
     * @param $code
     * @return bool
     */
    public function verifyPhoneCode($phone, $code){
        $strPhoneCode = $this->find('phone_code',array(
            'phone'=>$phone,
        ));

        #空数据
        if($strPhoneCode==''){
            return false;exit;
        }

        #空验证码
        if($strPhoneCode['code']==''){
            return false;exit;
        }

        #手机验证码错误次数>=2
        if($strPhoneCode['nums']>=2){
            $this->update('phone_code',array(
                'phone'=>$phone,
            ),array(
                'code'=>'',
                'nums'=>0,
            ));
            return false;exit;
        }

        #手机验证码错误
        if($strPhoneCode['code']!=$code){
            $this->update('phone_code',array(
                'phone'=>$phone,
            ),array(
                'nums'=>$strPhoneCode['nums']+1,
            ));
            return false;exit;
        }
        return true;
    }

    /**
     * 验证Email验证码
     */
    public function verifyEmailCode($email, $code){
        $strEmailCode = $this->find('email_code',array(
            'email'=>$email,
        ));

        #空数据
        if($strEmailCode==''){
            return false;exit;
        }

        #空验证码
        if($strEmailCode['code']==''){
            return false;exit;
        }

        #验证码错误次数>=2
        if($strEmailCode['nums']>=2){
            $this->update('email_code',array(
                'email'=>$email,
            ),array(
                'code'=>'',
                'nums'=>0,
            ));
            return false;exit;
        }

        #验证码错误
        if($strEmailCode['code']!=$code){
            $this->update('email_code',array(
                'email'=>$email,
            ),array(
                'nums'=>$strEmailCode['nums']+1,
            ));
            return false;exit;
        }
        return true;
    }

    

    /**
     * 删除点赞
     *
     * @param [type] $ptable
     * @param [type] $pkey
     * @param [type] $pid
     * @param integer $userid
     * @return void
     */
    public function delLove($ptable,$pkey,$pid,$userid=0){
        if($userid){
            $this->delete('love',array(
                'ptable'=>$ptable,
                'pkey'=>$pkey,
                'pid'=>$pid,
                'userid'=>$userid,
            ));
        }else{
            $this->delete('love',array(
                'ptable'=>$ptable,
                'pkey'=>$pkey,
                'pid'=>$pid,
            ));
        }
        
        return true;
    }

    /**
     * 添加项目数据到ts_topic表
     *
     * @param [type] $ptable
     * @param [type] $pkey
     * @param [type] $pid
     * @param string $pjson
     * @param [type] $groupid
     * @param [type] $userid
     * @param [type] $title
     * @param [type] $gaiyao
     * @return void
     */
    public function addPtable($ptable,$pkey,$pid,$pjson,$groupid,$userid,$title,$gaiyao){
        $topicid = $this->create('topic',array(
            'ptable'=>$ptable,
            'pkey'=>$pkey,
            'pid'=>$pid,
            'pjson'=>$pjson,
            'groupid'=>$groupid,
            'userid'=>$userid,
            'title'=>$title,
            'gaiyao'=>$gaiyao,
            'isaudit'=>0,
            'addtime'=>time(),
            'uptime'=>time(),
        ));
        return $topicid;
    }

    /**
     * 更新项目数据到ts_topic表
     *
     * @param [type] $ptable
     * @param [type] $pkey
     * @param [type] $pid
     * @param string $pjson
     * @param [type] $title
     * @param [type] $gaiyao
     * @return void
     */
    public function editPtable($ptable,$pkey,$pid,$pjson,$title,$gaiyao){
        $this->update('topic',array(
            'ptable'=>$ptable,
            'pkey'=>$pkey,
            'pid'=>$pid,
        ),array(
            'pjson'=>$pjson,
            'title'=>$title,
            'gaiyao'=>$gaiyao,
        ));
    }

    /**
     * 更新项目浏览数到ts_topic表
     *
     * @param [type] $ptable
     * @param [type] $pkey
     * @param [type] $pid
     * @param [type] $count_view
     * @return void
     */
    public function upPtableView($ptable,$pkey,$pid,$count_view){
        $this->update('topic',array(
            'ptable'=>$ptable,
            'pkey'=>$pkey,
            'pid'=>$pid,
        ),array(
            'count_view'=>$count_view,
        ));
    }

    /**
     * 更新项目评论数到ts_topic表
     *
     * @param [type] $ptable
     * @param [type] $pkey
     * @param [type] $pid
     * @param [type] $count_comment
     * @return void
     */
    public function upPtableComment($ptable,$pkey,$pid,$count_comment){
        if($ptable!='topic'){
            $this->update('topic',array(
                'ptable'=>$ptable,
                'pkey'=>$pkey,
                'pid'=>$pid,
            ),array(
                'count_comment'=>$count_comment,
                'uptime'=>time(),
            ));
        }
    }

    /**
     *  更新项目点赞数到ts_topic表
     *
     * @param [type] $ptable
     * @param [type] $pkey
     * @param [type] $pid
     * @param [type] $count_love
     * @return void
     */
    public function upPtableLove($ptable,$pkey,$pid,$count_love){
        if($ptable!='topic'){
            $this->update('topic',array(
                'ptable'=>$ptable,
                'pkey'=>$pkey,
                'pid'=>$pid,
            ),array(
                'count_love'=>$count_love,
            ));
        }
    }

    /**
     * 从ts_topic表删除项目数据
     *
     * @param [type] $ptable
     * @param [type] $pkey
     * @param [type] $pid
     * @return void
     */
    public function delPtable($ptable,$pkey,$pid){
        $this->delete('topic',array(
            'ptable'=>$ptable,
            'pkey'=>$pkey,
            'pid'=>$pid,
        ));
    }
    
    /**
     * 用户内容添加、修改、删除记录
     *
     * @param [type] $ptable
     * @param [type] $pkey
     * @param [type] $pid
     * @param [type] $userid
     * @param [type] $title
     * @param [type] $content
     * @param [type] $status
     * @return void
     */
    public function addLogs($ptable,$pkey,$pid,$userid,$title,$content,$status){
        $this->create('logs',array(
            'ptable'=>$ptable,
            'pkey'=>$pkey,
            'pid'=>$pid,
            'userid'=>$userid,
            'title'=>$title,
            'content'=>$content,
            'status'=>$status,
            'addtime'=>time(),
        ));
    }

    /**
     * 更新项目推荐
     *
     * @param [type] $ptable
     * @param [type] $pkey
     * @param [type] $pid
     * @param integer $isrecommend 1推荐0不推荐
     * @return void
     */
    public function upPtableRecommend($ptable,$pkey,$pid,$isrecommend=1){
        if($ptable!='topic'){
            $this->update('topic',array(
                'ptable'=>$ptable,
                'pkey'=>$pkey,
                'pid'=>$pid,
            ),array(
                'isrecommend'=>$isrecommend,
            ));
        }
    }

    /**
     * ThinkSAAS分块上传文件
     * upsize和upcount为空的情况下就是单个文件，并且该文件比设定的大小要小
     *
     * @param [type] $userid    用户ID
     * @param [type] $upsize    总共分几段上传
     * @param [type] $upcount   每次分段的size
     * @param array $uptype     上传文件类型
     * @return void
     */
    public function chunkUpload($userid,$files,$upsize,$upcount,$uptype=array()){

        $upid = $this->create('upload',array(
            'userid'=>$userid,
            'addtime'=>date('Y-m-d H:i:s'),
        ));

        #分块上传到本地服务器
        $arrUpload = tsUploadLocal($files,$upid,'upload',$uptype);

        if($arrUpload['size']){

            $this->update('upload',array(
                'upid'=>$upid,
            ),array(
                'fileurl'=>$arrUpload['url'],
                'filename'=>$arrUpload['name'],
                'filesize'=>$arrUpload['size'],
                'filetype'=>$arrUpload['type'],
            ));

            if($arrUpload['size']<$upsize || $upsize==''){
                
                $arrUp = $this->findAll('upload',array(
                    'userid'=>$userid,
                ),'upid asc');

                if(count($arrUp)==$upcount || ($arrUp && $upcount=='')){

                    return $arrUp;

                }else{
                    
                    return 1;

                }

            }else{
                
                return 1;

            }

        }else{

            return 0;

        }

    }

    /**
     * 合并上传文件
     *
     * @param [type] $userid
     * @param [type] $projectid
     * @param [type] $dir
     * @param array $arrUp
     * @return void
     */
    public function mergeUpload($projectid,$dir,$arrUp=array()){

        $path = getDirPath($projectid);
        $dest_dir = 'uploadfile/' . $dir . '/' . $path;
        createFolders($dest_dir);
        $name = $projectid . '.' . $arrUp[0]['filetype'];
        $dest = $dest_dir . '/' . $name;

        #删除原文件
        unlink($dest);

        $fp = fopen($dest, "ab");

        $filesize = 0;
        
        foreach($arrUp as $key=>$item){

            $upfile = 'uploadfile/upload/'.$item['fileurl'];
            $handle = fopen($upfile,"rb");
            fwrite($fp, fread($handle,$item['filesize']));
            fclose($handle);
            unset($handle);
            unlink($upfile);//合并完毕的文件就删除

            $filesize = $item['filesize'];
            $filesize++;

            #删除ts_upload
            $this->delete('upload',array(
                'upid'=>$item['upid'],
            ));

        }

        return array(
            'name' => $arrUp[0]['filename'], 
            'path' => $path, 
            'url' => $path . '/' . $name, 
            'type' => $arrUp[0]['filetype'], 
            'size' => $filesize,
        );

    }

    /**
     * 删除用户分段上传的文件ts_upload
     *
     * @param [type] $userid
     * @return void
     */
    public function delUpload($userid){
        $arrUp = $this->findAll('upload',array(
            'userid'=>$userid,
        ),'upid asc');
        foreach($arrUp as $key=>$item){
            $upfile = 'uploadfile/upload/'.$item['fileurl'];
            unlink($upfile);
        }

        #删除ts_upload
        $this->delete('upload',array(
            'userid'=>$userid,
        ));

    }

    /**
     * 更新APP用户组权限
     * 
     * 权限参数说明，app,action必须，其他参数可选
     * app-action-ts
     * app-action-mg-ts     当action=admin
     * app-action-api-ts    当action=api
     * 
     */
    public function upAppPermissions($ugid,$app,array $arrOption){
        foreach($arrOption as $key=>$item){
            $status = $item;
            if($ugid==1) $status=1;
            $arrKey = explode('_',$key);
            $key_app = $arrKey[0];
            $key_ac = $arrKey[1];
            $key_mg = '';
            $key_api = '';
            $key_ts = '';
            
            if($key_ac=='admin'){
                $key_mg = $arrKey[2];
                $key_ts = $arrKey[3];
            }elseif($key_ac=='api'){
                $key_api = $arrKey[2];
                $key_ts = $arrKey[3];
            }else{
                $key_ts = $arrKey[2];
            }
            if($key_ts==null) $key_ts='';

            $this->replace('permissions',array(
                'ugid'=>$ugid,
                'app'=>$key_app,
                'action'=>$key_ac,
                'mg'=>$key_mg,
                'api'=>$key_api,
                'ts'=>$key_ts,
            ),array(
                'ugid'=>$ugid,
                'app'=>$key_app,
                'action'=>$key_ac,
                'mg'=>$key_mg,
                'api'=>$key_api,
                'ts'=>$key_ts,
                'status'=>$status,
            ));

        }

        //存储permissions到本地文件
        $arrPermissions = $this->findAll('permissions',array(
            'app'=>$app,
        ));
        foreach($arrPermissions as $key=>$item){
            
            $option = $item['app'].'_'.$item['action'];
            if($item['mg']) $option .= '_'.$item['mg'];
            if($item['api']) $option .= '_'.$item['api'];
            if($item['ts']) $option .= '_'.$item['ts'];
            
            $arrData[$item['ugid']][$option] = $item['status'];

        }

        fileWrite($app.'_permissions.php','data',$arrData);
        $GLOBALS['tsMySqlCache']->set($app.'_permissions',$arrData);

    }


}