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
                required: "New Password is required",
                pattern: "Should not contain spaces"
            },
            "fos_user_change_password_form[plainPassword][second]": {
                required: "Confirm password is required",
                equalTo: "The password and confirm password don't match"
            }
        },
        submitHandler: function (form) {
            var url = $(form).attr("action");
            $(form).find("[type='submit']").attr("disabled", "disabled");
            $.ajax({
                type: "post",
                url: url,
                data: $(form).serialize(),
                success: function (data) {
                    $(form).closest(".change_password_wrapper").find(".msg_wrapper").find("div").hide();
                    if (data.success) {
                        $(form).closest(".change_password_wrapper").find(".result_wrapper").show().find("span").text(data.message);
                        form.reset();
                    } else {
                        $(form).closest(".change_password_wrapper").find(".error_wrapper").show().find("span").text(data.message);
                    }
                    $(form).find("[type='submit']").removeAttr("disabled");
                }
            });
        }

    });


    $(".change_info_form").validate({
        rules: {
            "firstname": {
                required: true,
                pattern: /[a-zA-Z]+/
            },
            "lastname": {
                required: true,
                pattern: /[a-zA-Z]+/
            }
        },
        submitHandler: function (form) {
            var url = $(form).attr("action");
            $(form).find("[type='submit']").attr("disabled", "disabled");
            $.ajax({
                type: "post",
                url: url,
                data: $(form).serialize(),
                success: function (data) {
                    $(form).closest(".msg_wrapper").find("div").hide();
                    if (data.success) {
                        $(form).find(".result_wrapper").show().find("span").text(data.message);
                    } else {
                        $(form).find(".error_wrapper").show().find("span").text(data.message);
                    }
                    $(form).find("[type='submit']").removeAttr("disabled");
                }
            });
        }
    });


    $(".change_email_form").validate({
        rules: {
            "vendor_email_type[new_email]": {
                required: true,
                pattern: /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            },
            "vendor_email_type[current_password]": {
                required: true
            }
        },
        messages: {
            "vendor_email_type[new_email]": {
                pattern: "Invalid email"
            }
        },
        submitHandler: function (form) {
            var url = $(form).attr("action");
            $(form).find("[type='submit']").attr("disabled", "disabled");
            $.ajax({
                type: "post",
                url: url,
                data: $(form).serialize(),
                success: function (data) {
                    $(form).find(".msg_wrapper div").hide();
                    if (data.success) {
                        $(form).find(".result_wrapper").show().find("span").text(data.message);
                    } else {
                        $(form).find(".error_wrapper").show().find("span").text(data.message);
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