<?php

  require('DoubanOAuth.php');
  require('config.php');

  $douban = new DoubanOAuth(array(
    'key' => KEY,
    'secret' => SECRET,
    'redirect_url' => REDIRECT,
  ));

  $url = $douban->getAuthorizeURL(SCOPE, STATE);

  //echo '<a href="' . $url . '">使用豆瓣帐号登录</a>';
  
  header('Location: '.$url);
