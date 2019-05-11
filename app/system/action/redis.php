<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/7
 * Time: 14:42
 */
defined('IN_TS') or die('Access Denied.');

//连接本地的 Redis 服务
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
echo "Connection to server sucessfully";
echo '<br />';
//查看服务是否运行
echo "Server is running: " . $redis->ping();