<?php

  require('DoubanOAuth.php');
  require('config.php');

  $douban = new DoubanOAuth();

  $result = $douban->get('book/search', array(
   'q' => $_GET['q'],
  ));

  print_r($douban->http_code, $result);
