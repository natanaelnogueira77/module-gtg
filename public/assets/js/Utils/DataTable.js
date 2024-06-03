class DataTable 
{
    container;
    #ajaxURL;
    #filters;
    #defaultFilters;
    #filtersForm = null;
    #isRequestRunning = false;
    #afterAjax = null;

    constructor(elem) 
    {
        this.container = elem;
        this.#ajaxURL = '';
        this.#defaultFilters = {};
        this.#filters = {};
    }

    setFilters(filters = {}) 
    {
        this.#filters = filters;
        return this;
    }

    setDefaultFilters(filters = {}) 
    {
        this.#defaultFilters = filters;
        return this;
    }

    setAjaxURL(url) 
    {
        this.#ajaxURL = url;
        return this;
    }

    setFiltersForm(form) 
    {
        this.#filtersForm = form;
        this.#setFiltersChangeEvent();
        return this;
    }

    #setFiltersChangeEvent() 
    {
        const object = this;

        object.#filtersForm.find(`[name]`).change(function () {
            var returnArray = {};
            var formArray = object.#filtersForm.serializeArray();

            if(formArray) {
                for(var i = 0; i < formArray.length; i++){
                    returnArray[formArray[i]['name']] = formArray[i]['value'];
                }
            }
            
            object.setFilters(returnArray);
            object.load();
        });

        object.#filtersForm.submit(function (e) {
            e.preventDefault();
        });
    }

    afterAjax(callback) 
    {
        this.#afterAjax = callback;
        return this;
    }

    load() 
    {
        const object = this;

        if(object.#isRequestRunning) return;

        $.ajax({
            url: object.#ajaxURL,
            type: 'get',
            data: object.#filters,
            dataType: 'html',
            beforeSend: function() {
                object.#isRequestRunning = true;
                object.container.css('opacity', '0.1');
            },
            success: function(response) {
                if(response) {
                    object.container.children().remove();
                    object.container.append(response);
                    object.#setPagination();
                    object.#setOrdenation();
                    
                    if(object.#afterAjax) {
                        object.#afterAjax();
                    }
                }
            },
            complete: function() {
                object.#isRequestRunning = false;
                object.container.animate({opacity: '1'}, 'fast');
            }
        });

        return object;
    }

    #setPagination() 
    {
        const object = this;
        object.container.find("[dt-page]").click(function () {
            object.#filters.page = $(this).attr('dt-page');
            object.load();
        });

        return object;
    }

    #setOrdenation() 
    {
        const object = this;
        object.container.find("[dt-order-by]").click(function () {
            object.#filters.orderBy = $(this).attr('dt-order-by');
            object.#filters.orderType = $(this).attr('dt-order-type');
            object.load();
        });

        return object;
    }

    clear() 
    {
        this.#filters = this.#defaultFilters;
        this.load();

        return this;
    }
}