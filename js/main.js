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
            // if ($(this).data('status') === 'no')
            //     return true; // continue
            $(this).click();
        });
        $(this).hide();
    });
    $(".judge-article").click(function () {
        $("#status").text("Submiting...");
        $(".judge-article").attr("disabled", "disabled");
        $.ajax({
            url: "api/handler.php",
            dataType: "json",
            method: "post",
            data: {
                func: "do_judgement",
                v: $(this).data('verdict'),
                x: $(this).data('pageTitle'),
                y: $(this).data('username'),
                k: $("#remarks").val(),
                z: $(this).data('wiki')
            }
        }).done(function(data) {
            $(".judge-article").removeClass("active");
            $(".judge-article").removeAttr("disabled");
            $("#status").text(data);
            setTimeout(function () {
                $("#status").text("");
            }, 2000);
            $(this).addClass("active");
        }.bind(this));
    });
    $("#manual-add").submit(function (e) {
        e.preventDefault();
        $("#status").text("Submiting...");
        $("#manual-add-btn").attr("disabled", "disabled");
        $.ajax({
            url: "api/handler.php",
            dataType: "json",
            method: "post",
            data: {
                func: "do_judgement",
                v: "pending",
                x: $("#art-name").val(),
                y: $(this).data('username'),
                k: $("#art-remarks").val(),
                z: $(this).data('wiki')
            }
        }).done(function(data) {
            $("#manual-add-btn").removeAttr("disabled");
            $("#status").text(data);
            setTimeout(function () {
                $("#status").text("");
                $("#add-art").modal("hide");
                window.location.reload();
            }, 2000);
        }.bind(this));
    });
    $(".check-stats").click(function () {
        $(this).attr("disabled", "disabled");
        $.ajax({
            url: "api/handler.php",
            dataType: "json",
            data: {
                func: "get_user_stats",
                x: $(this).data('username'),
                y: $(this).data('wiki')
            }
        }).done(function(data) {
            $(this).parent().removeAttr("colspan");
            $(this).parent().parent().append($("<td/>", {
              text: data.yes + data.no
            }));
            $(this).parent().parent().append($("<td/>", {
              text: data.pending
            }));
            $(this).parent().parent().append($("<td/>", {
              text: data.yes
            }));
            $(this).parent().parent().append($("<td/>", {
              text: data.no
            }));
            $(this).replaceWith(data.all);

        }.bind(this));
    });
    $("#check-all-stats").click(function () {
        $(".check-stats").each(function () {
            $(this).click();
        });
        $(this).hide();
    });
});
