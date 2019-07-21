<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/7
 * Time: 14:42
 */
defined('IN_TS') or die('Access Denied.');

//实例化redis
$redis = new Redis();
//连接
$redis->connect($TS_CF['redis']['host'], $TS_CF['redis']['port']);
//检测是否连接成功
echo "Server is running: " . $redis->ping();
// 输出结果 Server is running: +PONG