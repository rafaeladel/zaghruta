function singleUploadProduct() {
    $("body").find(".product_browse").dropzone({
        url: $(".product_browse").closest("form").attr("action"),
        parallelUploads: 1,
        maxFiles: 1,
        maxFilesize: 5,
        thumbnailWidth: 300,
        thumbnailHeight: 300,
        acceptedFiles: "image/*",
        autoProcessQueue: false,
        previewsContainer: ".photo_preview",
        dictFileTooBig: "File is too big (max 5 MB)." ,
        previewTemplate: '<div class="dz-preview dz-file-preview">\
                            <div class="dz-details">\
                                <div class="dz-filename"><span data-dz-name></span></div>\
                                <div class="dz-size" data-dz-size></div>\
                                <img data-dz-thumbnail />\
                            </div>\
                            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>\
                            <a href="#" data-dz-remove>Delete</a>\
                            <div class="dz-error-message custom_dpz_error"><span data-dz-errormessage></span></div>\
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

            var form = $(".product_browse").closest("form");
            form.find("[type='submit']").on("click", function (e) {
                e.preventDefault();
                form.validate({
                    rules: {
                        "product[name]": {
                            required: true
                        },
                        "product[price]": {
                            number: true,
                            min: 1
                        }
                    },
                    messages:{
                        "product[name]": {
                            required: "Product name is required"
                        },
                        "product[price]": {
                            number: "Price must be a valid number",
                            min: "Price must be a positive number"
                        }
                    }
                });
                if(form.valid()) {
                    if (myDropzone.getQueuedFiles().length > 0) {
                        $(e.target).attr("disabled", "disabled");
                        myDropzone.on("sending", function (file, xhr, formData) {
                            var serialzedForm = $(e.target).closest("form").serializeArray();
                            $(serialzedForm).each(function(i, v){
                                formData.append(v.name, v.value);
                            });
                        });
                        myDropzone.processQueue();
                    } else {
                        $("body").find(".btnBrowsePhoto").after("<div class='dropzone_custom_error'><p style='font-weight: bold;'>Image is required</p></div>");
                    }
                }
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
                $("body").find(".dropzone_custom_error").remove();
                if (this.files[1]!=null){
                    this.removeFile(this.files[0]);
                }
                $(".exp_tip_browse").closest("form").find("[type='submit']").removeAttr("disabled", "disabled");
            });


            myDropzone.on("removedfile", function (file) {
                $(".product_browse").closest("form").find("[type='submit']").removeAttr("disabled");
            });

            myDropzone.on("error", function (file, msg) {
                $(".product_browse").closest("form").find("[type='submit']").attr("disabled", "disabled");
            });


        }
    });

    function refreshWrapper(data) {
        var submit_btn = $(".product_browse").closest("form").find("[type='submit']");
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
            singleUploadProduct();

            ThraceForm.select2();
        }
    }

}