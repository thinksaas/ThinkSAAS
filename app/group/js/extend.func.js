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