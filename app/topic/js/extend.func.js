//向下加载更多帖子
function loadTopic(userid,page){
    var num = parseInt(page)+1;
    $("#viewmore").html('<img src="'+siteUrl+'public/images/loading.gif" />');
    $.get(siteUrl+'index.php?app=topic&ac=ajax&ts=topic',{'userid':userid,'page':page},function(rs){
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
    $.post(siteUrl+'index.php?app=topic&ac=ajax&ts=topicaudit',{'topicid':topicid,'token':token},function(rs){
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

//标注
function toBook(topicid){
    var book = $('#book-text').val();
    //if(topicid && book){
    if(topicid){
        $.post(siteUrl+'index.php?app=topic&ac=ajax&ts=book',{'topicid':topicid,'book':book},function (rs) {
            if(rs==1){
                window.location.reload()
            }else{
                //$('#book-alert').html('标注不能为空');
            }
        })
    }else{
        //$('#book-alert').html('标注不能为空');
    }
}