$(function () {
    const app = new App();
    const users_table = $("#usuarios");

    app.setMediaLibrary();

    $("#logo_upload").click(function () {
        app.mediaLibrary.openML({
            accept: ['jpg', 'jpeg', 'png'],
            success: function(path) {
                $("#logo").val(path);
                $("img#logo_view").attr("src", `${app.mediaLibrary.path}/${path}`);
            }
        });
    });

    $("#logo_icon_upload").click(function () {
        app.mediaLibrary.openML({
            accept: ['jpg', 'jpeg', 'png'],
            success: function(path) {
                $("#logo_icon").val(path);
                $("img#logo_icon_view").attr("src", `${app.mediaLibrary.path}/${path}`);
            }
        });
    });

    app.table(users_table);

    $("form#system, form#database, form#email, form#usertypes").each(function () {
        app.form($(this));
    });

    app.table(users_table, function () {
        users_table.find("button[user-confirm]").click(function () {
            var data = $(this).data();

            app.callAjax({
                url: data.confirm,
                type: "POST",
                data: data,
                success: function (response) {
                    users_table.loadTable();
                }
            });
        });
    });

    $("[data-info=users]").click(function() {
        var data = $(this).data();
        $("#panel_users").show('fast');
        
        users_table.loadData({
            p: 1,
            ord: "name",
            ordtype: "ASC",
            user_type: data.id
        });
        users_table.loadTable();

        $('html,body').animate({
            scrollTop: $("#panels_top").offset().top},
            'slow');
    });
});