$(document).ready(function () {
    var notificationsUrl = Routing.generate("zgh_fe.notifications");
    setInterval(function () {
        $("body").find(".notificationsWidget").load(notificationsUrl);
    }, 10000);


    $(window).on("popstate", function (e) {
        e.preventDefault();
        window.location.href = window.location.href;
    });

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
        if($("body").find(".post").length == 0)
        {
            $("body").find(".load-more").remove();
        }
    });

});