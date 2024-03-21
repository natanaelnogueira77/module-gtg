class UsersDataTable
{
    #dataTable;
    #filtersForm;
    #modal;
    #saveUserForm;

    constructor(table, filtersForm, modal, saveUserForm) 
    {
        this.#filtersForm = filtersForm;
        this.#modal = modal;
        this.#saveUserForm = saveUserForm;
        this.#setDataTable(table);
    }

    #setDataTable(table) 
    {
        const object = this;

        this.#dataTable = App.getDataTable(table).setFiltersForm(object.#filtersForm).setAjaxURL(
            table.data('action')
        ).afterAjax(function() {
            this.container.find(`[dt-event='edit']`).click(function () {
                const data = $(this).data();
                App.ajax({
                    url: data.action,
                    type: data.method,
                    success: function(response) {
                        object.#saveUserForm.dynamicForm.setAction(response.update.action);
                        object.#saveUserForm.dynamicForm.setMethod(response.update.method);
                        object.#saveUserForm.resetWithUpdatePasswordArea();
                        object.#saveUserForm.fillFields(response.data);

                        object.#modal.find('.modal-title').text(data.modalTitle);
                        object.#modal.modal('show');
                    }
                });
            });
            
            this.container.find(`[dt-event='delete']`).click(function () {
                if(confirm($(this).data('confirmMessage'))) {
                    App.ajax({
                        url: $(this).data('action'),
                        type: $(this).data('method'),
                        success: function(response) {
                            object.#dataTable.load();
                        }
                    });
                }
            });
        });
    }

    load() 
    {
        this.#dataTable.load();
    }
}