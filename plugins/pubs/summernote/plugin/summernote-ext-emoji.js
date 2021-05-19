(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof module === 'object' && module.exports) {
        module.exports = factory(require('jquery'));
    } else {
        factory(window.jQuery);
    }
}(function ($) {
    $.extend($.summernote.plugins, {
        'emoji': function (context) {
            var self = this;
            var ui = $.summernote.ui;
            var emojis = ['1', '2', '3', '4', '5','6'];

            var chunk = function (val, chunkSize) {
                var R = [];
                for (var i = 0; i < val.length; i += chunkSize)
                    R.push(val.slice(i, i + chunkSize));
                return R;
            };

            /*IE polyfill*/
            if (!Array.prototype.filter) {
                Array.prototype.filter = function (fun /*, thisp*/) {
                    var len = this.length >>> 0;
                    if (typeof fun != "function")
                        throw new TypeError();

                    var res = [];
                    var thisp = arguments[1];
                    for (var i = 0; i < len; i++) {
                        if (i in this) {
                            var val = this[i];
                            if (fun.call(thisp, val, i, this))
                                res.push(val);
                        }
                    }
                    return res;
                };
            }

            var addListener = function () {
                $(document).on('click', '.closeEmoji', function(){
                    $('#emoji-dropdown').modal('hide');
                });
                $(document).on('click', '.selectEmoji', function(){
                    var img = new Image();
                    img.src = '/plugins/pubs/summernote/plugin/emojis/'+$(this).attr('data-value')+'.png';
                    img.alt = $(this).attr('data-value');
                    img.className = 'emoji-icon-inline';
                    context.invoke('editor.insertNode', img);

                });
            };

            var render = function (emojis) {
                var emoList = '';
                /*limit list to 24 images*/
                var emojis = emojis;
                var chunks = chunk(emojis, 6);
                for (j = 0; j < chunks.length; j++) {
                    emoList += '<div class="row">';
                    for (var i = 0; i < chunks[j].length; i++) {
                        var emo = chunks[j][i];
                        emoList += '<div class="col-xs-2">' +
                        //'<a href="javascript:void(0)" class="selectEmoji closeEmoji" data-value="' + emo + '"><span class="emoji-icon" style="background-image: url(\'' + document.emojiSource + emo + '.png\');"></span></a>' +
                        '<a href="javascript:void(0)" class="selectEmoji closeEmoji" data-value="' + emo + '"><img src="' + document.emojiSource + emo + '.png" class="emoji-icon" /></a>' +
                        '</div>';
                    }
                    emoList += '</div>';
                }

                return emoList;
            };

            var filterEmoji = function (value) {
                var filtered = emojis.filter(function (el) {
                    return el.indexOf(value) > -1;
                });
                return render(filtered);
            };

            // add emoji button
            context.memo('button.emoji', function () {
                // create button
                var button = ui.button({
                    contents: '<i class="fa fa-smile"/>',
                    //tooltip: 'emoji',
                    click: function () {
                        if(document.emojiSource === undefined)
                            document.emojiSource = '';

                        //self.$panel.show();
                        $('#emoji-dropdown').modal('show');
                    }
                });

                // create jQuery object from button instance.
                var $emoji = button.render();
                return $emoji;
            });

            // This events will be attached when editor is initialized.
            this.events = {
                // This will be called after modules are initialized.
                'summernote.init': function (we, e) {
                    addListener();
                },
                // This will be called when user releases a key on editable.
                'summernote.keyup': function (we, e) {
                }
            };

            // This method will be called when editor is initialized by $('..').summernote();
            // You can create elements for plugin
            this.initialize = function () {

                this.$panel = $('<div class="modal fade"  role="dialog" id="emoji-dropdown">' +
                        '<div class="modal-dialog">'+
                            '<div class="modal-content">'+
                                '<div class="modal-header">插入表情'+
                                    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'+
                                '</div>'+
                                '<div class="modal-body">'+
                                    '<div class="emoji-list">' +
                                    render(emojis) +
                                    '</div>' +
                                '</div>'+
                            '</div>'+
                        '</div>'+
                '</div>').hide();

                this.$panel.appendTo('body');
            };

            this.destroy = function () {
                this.$panel.remove();
                this.$panel = null;
            };
        }
    });
}));
