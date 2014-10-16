function postRefresh()
{
    // select the target node
    var target = $("body").find('.comments_wrapper');

    var observeDOM = function() {
        var MutationObserver = window.MutationObserver || window.WebKitMutationObserver,
            eventListenerSupported = window.addEventListener;

        return function(obj, callback) {
            if(MutationObserver) {
                var observer = new MutationObserver(function (mutations) {
                    mutations.forEach(function (mutation) {
                        callback();

                    });
                });
                var config = { attributes: true, childList: true, characterData: true };
                $(target).each(function(i, v){
                    observer.observe(obj, config);
                });

            } else if(eventListenerSupported) {
                obj.addEventListener('DOMNodeInserted', callback, false);
                obj.addEventListener('DOMNodeRemoved', callback, false);
            }
        }
    };

    observeDOM(target, function(){
        var comments_count = $(mutation.target).find(".postComment").length;
        $(mutation.target).closest(".post, .photo, .experience, .tip, .product").find(".comments_count").text(comments_count);
    });


    //// configuration of the observer:
    //
    //$(target).each(function(i, v){
    //    observer.observe(v, config);
    //});

    $("body").find('.tooltip').tooltip();

    $('body').find('.collapse').collapse();

    $('body').find('.animated').autosize();

}