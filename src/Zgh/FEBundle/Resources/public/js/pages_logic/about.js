$(document).ready(function(){
    $("body").on("click", ".about_edit" ,function(e){
        e.preventDefault();
        var url = $(e.currentTarget).attr("href");
        $(e.currentTarget).html('<img style="margin: auto; display: block;" src='+UrlContainer.loader+' />');
        $(e.currentTarget).parents(".about_wrapper").load(url);
    });

    $("body").on("change", ".status_ddl", function(e){
        if($(e.target).val() == "Single"){
            $(e.target).siblings(".user_select").hide();
            return;
        }
        $(e.target).siblings(".user_select").show();
    });


    $("body").on("click", ".cancel_about_edit", function(e){
        e.preventDefault();
        $(e.currentTarget).html('<img style="margin: auto; display: block;" src="'+UrlContainer.loader+'" />');

        /* about_wrapper is the div right above the table tag */
        $(e.currentTarget).parents(".about_wrapper").load(UrlContainer.aboutPartial);
    });

    $("body").on("click", ".save_about", function(e){
        e.preventDefault();
        $(e.target).attr("disabled", "disabled");
        $(e.target).val("Saving");
        var form = $(e.target).closest("form");
        $.ajax({
            type: "POST",
            url: $(form).attr("action"),
            data: $(form).serialize(),

            success: function(data){
                if(data.status == 200){
                    $(e.target).parents(".about_wrapper").load(UrlContainer.aboutPartial);
                } else if(data.status == 500)
                {
                    $(e.target).parents(".about_wrapper").html(data.view);
                }
            }
        })
    })
});