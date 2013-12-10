function joinGroup(groupid){
	$.post(siteUrl+'index.php?app=mobile&ac=group&ts=join',{'groupid':groupid},function(rs){
		if(rs==0){
			alert('请登录后再加入小组');return false;
		}else if(rs==1){
			alert('你已经加入了小组');return false;
		}else if(rs==2){
			alert('加入小组成功！');
			window.location.reload()
		}
	})
}

/*!刷新验证码*/
function newgdcode(obj, url) {
    obj.src = url + "&nowtime=" + new Date().getTime()
}