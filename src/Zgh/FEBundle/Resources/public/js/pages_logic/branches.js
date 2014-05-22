$(document).ready(function(){
    $("body").on("click", ".branchSubmit", function(e){
        e.preventDefault();
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
    });
});