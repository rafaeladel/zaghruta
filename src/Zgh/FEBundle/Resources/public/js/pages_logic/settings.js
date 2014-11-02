$(document).ready(function () {
    $(".change_password_form").validate({
        rules: {
            "fos_user_change_password_form[current_password]": {
                required: true
            },
            "fos_user_change_password_form[plainPassword][first]": {
                required: true,
                minlength: 6,
                pattern: /^\S*$/
            },
            "fos_user_change_password_form[plainPassword][second]": {
                required: true,
                equalTo: "[name='fos_user_change_password_form[plainPassword][first]']"
            }
        },
        messages: {
            "fos_user_change_password_form[current_password]": {
                required: "Password is required"
            },
            "fos_user_change_password_form[plainPassword][first]": {
                required: "Password is required",
                pattern: "Should not contain spaces"
            },
            "fos_user_change_password_form[plainPassword][second]": "Confirm password is required"
        },
        submitHandler: function(form){
           form.submit();
        }

    });

    $(".change_email_form").validate({
       rules: {
            "vendor_email_type[new_email]": {
                required: true,
                email: true
            },
           "vendor_email_type[current_password]": {
               required: true
           }
       },
       submitHandler: function (form) {
           var url = $(form).attr("action");
           $(form).find("[type='submit']").attr("disabled", "disabled");
           $.ajax({
               type: "post",
               url: url,
               data: $(form).serialize(),
               success: function(data) {
                   if(data.success) {
                       $(form).find(".result_wrapper").find("span").text(data.message);
                   } else {
                       $(form).find(".error_wrapper").find("span").text(data.message);
                   }
                   $(form).find("[type='submit']").removeAttr("disabled");
               }
           });
       }
    });




    $("body").on("click", ".privacyToggle", function (e) {
        $(".privacyToggle").attr("disabled", "disabled");
        var url = $(e.target).data("url");
        $.ajax({
            type: "get",
            url: url,
            success: function (data) {
                $(".privacyToggle").removeAttr("disabled");
            }
        })
    });

    $("body").on("click", ".emailNotification", function (e) {
        $(e.target).attr("disabled", "disabled");
        var url = $(e.target).data("url");
        $.ajax({
            type: "post",
            url: url,
            success: function (data) {
                $(e.target).removeAttr("disabled");
            }
        })
    });
});