$(document).ready(function () {

    if ($("body").find(".post").length < 6 ) {
        $("body").find(".load-more").remove();
    }

    $("body").on("click", ".tab", function (e) {
        e.preventDefault();
        var tab = $(e.currentTarget);
        if (!tab.is(".activated")) {
            document.title = tab.text() + " | Zaghruta";
            $(".tab").removeClass("active");
            tab.addClass("active");
            $(".content_wrapper").html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');

            //to prevent chrome ajax caching (back button)
            var d = new Date();
            var n = d.getTime();
            $.ajax({
                type: "GET",
                url: tab.data("target_url")+"?ajax="+n,
                success: function (data) {
                    $(".content_wrapper").html(data);
                }
            });
            //$(".content_wrapper").load(tab.data("target_url"));
            var stateObj = {
                targetUrl : tab.data("target_url"),
                targetId: tab.data("id")
            };
            history.pushState(stateObj, null, tab.data("target_url"));
        }
    });


    $("body").on("click", ".load-more", function (e) {
        var btn = $(e.currentTarget);
        var url = btn.data("url") + "?f=" + $(".post").length + "&idh=" + $(".post").first().data("post_id");
        btn.attr("disabled", "disabled");
        $.ajax({
            type: "get",
            url: url,
            success: function (data) {
                if (data.success) {
                    $("body").find(".post").last().after(data.view);
                } else {
                    btn.remove();
                }
                btn.removeAttr("disabled");
            }
        });
    });

    $("body").on("change", ".photo_btn", function (e) {
        e.preventDefault();
        var form = $(e.target).closest("form");
        form.validate({
            rules: {
                picture: {
                    accept: "image/*",
                    file_size: 1
                }
            },
            messages: {
                picture: {
                    file_size: "File is too large (5 MB max)"
                }
            },
            errorPlacement: function(label, element) {
                label.addClass('arrow');
                label.insertAfter($(element).closest(".btnBrowsePhoto"));
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
        var wrapper = form.find(".thumbnailUpload");
        wrapper.show();
        var imgWrapper = wrapper.find("img");

        if (form.valid()) {
            imgWrapper.show();
            imgWrapper.attr("src", URL.createObjectURL(this.files[0]));
            form.find(".pp_errors").html("").hide();
        } else {
            imgWrapper.attr("src", "#").hide();
            form.find(".pp_errors").show().text("Invalid file, must be an image and size must be under (5 MB max).");
        }

    });

    var img_crop = new ImgCrop({
        height: 160,
        width: 535,
        image_class: "cover_thumb",
        wrapper: "cover_wrapper",
        form_class: "cover_form",
        input_class: "cover_input",
        crop: true,
        autocrop: true,
        file_size: 5
    });
    img_crop.init();

    $(".fancyboxArrow").fancybox({
        'showNavArrows': 'true'
    });


    $("body").on("click", ".reset_photo", function(e){
        e.preventDefault();
        var wrapper = $(e.currentTarget).closest("form").find(".thumbnailUpload");
        wrapper.hide();
        var file_input = $(e.currentTarget).closest("form").find(".photo_btn");
        file_input.wrap('<form>').closest('form').get(0).reset();
        file_input.unwrap();
    });


});