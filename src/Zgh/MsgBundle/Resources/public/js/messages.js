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

    var inbox_content_url = Routing.generate("zgh_message_inbox_content");

    setInterval(function(){
        $("body").find(".sidebarMessage").load(inbox_content_url);
        var thread_id = $("body").find(".messagesWrapper").data("t_id");
        var message_list_url = Routing.generate("fos_message_list_view", {threadId: thread_id});
        $("body").find(".messagesWrapper").load(message_list_url);
    }, 10000);
});