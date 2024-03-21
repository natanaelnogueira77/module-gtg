class SaveUserForm 
{
    #dynamicForm;

    constructor(dynamicForm) 
    {
        this.#dynamicForm = dynamicForm;
        this.#setEvents();
    }

    get dynamicForm() 
    {
        return this.#dynamicForm;
    }

    #setEvents() 
    {
        const object = this;
        object.#dynamicForm.form.find(`[name='update_password']`).change(function() {
            if($(this).is(":checked")) {
                if($(this).val() == 1) {
                    object.#dynamicForm.form.find("#password-area").show('fast');
                } else {
                    object.#dynamicForm.form.find("#password-area").hide('fast');
                }
            }
        });
    }

    fillFields(content) 
    {
        this.#dynamicForm.fillFieldsByAttribute('name', content);
        return this;
    }

    resetWithPasswordArea() 
    {
        this.#reset()
        this.#dynamicForm.form.find("#password-area").show();
        this.#dynamicForm.form.find("#update-password-area").hide();
    }

    #reset() 
    {
        this.#dynamicForm.clearFieldsAndValidations();
        return this;
    }

    resetWithUpdatePasswordArea() 
    {
        this.#reset()
        this.#dynamicForm.form.find("#password-area").hide();
        this.#dynamicForm.form.find("#update-password-area").show();
    }

    apply() 
    {
        this.#dynamicForm.apply();
    }
}