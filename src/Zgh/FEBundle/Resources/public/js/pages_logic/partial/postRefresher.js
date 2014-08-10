function postRefresh()
{
    // select the target node
    var target = $("body").find('.comments_wrapper');

    // create an observer instance
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            var comments_count = $(mutation.target).find(".postComment").length;
            $(mutation.target).closest(".post, .photo, .experience, .tip, .product").find(".comments_count").text(comments_count);
        });
    });

    // configuration of the observer:
    var config = { attributes: true, childList: true, characterData: true };

    $(target).each(function(i, v){
        observer.observe(v, config);
    });

    $("body").find('.tooltip').tooltip();

    $('body').find('.collapse').collapse();

    $('body').find('.animated').autosize();

}