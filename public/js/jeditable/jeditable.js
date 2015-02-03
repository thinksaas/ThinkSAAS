
(function($) {



    $.fn.editable = function(target, options) {

            

        if ('disable' == target) {

            $(this).data('disabled.editable', true);

            return;

        }

        if ('enable' == target) {

            $(this).data('disabled.editable', false);

            return;

        }

        if ('destroy' == target) {

            $(this)

                .unbind($(this).data('event.editable'))

                .removeData('disabled.editable')

                .removeData('event.editable');

            return;

        }

        

        var settings = $.extend({}, $.fn.editable.defaults, {target:target}, options);

        

        /* setup some functions */

        var plugin   = $.editable.types[settings.type].plugin || function() { };

        var submit   = $.editable.types[settings.type].submit || function() { };

        var buttons  = $.editable.types[settings.type].buttons 

                    || $.editable.types['defaults'].buttons;

        var content  = $.editable.types[settings.type].content 

                    || $.editable.types['defaults'].content;

        var element  = $.editable.types[settings.type].element 

                    || $.editable.types['defaults'].element;

        var reset    = $.editable.types[settings.type].reset 

                    || $.editable.types['defaults'].reset;

        var callback = settings.callback || function() { };

        var onedit   = settings.onedit   || function() { }; 

        var onsubmit = settings.onsubmit || function() { };

        var onreset  = settings.onreset  || function() { };

        var onerror  = settings.onerror  || reset;

          

        /* show tooltip */

        if (settings.tooltip) {

            $(this).attr('title', settings.tooltip);

        }

        

        settings.autowidth  = 'auto' == settings.width;

        settings.autoheight = 'auto' == settings.height;

        

        return this.each(function() {

                        

            /* save this to self because this changes when scope changes */

            var self = this;  

                   

            /* inlined block elements lose their width and height after first edit */

            /* save them for later use as workaround */

            var savedwidth  = $(self).width();

            var savedheight = $(self).height();

            

            /* save so it can be later used by $.editable('destroy') */

            $(this).data('event.editable', settings.event);

            

            /* if element is empty add something clickable (if requested) */

            if (!$.trim($(this).html())) {

                $(this).html(settings.placeholder);

            }

            

            $(this).bind(settings.event, function(e) {

                

                /* abort if disabled for this element */

                if (true === $(this).data('disabled.editable')) {

                    return;

                }

                

                /* prevent throwing an exeption if edit field is clicked again */

                if (self.editing) {

                    return;

                }

                

                /* abort if onedit hook returns false */

                if (false === onedit.apply(this, [settings, self])) {

                   return;

                }

                

                /* prevent default action and bubbling */

                e.preventDefault();

                e.stopPropagation();

                

                /* remove tooltip */

                if (settings.tooltip) {

                    $(self).removeAttr('title');

                }

                

                /* figure out how wide and tall we are, saved width and height */

                /* are workaround for http://dev.jquery.com/ticket/2190 */

                if (0 == $(self).width()) {

                    //$(self).css('visibility', 'hidden');

                    settings.width  = savedwidth;

                    settings.height = savedheight;

                } else {

                    if (settings.width != 'none') {

                        settings.width = 

                            settings.autowidth ? $(self).width()  : settings.width;

                    }

                    if (settings.height != 'none') {

                        settings.height = 

                            settings.autoheight ? $(self).height() : settings.height;

                    }

                }

                //$(this).css('visibility', '');

                

                /* remove placeholder text, replace is here because of IE */

                if ($(this).html().toLowerCase().replace(/(;|")/g, '') == 

                    settings.placeholder.toLowerCase().replace(/(;|")/g, '')) {

                        $(this).html('');

                }

                                

                self.editing    = true;

                self.revert     = $(self).html();

                $(self).html('');



                /* create the form object */

                var form = $('<form />');

                

                /* apply css or style or both */

                if (settings.cssclass) {

                    if ('inherit' == settings.cssclass) {

                        form.attr('class', $(self).attr('class'));

                    } else {

                        form.attr('class', settings.cssclass);

                    }

                }



                if (settings.style) {

                    if ('inherit' == settings.style) {

                        form.attr('style', $(self).attr('style'));

                        /* IE needs the second line or display wont be inherited */

                        form.css('display', $(self).css('display'));                

                    } else {

                        form.attr('style', settings.style);

                    }

                }



                /* add main input element to form and store it in input */

                var input = element.apply(form, [settings, self]);



                /* set input content via POST, GET, given data or existing value */

                var input_content;

                

                if (settings.loadurl) {

                    var t = setTimeout(function() {

                        input.disabled = true;

                        content.apply(form, [settings.loadtext, settings, self]);

                    }, 100);



                    var loaddata = {};

                    loaddata[settings.id] = self.id;

                    if ($.isFunction(settings.loaddata)) {

                        $.extend(loaddata, settings.loaddata.apply(self, [self.revert, settings]));

                    } else {

                        $.extend(loaddata, settings.loaddata);

                    }

                    $.ajax({

                       type : settings.loadtype,

                       url  : settings.loadurl,

                       data : loaddata,

                       async : false,

                       success: function(result) {

                          window.clearTimeout(t);

                          input_content = result;

                          input.disabled = false;

                       }

                    });

                } else if (settings.data) {

                    input_content = settings.data;

                    if ($.isFunction(settings.data)) {

                        input_content = settings.data.apply(self, [self.revert, settings]);

                    }

                } else {

                    input_content = self.revert; 

                }

                content.apply(form, [input_content, settings, self]);



                input.attr('name', settings.name);

        

                /* add buttons to the form */

                buttons.apply(form, [settings, self]);

         

                /* add created form to self */

                $(self).append(form);

         

                /* attach 3rd party plugin if requested */

                plugin.apply(form, [settings, self]);



                /* focus to first visible form element */

                $(':input:visible:enabled:first', form).focus();



                /* highlight input contents when requested */

                if (settings.select) {

                    input.select();

                }

        

                /* discard changes if pressing esc */

                input.keydown(function(e) {

                    if (e.keyCode == 27) {

                        e.preventDefault();

                        //self.reset();

                        reset.apply(form, [settings, self]);

                    }

                });



                /* discard, submit or nothing with changes when clicking outside */

                /* do nothing is usable when navigating with tab */

                var t;

                if ('cancel' == settings.onblur) {

                    input.blur(function(e) {

                        /* prevent canceling if submit was clicked */

                        t = setTimeout(function() {

                            reset.apply(form, [settings, self]);

                        }, 500);

                    });

                } else if ('submit' == settings.onblur) {

                    input.blur(function(e) {

                        /* prevent double submit if submit was clicked */

                        t = setTimeout(function() {

                            form.submit();

                        }, 200);

                    });

                } else if ($.isFunction(settings.onblur)) {

                    input.blur(function(e) {

                        settings.onblur.apply(self, [input.val(), settings]);

                    });

                } else {

                    input.blur(function(e) {

                      /* TODO: maybe something here */

                    });

                }



                form.submit(function(e) {



                    if (t) { 

                        clearTimeout(t);

                    }



                    /* do no submit */

                    e.preventDefault(); 

            

                    /* call before submit hook. */

                    /* if it returns false abort submitting */                    

                    if (false !== onsubmit.apply(form, [settings, self])) { 

                        /* custom inputs call before submit hook. */

                        /* if it returns false abort submitting */

                        if (false !== submit.apply(form, [settings, self])) { 



                          /* check if given target is function */

                          if ($.isFunction(settings.target)) {

                              var str = settings.target.apply(self, [input.val(), settings]);

                              $(self).html(str);

                              self.editing = false;

                              callback.apply(self, [self.innerHTML, settings]);

                              /* TODO: this is not dry */                              

                              if (!$.trim($(self).html())) {

                                  $(self).html(settings.placeholder);

                              }

                          } else {

                              /* add edited content and id of edited element to POST */

                              var submitdata = {};

                              submitdata[settings.name] = input.val();

                              submitdata[settings.id] = self.id;

                              /* add extra data to be POST:ed */

                              if ($.isFunction(settings.submitdata)) {

                                  $.extend(submitdata, settings.submitdata.apply(self, [self.revert, settings]));

                              } else {

                                  $.extend(submitdata, settings.submitdata);

                              }



                              /* quick and dirty PUT support */

                              if ('PUT' == settings.method) {

                                  submitdata['_method'] = 'put';

                              }



                              /* show the saving indicator */

                              $(self).html(settings.indicator);

                              

                              /* defaults for ajaxoptions */

                              var ajaxoptions = {

                                  type    : 'POST',

                                  data    : submitdata,

                                  dataType: 'html',

                                  url     : settings.target,

                                  success : function(result, status) {

                                      if (ajaxoptions.dataType == 'html') {

                                        $(self).html(result);

                                      }

                                      self.editing = false;

                                      callback.apply(self, [result, settings]);

                                      if (!$.trim($(self).html())) {

                                          $(self).html(settings.placeholder);

                                      }

                                  },

                                  error   : function(xhr, status, error) {

                                      onerror.apply(form, [settings, self, xhr]);

                                  }

                              };

                              

                              /* override with what is given in settings.ajaxoptions */

                              $.extend(ajaxoptions, settings.ajaxoptions);   

                              $.ajax(ajaxoptions);          

                              

                            }

                        }

                    }

                    

                    /* show tooltip again */

                    $(self).attr('title', settings.tooltip);

                    

                    return false;

                });

            });

            

            /* privileged methods */

            this.reset = function(form) {

                /* prevent calling reset twice when blurring */

                if (this.editing) {

                    /* before reset hook, if it returns false abort reseting */

                    if (false !== onreset.apply(form, [settings, self])) { 

                        $(self).html(self.revert);

                        self.editing   = false;

                        if (!$.trim($(self).html())) {

                            $(self).html(settings.placeholder);

                        }

                        /* show tooltip again */

                        if (settings.tooltip) {

                            $(self).attr('title', settings.tooltip);                

                        }

                    }                    

                }

            };            

        });



    };





    $.editable = {

        types: {

            defaults: {

                element : function(settings, original) {

                    var input = $('<input type="hidden"></input>');                

                    $(this).append(input);

                    return(input);

                },

                content : function(string, settings, original) {

                    $(':input:first', this).val(string);

                },

                reset : function(settings, original) {

                  original.reset(this);

                },

                buttons : function(settings, original) {

                    var form = this;

                    if (settings.submit) {

                        /* if given html string use that */

                        if (settings.submit.match(/>$/)) {

                            var submit = $(settings.submit).click(function() {

                                if (submit.attr("type") != "submit") {

                                    form.submit();

                                }

                            });

                        /* otherwise use button with given string as text */

                        } else {

                            var submit = $('<button type="submit" />');

                            submit.html(settings.submit);                            

                        }

                        $(this).append(submit);

                    }

                    if (settings.cancel) {

                        /* if given html string use that */

                        if (settings.cancel.match(/>$/)) {

                            var cancel = $(settings.cancel);

                        /* otherwise use button with given string as text */

                        } else {

                            var cancel = $('<button type="cancel" />');

                            cancel.html(settings.cancel);

                        }

                        $(this).append(cancel);



                        $(cancel).click(function(event) {

                            //original.reset();

                            if ($.isFunction($.editable.types[settings.type].reset)) {

                                var reset = $.editable.types[settings.type].reset;                                                                

                            } else {

                                var reset = $.editable.types['defaults'].reset;                                

                            }

                            reset.apply(form, [settings, original]);

                            return false;

                        });

                    }

                }

            },

            text: {

                element : function(settings, original) {

                    var input = $('<input />');

                    if (settings.width  != 'none') { input.width(settings.width);  }

                    if (settings.height != 'none') { input.height(settings.height); }

                    /* https://bugzilla.mozilla.org/show_bug.cgi?id=236791 */

                    //input[0].setAttribute('autocomplete','off');

                    input.attr('autocomplete','off');

                    $(this).append(input);

                    return(input);

                }

            },

            textarea: {

                element : function(settings, original) {

                    var textarea = $('<textarea />');

                    if (settings.rows) {

                        textarea.attr('rows', settings.rows);

                    } else if (settings.height != "none") {

                        textarea.height(settings.height);

                    }

                    if (settings.cols) {

                        textarea.attr('cols', settings.cols);

                    } else if (settings.width != "none") {

                        textarea.width(settings.width);

                    }

                    $(this).append(textarea);

                    return(textarea);

                }

            },

            select: {

               element : function(settings, original) {

                    var select = $('<select />');

                    $(this).append(select);

                    return(select);

                },

                content : function(data, settings, original) {

                    /* If it is string assume it is json. */

                    if (String == data.constructor) {      

                        eval ('var json = ' + data);

                    } else {

                    /* Otherwise assume it is a hash already. */

                        var json = data;

                    }

                    for (var key in json) {

                        if (!json.hasOwnProperty(key)) {

                            continue;

                        }

                        if ('selected' == key) {

                            continue;

                        } 

                        var option = $('<option />').val(key).append(json[key]);

                        $('select', this).append(option);    

                    }                    

                    /* Loop option again to set selected. IE needed this... */ 

                    $('select', this).children().each(function() {

                        if ($(this).val() == json['selected'] || 

                            $(this).text() == $.trim(original.revert)) {

                                $(this).attr('selected', 'selected');

                        }

                    });

                }

            }

        },



        /* Add new input type */

        addInputType: function(name, input) {

            $.editable.types[name] = input;

        }

    };



    // publicly accessible defaults

    $.fn.editable.defaults = {

        name       : 'value',

        id         : 'id',

        type       : 'text',

        width      : 'auto',

        height     : 'auto',

        event      : 'click.editable',

        onblur     : 'cancel',

        loadtype   : 'GET',

        loadtext   : 'Loading...',

        placeholder: 'Click to edit',

        loaddata   : {},

        submitdata : {},

        ajaxoptions: {}

    };



})(jQuery);

