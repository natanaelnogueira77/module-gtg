import App from '../Utils/App.js';
import MediaLibrary from '../Utils/MediaLibrary.js';
import UsersDataTable from '../DataTables/UsersDataTable.js';
import SaveUserForm from '../Forms/SaveUserForm.js';

$(function() {
    const mediaLibrary = new MediaLibrary();
    const createButton = $(`#${LIBRARY.get('usersPage').actionButtonId}`);
    const saveModal = $(`#${LIBRARY.get('usersPage').modalId}`);

    const saveUserForm = new SaveUserForm(
        App.getDynamicForm(
            $(`#${LIBRARY.get('usersPage').formId}`)
        ).onSuccess(function(instance, response) {
            usersDataTable.load();
            saveModal.modal('toggle');
        }).setDoubleClickPrevention(
            saveModal.find("button[type='submit']")
        ), 
        mediaLibrary
    );

    const usersDataTable = new UsersDataTable(
        $(`#${LIBRARY.get('usersPage').tableId}`), 
        $(`#${LIBRARY.get('usersPage').filtersFormId}`), 
        saveModal, 
        saveUserForm
    );

    createButton.click(function() {
        console.log($(this).data('action'));
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