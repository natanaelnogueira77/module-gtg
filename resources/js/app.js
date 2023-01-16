class App {
    constructor() {
        this.mediaLibrary = null;
    }

    form(elem, callback = function () {}) {
        const object = this;

        elem.submit(function (e) {
            e.preventDefault();
            var form = $(this);

            var action = form.attr("action");
            var method = form.attr("method");
            var data = form.serialize();

            $.ajax({
                url: action,
                type: method,
                data: data,
                dataType: "json",
                beforeSend: function () {
                    object.load("open");
                },
                success: function(response) {
                    var errors = null;
                    if(response.message) {
                        object.showMessage(response.message.message, response.message.type);
                    }

                    if(response.errors) {
                        errors = response.errors;
                    }

                    object.showFormErrors(elem, errors, elem.data('errors') ?? 'name');

                    if(callback) {
                        callback(response);
                    }
                },
                complete: function () {
                    object.load("close");
                }
            });
        });
    }

    table(elem, urlBase) {
        return new DataTable(elem, urlBase);
    }

    showMessage(message = '', type = 'success', timeOut = 5000, 
        fadeIn = 5000, fadeOut = 5000, positionClass = 'toast-top-center') {
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

    showFormErrors(form, errors = null, attr = 'id') {
        form.find(".is-invalid").toggleClass("is-invalid");
        form.find("[data-error]").html(``);
        form.find(".invalid-feedback").html(``);

        if(errors) {
            for(const [key, value] of Object.entries(errors)) {
                var input = form.find(`[${attr}="${key}"]`);
                input.toggleClass('is-invalid');
                input.parent().children('.invalid-feedback').html(value);
                form.find(`[data-error="${key}"]`).html(value);
            }
        }
    }

    createMask(jQueryElem, mask) {
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

    callAjax(data = {}) {
        const object = this;

        $.ajax({
            url: data.url,
            type: data.type ? data.type : "GET",
            data: data.data ? data.data : {},
            dataType: data.dataType ? data.dataType : "json",
            beforeSend: function () {
                if(!data.noLoad) {
                    object.load("open");
                }
            }, 
            success: function (response) {
                if(response.message) {
                    object.showMessage(response.message.message, response.message.type);
                }

                if(data.success) {
                    data.success(response);
                }
            }, 
            complete: function () {
                if(!data.noLoad) {
                    object.load("close");
                }
            }
        })
    }

    load(action) {
        var load_div = $(".ajax_load");
        if(action === "open") {
            load_div.fadeIn().css("display", "flex");
        } else {
            load_div.fadeOut();
        }
    }
    
    objectifyForm(form) {
        var returnArray = {};
        var formArray = form.serializeArray();

        if(formArray) {
            for(var i = 0; i < formArray.length; i++){
                returnArray[formArray[i]["name"]] = formArray[i]["value"];
            }
        }

        return returnArray;
    }

    setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));

        let expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    getCookie(cname) {
        let name = cname + "=";
        let decodedCookie = decodeURIComponent(document.cookie);
        let ca = decodedCookie.split(';');

        for(let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        
        return "";
    }

    getAddressByCEP(cep, inputs = {}) {
        let url = `https://viacep.com.br/ws/${cep}/json/`;
        let xmlHttp = new XMLHttpRequest();
    
        var endereco = document.getElementById(inputs.endereco);
        var bairro = document.getElementById(inputs.bairro);
        var cidade = document.getElementById(inputs.cidade);
        var estado = document.getElementById(inputs.estado);
    
        endereco.value = "Carregando...";
        bairro.value = "Carregando...";
        cidade.value = "Carregando...";
        estado.value = "Carregando...";
    
        xmlHttp.open('GET', url);
        xmlHttp.onreadystatechange = () => {
            if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                let dadosJSONText = xmlHttp.responseText;
                let dadosJSONObj = JSON.parse(dadosJSONText);
    
                endereco.value = dadosJSONObj.logradouro ? dadosJSONObj.logradouro : '';
                bairro.value = dadosJSONObj.bairro ? dadosJSONObj.bairro : '';
                cidade.value = dadosJSONObj.localidade ? dadosJSONObj.localidade : '';
                estado.value = dadosJSONObj.uf ? dadosJSONObj.uf : '';
            }
        }
    
        xmlHttp.send();
    }

    copyText(sel, text) {
        var copyElem = document.querySelector(sel);
        copyElem.select();
        document.execCommand("Copy");
        
        this.showMessage(text + copyElem.value, "success");
    }

    addTextAtSelectionPosition(elem, text = "") {
        var curPos = elem.selectionStart;
        var textArea = $(elem).val();
        $(elem).val(textArea.slice(0, curPos) + text + textArea.slice(curPos));
    }

    tinymce(init = {}) {
        tinymce.init(init);
    }

    setMediaLibrary() {
        const modal = $('#modal-media-library');
        if(modal.length != 0) {
            this.mediaLibrary = new MediaLibrary({
                root: modal.attr("data-root"),
                load_script: modal.attr("data-load"), 
                add_script: modal.attr("data-add"),
                del_script: modal.attr("data-delete"),
                path: modal.attr("data-path")
            });
        }
    }

    setModal(modal, data = {}) {
        const modal_header = modal.find(".modal-header");
        const modal_title = modal_header.find(".modal-title");

        if(data) {
            if(data.effect) {
                modal.attr("class", `modal ${data.effect}`);
            }

            if(!data.noBackdrop) {
                modal.attr("data-bs-backdrop", "static");
            }

            if(!data.keyboard) {
                modal.attr("data-bs-keyboard", "false");
            }

            if(data.size) {
                modal.find(".modal-dialog").attr("class", `modal-dialog modal-${data.size}`)
            }

            if(data.title) {
                modal_title.text(data.title);
            }
        }
    }

    expiredSession(check_url) {
        const object = this;
        var is_session_expired = 'no';

        function check_session(url) {
            object.callAjax({
                url: url,
                type: "POST",
                data: {},
                success: function (response) {
                    if(response.success) {
                        $('#login-modal').modal({
                            backdrop: "static",
                            keyboard: false
                        });
                        $('#login-modal').modal("show");
                        is_session_expired = 'yes';
                    }
                },
                noLoad: true
            });
        }
        
        var count_interval = setInterval(function () {
            check_session(check_url);
            if(is_session_expired == 'yes') {
                clearInterval(count_interval);
            }
        }, 10000);
        
        $('#login_form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            
            object.callAjax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: form.serialize(),
                success: function(response) {
                    if(response.success) {
                        form.closest(".modal").modal('toggle');
                    }

                    if(response.message) {
                        object.showMessage(response.message.message, response.message.type)
                    }
                }
            });
        });
    }
}

