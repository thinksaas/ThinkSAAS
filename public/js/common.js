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

    var chuangkou = '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-header"> <div class="modal-title" id="myModalLabel">提示</div> <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button> </div> <div class="modal-body"> </div> <div class="modal-footer"> <!--<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">关闭</button>--> </div> </div> </div> </div>';

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

function changeImageCode() {
    var imgsrc = $("#imagecode")[0].src;
    $("#imagecode").attr('src',imgsrc+"&nowtime=" + new Date().getTime());
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

//发送手机验证码
function sendPhoneCode(typeid,vaptcha_token){
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
    $.post(siteUrl+'index.php?app=pubs&ac=phone',{'phone':phone,'authcode':authcode,'typeid':typeid,'vaptcha_token':vaptcha_token},function(rs){
        if (rs.status == 0) {
			tsNotice(rs.msg);
        } else if(rs.status==1) {
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
        }

    },'json');
}

//发送Email验证码
function sendEmailCode(typeid,vaptcha_token){
    var email = $("#myemail").val();
    var authcode = $("#authcode").val();
    if(email==''){
        tsNotice('Email不能为空！');
        return false;
    }
    $.post(siteUrl+'index.php?app=pubs&ac=email',{'email':email,'authcode':authcode,'typeid':typeid,'vaptcha_token':vaptcha_token},function(rs){
        if (rs.status == 0) {
			tsNotice(rs.msg);
        } else if(rs.status==1) {
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
        }

    },'json');
}

function NumberCheck(t){
    var num = t.value;
    var re=/^\d*$/;
    if(!re.test(num)){
        isNaN(parseInt(num))?t.value=0:t.value=parseInt(num);
    }
}

//图片预览
function imgView () {
    var r= new FileReader();
    f=document.getElementById('img-file').files[0];

    r.readAsDataURL(f);
    r.onload=function (e) {
        $("#img-view").show();
        document.getElementById('img-show').src=this.result;
    };
}


/**
 * 打开评论回复框
 * @param {Number} commentid 评论ID
 */
function commentOpen(commentid){
    $('#rcomment_'+commentid).toggle('fast');
}
/**
 * 回复评论
 * @param {*} rid 上级评论ID
 * @param {*} ptable 
 * @param {*} pkey 
 * @param {*} pid 
 * @param {*} touid 
 */
function recomment(commentid,referid,ptable,pkey,pid,touid){
    var content = $('#recontent_'+commentid).val();
    //console.log('#recontent_'+commentid)
    if(content==''){
        tsNotice('回复内容不能为空！');
    }else{

        $('#recomm_btn_'+commentid).hide();

        tsPost('index.php?app=pubs&ac=comment&ts=do&js=1',{
            ptable:ptable,
            pkey:pkey,
            pid:pid,

            referid:referid,
            touserid:touid,

            content:content
        })

    }
}

/**
 * 加载更多评论回复
 * @param {*} commentid 
 * @param {*} userid //项目用户ID
 */
function loadRecomment(commentid,userid){
    $.get(siteUrl+'index.php?app=pubs&ac=ajax&ts=recomment&referid='+commentid+'&userid='+userid,function (rs) {
        $("#recomment_"+commentid).html(rs)
    })
}