$(document).ready(function(){
    $("body").on("click", ".branchSubmit", function(e){
        e.preventDefault();
//        $("body").off("click", ".branchSubmit");

        var btn = $(e.currentTarget);
        var form = btn.closest("form");
        form.validate({
            rules: {
                "branch[address]": "required"
            },
            messages: {
                "branch[address]": "Address is required"
            }
        });
        if(form.valid()) {
            btn.attr("disabled", "disabled");
            $.ajax({
                type: "post",
                url: form.attr("action"),
                data: form.serialize(),
                success: function(data){
                    if(data.status == 200){
                        form.get(0).reset();
                        $("#addBranch").modal("hide");
                        btn.removeAttr("disabled");
                        $(".content_wrapper").find("#branches_list").load(UrlContainer.branchesList);
                        $(e.target).closest("#addBranch").find(".form_wrapper").html(data.view);
                    } else if(data.status == 500){
                        form.get(0).reset();
                        btn.removeAttr("disabled");
                        $(e.target).closest("#addBranch").find(".form_wrapper").html(data.view);
                    }
                }
            });
        }
    });

    $("body").on("click", ".branch_edit", function(e){
        e.preventDefault();
        var url = $(e.currentTarget).attr("href");
        $(e.currentTarget).html('<img style="margin: auto; display: inline;" src="'+UrlContainer.loader+'" />');
       $(e.currentTarget).closest(".branch").load(url);
        ThraceForm.select2();
    });

    $("body").on("click", ".save_edit", function(e){
        e.preventDefault();
        var btn = $(e.currentTarget);
        var form = btn.closest("form");
        var url = form.attr("action");
        var back_url = btn.siblings(".cancel_edit").attr("href");
        var list_wrapper = btn.closest(".branch");
        $(e.currentTarget).html('<img style="margin: auto; display: inline;" src="'+UrlContainer.loader+'" />');
        $.ajax({
            type: "post",
            url: url,
            data: form.serialize(),
            success: function(data){
                if(data.status == 200){
                    list_wrapper.load(back_url, function() {
                        form.get(0).reset();
                    });
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

    $("body").on("click", ".branch_delete", function(e) {
        e.preventDefault();
        var btn = $(e.target);
        btn.attr("disabled", "disabled").text("Deleting..");
        var modal = btn.closest(".modal");
        var form = $(e.target).closest("form");
        var url = $(form).attr("action");
        var sel_branch = $(e.target).closest(".branch");
        var branches_wrapper = $(e.target).closest("#branches_list");
        $.ajax({
            type: "POST",
            url: url,
            success: function (data) {
                if(data.success) {
                    modal.modal('hide');
                    modal.on('hidden.bs.modal', function (e) {
                        branches_wrapper.html(data.view);
                    });
                } else {

                }
            }
        });

    });
});