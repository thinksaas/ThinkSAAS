//及时消息开始
var newMessageRemind = {
	_step : 0,
	_title : document.title,
	_timer : null,
	//显示新消息提示
	show : function () {
		var temps = newMessageRemind._title.replace("【　　　】", "").replace("【新消息】", "");
		newMessageRemind._timer = setTimeout(function () {
				newMessageRemind.show();
				//这里写Cookie操作
				newMessageRemind._step++;
				if (newMessageRemind._step == 3) {
					newMessageRemind._step = 1
				};
				if (newMessageRemind._step == 1) {
					document.title = "【　　　】" + temps
				};
				if (newMessageRemind._step == 2) {
					document.title = "【新消息】" + temps
				};
			}, 800);
		return [newMessageRemind._timer, newMessageRemind._title];
	},
	//取消新消息提示
	clear : function () {
		clearTimeout(newMessageRemind._timer);
		document.title = newMessageRemind._title;
		//这里写Cookie操作
	}
	
};
function clearNewMessageRemind() {
	newMessageRemind.clear();
}

function evdata(userid) {
	$.ajax({
		type : "GET",
		url : siteUrl + "index.php?app=message&ac=newmsg&userid=" + userid,
		success : function (msg) {
			if (msg == '0') {}
			else if (msg > 0) {
				$('#newmsg').html(msg);
				newMessageRemind.show();
			}
		}
	});
}
//及时消息结束