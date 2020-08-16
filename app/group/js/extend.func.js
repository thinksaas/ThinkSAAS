

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