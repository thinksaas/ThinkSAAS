//实例化编辑器
var options = {
	toolbars:[['Bold','link','unlink','simpleupload','insertimage','music','attachment','insertvideo']],
	initialFrameWidth:'100%',
	initialFrameHeight:100,
	focus:false,
    elementPathEnabled:false,
    wordCount:false
};
var ue = UE.getEditor('tseditor', options);