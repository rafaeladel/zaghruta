function Uploader(params)
{
    this.wrapperID = params.wrapperID;
    this.modalID = params.hasOwnProperty("listBtnID") ? params.modalID : null;
    this.form = $("#"+this.modalID).find("form");
    this.listBtnID = params.listBtnID;
    this.browseClass = params.browseClass;
    this.saveButtonClass= params.saveButtonClass;
    this.previewClass = params.previewClass;
    this.targetUrl = params.targetUrl;
    this.additionalData = params.additionalData;
    this.imageRequired = params.hasOwnProperty("imageRequired") ? params.imageRequired : true;
    this.numOfFiles = params.numOfFiles;

    this.listWrapperClass = $("#"+this.wrapperID+" #"+this.modalID).data("list_class") != undefined
                                    ? $("#"+this.wrapperID+" #"+this.modalID).data("list_class")
                                    : params.listWrapperClass;
    this.ajaxLoadUrl = $("#"+this.wrapperID+" #"+this.modalID).data("ajax_url") != undefined
                                    ? $("#"+this.wrapperID+" #"+this.modalID).data("ajax_url")
                                    : params.ajaxLoadUrl;

    this.init = function(){
        var _this = this;
        $("body").find("."+this.browseClass).dropzone({
            init: function(){
                var myDropzone = this;
                this.on("queuecomplete", function(file) {
                    refresh_uploader(_this.modalID, _this.listBtnID, _this.listWrapperClass, _this.ajaxLoadUrl);
                });

//                $("body").off("click", "."+_this.saveButtonClass, false);
                $("."+_this.saveButtonClass).on("click", function(e){
                    e.preventDefault();

                    //Validating inputs
                    if(!validate(_this.additionalData, $(e.target).closest("form"))) return false;

                    if (myDropzone.getQueuedFiles().length > 0) {
                        myDropzone.on("sending", function(file, xhr, formData) {
                            $.each(_this.additionalData, function(index, name){
                                formData.append(""+index+"", $(e.target).closest("form").find("[name='"+index+"']").val());
                            });

                            formData.append("caption", $(file.previewElement.children[1]).val());
                        });
                        myDropzone.processQueue();
                    } else {
                        if(_this.imageRequired == false){
                            var form_data = new FormData(_this.form.get(0));
                            $.ajax({
                                type: "POST",
                                url: _this.targetUrl,
                                data: form_data,
                                processData: false,
                                contentType: false,
                                success: function(data){
                                    refresh_uploader(_this.modalID, _this.listBtnID, _this.listWrapperClass, _this.ajaxLoadUrl);
                                }
                            });
                        }
                    }
                });
            },
            url: _this.targetUrl,
            parallelUploads: _this.numOfFiles,
            maxFiles: _this.numOfFiles,
            thumbnailWidth: 150,
            thumbnailHeight: 150,
            acceptedFiles: "image/*",
            autoProcessQueue: false,
            previewsContainer: "."+_this.previewClass,
            previewTemplate: '<div class="dz-preview dz-file-preview">\
                                <div class="dz-details">\
                                    <div class="dz-filename"><span data-dz-name></span></div>\
                                    <div class="dz-size" data-dz-size></div>\
                                    <img data-dz-thumbnail />\
                                </div>\
                                <input type="text" class="form-control" placeholder="caption" name="caption" />\
                                <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>\
                                <div class="dz-error-message"><span data-dz-errormessage></span></div>\
                                <a href="#" data-dz-remove>Delete</a>\
                            </div>\
                            '
        });
    }
}

function refresh_uploader(wrapper, list_btn_id, list_wrapper_class, ajax_url)
{
    var w = $("#"+wrapper);
    w.hide();
    w.find(".modalReset").click();
    w.find(".modalClose").click();
    $("."+list_wrapper_class).load(ajax_url, null, function(){
        $("#"+list_btn_id).parent().find("a").each(function(){
            $(this).removeClass("active");
        });
        $("#"+list_btn_id).addClass("active");

    });
}

function validate(data, form)
{
    form.find(".error").remove();
    var is_valid = true;
    $.each(data, function(index, name){
        var element = form.find("[name='"+index+"']");
        var required = name.required;
        if(required){
            if(element.val().length == 0){
                var message = name.message;
                element.after("<p style='color: red!important; font-size: 11px!important;' class='error'>"+message+"</p>");
                is_valid = false;
            }
        }
    });

    return is_valid;
}
