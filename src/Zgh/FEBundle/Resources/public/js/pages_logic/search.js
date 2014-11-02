$(document).ready(function () {

    var tab_hash = $(document.location).prop('hash');
    if(tab_hash != "#_=_"){
        var panel = $(tab_hash);
        if (panel.length > 0) {
            $(".resultWrapper").hide();
            $(tab_hash).show();
            var filtr_button = $(".filtr[data-id='" + tab_hash + "']");
            $(".filtr").removeClass('active-filtration');
            filtr_button.addClass("active-filtration");
        }
    }


    $("body").on("click", function (e) {
        $("body").find(".input-group-btn").hide();
        if ($(e.target).parents(".mainSearch").length == 1) {
//            $(e.target).closest(".mainSearch").find(".search_text").css('border-radius', '6px 0 0 6px');
            var ddl = $(e.target).closest(".mainSearch").find(".input-group-btn");
            ddl.show();
        }
    });


    $("body").on("click", ".filtr", function (e) {
        var wrapperid = $(e.currentTarget).data("id");
        var current_url = document.location;
        $(".filtr").removeClass("active-filtration");
        $(e.currentTarget).addClass("active-filtration");
        if (wrapperid == "all") {
            $(".resultWrapper").show();
            if(window.location.hash)
            {
                window.location.hash = "";
            }
        } else {
            $(".resultWrapper").hide();
            $(wrapperid).show();
                window.location.hash = wrapperid;
        }
    });

    $("body").on("keyup", ".search_text", function (e) {
        e.preventDefault();
        if (e.which == 13) {
            $("body").siblings(".search_submit").click();
        }
    });

    $("body").on("click", ".search_submit", function (e) {
        e.preventDefault();
        var form = $(e.target).closest("form");
        form.submit();
    });

    $("body").on("click", ".searchOption", function (e) {
        e.preventDefault();
        var cat = $(e.currentTarget).data("cat");
        $(e.currentTarget).closest(".cat_ddl_btn").find("button").text($(e.currentTarget).text());
        $(e.currentTarget).closest("form").find(".category_data[type='hidden']").val(cat);
    });

    $("body").on("click", ".doSearch", function (e) {
        e.preventDefault();
        startSearch(e.target);
    });

    $("body").on("click", ".searchOptionProd", function (e) {
        e.preventDefault();
        startSearch(e.target);
    });

    $("body").on("keyup", ".searchTextAjax", function (e) {
        e.preventDefault();
        var term = $.trim($(e.currentTarget).val());
        term = term.length == 0 ? "" : term;
        var prevent_keys = [27, 16, 20, 17, 18, 91, 39, 37, 38, 40, 16, 36, 35, 33, 34];
        if ($.inArray(e.which, prevent_keys) != -1) {
            return false;
        }
        console.log(term);
        startSearch(e.target);
    });

    function startSearch(target) {
        var form = $(target).closest("form");
        var url = form.attr("action");
        var result_wrapper = $(target).closest("#c_products").find("#products_list");
        console.log(result_wrapper.length);
        result_wrapper.html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
        $.ajax({
            type: "get",
            url: url,
            data: form.serialize(),
            success: function (data) {
                result_wrapper.html(data);
            }
        });
    }
});

