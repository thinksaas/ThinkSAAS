<?php 
defined('IN_TS') or die('Access Denied.');



function abcefile($path){
    if ($handle = opendir($path)){
        while (false !== ($file = readdir($handle))){
            if ($file != "." && $file != ".."){
                if (is_dir($path."/".$file)){
                    abcefile($path."/".$file);
                }else{
					$upfile = $path."/".$file;
					//如果文件存在
					if(is_file($upfile)){
				
						//检测文件是否可写
						if(is_writable($upfile)==false){
							return '1';exit;//不可写就停止并返回1
						}
					}
					
                }
            }
        }
    }
}


switch($ts){
		
 	case "iswritable":
	

		//检测几个函数是否可用
		$f_opendir = function_exists('opendir');
		$f_readdir = function_exists('readdir');
		$f_copy = function_exists('copy');
	
		
		$rs = abcefile(THINKROOT);
		
		if($f_opendir==false){
			echo '0';exit;	//opendir函数不可用
		}
		
		if($f_readdir==false){
			echo '1';exit;	//readdir函数不可用
		}
		
		if($f_copy==false){
			echo '2';exit;	//copy函数不可用
		}
		
		if($rs){
			echo '3';exit; //文件不可写
		}
		
		echo '4';exit;	//完全没问题
		
		break;
		
	//第一步检测可写权限
	case "one":
		
		include template('handup_one');
		break;
		
	//第二步，升级数据库
	case "two":
	
		
		
		include template('handup_two');
		break;
		
	case "twodo":
		
		$upsql = trim($_POST['upsql']);

		if($upsql){
		
			$arrSql = explode('--------------------',$upsql);
			foreach($arrSql as $item){
				$item = trim($item);
				if ($item){
					$db->query($item);
				}
			}
			
			//执行成功
			echo '1';exit;
		
		}else{
		
			//无SQL可执行
			echo '0';exit;
		
		}
		
		//echo '1';exit;
		
		break;
		
	//第三部，手动下载覆盖
	case "three":
	
		include template('handup_three');
		break;

}