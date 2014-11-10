$(document).ready(function(){
    $("body").on("click", ".photoTab", function(e){
        e.preventDefault();
        if(!$(e.target).is(".active")){
            $(".photosWrapper").html('<img style="margin: auto; display: block;" src="'+UrlContainer.loader+'" />');
            $(".photoTab").removeClass("active");

            $(".content_wrapper").load($(e.target).data("target_url"));
            history.pushState(null, null, $(e.target).data("target_url"));

        }
    });

    $("body").on("click", ".load_images", function(e){
        e.preventDefault();
        $(e.currentTarget).closest(".content_wrapper").load($(e.currentTarget).data("url"));
        $(".photosWrapper").html('<img style="margin: auto; display: block;" src="'+UrlContainer.loader+'" />');
        history.pushState(null, null, $(e.currentTarget).data("url"));
    })

    $("body").on("click", ".back_btn", function(e){
        e.preventDefault();
        $(".photosWrapper").html('<img style="margin: auto; display: block;" src="'+UrlContainer.loader+'" />');
        $(".content_wrapper").load($(e.currentTarget).data("target_url"));
        history.pushState(null, null, $(e.currentTarget).data("target_url"));
    });

    $("body").on("click", ".edit_caption, .cancel_caption", function(e) {
        e.preventDefault();
        var btn = $(e.target);
        var url = btn.data("url");
        if(!btn.hasClass("disabled")) {
            btn.addClass("disabled").css({"color": "gray", "cursor": "default"});
            $.ajax({
                type: "get",
                url: url,
                success: function (data) {
                    btn.closest(".caption_wrapper").html(data.view);
                }
            });
        }
    });


    $("body").on("click", ".save_caption", function(e) {
        e.preventDefault();
        var btn = $(e.target);
        var form  = $(e.target).closest("form");
        var url = form.attr("action");
        if(!btn.hasClass("disabled")) {
            btn.addClass("disabled").css({"color": "gray", "cursor": "default"});
            $.ajax({
                type: "post",
                url: url,
                data: form.serialize(),
                success: function (data) {
                    btn.closest(".caption_wrapper").html(data.view);
                }
            });
        }
    });

    photoRefresh();

});

