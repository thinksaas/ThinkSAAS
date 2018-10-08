var E = window.wangEditor
var editor = new E('#tseditor')
// 或者 var editor = new E( document.getElementById('editor') )

editor.customConfig.zIndex = 100

editor.create()