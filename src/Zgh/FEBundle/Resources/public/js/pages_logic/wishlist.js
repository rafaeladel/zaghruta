$(document).ready(function(){
    $("body").on("click", ".wishlistSubmit", function(e){
        e.preventDefault();
        var form = $(e.target).closest("form");
        $(e.target).attr("disabled","disabled").text("Saving");
        $.ajax({
            type: "POST",
            url: $(form).attr('action'),
            data: $(form).serialize(),
            success: function(){
                $(".modalClose").click();
                $(form).get(0).reset();
                $("#wishlists_list").load(UrlContainer.wishlistPartial);
                $(e.target).removeAttr("disabled").text("Create");
            }
        });
    });
});