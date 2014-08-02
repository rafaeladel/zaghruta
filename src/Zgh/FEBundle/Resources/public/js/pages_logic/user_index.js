$(document).ready(function(){


    $("body").on("click", ".tab", function(e){
        e.preventDefault();
        var tab = $(e.currentTarget);
        if(!tab.is(".activated")){
            document.title = tab.text() + " | Zaghruta";
            $(".tab").removeClass("active");
            tab.addClass("active");
            $(".content_wrapper").html('<img style="margin: auto; display: block;" src="'+UrlContainer.loader+'" />');
            $(".content_wrapper").load(tab.data("target_url"));
            history.pushState(null, null, tab.data("target_url"));
        }
    });

    $("body").on("click", ".load-more", function(e){
        var btn = $(e.currentTarget);
        var url = btn.data("url")+"?f="+$(".post").length;
        btn.attr("disabled", "disabled");
        $.ajax({
            type: "get",
            url : url,
            success: function(data)
            {
                $("body").find(".post").last().after(data.view);
                if(data.view != "") {
                    btn.removeAttr("disabled");
                } else {
                    btn.remove();
                }
            }
        });
    });

    $("body").on("change", ".photo_btn", function(e){
        e.preventDefault();
        var form = $(".picture_form");
        form.validate({
            rules:{
                picture:{
                    accept: "audio/*",
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
            form.find(".pp_errors").show().text("Invalid file, must be an image and size must be under (2 MB max).");
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