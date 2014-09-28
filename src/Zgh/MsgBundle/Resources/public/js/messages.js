$(document).ready(function () {
    $("body").find(".thread").first().click();

    $("body").on("click", ".thread", function (e) {
        e.preventDefault();
        var thread_id = $(e.currentTarget).data("t_id");
        var url = Routing.generate('fos_message_thread_view', {threadId: thread_id}, true);
        var wrapper = $(e.currentTarget).closest(".container").find(".listMessage");
        wrapper.html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
        wrapper.load(url, function() {
            wrapper.find(".messagesWrapper").mCustomScrollbar({
                scrollButtons: {
                    enable: true
                },
                theme: "dark"
            });
            wrapper.find(".messagesWrapper").mCustomScrollbar("scrollTo", ".msgUser:last" ,{
                scrollInertia:250,
                scrollEasing:"easeInOutQuad"
            });

        });
    });

    $("body").on("click", ".reply_submit", function (e) {
        e.preventDefault();
        $(e.currentTarget).attr("disabled", "disabled");
        var form = $(e.currentTarget).closest("form");

        $.ajax({
            type: "post",
            url: form.attr("action"),
            data: form.serialize(),
            success: function(data){
                updateMessages(function() {
                    form.get(0).reset();
                    $(e.target).removeAttr("disabled");
                });
            }
        });
    });

    $("body").on("click", ".btnRemove", function(e){
        var deleteModal = $("body").find("#deleteThread");
        var deleteUrl = $(e.currentTarget).data("url");
        deleteModal.find("form").attr("action", deleteUrl);
    });


    setInterval(function(){
        updateThreads();
        updateMessages();
    }, 10000);


    $("body").find(".sidebarMessage").mCustomScrollbar({
        scrollButtons: {
            enable: true
        },
        theme: "dark",
        mouseWheel:{
            scrollAmount: 1000
        }
    });



    $("body").find(".messagesWrapper").mCustomScrollbar({
        scrollButtons: {
            enable: true
        },
        theme: "dark",
        mouseWheel:{
            scrollAmount: auto
        }

    });

    updateThreadScroll();
    updateMsgScroll();

    function updateThreads(callback) {
        var inbox_content_url = Routing.generate("zgh_message_inbox_content");
        $("body").find(".sidebarMessage .mCSB_container").load(inbox_content_url, function() {
            updateThreadScroll();
        });
        if(typeof callback == "function") {
            callback();
        }
    }

    function updateMessages(callback) {
        var thread_id = $("body").find(".messagesWrapper").data("t_id");
        var message_list_url = Routing.generate("fos_message_list_view", {threadId: thread_id});
        $("body").find(".messagesWrapper .mCSB_container").load(message_list_url, function() {
            updateMsgScroll();
        });
        if(typeof callback == "function") {
            callback();
        }
    }

    function updateThreadScroll() {
        $("body").find(".sidebarMessage").mCustomScrollbar("update");
    }

    function updateMsgScroll() {
        $("body").find(".messagesWrapper").mCustomScrollbar("update");

        $("body").find(".messagesWrapper").mCustomScrollbar("scrollTo", ".msgUser:last" ,{
            scrollInertia:250,
            scrollEasing:"easeInOutQuad"
        });
    }


});