(function($){

    $.fn.messengerCounter = function() {

        return this.each(function() {

            var counter = $(this),
                url = counter.data('url'),
                timeout = counter.data('timeout')*1000,

                success = function(data){
                    counter.html(data);
                },

                update = function(){
                    $.ajax({
                        url: url,
                        type: 'get',
                        success: function (data, status, xhr) {
                            success(data)
                        }
                    });
                };

            update();

            setInterval(update, timeout);

        });

    }

})(jQuery);

