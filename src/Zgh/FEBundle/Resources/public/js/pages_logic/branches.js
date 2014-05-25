$(document).ready(function(){
    $("body").on("click", ".branchSubmit", function(e){
        $("#myform").parsley().subscribe("parsley:form:validate", function(instance){
           instance.submitEvent.preventDefault();
           if(instance.isValid()){
               $(e.currentTarget).attr("disabled", "disabled").text("Saving");
               var form = $(e.currentTarget).closest("form");
               $.ajax({
                   type: "post",
                   url: form.attr("action"),
                   data: form.serialize(),
                   success: function(data){
                       if(data.status == 200){
                           form[0].reset();
                           $("#addBranch").modal("hide");
                           $(".content_wrapper").find("#branches_list").load(UrlContainer.branchesList);
                       } else if(data.status == 500){
                           $(e.currentTarget).closest("#addBranch").find(".form_wrapper").html(data.view);
                           $(e.currentTarget).removeAttr("disabled").text("Create");
                       }
                   }
               });
           }
        });
    });

    $("body").on("click", ".branch_edit", function(e){
        e.preventDefault();
        $(e.currentTarget).html('<img style="margin: auto; display: inline;" src="'+UrlContainer.loader+'" />');
        var url = $(e.currentTarget).attr("href");
       $(e.currentTarget).closest(".branch").load(url);
    });

    $("body").on("click", ".save_edit", function(e){
        e.preventDefault();
        var form = $(e.currentTarget).closest("form");
        var url = form.attr("action");
        $(e.currentTarget).html('<img style="margin: auto; display: inline;" src="'+UrlContainer.loader+'" />');
        $.ajax({
            type: "post",
            url: url,
            data: form.serialize(),
            success: function(data){
                if(data.status == 200){
                    var list_url = $(e.currentTarget).siblings(".cancel_edit").attr("href");
                    $(e.currentTarget).closest(".branch").load(list_url);
                } else if(data.status == 500) {
                    $(e.currentTarget).closest(".branch").html(data.view);
                }
            }
        });
    });

    $("body").on("click", ".cancel_edit", function(e){
        e.preventDefault();
        var url = $(e.currentTarget).attr("href");
        $(e.currentTarget).html('<img style="margin: auto; display: inline;" src="'+UrlContainer.loader+'" />');
        $(e.currentTarget).closest(".branch").load(url);
    });
});