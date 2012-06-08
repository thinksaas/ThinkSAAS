<?php
defined('IN_TS') or die('Access Denied.');

function code_header(){
   echo "
   <script type='text/javascript' src='".SITE_URL."plugins/pubs/codelight/shCore.js?'></script>
   <script type='text/javascript' src='".SITE_URL."plugins/pubs/codelight/shBrushPhp.js?ver=3.0.83'></script>
  <link rel='stylesheet' id='shCore-css'  href='".SITE_URL."plugins/pubs/codelight/shCore.css' />
  <link rel='stylesheet' id='shCoreDefault-css'  href='".SITE_URL."plugins/pubs/codelight/shCoreDefault.css' type='text/css' media='all' />
  <link rel='stylesheet' id='shThemeDefault-css'  href='".SITE_URL."plugins/pubs/codelight/shThemeDefault.css' type='text/css' media='all' />";
}

function code_footer(){
   echo '
   <script type="text/javascript">
SyntaxHighlighter.all();
</script>';
}

addAction('pub_header_top','code_header');
addAction('pub_footer','code_footer');