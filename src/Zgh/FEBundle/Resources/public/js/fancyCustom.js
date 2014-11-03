
// Fancy box
// ======================
$(function () {
$('.fancyboxBg').fancybox({

    beforeShow: function (e) {
        $(".fancybox-skin").css("backgroundColor", "white");
        $(".fancybox-skin").css("border", "1px solid rgb(143, 210, 200)");
    },
    helpers: {
        overlay: {
            css: {
                'background': 'rgba(256, 256, 256, 0.7)'
            },
           //  locked: false

        }
    },
    padding: 20,
    maxWidth: 720,
    scrolling: false
});
$(".fancybox")
//    .attr('rel', 'gallery')
    .fancybox({
        beforeShow: function (e) {
            $(".fancybox-skin").css("backgroundColor", "transparent");
        },
        padding: 0,
        maxWidth: 720,
        scrolling: false,
        helpers: {
            overlay: {
            // locked: false
    }
  }


    });

});
