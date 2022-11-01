$(function () {
    const app = new App();
    const table_action = $("#emails");

    app.table(table_action, function (response) {
        table_action.find("[delete]").click(function () {
            var data = $(this).data();

            if(confirm("Deseja realmente excluir este Template de E-mail?")) {
                app.callAjax({
                    url: data.action,
                    type: data.method,
                    success: function (response) {
                        table_action.loadTable();
                    }
                });
            }
        });
    });
    table_action.loadTable();
});