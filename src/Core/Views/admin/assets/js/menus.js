$(function () {
    const app = new App();
    const delete_forms = $("[menu-delete]");

    delete_forms.each(function () {
        app.form($(this), function (response) {
            
        });
    });
});