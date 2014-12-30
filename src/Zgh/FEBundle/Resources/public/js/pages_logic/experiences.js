$(document).ready(function(){

    $("body").on("click", ".moveExpTip", function(e){
        e.preventDefault();
        $(".content_wrapper").html('<img style="margin: auto; display: block;" src="'+UrlContainer.loader+'" />');
        $(".content_wrapper").load($(e.currentTarget).attr("href"));
        history.pushState(null, null, $(e.currentTarget).attr("href"));
    });

    $("body").on("click", ".moveEditExpTip", function (e) {
        e.preventDefault();
        var url = $(e.currentTarget).attr("href");
        $(".exptip_content_wrapper").html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
        $("body").find(".exptip_content_wrapper").load(url, function () {
            history.pushState(null, null, url);
            ThraceForm.select2();
        });
    });

    $("body").on("click", ".exptip_edit_submit", function (e) {
        e.preventDefault();
        var form = $(e.currentTarget).closest("form");
        var btn = $(e.currentTarget);
        btn.attr("disabled", "disabled");
        form.validate();
        if(form.valid()) {
            $.ajax({
                type: "post",
                url: form.attr("action"),
                data: form.serialize(),
                success: function (data) {
                    var wrapper = $("body").find(".exptip_content_wrapper");
                    if (data.status == 200) {
                        btn.removeAttr("disabled");
                        var back_url = btn.data("back_url");
                        wrapper.html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
                        wrapper.load(back_url, function() {
                            history.pushState(null, null, back_url);
                        });
                    } else if (data.status == 500) {
                        btn.removeAttr("disabled");
                        wrapper.html(data.view);

                    }
                }
            });
        } else {
            btn.removeAttr("disabled");
        }

    });



    singleUploadExpTip();
});