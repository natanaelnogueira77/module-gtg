<script>
    $(function() {
        const dynamicForm = App.getDynamicForm(
            $("#<?= $widget->getFormId() ?>")
        ).setDoubleClickPrevention(
            $("button[type='submit'][form='<?= $widget->getFormId() ?>']")
        );

        dynamicForm.form.find(`[name='update_password']`).change(function() {
            if($(this).is(":checked")) {
                if($(this).val() == 1) {
                    dynamicForm.form.find("#password-area").show('fast');
                } else {
                    dynamicForm.form.find("#password-area").hide('fast');
                }
            }
        });

        dynamicForm.apply();
    });
</script>