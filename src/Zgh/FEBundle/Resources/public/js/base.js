$(document).ready(function(){
    var notificationsUrl = Routing.generate("zgh_fe.notifications");
    setInterval(function(){
        $("body").find(".notificationsWidget").load(notificationsUrl);
    }, 10000000);


    $(window).on("popstate", function (e) {
        e.preventDefault();
        window.location.href = window.location.href;
    });


    $(document).on("ajaxSend", function (e) {
        var target = $(e.target.activeElement);
        if (target.is("input[type='submit']") || target.is("button[type='submit']")) {
            target.attr("disabled", "disabled");
        }
    });

    $(document).on("ajaxSuccess", function (e) {
        var target = $(e.target.activeElement);
        if (target.is("input[type='submit']") || target.is("button[type='submit']")) {
            target.removeAttr("disabled");
        }
        ThraceForm.select2();
    });

});