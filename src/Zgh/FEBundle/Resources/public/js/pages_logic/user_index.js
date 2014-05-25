$(document).ready(function(){


    $("body").on("click", ".tab", function(e){
        e.preventDefault();
        if(!$(e.target).is(".activated")){
            $(".tab").removeClass("active");
            $(e.target).addClass("active");
            $(".content_wrapper").html('<img style="margin: auto; display: block;" src="'+UrlContainer.loader+'" />');
            $(".content_wrapper").load($(e.target).data("target_url"));
            history.pushState(null, null, $(e.target).data("target_url"));
        }
    });

    var img_crop = new ImgCrop({
        height : 130,
        width : 535,
        image_class : "cover_thumb",
        wrapper : "cover_wrapper",
        form_class : "cover_form",
        input_class: "cover_input",
        crop: true,
        autocrop: true
    });
    img_crop.init();

    $(".fancyboxArrow").fancybox({
        'showNavArrows'     : 'true'
    });
});