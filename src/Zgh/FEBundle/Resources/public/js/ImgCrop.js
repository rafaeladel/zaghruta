function ImgCrop(param)
{
    this.height = "height" in param ? param.height : 0;
    this.width = "width" in param ? param.width : 0;
    this.image_class = param.image_class;
    this.x = 0;
    this.y = 0;
    this.temp_height = 0;
    this.temp_width = 0;
    this.wrapper = param.wrapper_class;
    this.form = $("."+param.form_class+"");
    this.original_post_url = this.form.attr('action');
    this.query_string = null;
    this.post_url = null;
    this.crop = param.crop;
    this.upload_btn = $("."+param.input_class+"").clone();
    this.file_size = "file_size" in param ? param.file_size : null;
    //There is also autocrop option.. for creating crop box on init

    this.init = function()
    {
        $("."+ param.input_class).css("display", "block");
        var _this = this;
        _this.form.on("change", "."+param.input_class, function(e){
            readURL(this, e.target);
        });

        function readURL(input, trigger)
        {
            if(input.files && input.files[0])
            {
                var myImg = $(trigger).parentsUntil("."+_this.wrapper).find("."+_this.image_class).find("img");
                var file_invalid = false;
                var invalid_msg = [];
                var size = input.files[0].size/1048576;
                var type = input.files[0].type;
                var allowed = ["image/png", "image/jpeg", "image/gif"];

                if(_this.file_size != null)
                {
                    if(size > _this.file_size)
                    {
                        file_invalid = true;
                        invalid_msg.push("File is too large ("+_this.file_size+" MB max).");
                    }
                }

                if($.inArray(type, allowed) == -1)
                {
                    file_invalid = true;
                    invalid_msg.push("Only images are allowed (png, jpeg or gif)");
                }


                if(file_invalid == true)
                {
                    $errors = invalid_msg.join("<br>");
                    $(_this.form).find(".errors").show().html($errors);
                    $(_this.form).find(".cover_thumb").hide();
                    $(trigger).replaceWith(_this.upload_btn);
                    myImg.imgAreaSelect({
                        disable: true,
                        hide: true
                    });
                    myImg.css("cursor", "default");
                    $(myImg).attr("src", "http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image");
                    return;
                }
                else
                {
                    myImg.imgAreaSelect({
                        disable: false,
                        hide: false
                    });
                    $(_this.form).find(".cover_thumb").show();
                    $(_this.form).find(".errors").hide().html("");
                }

                var reader = new FileReader();
                reader.readAsDataURL(input.files[0]);


                reader.onload = function(e)
                {
                    myImg.attr('src', e.target.result);

//                    _this.query_string = "?temp_height="+_this.temp_height+"&temp_width="+_this.temp_width+"&height="+_this.height+"&width="+_this.width+"&x="+_this.x+"&y="+_this.y;
//                    var post_url = _this.original_post_url + _this.query_string;
//                    _this.form.attr("action", post_url);

//                    var hasCrop = false;
//
//                    var img = new Image();
//                    img.onload = function(e){
//                        console.log("onload called")
//                        if(img.height > _this.height || img.weight > _this.width)
//                        {
//                            console.log("te")
//                            hasCrop = true;
//                        }
//                    }
//                    img.src = reader.result;
//
//                    console.log(hasCrop)
//                    if(!hasCrop) return;


                    if(!_this.crop)
                    {
                        return;
                    }
                    myImg.css("cursor", "crosshair");

                    var cropper = $(trigger).parentsUntil("."+_this.wrapper).find("."+_this.image_class).find("img").imgAreaSelect({
                        minHeight: _this.height,
                        minWidth: _this.width,
                        maxHeight: _this.height,
                        maxWidth: _this.width,
                        zIndex: 1000000000001,
                        parent: "#testtt",
                        instance: true,
                        onSelectEnd: function(img, selection){

                            height = selection.height;
                            width = selection.width;
                            x = selection.x1;
                            y = selection.y1;
                            temp_height = $(myImg).height();
                            temp_width = $(myImg).width();

                            _this.query_string = "?temp_height="+temp_height+"&temp_width="+temp_width+"&height="+height+"&width="+width+"&x="+x+"&y="+y;
                            post_url = _this.original_post_url + _this.query_string;
                            _this.form.attr("action", post_url);
                        },
                        onInit: function(img, selection)
                        {
                            if("autocrop" in param)
                            {
                                if(param.autocrop == true)
                                {
                                    cropper.setSelection(0, 0, _this.width, _this.height, true);
                                    cropper.setOptions({show:true});
                                    cropper.update();

                                    height = _this.height;
                                    width = _this.width;
                                    x = selection.x1;
                                    y = selection.y1;
                                    temp_height = $(myImg).height();
                                    temp_width = $(myImg).width();

                                    _this.query_string = "?temp_height="+temp_height+"&temp_width="+temp_width+"&height="+height+"&width="+width+"&x="+x+"&y="+y;
                                    post_url = _this.original_post_url + _this.query_string;
                                    _this.form.attr("action", post_url);
                                }
                            }

                        }
                    });

                }
            }
        }
    }
}