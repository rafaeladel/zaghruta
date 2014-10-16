////////// Vendor Profile Page //////////
// Tabs Jquery
// ======================
$('.menuSection').hide();
$('.menuLinks > a').bind('click',function (e) {
    $('.menuLinks a.active').removeClass('active');
    $('.menuSection:visible').hide();
    $(this.hash).show();
    $(this).addClass('active');
    e.preventDefault();
}).filter(':first').click();


// Tool tip
// ======================
$('.top').tooltip();
$(".top").tooltip({
    placement: "top",
    title: "Edit"
});

function fixingheight() {

    $('.mainContent').height(function (index, height) {
        return $("#theH").height() - $(this).offset().top;
    });
}
function SwitchView(param) {
    $('#switcher .search-Results .btn-group a').removeClass('active')
    if (param == 'list') {
        $('#c_products #switcher').removeClass('GridView').addClass('ListView');
        $('#c_products .product-G').removeClass('col-md-3 col-xs-6  col-sm-6');
        $('#c_products .product-G').addClass('col-md-12');
        $('#c_products .textImageContainer').addClass('col-md-3');
        $('#c_products .caption').addClass('col-md-9');
    }
    else {
        $('#c_products #switcher').removeClass('ListView').addClass('GridView');
        $('#c_products .product-G').removeClass('col-md-12');
        $('#c_products .textImageContainer').removeClass('col-md-3');
        $('#c_products .caption').removeClass('col-md-9');
        $('#c_products .product-G').addClass('col-md-3 col-xs-6 col-sm-6');
    }
}
function photoAlbumTabs(param) {
    $('#vendPhotos > .row .btn-group a').removeClass('active');
    $(this).addClass('active');
    $('#photos > div').hide();
    if (param == 'albums') {
        $('#photos > .albumCont').show();
        $('#addNewAlbum').show();
        $('#addNewPhoto').hide();
    } else if (param == 'photos') {
        $('#photos > .photosCont').show();
        $('#addNewPhoto').show();
        $('#addNewAlbum').hide();
    }
}

function connectionsTabs(param) {
    $('#vendConnec > .row  .btn-group a').removeClass('active')
    $(this).addClass('active')
    $('#connections > div').hide()
    if (param == 'followers') {

        $('#connections > .followerCont').show()
    } else if (param == 'following') {
        $('#connections > .followingCont').show()
    } else if (param == 'vendors') {
        $('#connections > .vendorsCont').show()
    }
}
//////////// End of Vendor Profile ///////////
$('#loginTabs a').click(function (e) {
    e.preventDefault()
    $(this).tab('show')
})

fixingheight();


$(window).resize(function () {
    fixingheight()

});

$(".contentInterests ul").addClass("col-md-12");
$(".contentInterests ul li").addClass("col-md-3");

$(document).ready(function () {
    //$("#intro_description").siblings().hide();

    $(".iconGroup a").mouseenter(function(){
        var secName = $(this).attr('class');
        $('#' + secName + '').siblings().hide();
        $('#' + secName + '').fadeIn(100);
    });
    $(".iconGroup a").mouseleave(function(){
        $(".active").show();
        $(".hide-show").hide();
        //$("#intro_description").show();
    });
   $(".modalClose").click(function(){
        $(".select2-drop").hide();
   });
   $("#myModal").click(function() {
        $(".select2-drop").hide();

   });

   //jquer media query retina  

   // Checking for Retina Devices
    // function isRetina() {
    // var query = '(-webkit-min-device-pixel-ratio: 2),\
    // (min--moz-device-pixel-ratio: 2),\
    // (-o-min-device-pixel-ratio: 2),\
    // (min-device-pixel-ratio: 2),\
    // (min-resolution: 192dpi),\
    // (min-resolution: 2dppx)';

    // if (window.devicePixelRatio > 1.5 || (window.matchMedia && window.matchMedia(query).matches)) {
    // return true;
    // }
    // return false;
    // }

    // if (isRetina) {
    //     $('.logoRetina img').attr('src','/zaghruta/web/bundles/zghfe/img/logox2.png');
    // }

});


