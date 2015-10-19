$(document).ready(function () {
    $(".check-wc").click(function () {
        $.ajax({
            url: "api/handler.php",
            dataType: "json",
            data: {
                func: "get_page_wordcount",
                x: $(this).data('pageid'),
                y: $(this).data('wiki')
            }
        }).done(function(data) {
            $(this).replaceWith(data);
        }.bind(this));
    });
    $("#check-all-wc").click(function () {
        $(".check-wc").each(function () {
            if ($(this).data('status') === 'no')
                return true; // continue
            $(this).click();
        });
        $(this).hide();
    });
});
