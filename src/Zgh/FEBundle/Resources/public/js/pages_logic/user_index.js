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

    $("body").on("change", ".photo_btn", function(e){
        e.preventDefault();
        var form = $(".picture_form");
        form.validate({
            rules:{
                picture:{
                    file_size: 2
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

        var wrapper = form.find(".thumbnailUpload");
        wrapper.show();
        var imgWrapper = wrapper.find("img");

        if(form.valid()){
            imgWrapper.show();
            imgWrapper.attr("src", URL.createObjectURL(this.files[0]));
            form.find(".pp_errors").html("").hide();
        } else {
            imgWrapper.attr("src", "#").hide();
            form.find(".pp_errors").show().text("File is too large (2 MB max).");
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
        autocrop: true,
        file_size: 2
    });
    img_crop.init();

    $(".fancyboxArrow").fancybox({
        'showNavArrows'     : 'true'
    });

});