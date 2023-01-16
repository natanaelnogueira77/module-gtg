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

                if(confirm("Deseja realmente excluir este Usuário?")) {
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
});