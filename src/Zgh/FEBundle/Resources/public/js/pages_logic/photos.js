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
        $(".content_wrapper").load($(e.target).data("target_url"));
        history.pushState(null, null, $(e.target).data("target_url"));
    });

    photoRefresh();

});

