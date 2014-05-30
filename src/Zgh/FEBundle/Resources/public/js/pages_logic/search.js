$(document).ready(function(){
    $("body").on("click", ".doSearch", function(e){
        e.preventDefault();
        startSearch(e.target);
    });

    $("body").on("keyup", ".searchText", function(e){
        e.preventDefault();
        var term = $(e.target).val();
//        if($.trim(term).length >= 3){
            startSearch(e.target);
//        }
    });

    $("body").find(".searchText").autocomplete({
       source: tstSrc
    });

    function startSearch(target)
    {
        var form = $(target).closest("form");
        var url = form.attr("action");
        var result_wrapper = $(target).closest("#c_products").find("#products_list");
        result_wrapper.html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
        $.ajax({
            type: "post",
            url: url,
            data: form.serialize(),
            success: function(data){
                result_wrapper.html(data);
            }
        });
    }
});