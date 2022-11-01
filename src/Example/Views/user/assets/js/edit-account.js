$(function () {
    const app = new App();
    const user_id = $("input#user-id").val();

    app.form($("#save-user"), function (response) {
        if(response.link) window.location.href = response.link;
    });

    $("[name=usu_slug]").blur(function () {
        var input = $(this);
        var data = $(this).data();
        var value = $(this).val();

        app.callAjax({
            url: data.action,
            type: "POST",
            data: {
                slug: value,
                user_id: user_id
            },
            success: function (response) {
                if(response.success) {
                    $("#slug-feedback").removeClass("invalid-feedback").addClass("valid-feedback");
                    input.removeClass("is-invalid").addClass("is-valid");
                    $("#slug-feedback").text(`Este Apelido parece ótimo!`);
                } else {
                    $("#slug-feedback").removeClass("valid-feedback").addClass("invalid-feedback");
                    input.removeClass("is-valid").addClass("is-invalid");
                    if(response.empty) {
                        $("#slug-feedback").text(`Por favor, você precisa digitar um Apelido!`);
                    } else {
                        $("#slug-feedback").text(`Parece que este apelido já está sendo usado por alguém! Tente outro.`);
                    }
                }
            }
        });
    });

    $("input[name$='update_password']").change(function(){
        if($('#update_password1').is(':checked')) {
            $("#password").show('fast');
        }

        if($('#update_password2').is(':checked')) {
            $("#password").hide('fast');
        }
    });
});