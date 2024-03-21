<?php loadScript('Forms/SaveUserForm'); ?>
<script>
    $(function() {
        const saveUserForm = new SaveUserForm(
            App.getDynamicForm(
                $("#<?= $widget->getFormId() ?>")
            ).setDoubleClickPrevention(
                $("button[type='submit'][form='<?= $widget->getFormId() ?>']")
            )
        );

        saveUserForm.apply();
    });
</script>