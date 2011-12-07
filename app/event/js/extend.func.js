//参加或者感感兴趣
function userDoWish(eventid,status){
	$.ajax({
		type: "POST",
		url: siteUrl+"index.php?app=event&ac=do&ts=dowish",
		data: "eventid="+eventid+"&status="+status,
		beforeSend:function(){
		},
		success:function(result){
			if(result == '0'){
				art.dialog.open(siteUrl+'index.php?app=user&ac=ajax&ts=login', {title: '登录'});
			}else if(result == '1'){
				art.dialog({
					icon: 'warning',
					content: '你已经参加了该活动^_^'
				});
			}else if(result == '2'){
				art.dialog({
					time: 3,
					icon: 'succeed',
					content: '参加活动成功^_^'
				});
				window.location.reload(); 
			}
		}
	});
}

//取消参加活动
function cancelEvent(eventid,userid){
	art.dialog.confirm('确定不参加了吗？', function(){
	$.ajax({
		type: "POST",
		url: siteUrl+"index.php?app=event&ac=do&ts=cancel",
		data: "eventid="+eventid+"&userid="+userid,
		beforeSend:function(){
		},
		success:function(result){
			if(result == '1'){
				art.dialog({
					time: 3,
					icon: 'succeed',
					content: '取消参加活动^_^'
				});
				window.location.reload(); 
			}
		}
	});
	});
}

//参加活动
function doEvent(eventid,userid){
	$.ajax({
		type: "POST",
		url: siteUrl+"index.php?app=event&ac=do&ts=do",
		data: "eventid="+eventid+"&userid="+userid,
		beforeSend:function(){
		},
		success:function(result){
			if(result == '1'){
				art.dialog({
					time: 3,
					icon: 'succeed',
					content: '参加活动成功^_^'
				});
				window.location.reload(); 
			}
		}
	});
}