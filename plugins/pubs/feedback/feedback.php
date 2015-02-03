<?php 
//feedback反馈插件 
function feedback_html(){
	global $tsMySqlCache;
	$code = fileRead('data/plugins_pubs_feedback.php');
	
	if($code==''){
		$code = $tsMySqlCache->get('plugins_pubs_feedback');
	}
	
	echo '<div class="feedback-box">
'.stripslashes($code).'
</div>';
}

addAction('pub_footer','feedback_html');

function feedback_css(){
	echo '<style>.feedback-box {
    background-color: #83ACC6;
    border-right: 1px solid #D3E3F0;
    bottom: 60%;
    opacity: 0.6;
    padding: 1px 0;
    position: fixed;
    right: 0;
    width: 20px;
}
.feedback-box:hover {
    background-color: #558BC6;
    border-right: 1px solid #BFD6E6;
    opacity: 1;
}
.feedback-box:hover a {
    border-left-color: #558BC6;
}
.feedback-box a {
    background: url("'.SITE_URL.'plugins/pubs/feedback/feed-back.png") no-repeat 3px 70px;
    border-left: 1px solid #83ACC6;
    color: #FFFFFF !important;
    display: block;
    margin-left: -1px;
    padding: 4px 4px 24px;
    text-decoration: none;
}</style>';
}

addAction('pub_header_top','feedback_css');