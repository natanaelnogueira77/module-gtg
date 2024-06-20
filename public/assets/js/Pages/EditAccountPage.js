import App from '../Utils/App.js';
import MediaLibrary from '../Utils/MediaLibrary.js';
import SaveUserForm from '../Forms/SaveUserForm.js';

$(function() {
    const mediaLibrary = new MediaLibrary();
    const saveUserForm = new SaveUserForm(
        App.getDynamicForm($(`#${LIBRARY.get('editAccountPage').formId}`)).setDoubleClickPrevention($("button[type='submit']")), 
        mediaLibrary
    );
    saveUserForm.apply();
});