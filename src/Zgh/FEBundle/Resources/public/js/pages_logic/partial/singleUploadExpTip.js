function singleUploadExpTip() {
    $("body").find(".exp_tip_browse").dropzone({
        url: $(".exp_tip_browse").closest("form").attr("action"),
        parallelUploads: 1,
        maxFiles: 1,
        maxFilesize: 2,
        thumbnailWidth: 300,
        thumbnailHeight: 300,
        acceptedFiles: "image/*",
        autoProcessQueue: false,
        previewsContainer: ".photo_preview",
        previewTemplate: '<div class="dz-preview dz-file-preview">\
                                <div class="dz-details">\
                                    <div class="dz-filename"><span data-dz-name></span></div>\
                                    <div class="dz-size" data-dz-size></div>\
                                    <img data-dz-thumbnail />\
                                </div>\
                                <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>\
                                <div class="dz-error-message"><span data-dz-errormessage></span></div>\
                                <a href="#" data-dz-remove>Delete</a>\
                            </div>\
                            ',
        init: function () {
            var myDropzone = this;

            //setting dropzone name for post request
            myDropzone.options.paramName = $(myDropzone.element).attr("name");

            //If the element is input file, make sure not to browse for file twice
            $(myDropzone.element).on("click", function (e) {
                if ($(e.target).is("input")) {
                    e.preventDefault();
                    $(e.target).removeAttr("name");
                }
            });

            $(".exp_tip_browse").closest("form").find("[type='submit']").on("click", function (e) {
                $("#myform").parsley().subscribe("parsley:form:validate", function (instance) {
                    instance.submitEvent.preventDefault();
                    if (instance.isValid()) {
                        $(e.target).attr("disabled", "disabled");
                        if (myDropzone.getQueuedFiles().length > 0) {
                            myDropzone.on("sending", function (file, xhr, formData) {
                                var serialzedForm = $(e.target).closest("form").serializeArray();
                                $(serialzedForm).each(function(i, v){
                                    formData.append(v.name, v.value);
                                });
                            });
                            myDropzone.processQueue();
                        } else {
                            var form_data = new FormData($(".exp_tip_browse").closest("form")[0]);
                            $.ajax({
                                type: "post",
                                url: $(".exp_tip_browse").closest("form").attr("action"),
                                data: form_data,
                                processData: false,
                                contentType: false,
                                success: function (data) {
                                    refreshWrapper(data);
                                }
                            });
                        }
                    } else {
                        $("#myform").parsley().unsubscribe("parsley:form:validate");
                    }
                });
            });

            if (myDropzone.options.uploadMultiple) {
                this.on("successmultiple", function (files, response) {
                    refreshWrapper(data);
                });
            } else {
                this.on("success", function (file, data) {
                    refreshWrapper(data);
                });
            }

            myDropzone.on("addedfile", function (file) {
                if (this.files[1]!=null){
                    this.removeFile(this.files[0]);
                }
            });

        }
    });

    function refreshWrapper(data) {
        var submit_btn = $(".exp_tip_browse").closest("form").find("[type='submit']");
        submit_btn.removeAttr("disabled");
        if (data.status == 200) {
            var back_url = submit_btn.data("back_url");
            $("body").find(".content_wrapper").html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
            $("body").find(".content_wrapper").load(back_url, function () {
                history.pushState(null, null, back_url);
            });
        }
        else if (data.status == 500) {
            $("body").find(".content_wrapper").html(data.view);

            //re-initializing dropzone plugin, because ajaxSuccess event is not triggered here
            singleUploadExpTip();

            ThraceForm.select2();
        }
    }

}