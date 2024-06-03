class UsersDataTable
{
    #dataTable;
    #filtersForm;
    #modal;
    #saveForm;

    constructor(table, filtersForm, modal, saveForm) 
    {
        this.#filtersForm = filtersForm;
        this.#modal = modal;
        this.#saveForm = saveForm;
        this.#setDataTable(table);
    }
    
    get dataTable() 
    {
        return this.#dataTable;
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
                        object.#saveForm.dynamicForm.setAction(response.update.action);
                        object.#saveForm.dynamicForm.setMethod(response.update.method);
                        object.#saveForm.resetWithUpdatePasswordArea();
                        object.#saveForm.fillFields(response.data);

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