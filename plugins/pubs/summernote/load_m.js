var content = $('textarea[name="content"]')
$(document).ready(function() {
    var $summernote = $('#tseditor').summernote({

        toolbar: [

            ['font', ['bold', 'clear']],
            //['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['picture','ts_attach','ts_video','ts_audio']]
        ],

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


