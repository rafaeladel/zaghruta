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
           } else {
               $("#myform").parsley().unsubscribe("parsley:form:validate");
           }
        });
    });

    $("body").on("click", ".branch_edit", function(e){
        e.preventDefault();
        $(e.target).html('<img style="margin: auto; display: inline;" src="'+UrlContainer.loader+'" />');
        var url = $(e.target).attr("href");
       $(e.target).closest(".branch").load(url);
    });

    $("body").on("click", ".save_edit", function(e){
        e.preventDefault();
        var form = $(e.target).closest("form");
        var url = form.attr("action");
        $(e.target).html('<img style="margin: auto; display: inline;" src="'+UrlContainer.loader+'" />');
        $.ajax({
            type: "post",
            url: url,
            data: form.serialize(),
            success: function(data){
                if(data.status == 200){
                    var list_url = $(e.target).siblings(".cancel_edit").attr("href");
                    $(e.target).closest(".branch").load(list_url);
                } else if(data.status == 500) {
                    $(e.target).closest(".branch").html(data.view);
                }
            }
        });
    });

    $("body").on("click", ".cancel_edit", function(e){
        e.preventDefault();
        var url = $(e.target).attr("href");
        $(e.target).html('<img style="margin: auto; display: inline;" src="'+UrlContainer.loader+'" />');
        $(e.target).closest(".branch").load(url, function(){
            $("body").find('.tooltip').tooltip();
        });
    });
});