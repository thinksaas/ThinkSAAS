<?php
/* PHP SDK
 * @version 2.0.0
 * @author connect@qq.com
 * @copyright © 2013, Tencent Corporation. All rights reserved.
 */

require_once(CLASS_PATH."ErrorCase.class.php");
class Recorder{
    private static $data;
    private $inc;
    private $error;

    public function __construct(){
		global $tsMySqlCache;
        $this->error = new ErrorCase();
		
        //-------读取配置文件
        //$incFileContents = file_get_contents(ROOT."comm/inc.php");
		
		$arrQQ = fileRead('data/plugins_pubs_qq.php');
		if($arrQQ==''){
			$arrQQ = $tsMySqlCache->get('plugins_pubs_qq');
		}
		
		$callback= urlencode($arrQQ['siteurl'].'index.php?app=pubs&ac=plugin&plugin=qq&in=callback');
		
		$incFileContents = '{"appid":"'.$arrQQ['appid'].'","appkey":"'.$arrQQ['appkey'].'","callback":"'.$callback.'","scope":"get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo,check_page_fans,add_t,add_pic_t,del_t,get_repost_list,get_info,get_other_info,get_fanslist,get_idolist,add_idol,del_idol,get_tenpay_addr","errorReport":true,"storageType":"file","host":"localhost","user":"root","password":"root","database":"test"}';
		
        $this->inc = json_decode($incFileContents);
        if(empty($this->inc)){
            $this->error->showError("20001");
        }

        if(empty($_SESSION['QC_userData'])){
            self::$data = array();
        }else{
            self::$data = $_SESSION['QC_userData'];
        }
    }

    public function write($name,$value){
        self::$data[$name] = $value;
    }

    public function read($name){
        if(empty(self::$data[$name])){
            return null;
        }else{
            return self::$data[$name];
        }
    }

    public function readInc($name){
        if(empty($this->inc->$name)){
            return null;
        }else{
            return $this->inc->$name;
        }
    }

    public function delete($name){
        unset(self::$data[$name]);
    }

    function __destruct(){
        $_SESSION['QC_userData'] = self::$data;
    }
}
