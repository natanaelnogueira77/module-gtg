class DynamicForm 
{
    #form;
    #isRequestRunning = false;
    #beforeSend = null;
    #onSuccess = null;
    #onComplete = null;
    #error = null;
    formData = null;

    constructor(form) 
    {
        this.#form = form;
    }

    get form() 
    {
        return this.#form;
    }

    beforeSend(callback) 
    {
        this.#beforeSend = callback;
        return this;
    }

    onSuccess(callback) 
    {
        this.#onSuccess = callback;
        return this;
    }

    onError(callback) 
    {
        this.#error = callback;
        return this;
    }

    onComplete(callback) 
    {
        this.#onComplete = callback;
        return this;
    }

    setAction(action) 
    {
        this.#form.attr('action', action);
        return this;
    }
    
    setMethod(method) 
    {
        this.#form.attr('method', method);
        return this;
    }

    getObjectifiedFields() 
    {
        var data = {};
        var serializedData = this.#form.serializeArray();
        if(serializedData) {
            for(var i = 0; i < serializedData.length; i++){
                data[serializedData[i]['name']] = serializedData[i]['value'];
            }
        }

        return data;
    }

    clearFieldsAndValidations() 
    {
        this.#form.find('input, textarea, select').each(function () {
            if(!['submit', 'checkbox', 'radio'].includes($(this).attr('type'))) {
                $(this).val(``);
            } else if(['checkbox', 'radio'].includes($(this).attr('type'))) {
                $(this).prop('checked', false);
            }
        });

        return this;
    }

    fillFieldsByAttribute(attr = 'name', content = {})
    {
        this.#form.find('input, textarea, select').each(function () {
            if(!['submit', 'checkbox', 'radio'].includes($(this).attr('type'))) {
                if(content[$(this).attr(attr)] != '') {
                    $(this).val(content[$(this).attr(attr)]);
                } else {
                    $(this).val(``);
                }
            } else if($(this).attr('type') == 'checkbox') {
                if(content[$(this).attr(attr)]) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                }
            } else if($(this).attr('type') == 'radio') {
                if(content[$(this).attr(attr)] == $(this).val()) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                }
            }
        });

        return this;
    }

    setDoubleClickPrevention(button) 
    {
        const object = this;
        button.click(function(event) {
            event.preventDefault();
            
            var innerHTML = button.html();

            button.prop('disabled', true);
            button.html(`
                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                <span class="visually-hidden" role="status"></span>
            `);
            object.#form.submit();

            setTimeout(function() { 
                button.prop('disabled', false);
                button.html(innerHTML);
            }, 1000);
        });
        return this;
    }

    apply() 
    {
        const object = this;

        object.#clearFieldOnChangeEvent();
        object.#form.submit(function (e) {
            e.preventDefault();

            if(object.#isRequestRunning) return;

            object.formData = object.getObjectifiedFields();
            if(object.#beforeSend) {
                object.#beforeSend();
            }

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: object.formData,
                dataType: 'json',
                beforeSend: function() {
                    object.#isRequestRunning = true;
                    object.#clearValidationErrors();
                },
                success: function(response) {
                    if(response.message) {
                        App.showMessage(response.message[1], response.message[0]);
                    }

                    if(object.#onSuccess) {
                        object.#onSuccess(object, response);
                    }
                },
                error: function(response) {
                    var errorData = response.responseJSON;
                    if(errorData) {
                        if(errorData.errors) {
                            object.#showValidationErrors(errorData.errors);
                        }

                        if(errorData.message) {
                            App.showMessage(response.responseJSON.message[1], response.responseJSON.message[0]);
                        }

                        if(object.#error) {
                            object.#error(object, response);
                        }
                    } else {
                        console.error(new Error(`The requisition of the Form returned an error!`));
                    }
                },
                complete: function() {
                    object.#isRequestRunning = false;
                    if(object.#onComplete) {
                        object.#onComplete();
                    }
                }
            });
        });
    }

    #clearFieldOnChangeEvent() 
    {
        const object = this;
        object.#form.find('input, textarea, select').change(function () {
            var id = $(this).attr('id');

            $(this).removeClass('is-invalid');
            $(this).parent().children('.invalid-feedback').html(``);

            object.#form.find(`[data-validation='${id}']`).html(``);
        });

        return object;
    }

    #clearValidationErrors() 
    {
        this.#form.find('.is-invalid').removeClass('is-invalid');
        this.#form.find('.invalid-feedback').html(``);
        this.#form.find(`[data-validation]`).html(``);

        return this;
    }

    #showValidationErrors(errors = {}) 
    {
        if(errors) {
            var attr = this.#form.data('validation') ? this.#form.data('validation') : 'name';
            for(const [key, value] of Object.entries(errors)) {
                var input = this.#form.find(`[${attr}="${key}"]`);

                input.toggleClass('is-invalid');
                input.parent().children('.invalid-feedback').html(value);

                this.#form.find(`[data-validation="${key}"]`).html(value);
            }
        }
    }
}