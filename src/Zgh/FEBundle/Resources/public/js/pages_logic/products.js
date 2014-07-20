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
        btn.attr("disabled", "disabled");
        console.log("in");
//        $("#myform").parsley().subscribe("parsley:form:validate", function (instance) {
//            console.log("out");
//            instance.submitEvent.preventDefault();
//            if (instance.isValid()) {
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
                        } else if (data.status == 500) {
                            btn.removeAttr("disabled");
                            wrapper.html(data.view);

                        }
                    }
                });
//            } else {
//                btn.removeAttr("disabled");
//
//            }
//        });
    });


    $("body").on("click", ".moveWishlist", function(e){
        e.preventDefault();
        var url = $(e.target).data("url");
        var wrapper = $(e.target).closest(".form_wrapper");
        wrapper.html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
        wrapper.load(url);
    });

    $("body").on("click", ".newWishlistSubmit", function(e){
        e.preventDefault();
        var form = $(e.target).closest("form");
        var back_url = $(e.target).closest("form").find(".moveWishlist").data("url");
        var wrapper = $(e.target).closest(".form_wrapper");
        form.validate();
        if(form.valid()) {
            $.ajax({
                type: "post",
                url: form.attr("action"),
                data: form.serialize(),
                success: function(data){
                    wrapper.html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
                    wrapper.load(back_url);
                }
            });
        }

    });

    $("body").on("click", ".addToWishlistSubmit", function(e){
        e.preventDefault();
        var form = $(e.target).closest("form");
        $.ajax({
            type: "post",
            url: form.attr("action"),
            data: form.serialize(),
            success: function(data){
                $(e.target).removeAttr("disabled").text("Save");
                $(e.target).closest("div.modal").modal("hide");
            }
        });
    });

    singleUploadProduct();
});