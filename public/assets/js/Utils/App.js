class App 
{
    static getDynamicForm(form) 
    {
        return new DynamicForm(form);
    }

    static getDataTable(elem) 
    {
        return new DataTable(elem);
    }

    static showMessage(
        message = '', 
        type = 'success', 
        timeOut = 5000, 
        fadeIn = 5000, 
        fadeOut = 5000, 
        positionClass = 'toast-bottom-right'
    ) 
    {
        toastr.options.timeOut = timeOut;
        toastr.options.fadeIn = fadeIn;
        toastr.options.fadeOut = fadeOut;
        toastr.options.positionClass = positionClass;

        if(type == 'success') {
            toastr.success(message);
        } else if(type == 'error') {
            toastr.error(message);
        } else if(type == 'info') {
            toastr.info(message);
        }
    }

    static createMask(jQueryElem, mask) 
    {
        if(jQueryElem && mask) {
            jQueryElem.mask(mask).focusout(function (event) {  
                var target, data, element;  
                target = (event.currentTarget) ? event.currentTarget : event.srcElement;  
                data = target.value.replace(/\D/g, '');
                
                element = $(target);  
                element.unmask();  
                element.mask(mask);
            });
        }
    }

    static ajax(data = {}) 
    {
        data.type = data.type ? data.type : 'get';
        data.data = data.data ? data.data : {};
        data.dataType = data.dataType ? data.dataType : 'json';

        var successCallback = data.success;
        data.success = function (response) {
            if(response.message) {
                App.showMessage(response.message[1], response.message[0]);
            }

            if(successCallback) {
                successCallback(response);
            }
        };

        var errorCallback = data.error;
        data.error = function (response) {
            if(response.responseJSON) {
                if(response.responseJSON.message) {
                    App.showMessage(response.responseJSON.message[1], response.responseJSON.message[0]);
                }

                if(errorCallback) {
                    errorCallback(response.responseJSON);
                }
            } else {
                console.log('An server error has occurred!');
            }
        };

        $.ajax(data);
    }
}