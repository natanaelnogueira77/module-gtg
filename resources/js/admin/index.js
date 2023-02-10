$(function () {
    const app = new App();
    const table = $("#users");
    const filters_form = $("#filters");
    const clear_btn = $("#clear");

    const dataTable = app.table(table, table.data('action'));
    dataTable.defaultParams(app.objectifyForm(filters_form))
        .filtersForm(filters_form)
        .clearButton(clear_btn)
        .setMsgFunc((msg) => app.showMessage(msg.message, msg.type))
        .addAction((table) => {
            table.find("[data-act=delete]").click(function () {
                var data = $(this).data();

                if(confirm(lang.users.delete.confirm)) {
                    app.callAjax({
                        url: data.action,
                        type: data.method,
                        success: function (response) {
                            dataTable.load();
                        }
                    });
                }
            });
        }).load();
    
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

    $("#login_img_upload").click(function () {
        app.mediaLibrary.openML({
            accept: ['jpg', 'jpeg', 'png'],
            success: function(path) {
                $("#login_img").val(path);
                $("img#login_img_view").attr("src", `${app.mediaLibrary.path}/${path}`);
            }
        });
    });

    $("form#system, form#database, form#email, form#usertypes").each(function () {
        app.form($(this));
    });

    $("[data-info=users]").click(function() {
        var data = $(this).data();
        $("#panel_users").show('fast');
        
        dataTable.params({
            user_type: data.id
        }).load();

        $('html,body').animate({
            scrollTop: $("#panels_top").offset().top},
            'slow');
    });
});