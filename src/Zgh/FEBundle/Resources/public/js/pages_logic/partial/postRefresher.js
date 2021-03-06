function postRefresh()
{
    // select the target node
    var target = $("body").find('.comments_wrapper');

// create an observer instance
    var observer = new JsMutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            var comments_count = $(mutation.target).find(".postComment").length;
            $(mutation.target).closest(".post, .photo, .experience, .tip, .product").find(".comments_count").text(comments_count);
        });
    });

// configuration of the observer:
    var config = { attributes: true, childList: true, characterData: true };

// pass in the target node, as well as the observer options
    $.each(target, function(i, v) {
        observer.observe(v, config);
    });


    $("body").find(".post_form").validate({
        rules: {
            "post[content]": {
                required: function(){
                    return $('[name="post[image_file]"]').val()  == 0;
                }
            }
        },
        messages: {
            "post[content]": {
                required: "Post content cannot be empty"
            }
        },
        submitHandler: function(form){
            var url = $(form).attr("action");
            var formData = new FormData(form);
            $(form).find(".newPost").attr("disabled", "disabled").text("Posting..");
            $.ajax({
                type: "post",
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function(data){
                    if(data.success) {
                        $("body").find(".posts_wrapper").prepend(data.view);
                        $(form).find(".newPost").removeAttr("disabled").text("Post");
                        var wrapper = $(form).find(".thumbnailUpload");
                        wrapper.hide();
                        form.reset();
                    } else {
                        console.log(data.errors);
                        $(form).find(".form_errors").text(data.errors);
                    }
                }
            });
        }
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