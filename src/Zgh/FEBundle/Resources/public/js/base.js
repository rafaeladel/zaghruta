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

//    $(document).on("ajaxSend", function (e) {
//        var target = $(e.target.activeElement);
//        if (target.is("input[type='submit']") || target.is("button[type='submit']")) {
//            target.attr("disabled", "disabled");
//        }
//    });

    $(document).on("ajaxSuccess", function (e) {
        ThraceForm.select2();
        if ($("body").find(".post").length < 6 ) {
            $("body").find(".load-more").remove();
        }

    });

});