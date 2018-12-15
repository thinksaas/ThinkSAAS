<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/15
 * Time: 20:56
 */
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

switch($ts){

    /**
     * 二级分类
     */
    case "twocate":

        $referid = intval($_GET['referid']);

        $arrCate = $new['article']->findAll('article_cate',array(
            'referid'=>$referid,
        ));

        echo '<select name="cateid2" class="form-control">';
        echo '<option value="0">选择二级分类</option>';
        foreach($arrCate as $key=>$item){
            echo '<option value="'.$item['cateid'].'">'.$item['catename'].'</option>';
        }
        echo '</select>';

        break;

}