$(document).ready(function(){

//    $("body").on("click", ".exp_tip_submit", function(e){
//        e.preventDefault();
//        $(e.currentTarget).attr("disabled", "disabled");
//        var form = $(e.currentTarget).closest("form");
//        $.ajax({
//            type: "post",
//            url: $(form).attr("action"),
//            data: $(form).serialize(),
//            success: function(data){
//                if(data.status == 200){
//                    $(".content_wrapper").html('<img style="margin: auto; display: block;" src="'+UrlContainer.loader+'" />');
//                    $(".content_wrapper").load($(".content_wrapper").find(".moveExpTip").attr("href"), function(){
//                        history.pushState(null, null, $(".content_wrapper").find(".moveExpTip").attr("href"));
//                    });
//                }
//                else if(data.status == 500)
//                {
//                    $("body").find(".content_wrapper").html(data.view);
//                    ThraceForm.select2();
//                }
//            }
//        });
//    });

    $("body").find(".exp_tip_browse").dropzone({
        url: $(".exp_tip_browse").closest("form").attr("action"),
        paramName: $(".exp_tip_browse").attr("name"),
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

            $(myDropzone.element).on("click", function(e){
                if($(e.target).is("input"))
                {
                    
                }
                e.preventDefault();
                $(e.target).removeAttr("name");
            });

            this.on("queuecomplete", function(file) {
                alert("finished");
            });

            $("body").find(".exp_tip_submit").on("click", function(e){
                e.preventDefault();
                if (myDropzone.getQueuedFiles().length > 0) {
                    myDropzone.on("sending", function(file, xhr, formData) {
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
                            alert("done");
                        }
                    });
                }
            });
        }
    });

    

    $("body").on("click", ".moveExpTip", function(e){
        e.preventDefault();
        $(".content_wrapper").html('<img style="margin: auto; display: block;" src="'+UrlContainer.loader+'" />');
        $(".content_wrapper").load($(e.currentTarget).attr("href"));
        history.pushState(null, null, $(e.currentTarget).attr("href"));
    });
});