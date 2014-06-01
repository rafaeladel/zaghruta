$(document).ready(function () {
    $("body").on("click", ".moveProduct", function (e) {
        e.preventDefault();
        $(".content_wrapper").html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
        $(".content_wrapper").load($(e.currentTarget).attr("href"));
        history.pushState(null, null, $(e.currentTarget).attr("href"));
    });

    $("body").on("click", ".moveEditProduct", function (e) {
        e.preventDefault();
        var url = $(e.currentTarget).attr("href");
        $(".content_wrapper").html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
        $("body").find(".content_wrapper").load(url, function () {
            history.pushState(null, null, url);
            ThraceForm.select2();
        });
    });

    $("body").on("click", ".product_edit_submit", function (e) {
        var form = $(e.target).closest("form");
        $(e.currentTarget).attr("disabled", "disabled").text("Saving");
        $("#myform").parsley().subscribe("parsley:form:validate", function (instance) {
            instance.submitEvent.preventDefault();
            if (instance.isValid()) {
                $.ajax({
                    type: "post",
                    url: form.attr("action"),
                    data: form.serialize(),
                    success: function (data) {
                        if (data.status == 200) {
                            var back_url = $(e.currentTarget).data("back_url");
                            $(".content_wrapper").html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
                            $("body").find(".content_wrapper").load(back_url);
                        } else if (data.status == 500) {
                            $(".content_wrapper").html(data.view);

                        }
                    }
                });
            } else {
                $(e.currentTarget).removeAttr("disabled").text("Save");
            }
        });
    });


    $("body").on("click", ".moveWishlist", function(e){
        e.preventDefault();
        var url = $(e.currentTarget).data("url");
        var wrapper = $(e.currentTarget).closest(".form_wrapper");
        wrapper.html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
        wrapper.load(url);
    });

    $("body").on("click", ".newWishlistSubmit", function(e){
        var form = $(e.currentTarget).closest("form");
        var back_url = $(e.currentTarget).closest("form").find(".moveWishlist").data("url");
        var wrapper = $(e.currentTarget).closest(".form_wrapper");
        $("#myform").parsley().subscribe("parsley:form:validate", function (instance) {
            instance.submitEvent.preventDefault();
            if (instance.isValid()) {
                $(e.currentTarget).attr("disabled", "disabled").text("Saving");
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
    });

    $("body").on("click", ".addToWishlistSubmit", function(e){
        e.preventDefault();
        $(e.currentTarget).attr("disabled","disabled").text("Saving");
        var form = $(e.currentTarget).closest("form");
        $.ajax({
            type: "post",
            url: form.attr("action"),
            data: form.serialize(),
            success: function(data){
                $(e.currentTarget).closest("div.modal").modal("hide");
                $(e.currentTarget).removeAttr("disabled").text("Save");
            }
        });
    });

    singleUpload("product_browse");
});