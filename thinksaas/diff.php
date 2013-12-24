<?php
error_reporting(7);
header ( 'Content-Type: text/html; charset=utf-8' );

$action = $argv[1];
if ($action == 'ok') {
    define("ACTION", "OK");
} else {
    define("ACTION", "NO");
}

define('ROOT_PATH',dirname(dirname(__FILE__)));

$serverinfo = loadServerInfo();
if (!$serverinfo) 
    exit("数据库信息提取失败!");

/*************配置信息******************/
define('MYSQL_HOST', $serverinfo['host'] . ':' . $serverinfo['port']);  // 数据库地址
define('MYSQL_USER', $serverinfo['user']);       // 数据库用户名
define('MYSQL_PASSWORD', $serverinfo['password']); // 数据库密码
define('MYSQL_CONNECT_CHAR', 'utf8'); // 数据库的连接编码
define('MYSQL_CONNECT_DATABASE', $serverinfo['plat'] . '_' . $serverinfo['game'] . '_user'); // 连接的库

define('SQL_FILE', ROOT_PATH . '/conf/sql/install.sql'); // sql的对比文件地址

define('LOG_PATH', './' .date('Ymd'). '.log'); // 执行sql的日志文件

/************************************/

/*************数据库连接*******************/
if (!$link = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD)) {
    exit('connect error:' . mysql_error() . "\n");
}
if (!mysql_select_db(MYSQL_CONNECT_DATABASE)) {
    exit('select db error:' . mysql_error() . "\n");
}
mysql_set_charset(MYSQL_CONNECT_CHAR);
/**************************************/

/*****************处理字符串，去掉一些注释的代码**********************/
$sql = file_get_contents(SQL_FILE);
// 去除如/***/的注释
$sql = preg_replace("[(/\*)+.+(\*/;\s*)]", '', $sql);
// 去除如--类的注释
$sql = preg_replace("(--.*?\n)", '', $sql);

/*****************处理字符串，去掉一些注释的代码**********************/

preg_match_all("/CREATE\s+TABLE\s+IF NOT EXISTS.+?(.+?)\s*\((.+?)\)\s*(ENGINE|TYPE)\s*\=(.+?;)/is", $sql, $matches);
$newtables = empty($matches[1])?array():$matches[1];
$newsqls = empty($matches[0])?array():$matches[0];

$execSqlInfo = new execSqlInfo();

$totalNum = count($newtables);
for ($num = 0; $num < $totalNum; $num++) {
    $newcols = getcolumn($newsqls[$num]);
    $newtable = $newtables[$num];
    $oldtable = $newtable;
    
    // 要把表得前缀与后缀替换掉
    $tmps = explode('_', $newtable);
    $tmps[0] = $serverinfo['plat'];
    array_pop($tmps);
    $tmps[] = $serverinfo['area'];
    $newtable = implode('_', $tmps);
    
    $checksql = "SHOW CREATE TABLE {$newtable}";
    $query = mysql_query($checksql);
    
    if (!$query) {
        $usql = $newsqls[$num];
        $usql = str_replace($oldtable, $newtable, $usql);
        execQuery($execSqlInfo, $usql);
    } else {
        $value = mysql_fetch_array($query);
        
        // 判断注释
        if ($comment = checkTableComment($newsqls[$num], $value['Create Table'])) {
            $usql = "ALTER TABLE ".$newtable." COMMENT =  '{$comment}'";
            execQuery($execSqlInfo, $usql);
        }
        
        $oldcols = getcolumn($value['Create Table']);
        $updates = array();
        $allfileds =array_keys($newcols);
        foreach ($newcols as $key => $value) {
            if($key == 'PRIMARY') {
                if($value != $oldcols[$key]) {
                    if(!empty($oldcols[$key])) {
                        $usql = "RENAME TABLE ".$newtable." TO ".$newtable . '_bak';
                        execQuery($execSqlInfo, $usql);
                    }
                    $updates[] = "ADD PRIMARY KEY $value";
                }
            } elseif ($key == 'KEY') {
                foreach ($value as $subkey => $subvalue) {
                    if(!empty($oldcols['KEY'][$subkey])) {
                        if($subvalue != $oldcols['KEY'][$subkey]) {
                            $updates[] = "DROP INDEX `$subkey`";
                            $updates[] = "ADD INDEX `$subkey` $subvalue";
                        }
                    } else {
                        $updates[] = "ADD INDEX `$subkey` $subvalue";
                    }
                }
            } elseif ($key == 'UNIQUE') {
                foreach ($value as $subkey => $subvalue) {
                    if(!empty($oldcols['UNIQUE'][$subkey])) {
                        if($subvalue != $oldcols['UNIQUE'][$subkey]) {
                            $updates[] = "DROP INDEX `$subkey`";
                            $updates[] = "ADD UNIQUE INDEX `$subkey` $subvalue";
                        }
                    } else {
                        $usql = "ALTER TABLE  ".$newtable." DROP INDEX `$subkey`";
                        execQuery($execSqlInfo, $usql);
                        $updates[] = "ADD UNIQUE INDEX `$subkey` $subvalue";
                    }
                }
            } else {
                if(!empty($oldcols[$key])) {
                    if(strtolower($value) != strtolower($oldcols[$key])) {
                        $updates[] = "CHANGE `$key` `$key` $value";
                    }
                } else {
                    $i = array_search($key, $allfileds);
                    $fieldposition = $i > 0 ? 'AFTER `'.$allfileds[$i-1].'`' : 'FIRST';
                    $updates[] = "ADD `$key` $value $fieldposition";
                }
            }
        }
        if ($updates) {
            $usql = "ALTER TABLE ".$newtable." ".implode(', ', $updates);
            execQuery($execSqlInfo, $usql);
        } else {
            checkColumnDiff($execSqlInfo, $newcols, $oldcols);
        }
    }
}

