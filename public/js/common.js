//提示
function tsNotice(msg,title){
	if(title==''){
		title = '提示';
	}	
	$(".modal-body").html(msg);
	$(".modal-title").html(title);
	$('#myModal').modal('show');
	//return false;
}

$(document).ready(function(){   
    //menu
    $('.topnav').hover(function(){  
        $(this).addClass("subhover").find('ul.subnav').stop(true, true).slideDown();
        }, function(){  
        $(this).removeClass("subhover").find('ul.subnav').stop(true, true).slideUp();  
        });
});

//签到
function qianDao(){
	$.post(siteUrl+'index.php?app=user&ac=signin',function(rs){
	
		if(rs==1){
			tsNotice('签到成功！');
		}else{
			tsNotice('签到失败！');
		}
	
	})
}

/*!刷新验证码*/
function newgdcode(obj, url) {
    obj.src = url + "&nowtime=" + new Date().getTime()
}
/*!搜索点击*/
function searchon() {
    $("#searchto").click()
}
/*!用户关注*/
function follow(userid, token) {
    $.getJSON(siteUrl + "index.php?app=user&ac=follow&ts=do", {
        "userid": userid,
        "token": token
    },
    function(json) {
        if (json.status == 0) {
			tsNotice(json.msg);
        } else {
            if (json.status == 1) {
				tsNotice(json.msg);
            } else {
                if (json.status == 2) {
					tsNotice(json.msg);
                    window.location.reload()
                }
            }
        }
    })
}
/*!取消用户关注*/
function unfollow(userid, token) {
    $.getJSON(siteUrl + "index.php?app=user&ac=follow&ts=un", {
        "userid": userid,
        "token": token
    },
    function(json) {
        if (json.status == 0) {
			tsNotice(json.msg);
        } else {
            if (json.status == 1) {
				tsNotice(json.msg);
                window.location.reload()
            }
        }
    })
}


/*
 * POST数据，返回JSON
 * url 	必需。规定把请求发送到哪个 URL。
 * datas 	可选。映射或字符串值。规定连同请求发送到服务器的数据。
 */
function tsPost(url,datas){
	$.post(siteUrl+url,datas,function(rs){
		if(rs.status==2 && rs.url){
			window.location = rs.url;
		}else if(rs.status==1){
			window.location.reload();
		}else{
			tsNotice(rs.data);
		}
	},'json')
}

jQuery(document).ready(function(){
    $('#comm-form').live('submit', function() {
		
    	$.ajax({
            cache: true,
            type: "POST",
            url:$(this).prop('action')+'&js=1',
            data:$(this).serialize(),
			dataType: "json", 
            async: false,
			
            error: function(request) {
				tsNotice('请求失败');
            },
			
            success: function(rs) {
			
				if(rs.status==2 && rs.url){
					window.location = rs.url;
				}else if(rs.status==1){
					window.location.reload();
				}else{
					tsNotice(rs.data);
				}
			
            }
        });
    	return false;
	
		
    });
	
});


if ($('html').hasClass('lt-ie8')) {
	var message = '<div class="alert alert-warning" style="margin-bottom:0;text-align:center;">';
	message += '您的浏览器版本太低，不能正常使用本站，请使用';
	message += '<a href="http://windows.microsoft.com/zh-CN/internet-explorer/downloads/ie" target="_blank">IE8浏览器</a>、';
	message += '<a href="http://www.baidu.com/s?wd=%E8%B0%B7%E6%AD%8C%E6%B5%8F%E8%A7%88%E5%99%A8" target="_blank">谷歌浏览器</a><strong>(推荐)</strong>、';
	message += '<a href="http://firefox.com.cn/download/" target="_blank">Firefox浏览器</a>，访问本站。';
	message += '</div>';

	$('body').prepend(message);
}

//前台提交验证
$(function(){
    $("#comm-form").validation();
    //.注册
    $("#comm-submit").on('click',function(event){
        // 2.最后要调用 valid()方法。
        //  valide(object,msg),提示信息显示，object位置后面增加提示信息。如不填object 则自动找最后一个button submit.
        //  valide(msg)
        if ($("#comm-form").valid(this,'填写信息不完整。')==false){
            return false;
        }
    })
})