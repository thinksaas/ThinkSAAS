function sendweibo(){
	var content = $("#weibocontent").val();
	
	if(content==''){
		tsNotice('发布内容不能为空！');return false;
	}
	
	$("#weibosend").attr('disabled','disabled');
	
	$.post(siteUrl+'index.php?app=weibo&ac=ajax&ts=add',{'content':content},function(rs){
		if(rs==0){
			
			tsNotice('请登陆后再发布唠叨！');
			
		}else if(rs==1){
			
			tsNotice('发布内容不能为空！');
			
		}else if(rs==2){
			$("#weibocontent").val('');
			$("#weibosend").removeAttr('disabled');
			weibolist();
		
		}
	});
}

function weibolist(){
	$.get(siteUrl+'index.php?app=weibo&ac=ajax&ts=list',function(rs){
		$("#weibolist").html(rs);
	})
}