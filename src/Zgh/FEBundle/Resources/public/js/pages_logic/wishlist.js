$(document).ready(function () {
    $("body").on("click", ".wishlistSubmit", function (e) {
        $("#myform").parsley().subscribe("parsley:form:validate", function (instance) {
            instance.submitEvent.preventDefault();
            if (instance.isValid()) {
                var form = $(e.target).closest("form");
                $.ajax({
                    type: "POST",
                    url: $(form).attr('action'),
                    data: $(form).serialize(),
                    success: function () {
                        $(".modalClose").click();
                        $(form).get(0).reset();
                        $("#wishlists_list").load(UrlContainer.wishlistPartial);
                    }
                });
            } else {
                $("#myform").parsley().unsubscribe("parsley:form:validate");
            }
        });
    });

    $("body").on("click", ".moveWishlistIndex", function (e) {
        e.preventDefault();
        var url = $(e.currentTarget).attr("href");
        var wrapper = $(e.currentTarget).closest(".content_wrapper");

        wrapper.html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
        wrapper.load(url);
    });
});