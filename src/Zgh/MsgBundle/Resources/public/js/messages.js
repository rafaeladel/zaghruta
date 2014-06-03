$(document).ready(function () {
    $("body").find(".thread").first().click();

    $("body").on("click", ".thread", function (e) {
        e.preventDefault();
        var thread_id = $(e.currentTarget).data("t_id");
        var url = Routing.generate('fos_message_thread_view', {threadId: thread_id}, true);
        var wrapper = $(e.currentTarget).closest(".container").find(".listMessage");
        wrapper.html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
        wrapper.load(url);
    });

    $("body").on("click", ".reply_submit", function (e) {
        e.preventDefault();
        $(e.currentTarget).attr("disabled", "disabled");
        var thread_id = $(e.currentTarget).data("t_id");
        var url = Routing.generate('fos_message_thread_view', {threadId: thread_id}, true);
        var wrapper = $(e.currentTarget).closest(".container").find(".listMessage");

        $.ajax({
            type: "post",
            url: url,
            data: $(e.currentTarget).closest("form").serialize(),
            success: function(data){
                wrapper.load(url,function(){
                    $(e.currentTarget).removeAttr("disabled");
                });
            }
        });
    });

    $("")
});