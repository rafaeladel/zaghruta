$(document).ready(function(){
    $("body").on("click", ".about_edit" ,function(e){
        e.preventDefault();
        var url = $(e.currentTarget).attr("href");
        $(e.currentTarget).html('<img style="margin: auto; display: block;" src='+UrlContainer.loader+' />');
        $(e.currentTarget).parents(".about_wrapper").load(url);
    });

    $("body").on("change", ".status_ddl", function(e){
        if($(e.currentTarget).val() == "Single"){
            $(e.currentTarget).siblings(".user_select").hide();
            return;
        }
        $(e.currentTarget).siblings(".user_select").show();
    });


    $("body").on("click", ".cancel_about_edit", function(e){
        e.preventDefault();
        $(e.currentTarget).html('<img style="margin: auto; display: block;" src="'+UrlContainer.loader+'" />');

        /* about_wrapper is the div right above the table tag */
        $(e.currentTarget).parents(".about_wrapper").load(UrlContainer.aboutPartial);
    });

    $("body").on("click", ".save_about", function(e){
        e.preventDefault();
        var btn = $(e.currentTarget);
        btn.attr("disabled", "disabled");
        var form = $(e.currentTarget).closest("form");
        $.ajax({
            type: "POST",
            url: $(form).attr("action"),
            data: $(form).serialize(),

            success: function(data){
                if(data.status == 200){
//                    console.log($(e.target).closest(".about_wrapper").attr("class"));
                    $(e.target).closest(".about_wrapper").load(UrlContainer.aboutPartial);
                    btn.removeAttr("disabled");
                } else if(data.status == 500)
                {
                    $(e.target).closest(".about_wrapper").html(data.view);
                    btn.removeAttr("disabled");

                }
            }
        })
    })
});