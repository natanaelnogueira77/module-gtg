<?php loadScript('Forms/SaveUserForm'); ?>
<script>
    $(function() {
        const mediaLibrary = new MediaLibrary();
        const saveUserForm = new SaveUserForm(
            App.getDynamicForm(
                $("#save-user")
            ).setDoubleClickPrevention(
                $("button[type='submit']")
            ), 
            mediaLibrary
        );
        saveUserForm.apply();
    });
</script>