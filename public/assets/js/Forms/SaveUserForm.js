import FileSelector from '../Utils/FileSelector.js';

export default class SaveUserForm 
{
    #dynamicForm;
    FSAvatarImage;

    constructor(dynamicForm, mediaLibrary) 
    {
        this.#dynamicForm = dynamicForm;
        this.FSAvatarImage = new FileSelector(`#avatar-image-area`, mediaLibrary.setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']));
        this.#setEvents();
    }

    get dynamicForm() 
    {
        return this.#dynamicForm;
    }

    #setEvents() 
    {
        const object = this;
        const avatarImage = object.#dynamicForm.form.find('#avatar-image-area');
        if(avatarImage.data('url')) {
            object.FSAvatarImage.loadFiles({
                url: avatarImage.data('url'),
                uri: avatarImage.data('uri')
            });
        }
        object.FSAvatarImage.render();

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
        if(content.avatar_image) {
            this.FSAvatarImage.loadFiles({
                uri: content.avatarImageURI,
                url: content.avatarImageURL
            }).render();
        }
        return this;
    }

    resetWithPasswordArea() 
    {
        this.#reset();
        this.#dynamicForm.form.find("#password-area").show();
        this.#dynamicForm.form.find("#update-password-area").hide();
    }

    #reset() 
    {
        this.#dynamicForm.clearFieldsAndValidations();
        this.FSAvatarImage.cleanFiles().render();
        return this;
    }

    resetWithUpdatePasswordArea() 
    {
        this.#reset();
        this.#dynamicForm.form.find("#password-area").hide();
        this.#dynamicForm.form.find("#update-password-area").show();
    }

    apply() 
    {
        const object = this;
        object.#dynamicForm.beforeSend(function() {
            this.formData['avatar_image'] = object.FSAvatarImage.getURIList();
            return this;
        }).apply();
    }
}