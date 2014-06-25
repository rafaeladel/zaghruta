$(document).ready(function(){
    $("body").on("click", ".moveExpTip", function(e){
        e.preventDefault();
        $(".content_wrapper").html('<img style="margin: auto; display: block;" src="'+UrlContainer.loader+'" />');
        $(".content_wrapper").load($(e.target).attr("href"));
        history.pushState(null, null, $(e.target).attr("href"));
    });

    $("body").on("click", ".moveEditExpTip", function (e) {
        e.preventDefault();
        var url = $(e.target).attr("href");
        $(".content_wrapper").html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
        $("body").find(".content_wrapper").load(url, function () {
            history.pushState(null, null, url);
            ThraceForm.select2();
        });
    });

    $("body").on("click", ".exptip_edit_submit", function (e) {
        var form = $(e.target).closest("form");
        var btn = $(e.target);
        btn.attr("disabled", "disabled");
        $("#myform").parsley().subscribe("parsley:form:validate", function (instance) {
            instance.submitEvent.preventDefault();
            if (instance.isValid()) {
                $.ajax({
                    type: "post",
                    url: form.attr("action"),
                    data: form.serialize(),
                    success: function (data) {
                        var wrapper = $("body").find(".content_wrapper");
                        if (data.status == 200) {
                            btn.removeAttr("disabled");
                            var back_url = btn.data("back_url");
                            wrapper.html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
                            wrapper.load(back_url);
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
    });



    singleUpload("exp_tip_browse");
});