<?php 
    loadScript('DataTables/UsersDataTable');
    loadScript('Forms/SaveUserForm');
?>
<script>
    $(function () {
        const createButton = $("#<?= $widget->getButtonId() ?>");
        const saveModal = $("#<?= $widget->getModalId() ?>");

        const saveUserForm = new SaveUserForm(
            App.getDynamicForm(
                $("#<?= $widget->getFormId() ?>")
            ).onSuccess(function(instance, response) {
                usersDataTable.load();
                saveModal.modal('toggle');
            }).setDoubleClickPrevention(
                saveModal.find("button[type='submit']")
            )
        );

        const usersDataTable = new UsersDataTable(
            $("#<?= $widget->getTableId() ?>"),
            $("#<?= $widget->getFiltersFormId() ?>"),
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

        saveUserForm.apply();
        usersDataTable.load();
    });
</script>