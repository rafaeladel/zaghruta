$(document).ready(function(){

    $("body").on("focus", ".mainSearch .search_text", function (e) {
        $(e.currentTarget).css('border-radius', '6px 0 0 6px');
        $(e.currentTarget).closest(".input-group").find(".input-group-btn").show();
    });

    $("body").on("click", function(e){
        if($(e.target).parents(".input-group-btn").length == 0) {
            $(".input-group-btn").hide();
        } else {
            $(e.target).closest(".input-group").find(".search_text").css('border-radius', '6px 0 0 6px');
        }
    });
//
//    $("body").on("blur", ".mainSearch .search_text", function (e) {
//        if ($(e.currentTarget).closest(".input-group").find(".input-group-btn").data("hovered")) {
//            $(e.currentTarget).css('border-radius', '0px 0 0 0px');
//        }
//        else {
//            $(e.currentTarget).closest(".input-group").find(".input-group-btn").hide();
//        }
//    });

    $("body").on("click",".filtr",function(e){
        var wrapperid=$(e.currentTarget).data("id");
        $(".filtr").removeClass("active-filtration");
        $(e.currentTarget).addClass("active-filtration");
        if(wrapperid=="all"){
            $(".resultWrapper").show();
        }else{
            $(".resultWrapper").hide();
            $(wrapperid).show();
        }
    });
//
//    $("body").on("keyup", ".search_text", function(e){
//        e.preventDefault();
//        var form = $(e.currentTarget).closest("form");
//        if(e.which == 13) {
//            form.submit();
//        }
//    });

    $("body").on("click", ".search_submit", function(e){
        e.preventDefault();
        var form = $(e.currentTarget).closest("form");
        form.submit();
    });

    $("body").on("click", ".searchOption", function(e){
        e.preventDefault();
        var cat = $(e.currentTarget).data("cat");
        $(e.currentTarget).closest(".cat_ddl_btn").find("button").text($(e.currentTarget).text());
        $(e.currentTarget).closest("form").find("input[type='hidden']").val(cat);
    });

    $("body").on("click", ".doSearch", function(e){
        e.preventDefault();
        startSearch(e.target);
    });

    $("body").on("click", ".searchOptionProd", function(e){
        e.preventDefault();
        startSearch(e.target);
    });

    $("body").on("keyup", ".searchTextAjax", function(e){
        e.preventDefault();
        var term = $.trim($(e.target).val());
        term = term.length == 0 ? "" : term;
        if(term == "" || term.length > 2) {
            var prevent_keys = [27,16,20,17,18,91,39,37,38,40,16,36,35,33,34];
            if($.inArray(e.which, prevent_keys) != -1) {
                return false;
            }
            startSearch(e.target);
        }
    });

    function startSearch(target)
    {
        var form = $(target).closest("form");
        var url = form.attr("action");
        var result_wrapper = $(target).closest("#c_products").find("#products_list");
        result_wrapper.html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
        $.ajax({
            type: "get",
            url: url,
            data: form.serialize(),
            success: function(data){
                result_wrapper.html(data);
            }
        });
    }
});

