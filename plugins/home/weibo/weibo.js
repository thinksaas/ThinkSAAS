function sendweibo(){
	var content = $("#weibocontent").val();
	
	if(content==''){
		art.dialog({
			lock: true,
			content : '发布内容不能为空！',
			time : 2000
		});
		return false;
	}
	
	$("#weibosend").attr('disabled','disabled');
	
	$.post(siteUrl+'index.php?app=weibo&ac=ajax&ts=add',{'content':content},function(rs){
		if(rs==0){
			art.dialog({
				lock: true,
				content : '请登陆后再发布微博说！',
				time : 2000
			});
		}else if(rs==1){
			art.dialog({
				lock: true,
				content : '发布内容不能为空！',
				time : 2000
			});
		}else if(rs==2){
			
			var content = $("#weibocontent").val('');
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