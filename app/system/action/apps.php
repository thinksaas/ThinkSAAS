<?php
defined('IN_TS') or die('Access Denied.');


switch($ts){
	//app列表
	case "list":

        $arrAppNav = $GLOBALS['TS_SITE']['appnav'];

		$applists	= tsScanDir('app');
		foreach($applists as $key=>$item){
			if(is_file('app/'.$item.'/about.php')){

                $arrApps[$key]['name'] = $item;

				$arrApps[$key]['about'] = require_once 'app/'.$item.'/about.php';

                if($arrAppNav[$item]){
                    $arrApps[$key]['about']['name'] = $arrAppNav[$item];
                }

                if(is_file('app/'.$item.'/icon.png')){
                    $arrApps[$key]['icon'] = SITE_URL.'app/'.$item.'/icon.png';
                }else{
                    $arrApps[$key]['icon'] = SITE_URL.'public/images/app.png';
                }

			}



		}

		foreach($arrApps as $item){
			$arrApp[] = $item;
		}

		$title = 'APP管理';

		include template("apps");
		break;
	
	//安装APP
	/*
	case "install":
		$appname = tsTrim($_GET['appname']);
		
		$appAbout = require_once 'app/'.$appname.'/about.php';
		
		$isinstall = $appAbout['isinstall'];
		$issql = $appAbout['issql'];
		$issystem = $appAbout['issystem'];
		
		
		if($isinstall == '0'){
			if($issql == '1'){
				//安装数据库
				$sql = file_get_contents('app/'.$appname.'/sql/install.sql');
				$sql = str_replace('ts_',dbprefix,$sql);
				$array_sql = preg_split("/;[\r\n]/", $sql);
				
				foreach($array_sql as $sql){
					$sql = tsTrim($sql);
					if ($sql){
						if (strstr($sql, 'CREATE TABLE')){
							preg_match('/CREATE TABLE ([^ ]*)/', $sql, $matches);
							$ret = $db->query($sql);
						} else {
							$ret = $db->query($sql);
						}
					}
				}

			}
			//更新about.php文件
			$appAbout['isinstall'] = '1';
			AppFileWrite($appAbout,$appname,'about.php');
			
			echo '1';
		
		}elseif($isinstall == '1'){
		
			if($issql == '1'){
				//卸载数据库
				$sql = file_get_contents('app/'.$appname.'/sql/uninstall.sql');
				$sql = str_replace('ts_',dbprefix,$sql);
				$array_sql = preg_split("/;[\r\n]/", $sql);
				
				foreach($array_sql as $sql){
					$sql = tsTrim($sql);
					if ($sql){
						$ret = $db->query($sql);
					}
				}
			}
		
			$appAbout['isinstall'] = '0';
			AppFileWrite($appAbout,$appname,'about.php');
			
			echo '2';
			
		}else{
			echo '3';
		}
		
		break;
	*/
	//导航 
	case "appnav":
		$appkey = $_POST['appkey'];
		$appname = $_POST['appname'];
		
		$arrNav = include 'data/system_appnav.php';
		
		if(is_array($arrNav)){
			$arrNav[$appkey] = $appname;
		}else{
			$arrNav = array(
				$appkey=>$appname,
			);
		}
		
		foreach($arrNav as $key=>$item){
			
			if(!is_dir('app/'.$key)){
				unset($arrNav[$key]);
			}
		}
		
		fileWrite('system_appnav.php','data',$arrNav);
		$GLOBALS['tsMySqlCache']->set('system_appnav',$arrNav);
		
		echo '1';
		
		break;
		
	//取消导航 
	case "unappnav":
		
		$appkey = $_POST['appkey'];

		$arrNav = include 'data/system_appnav.php';
		
		unset($arrNav[$appkey]);
		
		fileWrite('system_appnav.php','data',$arrNav);
		$GLOBALS['tsMySqlCache']->set('system_appnav',$arrNav);
		
		echo '1';
		
		break;

    //我的社区导航
    case "mynav":
        $appkey = tsTrim($_POST['appkey']);
        $appname = tsTrim($_POST['appname']);

        $arrMyNav = include 'data/system_mynav.php';

        if(is_array($arrMyNav)){
            $arrMyNav[$appkey] = $appname;
        }else{
            $arrMyNav = array(
                $appkey=>$appname,
            );
        }

        foreach($arrMyNav as $key=>$item){
            if(!is_dir('app/'.$key)){
                unset($arrMyNav[$key]);
            }
        }

        fileWrite('system_mynav.php','data',$arrMyNav);
        $GLOBALS['tsMySqlCache']->set('system_mynav',$arrMyNav);

        echo '1';
        break;

    case "unmynav":
        $appkey = tsTrim($_POST['appkey']);
        $arrMyNav = include 'data/system_mynav.php';
        unset($arrMyNav[$appkey]);
        fileWrite('system_mynav.php','data',$arrMyNav);
        $GLOBALS['tsMySqlCache']->set('system_mynav',$arrMyNav);
        echo '1';
		break;
		
	case "applist":

		$arrAppNav = include 'data/system_appnav.php';

		include template("apps_applist");
		break;

	case "applistdo":

		$arrAppNav = include 'data/system_appnav.php';

		$jsondata = stripslashes($_POST['jsondata']);

		$arrJson = json_decode($jsondata,true);

		foreach($arrJson as $key=>$item){
			$arrNav[$item['id']] = $arrAppNav[$item['id']];
		}

		//print_r($arrNav);


		fileWrite('system_appnav.php','data',$arrNav);
		$GLOBALS['tsMySqlCache']->set('system_appnav',$arrNav);


		break;

	case "mylist":

		$arrMyNav = include 'data/system_mynav.php';


		include template("apps_mylist");
		break;


	case "mylistdo":

		$arrMyNav = include 'data/system_mynav.php';

		$jsondata = stripslashes($_POST['jsondata']);

		$arrJson = json_decode($jsondata,true);

		foreach($arrJson as $key=>$item){
			$arrNav[$item['id']] = $arrMyNav[$item['id']];
		}

		fileWrite('system_mynav.php','data',$arrNav);
		$GLOBALS['tsMySqlCache']->set('system_mynav',$arrNav);

		break;
		
}