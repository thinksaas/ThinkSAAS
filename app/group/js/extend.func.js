/*显示隐藏回复*/
function commentOpen(id,gid)
{
    $('#rcomment_'+id).toggle('fast');
}

//收藏帖子(1.8改为喜欢)
function loveTopic(tid){

    var url = siteUrl+'index.php?app=group&ac=do&ts=topic_collect';
    $.post(url,{topicid:tid},function(rs){
        if(rs == 0){

            tsNotice('请登录后再喜欢');

        }else if(rs == 1){

            tsNotice('自己不能喜欢自己的帖子哦')

        }else if(rs == 2){

            tsNotice('你已经喜欢过本帖啦，请不要再次喜欢');

        }else{
            window.location.reload();
        }
    });
}

//淘帖子,加专辑
function taoalbum(topicid){
    $.post(siteUrl+'index.php?app=group&ac=album&ts=topic',{'topicid':topicid},function(rs){
        if(rs==0){

            tsNotice('请登陆后再进行淘帖');

        }else if(rs == 1){

            tsNotice('请创建专辑后再进行淘贴');

        }else {


            tsNotice(rs);


        }
    })
}

//Ctrl+Enter 回复评论

function keyRecomment(rid,tid,event)
{
    if(event.ctrlKey == true)
    {
        if(event.keyCode == 13)
            recomment(rid,tid);
        return false;
    }
}

//回复评论
function recomment(rid,tid,token){

    c = $('#recontent_'+rid).val();
    if(c==''){alert('回复内容不能为空');return false;}
    var url = siteUrl+'index.php?app=group&ac=comment&ts=recomment';
    $('#recomm_btn_'+rid).hide();
    $.post(url,{referid:rid,topicid:tid,content:c,'token':token} ,function(rs){
        if(rs == 0)
        {
            //alert('回复成功');
            window.location.reload();
        }else{
            $('#recomm_btn_'+rid).show();
        }
    })
}



//向下加载更多帖子
function loadTopic(userid,page){
    var num = parseInt(page)+1;
    $("#viewmore").html('<img src="'+siteUrl+'public/images/loading.gif" />');
    $.get(siteUrl+'index.php?app=group&ac=ajax&ts=topic',{'userid':userid,'page':page},function(rs){
        if(rs==''){
            $("#viewmore").html('没有可以加载的内容啦...');
        }else{
            $("#before").before(rs);
            $("#viewmore").html('<a class="btn" href="javascript:void(0);" onclick="loadTopic('+userid+','+num+')">查看更多内容......</a>');
        }
    })
}

//帖子审核
function topicAudit(topicid,token){
    $.post(siteUrl+'index.php?app=group&ac=ajax&ts=topicaudit',{'topicid':topicid,'token':token},function(rs){
        if(rs==0){

            window.location.reload();
            return false;
        }else if(rs==1){

            window.location.reload();
            return false;

        }else if(rs==2){

            tsNotice('非法操作！');

        }
    })
}

//踢出小组
function kickedGroup(groupid,userid){
    $.post(siteUrl+'index.php?app=group&ac=kicked',{'groupid':groupid,'userid':userid},function(rs){
        if(rs=='0'){

            tsNotice('非法操作！')

        }else if(rs=='1'){

            tsNotice('非法操作！')

        }else{
            window.location.reload();
        }
    })

}

//加入小组
function joinGroup(groupid){
    tsPost('index.php?app=group&ac=ajax&ts=joingroup',{'groupid':groupid});
}
function exitGroup(groupid){
    tsPost('index.php?app=group&ac=ajax&ts=exitgroup',{'groupid':groupid});
}


//续期
function openXuqi(userid) {
    $("#xuqi_userid").val(userid);
    var html = $("#xuqi_html").html();
    tsNotice(html);
}


//标注
function toBook(topicid){
    var book = $('#book-text').val();
    if(topicid && book){
        $.post(siteUrl+'index.php?app=group&ac=ajax&ts=book',{'topicid':topicid,'book':book},function (rs) {
            if(rs==1){
                window.location.reload()
            }else{
                $('#book-alert').html('标注不能为空');
            }
        })
    }else{
        $('#book-alert').html('标注不能为空');
    }
}