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

function fixingheight(){

    $('.mainContent').height(function(index, height) {
        return $("#theH").height() - $(this).offset().top;
    });
}
function SwitchView(param) {
    $('#switcher .search-Results .btn-group a').removeClass('active')

    if (param == 'list') {
        $('#vendProducts #switcher').removeClass('GridView').addClass('ListView');
        $('#vendProducts .product-G').removeClass('col-md-3 col-xs-6  col-sm-6');
        $('#vendProducts .product-G').addClass('col-md-12');
        $('#vendProducts .textImageContainer').addClass('col-md-3');
        $('#vendProducts .caption').addClass('col-md-9');
    }
    else {
        $('#vendProducts #switcher').removeClass('ListView').addClass('GridView');
        $('#vendProducts .product-G').removeClass('col-md-12');
        $('#vendProducts .textImageContainer').removeClass('col-md-3');
        $('#vendProducts .caption').removeClass('col-md-9');
        $('#vendProducts .product-G').addClass('col-md-3 col-xs-6 col-sm-6');
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


$( window ).resize(function() {
    fixingheight()

});

    $(".input-group-btn").hover(function() {
    $(this).data("hovered", true);
}, function() {
    $(this).data("hovered", false);
});


$( ".mainSearch input" ).focus(function() {
    $(this).css('border-radius', '6px 0 0 6px') ;
	$(".mainSearch .input-group-btn" ).show();
}).blur(function(){
	if($('.input-group-btn').data("hovered") ) {
        $(".mainSearch input" ).css('border-radius', '0px 0 0 0px') ;
	}
	else{
      $( ".input-group-btn" ).hide();}
  });

$( ".mainSearch2 input" ).focus(function() {
    $(this).css('border-radius', '6px 0 0 6px') ;
    $(".mainSearch2 .input-group-btn" ).show();
}).blur(function(){
    if($('.input-group-btn').data("hovered") ) {
        $(".mainSearch2 input" ).css('border-radius', '0px 0 0 0px') ;
    }
    else{
        $( ".input-group-btn" ).hide();}
});




