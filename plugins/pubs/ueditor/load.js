//实例化编辑器
var options = {
	toolbars:[['FullScreen','Undo', 'Redo','removeformat','formatmatch','cleardoc','searchreplace','Bold','insertunorderedlist','insertorderedlist','forecolor','backcolor','fontsize','fontfamily','link','unlink','simpleupload','insertimage','music','attachment','insertvideo','emotion','insertcode','blockquote','inserttable','deletetable','mergeright','mergedown','splittorows','splittocols','splittocells','mergecells','insertcol','insertrow','deletecol','deleterow','insertparagraphbeforetable','map','template','wordimage','horizontal','anchor','spechars','autotypeset','pagebreak','drafts']],
	initialFrameWidth:'100%',
	initialFrameHeight:420,
	focus:true
};
var ue = UE.getEditor('tseditor', options);