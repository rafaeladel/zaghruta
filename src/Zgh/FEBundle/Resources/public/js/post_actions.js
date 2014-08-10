$(document).ready(function(){

    postRefresh();

    $(".post_form").validate({
        rules: {
            "post[content]": {
                required: function(){
                    return $('[name="post[image_file]"]').val()  == 0;
                }
            }
        },
        messages: {
            "post[content]": {
                required: "Post content cannot be empty"
            }
        },
        submitHandler: function(form){
            form.submit();
        }
    });

    $("body").on("click", ".doLike", function(e){
        e.preventDefault();
        var btn = $(e.currentTarget);
        btn.attr("disabled", "disabled");
        var form = btn.closest("form");
        var count_wrapper = btn.closest(".post, .photo, .experience, .tip, .product").find(".likes_count");
        var old_count = count_wrapper.text();
        if(btn.find("span").hasClass("liked")){
//            count_wrapper.text(--old_count);
//            btn.attr('title', "Like")
//                .tooltip('fixTitle')
//                .tooltip('show');
        } else {
//            count_wrapper.text(++old_count);
//            btn.attr('title', "Unlike")
//                .tooltip('fixTitle')
//                .tooltip('show');
        }
        $.ajax({
            type: "POST",
            url: $(form).attr("action"),
            success: function(data){
                count_wrapper.text(data.likes_count);
                if(data.like_state == 0){
                    btn.removeClass("btn-danger").addClass("likeBtn");
                    btn.attr('title', "Like")
                        .tooltip('fixTitle')
                        .tooltip('show');
                    btn.removeAttr("disabled");
                } else {
                    btn.addClass("btn-danger").removeClass("likeBtn");
                    btn.attr('title', "Unlike")
                        .tooltip('fixTitle')
                        .tooltip('show');
                    btn.removeAttr("disabled");
                }
            }
        });
    });

    $("body").on("click", ".openLikes", function(e){
        e.preventDefault();
        var wrapper = $(e.target).closest(".post, .experience, .photo, .tip, .product").find(".likes_wrapper");
        var id = $(e.currentTarget).data("entity_id");
        var entity_type = $(e.currentTarget).data("entity_type");
        var url = Routing.generate('zgh_fe.like.list', { id: id, entity_type: entity_type }, true);
        var loader = wrapper.data("loader");
        wrapper.html('<img style="margin: auto; display: block;" src='+loader+' />');
        wrapper.load(url);
    });

    $("body").on("keyup", "form [name='comment_content']",  function(e){
        if($(e.target).val() == 0){
            $(e.target).closest("form").find(".btn-comment").attr("disabled", "disabled");
        } else {
            $(e.target).closest("form").find(".btn-comment").removeAttr("disabled");
        }
    });


    $("body").on("click", ".btn-comment", function(e){
        e.preventDefault();
        var form = $(e.target).closest("form");
        var content = $(form).find('[name="comment_content"]').val();
        if(content.length == 0){
            return;
        }
        var uniqeID = Math.floor(Math.random() * 10000000000000001);
        var comment_markup = '<div class="row postComment margin-sm" id="'+ uniqeID +'">\
                                    <button data-target="#deleteComment_'+ uniqeID +'" data-toggle="modal" type="submit" class="row btn delete-post pull-right"><span class="glyphicon glyphicon-remove pull-right"></span></button>\
                                        <div id="deleteComment_'+uniqeID+'" aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal fade in">\
                                        <div class="modal-dialog">\
                                            <div class="modal-content">\
                                                <div class="modal-header modalHeader">\
                                                    <button type="button" class="close modalClose" data-dismiss="modal" aria-hidden="true">&times;</button>\
                                                    <h4 class="modal-title" id="myModalLabel">Delete Comment</h4>\
                                                </div>\
                                                <div class="modal-body">\
                                                    <p>Are you sure you want to delete this?</p>\
                                                </div>\
                                                <div class="modal-footer">\
                                                    <button type="button" class="btn btn-wide modalClose btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button>\
                                                    <form class="comment_delete_form btnForm" action="" method="post">\
                                                        <button type="submit" data-dismiss="modal"  aria-hidden="true" class="btn btn-wide btn-primary comment-delete">Delete</button>\
                                                    </form>\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                <div class="data-reacted  pull-left">\
                                        <div class="profile sm-img-post">\
                                        <a href="#" class="pull-left img-circle">\
                                        <img alt="" src="img/img-prf-comment2.jpg" class="img-responsive comment_author_pp media-object">\
                                        </a>\
                                </div>\
                                <a class="ZaghrutaTitle" href=""><strong class="comment_author"></strong></a>\
                            </div>\
                            <div class="content-post">\
                                    <p>'+ content +'</p>\
                            <span class="date-time comment_time"></span>\
                            <div class="clearfix"></div>\
                            </div>\
                            </div>';
        $(e.target).closest(".row").siblings(".comments_wrapper").append(comment_markup);
        $.ajax({
            type: "POST",
            url: $(form).attr("action"),
            data: $(form).serialize(),
            success: function(data){
                $("#"+uniqeID).find(".comment_delete_form").attr("action", data.deleteUrl).end()
                    .find(".comment_author").text(data.author).end()
                    .find(".comment_author_pp").attr("src",data.author_pp).end()
                    .find(".comment_author").parent().attr("href", data.author_url).end().end()
                    .find(".comment_time").text(data.time);
                $(e.target).attr("disabled", "disabled");
            }
        });
        $(form).find('[name="comment_content"]').val("");
        $(form).find('[name="comment_content"]').trigger('autosize.resize');

    });

    $("body").on("click", ".comment-delete", function(e){
        e.preventDefault();
        var form = $(e.target).closest("form");
        $.ajax({
            type: "POST",
            url: $(form).attr("action"),
            data: $(form).serialize()
        });
        $(e.target).closest("div.modal").modal("hide").on("hidden.bs.modal", function(){
            $(e.target).closest(".postComment").remove();
        });

    });

    $("body").on("focus", "*[name='comment_content']", function(e){
        var id = $(e.target).data("e_i");
        var entity_type = $(e.target).data("e_t");
        var url = Routing.generate("zgh_fe.comment.list", { id: id, entity_type: entity_type }, true);
        $(e.target).closest("form").parent().siblings(".comments_wrapper").load(url);
    });

});