$(function () {
    const app = new App();
    app.tinymce({
        selector:'textarea.tinymce',
        language: 'pt_BR',
        block_formats: 'Smart Meet=h2; Parágrafo=p; Cabeçalho 1=h1; Cabeçalho 2=h2; Cabeçalho 3=h3; Cabeçalho 4=h4; Cabeçalho 5=h5; Cabeçalho 6=h6',
        style_formats: [
            {title: 'Headers', items: [
                {title: 'Smart Meet', block: 'h2'},
                {title: 'Cabeçalho 1', block: 'h1'},
                {title: 'Cabeçalho 2', block: 'h2'},
                {title: 'Cabeçalho 3', block: 'h3'},
                {title: 'Cabeçalho 4', block: 'h4'},
                {title: 'Cabeçalho 5', block: 'h5'},
                {title: 'Cabeçalho 6', block: 'h6'}
            ]},
            {title: 'Inline', items: [
                {title: 'Bold', inline: 'b', icon: 'bold'},
                {title: 'Italic', inline: 'i', icon: 'italic'},
                {title: 'Underline', inline: 'span', styles : {textDecoration : 'underline'}, icon: 'underline'},
                {title: 'Strikethrough', inline: 'span', styles : {textDecoration : 'line-through'}},
                {title: 'Superscript', inline: 'sup', icon: 'superscript'},
                {title: 'Subscript', inline: 'sub', icon: 'subscript'},
                {title: 'Code', inline: 'code'},
            ]},
            {title: 'Blocks', items: [
                {title: 'Paragraph', block: 'p'},
                {title: 'Blockquote', block: 'blockquote'},
                {title: 'Div', block: 'div'},
                {title: 'Pre', block: 'pre'}
            ]},
            {title: 'Alinhamento', items: [
                {title: 'Left', block: 'div', styles : {textAlign : 'left'}},
                {title: 'Center', block: 'div', styles : {textAlign : 'center'}},
                {title: 'Right', block: 'div', styles : {textAlign : 'right'}},
                {title: 'Justify', block: 'div', styles : {textAlign : 'justify'}}
            ]}
        ]
    });
});