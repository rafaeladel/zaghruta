function Uploader(params) {
    this.wrapperID = params.wrapperID;
    this.modalID = params.hasOwnProperty("listBtnID") ? params.modalID : null;
    this.form = $("#" + this.modalID).find("form");
    this.listBtnID = params.listBtnID;
    this.browseClass = params.browseClass;
    this.saveButtonClass = params.saveButtonClass;
    this.previewClass = params.previewClass;
    this.targetUrl = params.targetUrl;
    this.additionalData = params.additionalData;
    this.imageRequired = params.hasOwnProperty("imageRequired") ? params.imageRequired : true;
    this.numOfFiles = params.numOfFiles;

    this.listWrapperClass = $("#" + this.wrapperID + " #" + this.modalID).data("list_class") != undefined
        ? $("#" + this.wrapperID + " #" + this.modalID).data("list_class")
        : params.listWrapperClass;
    this.ajaxLoadUrl = $("#" + this.wrapperID + " #" + this.modalID).data("ajax_url") != undefined
        ? $("#" + this.wrapperID + " #" + this.modalID).data("ajax_url")
        : params.ajaxLoadUrl;

    this.init = function () {
        var _this = this;

        var album_name = this.form.find("input[name='album_name']");
        $(album_name).on("keydown", function (e) {
            $("." + _this.saveButtonClass).removeAttr("disabled");
        });

        $("body").find("." + this.browseClass).dropzone({
            init: function () {
                var myDropzone = this;
                this.on("success", function (file, data) {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                        refresh_uploader(data, _this.modalID, _this.listBtnID, _this.listWrapperClass, _this.ajaxLoadUrl, _this.saveButtonClass);
                    }
                });

//                $("body").off("click", "."+_this.saveButtonClass, false);
                $("." + _this.saveButtonClass).on("click", function (e) {
                    e.preventDefault();
                    var btn = $(e.target);
                    btn.attr("disabled", "disabled");
                    if (!validate(_this.additionalData, $(e.target).closest("form"))) {
                        btn.removeAttr("disabled");
                        return false;
                    } else {
                        var result;
                        isUnique(_this.form).done(function (data) {
                            if (data.already_exists) {
                                album_name.after("<p style='color: red!important; font-size: 11px!important;'" +
                                " class='error'>Album already exists</p>");
                                //alert("Tet");
                                result = false;
                            } else {
                                if (myDropzone.getQueuedFiles().length > 0) {
                                    myDropzone.on("sending", function (file, xhr, formData) {
                                        $.each(_this.additionalData, function (index, name) {
                                            formData.append("" + index + "", $(e.target).closest("form").find("[name='" + index + "']").val());
                                        });

                                        formData.append("caption", $(file.previewElement.children[1]).val());
                                    });
                                    myDropzone.processQueue();
                                } else {
                                    if (_this.imageRequired == false) {
                                        var form_data = new FormData(_this.form.get(0));
                                        $.ajax({
                                            type: "POST",
                                            url: _this.targetUrl,
                                            data: form_data,
                                            processData: false,
                                            contentType: false,
                                            success: function (data) {
                                                refresh_uploader(data, _this.modalID, _this.listBtnID, _this.listWrapperClass, _this.ajaxLoadUrl, _this.saveButtonClass);
                                            }
                                        });
                                    } else {
                                        btn.removeAttr("disabled");
                                    }
                                }
                            }
                        });
                    }
                });
                myDropzone.on("addedfile", function (file) {
                    if (this.files.length > _this.numOfFiles) {
                        this.removeFile(file);
                    } else {
                        $("#" + _this.modalID).find(".general_errors").hide();
                    }
                    $("#" + _this.modalID).find("." + _this.saveButtonClass).removeAttr("disabled");
                });

                myDropzone.on("removedfile", function (file) {
                    var escape = false;
                    $(myDropzone.files).each(function (i, v) {
                        if (v.accepted == false) {
                            escape = true;
                            return;
                        }
                    });
                    if (!escape) {
                        $("#" + _this.modalID).find("." + _this.saveButtonClass).removeAttr("disabled");
                    }
                });


                myDropzone.on("maxfilesexceeded", function (file, msg) {
                    $("#" + _this.modalID).find(".general_errors").show().text("You cannot upload more than " + _this.numOfFiles + " photos at a time.");

                    $("#" + _this.modalID).find("." + _this.saveButtonClass).removeAttr("disabled");
                });

                myDropzone.on("error", function (file, msg) {
                    // alert(msg);
                    $("#" + _this.modalID).find("." + _this.saveButtonClass).attr("disabled", "disabled");
                });

            },
            url: _this.targetUrl,
            parallelUploads: _this.numOfFiles,
            maxFiles: _this.numOfFiles,
            maxFilesize: 5,
            dictFileTooBig: "File is too big (max 5 MB).",
            dictResponseError: "Error occurred during upload, please try again",
            thumbnailWidth: 300,
            thumbnailHeight: 300,
            acceptedFiles: "image/*",
            autoProcessQueue: false,
            previewsContainer: "." + _this.previewClass,
            previewTemplate: '<div class="dz-preview dz-file-preview">\
                                <div class="dz-details">\
                                    <div class="dz-filename"><span data-dz-name></span></div>\
                                    <div class="dz-size" data-dz-size></div>\
                                    <div class="dz-photo"><img data-dz-thumbnail /></div>\
                                </div>\
                                <input type="text" class="form-control" placeholder="caption" name="caption" />\
                                <a class="btn-dz-remove" href="#" data-dz-remove>Delete</a>\
                                <div class="dz-error-message custom_dpz_error"><span data-dz-errormessage></span></div>\
                                <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>\
                              </div>\
                            '
        });

    }
}

function refresh_uploader(response, wrapper, list_btn_id, list_wrapper_class, ajax_url, saveBtnClass) {
    var w = $("#" + wrapper);
    if (response.success == false) {
        w.find(".error").text(response.message);
    } else {
        w.modal("hide");
        window.location = response.url;
    }
    var saveBtn = $("." + saveBtnClass);
    saveBtn.removeAttr("disabled");

}

function validate(data, form) {
    form.find(".error").empty();
    var is_valid = true;
    $.each(data, function (index, name) {
        var element = form.find("[name='" + index + "']");
        var required = name.required;
        if (required) {
            if (element.val().trim().length == 0) {
                var message = name.message;
                element.after("<p style='color: red!important; font-size: 11px!important;' class='error'>" + message + "</p>");
                is_valid = false;
            }
        }
    });


    return is_valid;
}

function isUnique(form) {
    var album_name = form.find("input[name='album_name']");
    return $.ajax({
        type: "GET",
        url: album_name.data("isunique"),
        data: {
            "album_name": album_name.val()
        }
    });
}
