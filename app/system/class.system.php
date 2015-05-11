<?php
defined('IN_TS') or die('Access Denied.');

class system extends tsApp{

	//构造函数
	public function __construct($db){
		
        $tsAppDb = array();
		include 'app/system/config.php';
		
		//判断APP是否采用独立数据库
		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}
		
		parent::__construct($db);
	}
	
	/*
	 *垃圾词过滤
	 * 返回true:存在垃圾词
	 * 返回false:不存在垃圾词
	 */
	public function antiWord($text){
		
		//先干掉所有空格，不管你是所有空格+全角空格
		$text =preg_replace("/\s|　/","",$text);
	
		$arrWords = $this->findAll('anti_word');
		foreach($arrWords as $key=>$item){
			$arrWord[] = $item['word'];
		}
		
		$strWord = '';
		$count = 1;
		if(is_array($arrWord)){
			foreach ($arrWord as $item) {
				if ($count==1) {
					$strWord .= $item;
				} else {
					$strWord .= '|'.$item;
				}
					$count++;
			}
			
			//第一过滤层，大致的扫一下
			if($text){
				preg_match("/$strWord/i",$text, $matche1);
				if(!empty($matche1[0])){
					//tsNotice('提示：内容中存在被禁止使用的词汇：'.$matche1[0]);
					tsNotice('非法操作');
				}
			}
			
			//第二过滤层
			preg_match("/$strWord/i",t($text), $matche2);
			if(!empty($matche2[0])){
				//tsNotice('内容中存在被禁止使用的词汇：'.$matche2[0]);
				tsNotice('非法操作');
			}
			
			//第三过滤层，滤中文中的特殊字符
			$text3 = @preg_replace("/[^\x{4e00}-\x{9fa5}]/iu",'',$text);
			preg_match("/$strWord/i",t($text3), $matche3);
			if(!empty($matche3[0])){
				//tsNotice('内容中存在被禁止使用的词汇：'.$matche3[0]);
				tsNotice('非法操作');
			}
			
			//第四过滤层，过滤QQ号，电话，妈的，老子就不信搞不死你
			$text4 = @preg_replace("/[^\d]/iu",'',$text);
			preg_match("/$strWord/i",t($text4), $matche4);
			if(!empty($matche4[0])){
				//tsNotice('内容中存在被禁止使用的词汇：'.$matche4[0]);
				tsNotice('非法操作');
			}
			
		}
		
		return true;
		
	}
	
	//过滤用户ID
	function antiUser(){
		$arrUsers = $this->findAll('anti_user');
		foreach($arrUsers as $key=>$item){
			$arrUser[] = $item['userid'];
		}
		return $arrUser;
	}
	
	//过滤用户ip
	function antiIp(){
		$arrIps = $this->findAll('anti_ip');
		foreach($arrIps as $key=>$item){
			$arrIp[] = $item['ip'];
		}
		return $arrIp;
	}
	
	//APP OPTION 配置APP文件缓存
	function appOption($app,$option){
		//先清空数据 
		$db->query("TRUNCATE TABLE `".dbprefix.$app."_options`");
	
		foreach($option as $key=>$item){
			
			$optionname = $key;
			$optionvalue = trim($item);
			
			$this->create($app.'_options',array(
			
				'optionname'=>$optionname,
				'optionvalue'=>$optionvalue,
			
			));
		
		}
		
		$arrOptions = $this->findAll($app.'_options',null,null,'optionname,optionvalue');
		foreach($arrOptions as $item){
			$arrOption[$item['optionname']] = $item['optionvalue'];
		}
		
		fileWrite($app.'_options.php','data',$arrOption);
		$tsMySqlCache->set($app.'_options',$arrOption);
	}
	

	function searchDir($path,&$data){ 
		if(is_dir($path)){ 
			$dp=dir($path); 
			while($file=$dp->read()){ 
				if($file!='.'&& $file!='..'){ 
					$this->searchDir($path.'/'.$file,$data); 
				} 
			} 
			$dp->close(); 
		} 
		if(is_file($path)){ 
			$data[]=$path; 
		} 
	} 
	 
	function getfile($dir){ 
		$data=array(); 
		$this->searchDir($dir,$data); 
		return $data; 
	} 

}