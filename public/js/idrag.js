// JavaScript Document
$(function(){
	$(".m_title").bind('mouseover',function(){
		$(this).css("cursor","move")
	});
	
    var $show = $("#loader"); //进度条
    var $orderlist = $("#orderlist");
	var $list = $("#module_list");
	
	$list.sortable({
		opacity: 0.6,
		revert: true,
		cursor: 'move',
		handle: '.m_title',
		update: function(){
			 var new_order = [];
             $list.children(".modules").each(function() {
                new_order.push(this.title);
             });
			 
			 var o_order = [];
             $list.children("#orderlist").each(function() {
                o_order.push($(this).val());
             });
			 
			 var newid = new_order.join(',');
			 var oldid = o_order.join(',');
			 $.ajax({
                type: "post",
                url: orderurl,
                data: { id: newid, order: oldid },   //id:新的排列对应的ID,order：原排列顺序
                beforeSend: function() {
                     $show.html("正在更新排序");
                },
                success: function(msg) {
                     //alert(msg);
					 $show.html("");
                }
             });
		}
	});
});