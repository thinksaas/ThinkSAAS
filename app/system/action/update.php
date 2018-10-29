<?php 
defined('IN_TS') or die('Access Denied.');
function file_list($path){
    if ($handle = opendir($path)){
        while (false !== ($file = readdir($handle))){
            if ($file != "." && $file != ".."){
                if (is_dir($path."/".$file)){
                    file_list($path."/".$file);
                }else{
					$upfile = $path."/".$file;
					$nfile = substr($path.'/'.$file,13);
					$npath = substr($path,13);

					if(abcefile($npath)==='1'){
					    #return $npath;
					    getJson($npath.'目录没有可写权限，linux请给755权限',1,0);
                    }

					if(is_file($nfile)){

                        if(copy($upfile,$nfile)===false){
                            getJson('升级文件覆盖失败',1,0);
                        }
					
					}else{
						if(copy($upfile,$nfile)===false){
                            getJson('升级文件覆盖失败',1,0);
						}
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
	
        $msg = '';

        #检测php必要函数
        if(function_exists('opendir')==false) $msg .= 'opendir函数不可用<br />';#opendir
        if(function_exists('readdir')==false) $msg .= 'readdir函数不可用<br />';#readdir
        if(function_exists('copy')==false) $msg .= 'copy函数不可用<br />';#copy

        #检查php必要扩展
        if(extension_loaded('Fileinfo')==false) $msg .= 'Fileinfo扩展不可用<br />';#Fileinfo

        #检测upgrade目录是否可写
		if(abcefile('upgrade')) $msg .= 'upgrade目录不可写<br />';


		echo $msg;


		break;

    //手动升级检测
    case "hand":

        $upid = intval($_GET['upid']);

        include template('update_hand');
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

		if($upversion==''){
		    getJson('版本号有问题',1,0);
        }

		$filezip = $upversion.'.zip';

		//先删除旧的zip升级文件
		unlink('upgrade/'.$filezip);
        delDir('upgrade/'.$upversion);

		//拼接出要下载的远程文件
		$upfile = 'https://www.thinksaas.cn/upgrade/'.$filezip;
		
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

		chmod('upgrade/'.$filezip,0755);
		
		//第二步：下载完之后开始解压覆盖原有文件
		include 'thinksaas/pclzip.lib.php';
		$archive = new PclZip('upgrade/'.$filezip);
		if ($archive->extract(PCLZIP_OPT_PATH, 'upgrade/'.$upversion,PCLZIP_OPT_REPLACE_NEWER) == 0) {
            getJson('升级包解压失败',1,0);
			
		}else{
			unlink('upgrade/'.$filezip);
		}

		//直接循环覆盖吧
		file_list('upgrade/'.$upversion);
		
		//删除目录
        delDir('upgrade/'.$upversion);

        getJson('升级成功',1,1);
		break;
}