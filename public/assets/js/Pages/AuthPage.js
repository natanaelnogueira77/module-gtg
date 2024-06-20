import App from '../Utils/App.js';

$(function() {
    App.getDynamicForm(
        $(`#main-login-form`)
    ).onSuccess(function(instance, response) {
        if(response.redirectURL) window.location.href = response.redirectURL;
    }).apply();
});