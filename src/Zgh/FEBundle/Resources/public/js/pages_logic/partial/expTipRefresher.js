function expTipRefresh(){
    $("body").find(".exp_tip_browse").dropzone({
        url: $(".exp_tip_browse").closest("form").attr("action"),
        parallelUploads: 1,
        maxFiles: 1,
        thumbnailWidth: 150,
        thumbnailHeight: 150,
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
        init: function(){
            var myDropzone = this;

            //setting dropzone name for post request
            myDropzone.options.paramName = $(myDropzone.element).attr("name");

            //If the element is input file, make sure not to browse for file twice
            $(myDropzone.element).on("click", function(e){
                if($(e.target).is("input"))
                {
                    e.preventDefault();
                    $(e.target).removeAttr("name");
                }
            });



            $("body").find(".exp_tip_submit").on("click", function(e){
                $("#myform").parsley().subscribe("parsley:form:validate", function(instance){
                    instance.submitEvent.preventDefault();
                    if(instance.isValid())
                    {
                        $(e.target).attr("disabled", "disabled").text("Saving");
                        if (myDropzone.getQueuedFiles().length > 0) {
                            myDropzone.on("sending", function(file, xhr, formData) {

                                //store every tag with name attribute into the FormData object
                                $(e.target).closest("form").find("[name]").each(function(i, v){
                                    formData.append($(v).attr("name"), $(v).val());
                                });
                            });
                            myDropzone.processQueue();
                        } else {
                            var form_data = new FormData($(e.target).closest("form").get(0));
                            $.ajax({
                                type: "POST",
                                url: $(e.target).closest("form").attr("action"),
                                data: form_data,
                                processData: false,
                                contentType: false,
                                success: function(data){
                                    refreshWrapper(data);
                                }
                            });
                        }
                    }
                });
            });

            if(myDropzone.options.uploadMultiple){
                this.on("successmultiple", function(files, response){
                    refreshWrapper(data);
                });
            } else {
                this.on("success", function(file, data){
                    refreshWrapper(data);
                });
            }

        }
    });

    function refreshWrapper(data){
        $("body").find(".exp_tip_submit").removeAttr("disabled").text("Save");
        if(data.status == 200){
            var back_url = $(".content_wrapper").find(".moveExpTip").attr("href");
            $(".content_wrapper").html('<img style="margin: auto; display: block;" src="'+UrlContainer.loader+'" />');
            $(".content_wrapper").load(back_url , function(){
                history.pushState(null, null, $(".content_wrapper").find(".moveExpTip").attr("href"));
            });
        }
        else if(data.status == 500)
        {
            $("body").find(".content_wrapper").html(data.view);

            //re-initializing dropzone plugin, becaus ajaxSuccess event is not triggered here
            expTipRefresh();

            ThraceForm.select2();
        }
    }

}