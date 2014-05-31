$(document).ready(function () {
    $("body").on("click", ".moveProduct", function (e) {
        e.preventDefault();
        $(".content_wrapper").html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
        $(".content_wrapper").load($(e.currentTarget).attr("href"));
        history.pushState(null, null, $(e.currentTarget).attr("href"));
    });

    $("body").on("click", ".moveEditProduct", function (e) {
        e.preventDefault();
        var url = $(e.currentTarget).attr("href");
        $(".content_wrapper").html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
        $("body").find(".content_wrapper").load(url, function () {
            history.pushState(null, null, url);
            ThraceForm.select2();
        });
    });

    $("body").on("click", ".product_edit_submit", function (e) {
        var form = $(e.target).closest("form");
        $(e.currentTarget).attr("disabled", "disabled").text("Saving");
        $("#myform").parsley().subscribe("parsley:form:validate", function (instance) {
            instance.submitEvent.preventDefault();
            if (instance.isValid()) {
                $.ajax({
                    type: "post",
                    url: form.attr("action"),
                    data: form.serialize(),
                    success: function (data) {
                        if (data.status == 200) {
                            var back_url = $(e.currentTarget).data("back_url");
                            $(".content_wrapper").html('<img style="margin: auto; display: block;" src="' + UrlContainer.loader + '" />');
                            $("body").find(".content_wrapper").load(back_url);
                        } else if (data.status == 500) {
                            $(".content_wrapper").html(data.view);

                        }
                    }
                });
            } else {
                $(e.currentTarget).removeAttr("disabled").text("Save");
            }
        });
    });

    function split( val ) {
        return val.split( /,\s*/ );
    }
    function extractLast( term ) {
        return split( term ).pop();
    }

//    $("body").find(".tags_input")
//        // don't navigate away from the field on tab when selecting an item
//        .bind( "keydown", function( event ) {
//            if ( event.keyCode === $.ui.keyCode.TAB &&
//                $( this ).data( "ui-autocomplete" ).menu.active ) {
//                event.preventDefault();
//            }
//        })
//        .autocomplete({
//            minLength: 0,
//            source: function( request, response ) {
//                // delegate back to autocomplete, but extract the last term
//                response( $.ui.autocomplete.filter(
//                    ["rafael", "adel", "nadia", "nardeen"], extractLast( request.term ) ) );
//            },
////            response: function(event, ui){
////                console.log(event);
////            },
//            focus: function() {
//                // prevent value inserted on focus
//                return false;
//            },
//            select: function( event, ui ) {
//                var terms = split( this.value );
//                // remove the current input
//                terms.pop();
//                // add the selected item
//                terms.push( ui.item.value );
//                // add placeholder to get the comma-and-space at the end
//                terms.push( "" );
//                this.value = terms.join( ", " );
//                return false;
//            }
//        });

    singleUpload("product_browse");
});