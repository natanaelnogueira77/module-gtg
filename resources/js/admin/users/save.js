$(function () {
    const app = new App();
    const user_id = $("[name=id]").val() ? $("[name=id]").val() : null;
    const user_slug = $("[name=usu_slug]");
    const slug_feedback = $("#slug-feedback");
    const form = $("#save-user");
    const update_password = $("input[name$='update_password']");
    const password_area = $("#password");

    app.form(form, function (response) {
        if(response.link) window.location.href = response.link;
    });

    user_slug.blur(function () {
        var input = $(this);
        var data = $(this).data();
        var value = $(this).val();

        app.callAjax({
            url: data.action,
            type: data.method,
            data: {
                slug: value,
                user_id: user_id
            },
            success: function (response) {
                if(response.success) {
                    slug_feedback.removeClass("invalid-feedback").addClass("valid-feedback");
                    input.removeClass("is-invalid").addClass("is-valid");
                    slug_feedback.text(`Este Apelido parece ótimo!`);
                } else {
                    slug_feedback.removeClass("valid-feedback").addClass("invalid-feedback");
                    input.removeClass("is-valid").addClass("is-invalid");
                    if(response.empty) {
                        slug_feedback.text(`Por favor, você precisa digitar um Apelido!`);
                    } else {
                        slug_feedback.text(`Parece que este apelido já está sendo usado por alguém! Tente outro.`);
                    }
                }
            }
        });
    });

    update_password.change(function () {
        if($('#update_password1').is(':checked')) {
            password_area.show('fast');
        }

        if($('#update_password2').is(':checked')) {
            password_area.hide('fast');
        }
    });
});