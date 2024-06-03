<?php 
    loadScript('DataTables/UsersDataTable');
    loadScript('Forms/SaveUserForm');
?>
<script>
    $(function() {
        const mediaLibrary = new MediaLibrary();
        const createButton = $("#create-user");
        const saveModal = $("#save-user-modal");

        const saveUserForm = new SaveUserForm(
            App.getDynamicForm(
                $("#save-user")
            ).onSuccess(function(instance, response) {
                usersDataTable.load();
                saveModal.modal('toggle');
            }).setDoubleClickPrevention(
                saveModal.find("button[type='submit']")
            ), 
            mediaLibrary
        );

        const usersDataTable = new UsersDataTable(
            $("#users"),
            $("#filters"),
            saveModal, 
            saveUserForm
        );

        createButton.click(function() {
            saveUserForm.dynamicForm.setAction($(this).data('action'));
            saveUserForm.dynamicForm.setMethod($(this).data('method'));
            saveUserForm.resetWithPasswordArea();

            saveModal.find('.modal-title').text($(this).data('modalTitle'));
            saveModal.modal('show');
        });

        saveUserForm.FSAvatarImage.onAdd(function(fileSelector, elem, id) {
            saveModal.modal('toggle');
            setTimeout(() => mediaLibrary.setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']).onSuccess(function(uri, url) {
                fileSelector.addToSelector(uri, url);
                setTimeout(() => saveModal.modal('show'), 500);
            }).onCancel(() => setTimeout(() => saveModal.modal('show'), 500)).show(), 500);
        }).onEdit(function(fileSelector, elem, id) {
            saveModal.modal('toggle');
            setTimeout(() => mediaLibrary.setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']).onSuccess(function(uri, url) {
                fileSelector.updateOnSelector(elem, id, uri, url);
                setTimeout(() => saveModal.modal('show'), 500);
            }).onCancel(() => setTimeout(() => saveModal.modal('show'), 500)).show(), 500);
        });

        saveUserForm.apply();
        usersDataTable.load();
    });
</script>