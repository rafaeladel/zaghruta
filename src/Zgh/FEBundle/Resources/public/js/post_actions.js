$(document).ready(function(){


    $("body").find('.tooltip').tooltip();

    $('body').find('.collapse').collapse();

    $('body').find('.animated').autosize();

    $("body").on("click", ".likeBtn", function(e){
        e.preventDefault();
        var btn = $(e.currentTarget);
        $(e.currentTarget).attr("disabled", "disabled");
        var form = $(e.currentTarget).closest("form");
        var count_wrapper = $(e.currentTarget).closest(".post, .photo, .experience, .tip").find(".likes_count");
        var old_count = count_wrapper.text();
        if($(e.currentTarget).find("span").hasClass("glyphicon-heart-empty")){
            count_wrapper.text(--old_count);
            $(e.currentTarget).attr('title', "Like")
                .tooltip('fixTitle')
                .tooltip('show');
        } else {
            count_wrapper.text(++old_count);
            $(e.currentTarget).attr('title', "Unlike")
                .tooltip('fixTitle')
                .tooltip('show');
        }
        $.ajax({
            type: "POST",
            url: $(form).attr("action"),
            success: function(data){
                $(e.currentTarget).find("span").toggleClass("glyphicon-heart-empty");
                $(e.currentTarget).removeAttr("disabled");
            }
        });
    });

    $("body").on("click", ".openLikes", function(e){
        e.preventDefault();
        var wrapper = $(e.target).closest(".post, .experience, .photo, .tip").find(".likes_wrapper");
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
        var old_count = $(e.target).closest(".post, .photo, .experience, .tip").find(".comments_count").text();
        if(content.length == 0){
            return;
        }
        $(e.target).closest(".post, .photo, .experience").find(".comments_count").text(++old_count);
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
        $(e.target).closest(".row").before(comment_markup);
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
//                $(e.target).closest(".post, .photo, .experience").find(".comments_count").text(data.comments_count);
                $(e.target).attr("disabled", "disabled");
            }
        });
        $(form).find('[name="comment_content"]').val("");
        $(form).find('[name="comment_content"]').trigger('autosize.resize');

    });

    $("body").on("click", ".comment-delete", function(e){
        e.preventDefault();
        var form = $(e.target).closest("form");
        var count = $(e.target).closest(".post, .photo, .experience, .tip").find(".comments_count");
        var old_count = $(e.target).closest(".post, .photo, .experience, .tip").find(".comments_count").text();
        count.text(--old_count);
        $.ajax({
            type: "POST",
            url: $(form).attr("action"),
            data: $(form).serialize()
        });
        $(e.target).closest("div.modal").modal("hide").on("hidden.bs.modal", function(){
            $(e.target).closest(".postComment").remove();
        });

    });

});