$(function () {
    const app = new App();
    const preview_modal = $("#email-preview-modal");

    $("button#email-preview").click(function () {
        var html = $("#content").val();
        preview_modal.find(".modal-body").html(html);
        preview_modal.modal("show");
    });

    app.form($("form#save-email"), function (response) {
        if(response.link) window.location.href = response.link;
    });
});