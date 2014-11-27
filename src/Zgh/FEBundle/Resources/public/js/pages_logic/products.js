$(document).ready(function () {
    $("body").on("click", ".moveProduct", function (e) {
        e.preventDefault();
        $(".content_wrapper").html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
        $(".content_wrapper").load($(e.target).attr("href"));
        history.pushState(null, null, $(e.target).attr("href"));
    });

    $("body").on("click", ".moveEditProduct", function (e) {
        e.preventDefault();
        var url = $(e.target).attr("href");
        $(".content_wrapper").html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
        $("body").find(".content_wrapper").load(url, function () {
            history.pushState(null, null, url);
            ThraceForm.select2();
        });
    });

    $("body").on("click", ".product_edit_submit", function (e) {
        e.preventDefault();
        var form = $(e.target).closest("form");
        var btn = $(e.target);
        var wrapper = $("body").find(".content_wrapper");
        form.validate({
            rules: {
                "product[name]": {
                    required: true
                },
                "product[price]": {
                    number: true,
                    min: 1
                }
            },
            messages:{
                "product[name]": {
                    required: "Product name is required"
                },
                "product[price]": {
                    number: "Price must be a valid number",
                    min: "Price must be a positive number"
                }
            }
        });
        if(form.valid()) {
            btn.attr("disabled", "disabled");
            $.ajax({
                type: "post",
                url: form.attr("action"),
                data: form.serialize(),
                success: function (data) {
                    if (data.status == 200) {
                        btn.removeAttr("disabled");
                        wrapper.html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
                        var back_url = $(e.target).data("back_url");
                        $("body").find(".content_wrapper").load(back_url);
                        $("body").find(".product_name").text(data.product_name);
                    } else if (data.status == 500) {
                        btn.removeAttr("disabled");
                        wrapper.html(data.view);

                    }
                }
            });
        }
    });


    $("body").on("click", ".moveWishlist", function (e) {
        e.preventDefault();
        var url = $(e.target).data("url");
        var wrapper = $(e.target).closest(".form_wrapper");
        wrapper.html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
        wrapper.load(url);
    });

    $("body").on("click", ".addWishlist", function(e) {
        e.preventDefault();
        var url = $(e.currentTarget).data("url");
        var wrapper = $(e.currentTarget).siblings("div#addwishlist").find(".form_wrapper");
        wrapper.html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
        wrapper.load(url);
    });

    $("body").on("click", ".newWishlistSubmit", function (e) {
        e.preventDefault();
        var form = $(e.target).closest("form");
        var back_url = $(e.target).closest("form").find(".moveWishlist").data("url");
        var wrapper = $(e.target).closest(".form_wrapper");
        form.validate();
        if (form.valid()) {
            $.ajax({
                type: "post",
                url: form.attr("action"),
                data: form.serialize(),
                success: function (data) {
                    wrapper.html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
                    wrapper.load(back_url);
                }
            });
        }

    });

    $("body").on("click", ".addToWishlistSubmit", function (e) {
        e.preventDefault();
        var form = $(e.target).closest("form");
        if(form.find(":checked").length == 0) {
            form.find(".wishlist_error").text("Please select at least one wishlist");
            return;
        }
        $.ajax({
            type: "post",
            url: form.attr("action"),
            data: form.serialize(),
            success: function (data) {
                $(e.target).removeAttr("disabled").text("Save");
                $(e.target).closest("div.modal").modal("hide");
            }
        });

    });

    singleUploadProduct();
});