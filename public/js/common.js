function tsAlert(content){
    var html = '<div id="tsalert" class="alert alert-info text-center">'+content+' <span id="alert_daojishi"></span></div>';
    $('body').append(html);
    //倒计时
    var step = 10;
    var _res = setInterval(function() {
        step-=1;
        $('#alert_daojishi').html(step);
        if(step <= 0){
            $("#tsalert").detach();
            clearInterval(_res);//清除setInterval
        }
    },1000);
}
//提示
function tsNotice(msg,title){

    $('#myModal').modal('hide');

    var chuangkou = '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-header"> <div class="modal-title" id="myModalLabel">提示</div> <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button> </div> <div class="modal-body"> </div> <div class="modal-footer"> <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">关闭</button> </div> </div> </div> </div>';

    $('body').prepend(chuangkou);

	if(title==''){
		title = '提示';
	}	
	$(".modal-body").html(msg);
	$(".modal-title").html(title);
	$('#myModal').modal('show');
	//return false;
}

//签到
function qianDao(){
    $.post(siteUrl+'index.php?app=user&ac=signin',function(rs){
        if(rs==2){
            tsNotice('请登录后再签到！');
        }else if(rs==1){
            $.get(siteUrl+'index.php?app=user&ac=signin&ts=ajax',function(rs){
                $("#qiandao").html(rs);
            })
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
    $("#searchto").submit()
}
/*!用户关注*/
function follow(userid, token) {
    $.post(siteUrl + "index.php?app=user&ac=follow&ts=do", {
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
    },'json')
}
/*!取消用户关注*/
function unfollow(userid, token) {
    $.post(siteUrl + "index.php?app=user&ac=follow&ts=un", {
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
    },'json')
}


/*
 * POST数据，返回JSON
 * url 	必需。规定把请求发送到哪个 URL。
 * datas 	可选。映射或字符串值。规定连同请求发送到服务器的数据。
 */
function tsPost(url,datas){
	$.post(siteUrl+url,datas,function(rs){

        if(rs.url){

            //再来个提示
            tsNotice(rs.msg+'<br /><span class="text-danger" id="notice_daojishi">3</span>秒后自动跳转...');

            var step = 3;
            var _res = setInterval(function() {

                $('#notice_daojishi').html(step);
                step-=1;
                if(step <= 0){
                    window.location = rs.url;
                    clearInterval(_res);//清除setInterval
                }
            },1000);

        }else{
            tsNotice(rs.msg);
        }


	},'json')
}

jQuery(document).ready(function(){
    $('#comm-form').on('submit', function() {
        //alert(event.type);
        $('button[type="submit"]').html('发送中...');
        $('button[type="submit"]').attr("disabled", true);

        $.ajax({
            cache: true,
            type: "POST",
            url:$(this).prop('action')+'&js=1',
            data:$(this).serialize(),
            dataType: "json",
            async: false,

            error: function(request) {
                tsNotice('请求失败');
                $('button[type="submit"]').removeAttr("disabled");
                $('button[type="submit"]').html('重新提交');
            },

            success: function(rs) {

                if(rs.url){

                    //再来个提示
                    tsNotice(rs.msg+'<br /><span class="text-danger" id="notice_daojishi">3</span>秒后自动跳转...');

                    var step = 3;
                    var _res = setInterval(function() {

                        $('#notice_daojishi').html(step);
                        step-=1;
                        if(step <= 0){
                            window.location = rs.url;
                            clearInterval(_res);//清除setInterval
                        }
                    },1000);



                }else{
                    tsNotice(rs.msg);
                    $('button[type="submit"]').removeAttr("disabled");
                    $('button[type="submit"]').html('重新提交');
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
});


$(document).ready(function () {
    //响应式导航条效果
    $('.ts-top-nav .navbar-toggle').click(function() {
        if ($(this).parents('.ts-top-nav').find('.navbar-collapse').hasClass('active')) {
            $(this).parents('.ts-top-nav').find('.navbar-collapse').removeClass('active');
        } else {
            $(this).parents('.ts-top-nav').find('.navbar-collapse').addClass('active');
        }
    });
});



function sendPhoneCode(typeid){
    var phone = $("#myphone").val();
    var authcode = $("#authcode").val();
    if(phone==''){
        tsNotice('手机号码不能为空！');
        return false;
    }
    if(authcode==''){
        tsNotice('图片验证码不能为空！');
        return false;
    }
    $.post(siteUrl+'index.php?app=pubs&ac=phone',{'phone':phone,'authcode':authcode,'typeid':typeid},function(rs){
        if(rs==0){
            tsNotice('手机号码不能为空！');
        }else if(rs==1){
            tsNotice('30分钟内只能发送一次短信验证码！');
        }else if(rs==2){
            var step = 59;
            $('#mybtn').val('重新发送60');
            var _res = setInterval(function()
            {
                $("#mybtn").attr("disabled", true);//设置disabled属性
                $('#mybtn').html('重新发送'+step);
                step-=1;
                if(step <= 0){
                    $("#mybtn").removeAttr("disabled"); //移除disabled属性
                    $('#mybtn').html('获取验证码');
                    clearInterval(_res);//清除setInterval
                }
            },1000);
        }else if(rs==3){
            tsNotice('手机号已经被其他账号使用，请更换手机号！');
        }else if(rs==4){
            tsNotice('验证码发送失败，请联系管理员处理！');
        }else if(rs==5){
            tsNotice('图片验证码输入有误');
        }
    });
}
