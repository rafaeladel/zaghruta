$(document).ready(function(){
    $("body").on("click", ".branchSubmit", function(e){
        $("body").off("click", ".branchSubmit");

        var btn = $(e.target);
        $("#myform").parsley().subscribe("parsley:form:validate", function(instance){
           instance.submitEvent.preventDefault();
           if(instance.isValid()){
               btn.attr("disabled", "disabled");
               var form = $(e.target).closest("form");
               $.ajax({
                   type: "post",
                   url: form.attr("action"),
                   data: form.serialize(),
                   success: function(data){
                       if(data.status == 200){
                           form[0].reset();
                           $("#addBranch").modal("hide");
                           btn.removeAttr("disabled");
                           $(".content_wrapper").find("#branches_list").load(UrlContainer.branchesList);
                       } else if(data.status == 500){
                           btn.removeAttr("disabled");
                           $(e.target).closest("#addBranch").find(".form_wrapper").html(data.view);
                       }
                   }
               });
           }
        });
    });

    $("body").on("click", ".branch_edit", function(e){
        e.preventDefault();
        var url = $(e.currentTarget).attr("href");
        $(e.currentTarget).html('<img style="margin: auto; display: inline;" src="'+UrlContainer.loader+'" />');
       $(e.currentTarget).closest(".branch").load(url);
    });

    $("body").on("click", ".save_edit", function(e){
        e.preventDefault();
        var form = $(e.currentTarget).closest("form");
        var url = form.attr("action");
        var back_url = $(e.currentTarget).siblings(".cancel_edit").attr("href");
        var list_wrapper = $(e.currentTarget).closest(".branch");
        $(e.currentTarget).html('<img style="margin: auto; display: inline;" src="'+UrlContainer.loader+'" />');
        $.ajax({
            type: "post",
            url: url,
            data: form.serialize(),
            success: function(data){
                if(data.status == 200){
                    list_wrapper.load(back_url);
                } else if(data.status == 500) {
                    list_wrapper.html(data.view);
                }
            }
        });
    });

    $("body").on("click", ".cancel_edit", function(e){
        e.preventDefault();
        var url = $(e.currentTarget).attr("href");
        $(e.currentTarget).html('<img style="margin: auto; display: inline;" src="'+UrlContainer.loader+'" />');
        $(e.currentTarget).closest(".branch").load(url, function(){
            $("body").find('.tooltip').tooltip();
        });
    });
});