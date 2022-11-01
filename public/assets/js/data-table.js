// Plugin created in order to generate data tables more efficiently in the system. It uses Bootstrap classes and AJAX requests.

$.fn.dataTable = function (
    params = {
        table_id: null,
        url: "", 
        type: "", 
        data: {}, 
        dataType: "", 
        beforeSend: function() {},
        success: function(callback) {},
        complete: function() {}
    }
) {
    var ajaxRequest = null;
    this.params = params;
    this.functions = [];

    this.app = new App();

    this.addEvents = function() {
        const object = this;

        $(object).find("[data-button]").click(function () {
            if($(this).attr("data-button") == "filter") {
                $(object).find("[data-filter]").each(function () {
                    object.params.data[$(this).attr("data-filter")] = $(this).val();
                });
            } else if($(this).attr("data-button") == "clear") {
                $(object).find("[data-filter]").each(function () {
                    object.params.data[$(this).attr("data-filter")] = null;
                });
            }

            object.params.data.p = 1;
            if(object.params.table_id) {
                $(object).find("[data-filter]").each(function () {
                    object.app.setCookie(`table_filter_${object.params.table_id}`, $(this).attr("data-filter"), 3);
                });
            }

            object.ajaxCall();
        });

        $(object).find("[data-page]").click(function () {
            object.params.data.p = $(this).attr("data-page");

            if(object.params.table_id) {
                object.app.setCookie(`table_page_${object.params.table_id}`, object.params.data.p, 3);
            }

            object.ajaxCall();
        });

        $(object).find("[data-ord]").click(function () {
            object.params.data.ord = $(this).attr("data-ord");
            object.params.data.ordtype = $(this).attr("data-ordtype");

            if(object.params.table_id) {
                object.app.setCookie(`table_order_${object.params.table_id}`, object.params.data.ord, 3);
                object.app.setCookie(`table_ordertype_${object.params.table_id}`, object.params.data.ordtype, 3);
            }

            object.ajaxCall();
        });
    }

    this.addFunction = function(func = function() {}) {
        this.functions.push(func);
    }

    // Chamando o AJAX
    this.ajaxCall = function() {
        const object = this;
        if(ajaxRequest != null) ajaxRequest.abort();

        var url = this.params.url;
        var type = this.params.type;
        var data = this.params.data;
        var dataType = this.params.dataType;
        var beforeSend = this.params.beforeSend;
        var success = this.params.success;
        var complete = this.params.complete;

        ajaxRequest = $.ajax({
            url: url,
            type: type,
            data: data,
            dataType: dataType,
            beforeSend: beforeSend,
            success: success,
            complete: function() {
                complete;
                object.addEvents();
                object.loadFunctions();
            }
        });
    }

    this.loadData = function(data = {}) {
        if(data) {
            this.params.data = data;
        }
    }

    this.loadFunctions = function() {
        for(var i = 0; i < this.functions.length; i++) {
            this.functions[i]();
        }
    }

    this.loadTable = function () {
        this.ajaxCall();
    };

    return this.each(function () {
        return this;
    });
}