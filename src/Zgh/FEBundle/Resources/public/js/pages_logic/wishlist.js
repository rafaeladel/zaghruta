$(document).ready(function () {
    $("body").on("click", ".wishlistSubmit", function (e) {
        var btn = $(e.currentTarget);
        var form = btn.closest("form");
        form.validate();
        if(form.valid()) {
            btn.attr("disabled", "disabled");
            var form = btn.closest("form");
            $.ajax({
                type: "POST",
                url: $(form).attr('action'),
                data: $(form).serialize(),
                success: function () {
                    btn.removeAttr("disabled");
                    form.get(0).reset();
                    $("#addNewListPopup").modal("hide");
                    $("#wishlists_list").load(UrlContainer.wishlistPartial);
                }
            });
        }
    });

    $("body").on("click", ".moveWishlistIndex", function (e) {
        e.preventDefault();
        var url = $(e.currentTarget).attr("href");
        var wrapper = $(e.currentTarget).closest(".content_wrapper");

        wrapper.html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
        history.pushState(null, null, url);
        wrapper.load(url);
    });

    $("body").on("click", ".wishlist_back_btn", function(e){
        e.preventDefault();
        var wrapper = $("body").find(".content_wrapper");
        wrapper.html('<img style="margin: auto; display: block;" src="'+UrlContainer.loader+'" />');
        wrapper.load($(e.currentTarget).data("target_url"));
        history.pushState(null, null, $(e.currentTarget).data("target_url"));
    });
});