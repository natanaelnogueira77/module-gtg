import App from '../Utils/App.js';

export default class ExpiredSession 
{
    static #isSessionExpired = false;
    static #checkUrl = '';
    static #modal;
    static #form;

    static init(checkUrl, modalId, formId) 
    {
        ExpiredSession.#modal = $(`#${modalId}`);
        ExpiredSession.#form = $(`#${formId}`);
        ExpiredSession.#checkUrl = checkUrl;
        
        var countInterval = setInterval(() => {
            ExpiredSession.checkSession();
            if(ExpiredSession.#isSessionExpired == true) clearInterval(countInterval);
        }, 10000);

        App.getDynamicForm(
            ExpiredSession.#form
        ).onSuccess((response) => ExpiredSession.#modal.modal('toggle')).setDoubleClickPrevention(
            ExpiredSession.#modal.find(`button[type='submit'][form='${formId}']`)
        ).apply();
    }

    static checkSession() 
    {
        App.ajax({
            url: ExpiredSession.#checkUrl,
            type: 'post',
            success: function(response) {
                if(response.success) {
                    ExpiredSession.#modal.modal("show");
                    ExpiredSession.#isSessionExpired = true;
                }
            }
        });
    }
}