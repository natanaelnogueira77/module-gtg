<script>
    $(function () {
        const createButton = $("#<?= $widget->getButtonId() ?>");
        const saveModal = $("#<?= $widget->getModalId() ?>");

        const dataTable = App.getDataTable(
            $("#<?= $widget->getTableId() ?>")
        ).setFiltersForm($("#<?= $widget->getFiltersFormId() ?>")).setAjaxURL(
            $("#<?= $widget->getTableId() ?>").data('action')
        ).afterAjax(function() {
            this.container.find(`[dt-event='edit']`).click(function () {
                App.ajax({
                    url: $(this).data('action'),
                    type: $(this).data('method'),
                    success: function(response) {
                        dynamicForm.setAction(response.update.action);
                        dynamicForm.setMethod(response.update.method);
                        dynamicForm.clearFieldsAndValidations();
                        dynamicForm.fillFieldsByAttribute('name', response.data);

                        dynamicForm.form.find("#password-area").hide();
                        dynamicForm.form.find("#update-password-area").show();

                        saveModal.modal('show');
                    }
                });
            });
            
            this.container.find(`[dt-event='delete']`).click(function () {
                if(confirm(<?php echo json_encode(_('Are you sure you want to delete this user?')) ?>)) {
                    App.ajax({
                        url: $(this).data('action'),
                        type: $(this).data('method'),
                        success: function(response) {
                            dataTable.load();
                        }
                    });
                }
            });
        });
        
        const dynamicForm = App.getDynamicForm(
            $("#<?= $widget->getFormId() ?>")
        ).onSuccess(function(instance, response) {
            dataTable.load();
            saveModal.modal('toggle');
        }).setDoubleClickPrevention(
            saveModal.find("button[type='submit']")
        );

        createButton.click(function() {
            dynamicForm.setAction($(this).data('action'));
            dynamicForm.setMethod($(this).data('method'));
            dynamicForm.clearFieldsAndValidations();

            dynamicForm.form.find("#password-area").show();
            dynamicForm.form.find("#update-password-area").hide();

            saveModal.modal('show');
        });

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
        dataTable.load();
    });
</script>