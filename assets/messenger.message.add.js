(function($){

    $.fn.messengerAddMessage = function() {

        return this.each(function(){

            var bl = $(this),
                blId = bl.attr('id'),
                form = bl.find('form'),
                editor = bl.find('textarea'),
                successMsg = bl.find('.message-success'),
                errorMsg = bl.find('.message-error'),
                pjaxId = bl.data('pjax'),
                fancy = bl.data('fancy'),
                process = false;

            var error = function(data){
                successMsg.hide();
                errorMsg.show();

                bl.trigger({
                    type: 'message-error',
                    data: data
                });
            }

            var success = function(data){

                successMsg.show();
                errorMsg.hide();

                editor.val('');
                form.yiiActiveForm('resetForm');
                var yiiData = form.yiiActiveForm('data');
                yiiData.validated = false;

                if (pjaxId) {
                    $(document).on('pjax:end', function() {
                        scrollTo(data.id);
                        $(document).off('pjax:end');
                    });

                    $.pjax.reload({container:'#'+pjaxId, timeout: false});
                }

                bl.trigger({
                    type: 'message-success',
                    data: data
                });

                setTimeout(function(){

                    successMsg.hide();

                    if (fancy) {
                        $.fancybox.close();
                    }

                }, 3000);

            }

            var scrollTo = function(id) {
                var elem = $("#message-item-"+id);
                if(elem.length>0) {
                    var top = elem.offset().top;
                    $(window).scrollTop(top);
                }

            }

            form.on('submit', function(){
                return false;
            });

            form.on('beforeSubmit', function(e){

                e.preventDefault();

                if(process) {
                    return;
                }

                process = true;

                var action = form.attr('action');

                var jqXhr = $.ajax({
                    url: action,
                    type: 'post',
                    data: form.serialize(),
                    headers: {},
                    dataType: 'json',
                    success: function (data, status, xhr) {
                        if(xhr.status == 201) {
                            success(data);
                        }
                        else
                            error(data);
                    },
                    error: function(data, status, xhr) {
                        error(data);
                    }
                });

                jqXhr.always(function(){
                    process = false;
                });

            })

        });

    }

})(jQuery);