// 输出信息并存入
$message = $execSqlInfo->formatMessage();
echo $message;
file_put_contents(LOG_PATH, $message, FILE_APPEND);

function execQuery($execSqlInfo, $sql) {
    
    $res = true;
    
    if (ACTION == 'OK') {
        $res = mysql_query($sql);
    }
    if (!$res) {
        $debug = debug_backtrace();
        $execSqlInfo->setMsg('line ' . $debug[0]['line'] . ' : ' . 'sql wrong:' . $sql . '  ' . mysql_error());
    } else {
        // 记录
        $execSqlInfo->setSql($sql);
    }
    
}

/**
 * 
 * 检索两个数组的键值顺序是否一致，若不一致列出具体的信息
 */
function checkColumnDiff($execSqlInfo, $newCols, $oldCols) {
    
    if (array_keys($newCols) == array_keys($oldCols)) {
        return false;
    }
    if (count($newCols) != count($oldCols)) {
        return false;
    }
    $size = count($newCols);
    
    for ($i=0; $i < $size; $i++) {
        $newCol = key($newCols);
        $oldCol = key($oldCols);
        
        if (!empty($newCol) && !in_array($newCol, array('KEY', 'INDEX', 'UNIQUE', 'PRIMARY')) 
                && $newCol != $oldCol) {
            $execSqlInfo->setMsg("字段顺序不正确: 第" . ($i+1) . "个字段 sql中字段为 {$newCol} 数据库中字段为 {$oldCol}" );
        }
        next($newCols);
        next($oldCols);
    }
        
}

function checkTableComment($newSql, $oldSql) {
    if (!$newSql || !$oldSql) {
        return false;
    }
    
    // 获取最后一行
    $newlastSql = array_pop(explode("\n", $newSql));
    $oldlastSql = array_pop(explode("\n", $oldSql));
    $newComment = '';
    $oldComment = '';
    if (preg_match("/COMMENT\='(.*)'/is", $newlastSql, $matchs))
        $newComment = $matchs[1];

    if (!$newComment) 
        return false;
        
    if (preg_match("/COMMENT\='(.*)'/is", $oldlastSql, $matchs))
        $oldComment = $matchs[1];

        
    if ($newComment == $oldComment) 
        return false;
        
    return $newComment;
}

function remakesql($value) {
    $value = trim(preg_replace("/\s+/", ' ', $value));
    $value = str_replace(array('`',', ', ' ,', '( ' ,' )', 'mediumtext'), array('', ',', ',','(',')','text'), $value);
    return $value;
}

function getcolumn($creatsql) {

    preg_match("/\((.+)\)\s*(ENGINE|TYPE)\s*\=/is", $creatsql, $matchs);

    $cols = explode("\n", $matchs[1]);
    $newcols = array();
    foreach ($cols as $value) {
        $value = trim($value);
        if(empty($value)) continue;
        $value = remakesql($value);
        if(substr($value, -1) == ',') $value = substr($value, 0, -1);

        $vs = explode(' ', $value);
        $cname = $vs[0];

        if($cname == 'KEY' || $cname == 'INDEX' || $cname == 'UNIQUE') {

            $name_length = strlen($cname);
            if($cname == 'UNIQUE') $name_length = $name_length + 4;

            $subvalue = trim(substr($value, $name_length));
            $subvs = explode(' ', $subvalue);
            $subcname = $subvs[0];
            $newcols[$cname][$subcname] = trim(substr($value, ($name_length+2+strlen($subcname))));

        }  elseif($cname == 'PRIMARY') {

            $newcols[$cname] = trim(substr($value, 11));

        }  else {

            $newcols[$cname] = trim(substr($value, strlen($cname)));
        }
    }
    return $newcols;
}

function loadServerInfo() {
    $serverinfo = array();

/*
    $serverinfo['host'] = $xmlObj->System->MySQL->MySQLHost;
    $serverinfo['port'] = $xmlObj->System->MySQL->MySQLPort;
    $serverinfo['user'] = $xmlObj->System->MySQL->MySQLUser;
    $serverinfo['password'] = $xmlObj->System->MySQL->MySQLPwd;
    $serverinfo['prefix'] = $xmlObj->System->MySQL->MySQLPrefix;
    $serverinfo['plat'] = $xmlObj->System->Plat;
    $serverinfo['game'] = $xmlObj->System->Game;
    $serverinfo['area'] = $xmlObj->System->Area;
*/
    return $serverinfo;
}

class execSqlInfo {
    
    public $msgList = array();
    public $excsqlList = array();
    
    function setMsg($message) {
        $this->msgList[] = $message;
    }
    
    function setSql($querysql) {
        $this->excsqlList[] = $querysql;
    }
    
    function formatMessage() {
        $showMessage = "\n";
        $showMessage .= date('Y m d H:i:s') . "\n";
        
        if (!$this->excsqlList && !$this->msgList) {
            $showMessage .= "nothing to do!\n";
        } else {
            if($this->msgList) {
                $showMessage .= "error message:\n ";
                foreach ($this->msgList as $v) {
                    $showMessage .= $v . "\n
*******************************************************\n\n";
                }
            }
            $showMessage .= "exec sql :\n"; 
            foreach ($this->excsqlList as $v) {
                $showMessage .= $v . "\n
////////////////////////////////////////////////////////\n\n";
            }
        }
        return $showMessage;
    }
}