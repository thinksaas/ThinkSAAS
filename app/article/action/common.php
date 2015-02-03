<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

// 分类
$arrCate = $new ['article']->findAll ( 'article_cate',null,'orderid desc');