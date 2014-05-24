$(document).ready(function(){
    $("body").on("click", ".moveExpTip", function(e){
        e.preventDefault();
        $(".content_wrapper").html('<img style="margin: auto; display: block;" src="'+UrlContainer.loader+'" />');
        $(".content_wrapper").load($(e.currentTarget).attr("href"));
        history.pushState(null, null, $(e.currentTarget).attr("href"));
    });

    expTipRefresh();
});