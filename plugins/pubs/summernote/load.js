var content = $('textarea[name="content"]')
$(document).ready(function() {
    var $summernote = $('#tseditor').summernote({

        toolbar: [
            ['cleaner',['cleaner']],
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            //['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link','unlink', 'picture','ts_attach','ts_video','ts_audio']],
            ['highlight', ['highlight']],
            ['view', ['fullscreen']],
        ],

        cleaner:{
            action: 'both', // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
            newline: '<br>', // Summernote's default is to use '<p><br></p>'
            notStyle: 'position:absolute;top:0;left:0;right:0', // Position of Notification
            icon: '<i class="note-icon">[清理HTML]</i>',
            keepHtml: true, // Remove all Html formats
            keepOnlyTags: ['<p>', '<br>', '<ul>', '<li>', '<b>', '<strong>','<i>', '<a>','<img>'], // If keepHtml is true, remove all tags except these
            keepClasses: true, // Remove Classes
            badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'], // Remove full tags with contents
            badAttributes: ['style', 'start'], // Remove attributes from remaining tags
            limitChars: false, // 0/false|# 0/false disables option
            limitDisplay: 'both', // text|html|both
            limitStop: false // true/false
        },

        placeholder:'开始创作吧...',
        height:300,
		lang: 'zh-CN',
		
		callbacks: {
			onChange: function(contents, $editable) {
				//console.log('onChange:', contents, $editable);
				content.val(contents)
			},

            onImageUpload: function(files) {
                // upload image to server and create imgNode...
				// $summernote.summernote('insertNode', imgNode)
				//console.log(files[0])
                sendFile($summernote, files[0]);

            }

        }
		
    });


    //ajax上传图片
    function sendFile($summernote, file) {
        var formData = new FormData();
        formData.append("photo", file);
        $.ajax({
            url: siteUrl+"index.php?app=pubs&ac=editor&ts=photo",
            data: formData,
            type: 'POST',
            //如果提交data是FormData类型，那么下面三句一定需要加上
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                $summernote.summernote('insertImage', data);  //直接插入路径就行，filename可选
                console.log(data);
            },
            error:function(){
                alert("上传图片失败！");
            }
        });
    }


});


