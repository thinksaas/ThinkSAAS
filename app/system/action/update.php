<?php 
defined('IN_TS') or die('Access Denied.');

//循环删除目录和文件函数
function delDirAndFile($dirName){
	if($handle = opendir($dirName)){
	   while(false !== ($item = readdir($handle))){
		   if($item != "." && $item != ".."){
			   if(is_dir($dirName.'/'.$item)){
					delDirAndFile($dirName.'/'.$item);
			   }else{
				   if(unlink($dirName.'/'.$item)){
					
				   }
			   }
		   }
	   }
	   closedir($handle);
	   if(rmdir($dirName)){
		
	   }
	}
}


function file_list($path){
	
	require_once 'thinksaas/class.Diff.php';

    if ($handle = opendir($path)){
        while (false !== ($file = readdir($handle))){
            if ($file != "." && $file != ".."){
                if (is_dir($path."/".$file)){
                    file_list($path."/".$file);
                }else{
					$upfile = $path."/".$file;
					$nfile = substr($path.'/'.$file,18);
					
					if(is_file($nfile)){
					
						//if(Diff::compareFiles($nfile, $upfile)){
							if(copy($upfile,$nfile)===false){
								return '1';exit; //不可拷贝就返回1
							}
						//}
					
					}else{
						if(copy($upfile,$nfile)===false){
							return '1';exit; //不可拷贝就返回1
						}
					}
				

                }
            }
        }
    }
	
	
	
}

//处理1.98版本以下的程序把插件数据转到data目录下
function plugin2data($path){
    if ($handle = opendir($path)){
        while (false !== ($file = readdir($handle))){
            if ($file != "." && $file != ".."){
                if (is_dir($path."/".$file)){
                    plugin2data($path."/".$file);
                }else{
					$upfile = $path."/".$file;
					if(strpos($upfile,'data.php')){

						$arrData = explode('/',$upfile);
						$newfile = 'data/'.$arrData[0].'_'.$arrData[1].'_'.$arrData[2].'.php';
						
						copy($upfile,$newfile);
					
					}
					
                }
            }
        }
    }
}


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
	
	case "":
		
		include template('update');
		break;
		
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
		
		include template('update_one');
		break;
		
	//第二步，升级数据库
	case "two":
	
		
		
		include template('update_two');
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
		
	//第三部，升级系统文件
	case "three":
	
		include template('update_three');
		break;
		
	case "threedo":
		
		
		$upversion = trim($_GET['upversion']);
		
		$filezip = 'thinksaas'.$upversion.'.zip';

		//先删除旧文件
		unlink('upgrade/'.$filezip);

		//拼接出要下载的远程文件
		//$upfile = 'http://file.thinksaas.cn/down/'.$filezip;
		$upfile = 'http://git.oschina.net/thinksaas/thinksaas/repository/archive?ref=master';
		
		//第一步：多线程下载zip压缩文件
		$urls=array(
			$upfile,
			$upfile,
			$upfile,
		);
		$save_to='upgrade/';

		$mh=curl_multi_init();
		foreach($urls as $i=>$url){
			//$g=$save_to.basename($url);
			$g = $save_to.$filezip;
			if(!is_file($g)){
				$conn[$i]=curl_init($url);
				$fp[$i]=fopen($g,"w");
				curl_setopt($conn[$i],CURLOPT_USERAGENT,"Mozilla/4.0(compatible; MSIE 7.0; Windows NT 6.0)");
				curl_setopt($conn[$i],CURLOPT_FILE,$fp[$i]);
				curl_setopt($conn[$i],CURLOPT_HEADER ,0);
				curl_setopt($conn[$i],CURLOPT_CONNECTTIMEOUT,60);
				curl_multi_add_handle($mh,$conn[$i]);
			}
		}
		do{
			$n=curl_multi_exec($mh,$active);
		}while($active);
		foreach($urls as $i=>$url){
			curl_multi_remove_handle($mh,$conn[$i]);
			curl_close($conn[$i]);
			fclose($fp[$i]);
		}
		curl_multi_close($mh);

		chmod('upgrade/'.$filezip,0777);
		
		//第二步：下载完之后开始解压覆盖原有文件
		
		include 'thinksaas/pclzip.lib.php';
		$archive = new PclZip('upgrade/'.$filezip);
		if ($archive->extract(PCLZIP_OPT_PATH, 'upgrade',PCLZIP_OPT_REPLACE_NEWER) == 0) {
			//die("Error : ".$archive->errorInfo(true));
			
			echo '0';exit;//解压失败
			
		}else{
			unlink('upgrade/'.$filezip);
		}
		
		//直接循环覆盖吧
		$filers = file_list('upgrade/thinksaas');

		if($filers){
			echo '1';exit;
		}
		
		//删除目录
		delDirAndFile('upgrade/thinksaas');
		
		//更新数据库配置缓存下
		$arrOptions = $new['system']->findAll('system_options',null,null,'optionname,optionvalue');
		foreach($arrOptions as $item){
			$arrOption[$item['optionname']] = $item['optionvalue'];
		}
		fileWrite('system_options.php','data',$arrOption);
		$tsMySqlCache->set('system_options',$arrOption);
		
		//删除模板缓存
		rmrf('cache/template');
		
		
		echo '2';exit;
	
		break;

}