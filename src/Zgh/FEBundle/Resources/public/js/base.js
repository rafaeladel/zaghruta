$(document).ready(function () {
    var notificationsUrl = Routing.generate("zgh_fe.notifications");
    var login_url = Routing.generate("fos_user_security_login");
    setInterval(function () {
        $.ajax({
            type: "get",
            url: notificationsUrl,
            success: function(data) {
                if(data.logged_in) {
                    $("body").find(".notificationsWidget").html(data.view);
                } else {
                    window.location = login_url;
                }
            }
        });
    }, 10000);

    $.validator.setDefaults({
        debug: true,
        wrapper: "ul",
        errorElement: "li",
        errorPlacement: function(label, element) {
            label.addClass('errors-list-wrapper');
            label.insertAfter(element);
        }
    });
    $.validator.addMethod(
        "file_size",
        function(value, element, size) {
            var element_size = element.files[0].size/5000000;
            return element_size < size;
        },
        "File is large."
    );

    setTimeout(function () {
        $(window).on("popstate", function (e) {
            e.preventDefault();
            var targetUrl = e.originalEvent.state == null ? null : e.originalEvent.state.targetUrl,
                targetId = e.originalEvent.state == null ? null : e.originalEvent.state.targetId;
            if(targetId && targetUrl) {
                var tab = $("body").find("a[data-id='"+targetId+"']");
                document.title = tab.text() + " | Zaghruta";
                $(".tab").removeClass("active");
                tab.addClass("active");
                $("body").find(".content_wrapper").html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
                $("body").find(".content_wrapper").load(targetUrl);
            } else {
                window.location.href = window.location.href;
            }
        });
    }, 500);


    $("body").on("click", ".btnFollowing", function (e) {
        e.preventDefault();
        var btn = $(e.currentTarget);
        btn.attr("disabled", "disabled");
        var followers_count = btn.closest(".profile").find(".followerStats");
        var form = btn.closest("form");
        var url = btn.data("url");
        $.ajax({
            type: "post",
            url: form.attr("action"),
            success: function (data) {
                btn.find(".following_msg").text(data.msg);
                if(data.msg != "Pending") {
                    followers_count.text(data.follower_count);
                }
                btn.removeAttr("disabled");
            }
        })
    });



    $("body").on("click", ".comment_down, .comment_up, .post_up, .post_down", function(e){
        e.preventDefault();
        $(e.target).closest(".content_togglers").siblings(".post_content, .comment_content").toggleClass("show");
        $(e.target).closest(".content_togglers").find("a").toggle();
    });

    $(document).on("ajaxSuccess", function (e) {
        ThraceForm.select2();
        if ($("body").find(".post").length < 6 ) {
            $("body").find(".load-more").remove();
        }
        postRefresh();
        photoRefresh();
        singleUploadProduct();
        singleUploadExpTip();
        $(".animated").trigger('autosize.resize');
    });

});