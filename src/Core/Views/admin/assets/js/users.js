$(function () {
    const app = new App();
    const table_action = $("#users");

    app.table(table_action, function (response) {
        table_action.find("[delete]").click(function () {
            var data = $(this).data();

            if(confirm("Deseja realmente excluir este Usuário?")) {
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