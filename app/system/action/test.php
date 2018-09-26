<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/19
 * Time: 17:14
 */
defined('IN_TS') or die('Access Denied.');

switch($ts){

    case "":


        include template('test');
        break;


    case "do":

        print_r($_FILES['file']);

        break;

}