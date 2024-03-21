$(function () {
    $(`[data-bs-toggle="tooltip"]`).tooltip();

    tinymce.init({
        selector:'textarea.tinymce'
    });
});