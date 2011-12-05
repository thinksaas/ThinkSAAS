	//显示和隐藏
	function viewcontent(){
		$("#displaycontent").toggle();
		$("#displaytitle").hide();
	}
	//显示和隐藏
	function closecontent(){
		$("#displaycontent").hide();
		$("#displaytitle").toggle();
	}

//跟随用户 
function user_follow(userid,userid_follow){

	if(userid == ''){
		art.dialog.open(siteUrl+'index.php?app=user&ac=ajax&ts=login', {title: '登录'});
		return false;
	}else{
		$.ajax({
			type: "POST",
			url: siteUrl+"index.php?app=user&ac=do&ts=user_follow",
			data: "&userid=" + userid + "&userid_follow=" + userid_follow,
			beforeSend: function(){},
			success: function(result){
				if(result == '1'){
					art.dialog({
						icon: 'succeed',
						content: '跟随用户成功！'
					});
					window.location.reload(); 
				}
			}
		});
	}
}

//取消跟随用户
function user_nofollow(userid,userid_follow){
	art.dialog.confirm('确定取消跟随吗？', function(){
		$.ajax({
			type: "POST",
			url: siteUrl+"index.php?app=user&ac=do&ts=user_nofollow",
			data: "&userid=" + userid + "&userid_follow=" + userid_follow,
			beforeSend: function(){},
			success: function(result){
				if(result == '1'){
					art.dialog({
						icon: 'succeed',
						content: '取消跟随用户成功！'
					});
					window.location.reload(); 
				}
			}
		});
	});
}