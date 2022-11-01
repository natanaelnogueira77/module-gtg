$.fn.formBuilder = function () {
    this.draggable_area; // Área de drop
    this.items_list; // Lista de itens arrastáveis
    this.cur_keyname = 1; // Chave atual

    // Informações dos itens arrastáveis
    this.left_objects = [
        {
            id: 1, 
            name: "select_group",
            title: "Grupo de Seleção", 
            icon: "icofont-tasks", 
            menu: ["required", "title", "keyname", "help_text", "other", "options"]
        },
        {
            id: 2, 
            name: "date",
            title: "Campo de Data", 
            icon: "icofont-calendar", 
            menu: ["required", "title", "keyname", "help_text"]
        },
        {
            id: 3, 
            name: "header",
            title: "Cabeçalho", 
            icon: "icofont-heading", 
            menu: ["title", "keyname", "type"]
        },
        {
            id: 4, 
            name: "number",
            title: "Número", 
            icon: "icofont-ui-add", 
            menu: ["required", "title", "keyname", "help_text", "placeholder", "value", "min", "max", "step"]
        },
        {
            id: 5, 
            name: "paragraph",
            title: "Parágrafo", 
            icon: "icofont-paragraph", 
            menu: ["content", "keyname", "type"]
        },
        {
            id: 6, 
            name: "options_group",
            title: "Grupo de Opções", 
            icon: "icofont-listine-dots", 
            menu: ["required", "title", "keyname", "help_text", "other", "options"]
        },
        {
            id: 7, 
            name: "selection",
            title: "Seleção", 
            icon: "icofont-list", 
            menu: ["required", "title", "keyname", "help_text", "placeholder", "options"]
        },
        {
            id: 8, 
            name: "text",
            title: "Campo de Texto", 
            icon: "icofont-terminal", 
            menu: ["required", "title", "keyname", "help_text", "placeholder", "value", "type", "maxlength"]
        },
        {
            id: 9, 
            name: "textarea",
            title: "Área de Texto", 
            icon: "icofont-ui-text-chat", 
            menu: ["required", "title", "keyname", "help_text", "placeholder", "value", "maxlength", "rows"]
        },
        {
            id: 10, 
            name: "section",
            title: "Seção", 
            icon: "icofont-layout", 
            menu: []
        },
        {
            id: 11, 
            name: "hidden",
            title: "Campo Oculto", 
            icon: "icofont-terminal", 
            menu: ["keyname", "value"]
        }
    ];
    // Campos de configuração dos itens
    this.fields = [
        {name:"title", label: "Título", type: "text"},
        {name:"keyname", label: "Chave", type: "text"},
        {name:"required", label: "Obrigatório", type: "checkbox"},
        {name:"help_text", label: "Texto de Ajuda", type: "text"},
        {name:"other", label: "Habilitar Outro", type: "checkbox"},
        {name:"placeholder", label: "Placeholder", type: "text"},
        {name:"value", label: "Valor", type: "text"},
        {name:"type", label: "Tipo", type: "select"},
        {name:"min", label: "Min.", type: "number"},
        {name:"max", label: "Max.", type: "number"},
        {name:"step", label: "Step", type: "number"},
        {name:"content", label: "Conteúdo", type: "textarea"},
        {name:"maxlength", label: "Max.", type: "number"},
        {name:"rows", label: "Linhas", type: "number"},
        {name:"options", label: "Opções", type: "sortable"}
    ];
    // Templates da view e do menu dos itens na área de drop
    this.template = {
        view: {
            structure: `
                <div class="form-group">
                    <label v-title></label>
                </div>
            `,
            inputs: {
                text: `<input class="form-control">`,
                textarea: `<textarea class="form-control"></textarea>`,
                select: `<select class="form-control"></select>`,
                headline: `<tag></tag>`,
                group: `<div data-type="group"></div>`
            }
        },
        menu: {
            structure: `
                <div class="row mb-1">
                    <label class="col-sm-2 col-form-label text-right"></label>
                    <div class="col-sm-10"></div>
                </div>
            `,
            inputs: {
                text: `<input type="text" class="form-control form-control-sm">`,
                checkbox: `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox">
                    </div>
                `,
                select: `<select class="form-control form-control-sm"></select>`,
                number: `<input type="number" class="form-control form-control-sm">`,
                textarea: `<textarea class="form-control form-control-sm">Parágrafo</textarea>`,
                sortable: `
                    <ul></ul>
                    <div class='d-flex justify-content-around mt-2'>
                        <button type='button' class='btn btn-outline-success btn-md' add-option>Adicionar</button>
                    </div>
                `,
                sortable_item: `
                    <li class="list-group-item-action list-group-item p-0">
                        <div class="d-flex">
                            <div class="opt-handler bg-primary d-flex justify-content-around align-items-center" 
                                style="width: 32px; cursor: move;">
                                <i class="icofont-hand-drag text-white" style="font-size: 2rem"></i>
                            </div>
                            <div class="row p-2 w-100">
                                <div class="col-5 col-md-5">
                                    <input type="text" class="form-control form-control-sm" data-type="label">
                                </div>
                                <div class="col-5 col-md-5">
                                    <input type="text" class="form-control form-control-sm" data-type="value">
                                </div>
                                <div class="col-2 col-md-2 pl-1 d-flex align-items-center remove-option">
                                    <button type="button" class="btn-icon btn-icon-only btn btn-outline-danger py-1 px-2">
                                        <i class="pe-7s-trash btn-icon-wrapper"> </i></button>
                                </div>
                            </div>
                        </div>
                    </li>
                `
            }
        }
    };

    // HTML dos campos do menu e da view
    this.menu_fields = new Array();
    this.view_fields = new Array();
    // Montando o Layout
    this.layout = function () {
        const layout = $(`
            <div class="form-row mb-4">
                <div class="col-md-3 p-0">
                    <ul class="sticky-top p-0 m-0" style="top: 80px;" items></ul>
                </div>
                <div class="col-md-9 border border-primary border-5 rounded p-0 m-0" style="min-height: 400px;">
                    <ul style="height: 100%;" class="p-0 m-0" draggable-area></ul>
                </div>
            </div>
        `);

        this.items_list = layout.find("ul[items]");
        this.draggable_area = layout.find("ul[draggable-area]");

        this.setFormEvents();

        $(this).children().remove();
        $(this).append(layout);
    }
    // Definindo os campos da view (Executado automaticamente)
    this.setViewFields = function () {
        const view = this.template.view;
        const objects = this.left_objects;

        for(var i = 0; i < objects.length; i++) {
            const element = $(`<div></div>`);

            element.append(view.structure);
            element.find("label").text(objects[i].title);

            if(objects[i].name == "select_group") {
                element.find(".form-group").append(view.inputs.group);
            } else if(objects[i].name == "date") {
                element.find(".form-group").append(view.inputs.text);
                element.find("input").attr("type", "date");
                element.find("input").attr("v-elem", true);
            } else if(objects[i].name == "header") {
                element.find(".form-group").append(view.inputs.headline);
                element.find("tag").attr("v-elem", true);
            } else if(objects[i].name == "number") {
                element.find(".form-group").append(view.inputs.text);
                element.find("input").attr("type", "number");
                element.find("input").attr("v-elem", true);
            } else if(objects[i].name == "paragraph") {
                element.find(".form-group").append(view.inputs.headline);
                element.find("tag").attr("v-elem", true);
            } else if(objects[i].name == "options_group") {
                element.find(".form-group").append(view.inputs.group);
            } else if(objects[i].name == "selection") {
                element.find(".form-group").append(view.inputs.select);
                element.find("select").attr("v-elem", true);
            } else if(objects[i].name == "text") {
                element.find(".form-group").append(view.inputs.text);
                element.find("input").attr("type", "text");
                element.find("input").attr("v-elem", true);
            } else if(objects[i].name == "textarea") {
                element.find(".form-group").append(view.inputs.textarea);
                element.find("textarea").attr("v-elem", true);
            } else if(objects[i].name == "section") {
                element.find(".form-group").append(view.inputs.headline);
                element.find("tag").attr("v-elem", true);
            } else if(objects[i].name == "hidden") {
                element.find(".form-group").append(view.inputs.text);
                element.find("input").attr("type", "text");
                element.find("input").attr("v-elem", true);
            }
            
            this.view_fields[objects[i].name] = element.html();
        }
    }
    // Definindo os campos do menu (Executado automaticamente)
    this.setMenuFields = function () {
        const menu = this.template.menu;
        const fields = this.fields;

        for(var i = 0; i < fields.length; i++) {
            const element = $(`<div></div>`);
            element.append(menu.structure);
            element.find("label").text(fields[i].label);

            if(fields[i].type == "text") {
                element.find(".col-sm-10").html(menu.inputs.text);
                element.find("input").attr("data-name", fields[i].name);
            } else if(fields[i].type == "checkbox") {
                element.find(".col-sm-10").html(menu.inputs.checkbox);
                element.find("input").attr("data-name", fields[i].name);
            } else if(fields[i].type == "select") {
                element.find(".col-sm-10").html(menu.inputs.select);
                element.find("select").attr("data-name", fields[i].name);
            } else if(fields[i].type == "number") {
                element.find(".col-sm-10").html(menu.inputs.number);
                element.find("input").attr("data-name", fields[i].name);
            } else if(fields[i].type == "textarea") {
                element.find(".col-sm-10").html(menu.inputs.textarea);
                element.find("textarea").attr("data-name", fields[i].name);
            } else if(fields[i].type == "sortable") {
                element.find(".col-sm-10").html(menu.inputs.sortable);
                element.find("ul").attr("data-name", fields[i].name);
            }
            
            this.menu_fields[fields[i].name] = element.html();
        }
    }
    // Definindo os eventos do formulário (drag dos itens, executado automaticamente)
    this.setFormEvents = function () {
        const builder = this;
        const left = builder.left_objects;

        builder.draggable_area.sortable({
            update: function (event, ui) {
                var id = ui.item.attr("data-id");
                
                for(var i = 0; i < left.length; i++) {
                    if(left[i].id == id) {
                        var title = left[i].title;
                    }
                }

                if(!ui.item.attr("on-map")) {
                    ui.item.html(builder.getMapObjectHTML({
                        id: id,
                        title: title,
                        keyname: `keyname` + builder.cur_keyname
                    }));

                    ui.item.addClass("p-0");
                    builder.cur_keyname++;
                }

                ui.item.attr("on-map", true);
            },
            handle: ".handler",
            placeholder: "list-group-item-warning py-3"
        }).disableSelection();
    }
    // Definindo os itens arrastáveis (Executado automaticamente)
    this.setObjectArea = function (objects = []) {
        for(var i = 0; i < objects.length; i++) {
            this.addObject(objects[i]);
        }
    }
    // Adicionando um ítem arrastável na lista de itens
    this.addObject = function (object = {}) {
        const element = $(`
            <li class="list-group-item-action list-group-item" style="cursor: move; height: auto; width: auto;" 
                data-id="${object.id}" object>
                <i class="${object.icon}"></i>
                ${object.title}
            </li>
        `);

        this.setObjectEvents(element);
        this.items_list.append(element);
    }
    // Definindo os eventos do ítem arrastável (draggable)
    this.setObjectEvents = function (element) {
        const builder = this;
        
        element.draggable({
            connectToSortable: builder.draggable_area,
            helper: "clone",
            revert: "invalid"
        }).disableSelection();
    }
    // Adicionando ítens a área draggable
    this.addMapObjects = function (objects = []) {
        for(var i = 0; i < objects.length; i++) {
            this.addMapObject(objects[i]);
        }
    }
    // Adicionando um ítem a àrea draggable
    this.addMapObject = function (object = {}) {
        const builder = this;
        const html = this.getMapObjectHTML(object);
        const element = $(`<li class="list-group-item-action list-group-item p-0" 
            style="cursor: move; height: auto; width: auto;" 
            data-id="${object.id}" object on-map="true"></li>`);

        element.append(html);
        element.draggable({ 
            handle: ".handler",
            connectToSortable: builder.draggable_area,
            revert: "invalid"
        }).disableSelection();

        builder.cur_keyname++;
        builder.draggable_area.append(element);
    }
    // Definindo a estrutura HTML do ítem da área draggable
    this.getMapObjectHTML = function (object = {}) {
        const element = $(`
            <div class="d-flex" style="height: 100%;">
                <div class="handler bg-primary d-flex justify-content-around align-items-center p-0" 
                    style="width: 64px; cursor: move;">
                    <i class="icofont-hand-drag text-white" style="font-size: 2rem;"></i>
                </div>
                <div style="width: 100%; height: 100%; cursor: default;">
                    <div class="text-right">
                        <div class="btn-group">
                            <button type="button" class='btn-icon btn-icon-only btn btn-outline-primary mb-1' edit>
                                <i class='pe-7s-note btn-icon-wrapper'> </i>
                            </button>
                            <button type="button" class='btn-icon btn-icon-only btn btn-outline-danger mb-1' delete>
                                <i class='pe-7s-trash btn-icon-wrapper'> </i>
                            </button>
                        </div>
                    </div>
                    <div class="mx-2" style="cursor: default;" view></div>
                    <div class="mx-2" style='display: none; cursor: default;' menu></div>
                </div>
            </div>
        `);

        this.setMenu(element, object);
        this.setView(element, object);
        this.setMapObjectEvents(element, object);
        
        return element;
    }
    // Definindo os eventos do ítem na área draggable
    this.setMapObjectEvents = function (element, object = {}) {
        builder = this;

        element.find("button[delete]").click(function () {
            element.closest("li").remove();
        });

        element.find("button[edit]").click(function () {
            if($(this).attr("opened")) {
                builder.closeMenu(element, object);
            } else {
                builder.openMenu(element, object);
            }
        });

        const menu = element.find("[menu]");

        menu.find("ul").sortable({
            handle: ".opt-handler"
        });

        menu.find("[add-option]").click(function () {
            $(this).parent().parent().children("ul").append(builder.template.menu.inputs.sortable_item);
        });
    }
    // Definindo a View do ítem na área draggable
    this.setView = function (element, object = {}) {
        const view = element.find("[view]");

        var left = this.left_objects;
        var fields = this.view_fields;

        for(var i = 0; i < left.length; i++) {
            if(left[i].id == object.id) {
                var item = fields[left[i].name];
                view.append(item);
                this.updateView(element, object);
            }
        }
    }
    // Definindo o Menu do ítem na área draggable
    this.setMenu = function (element, object = {}) {
        const menu = element.find("[menu]");

        var left = this.left_objects;
        var fields = this.menu_fields;

        for(var i = 0; i < left.length; i++) {
            if(left[i].id == object.id) {
                for(var j = 0; j < left[i].menu.length; j++) {
                    var item = fields[left[i].menu[j]];
                    menu.append(item);
                    this.updateMenu(element, object);
                }
            }
        }
    }
    // Fechando o menu
    this.closeMenu = function (element, object = {}) {
        const btn = element.find("button[edit]");
        const view = element.find("[view]");
        const menu = element.find("[menu]");

        this.updateView(element, object);

        btn.removeAttr("opened");
        btn.removeClass("btn-primary").addClass("btn-outline-primary");

        menu.hide("fast");
        view.show("fast");
    }
    // Abrindo o menu
    this.openMenu = function (element, object = {}) {
        const btn = element.find("button[edit]");
        const view = element.find("[view]");
        const menu = element.find("[menu]");

        btn.attr("opened", true);
        btn.removeClass("btn-outline-primary").addClass("btn-primary");

        view.hide("fast");
        menu.show("fast");
    }
    // Atualizando a View de acordo com as configurações do menu do ítem
    this.updateView = function (element, object = {}) {
        const left = this.left_objects;

        const menu = element.find("[menu]");
        const view = element.find("[view]");

        for(var i = 0; i < left.length; i++) {
            if(left[i].id == object.id) {
                const menu_items = left[i].menu;
                if(menu_items.includes("options")) {
                    if(left[i].name == "select_group") {
                        view.find("div[data-type=group]").html(``);
                        menu.find("ul li").each(function () {
                            view.find("div[data-type=group]").append(`
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" value="${$(this).find("[data-type=value]").val()}">
                                    <label class="form-check-label">${$(this).find("[data-type=label]").val()}</label>
                                </div>
                            `);
                        });
                    } else if(left[i].name == "options_group") {
                        view.find("div[data-type=group]").html(``);
                        menu.find("ul li").each(function () {
                            view.find("div[data-type=group]").append(`
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" value="${$(this).find("[data-type=value]").val()}">
                                    <label class="form-check-label">${$(this).find("[data-type=label]").val()}</label>
                                </div>
                            `);
                        });
                    } else if(left[i].name == "selection") {
                        view.find("[v-elem]").html(``);
                        menu.find("ul li").each(function () {
                            view.find("[v-elem]").append(`
                                <option value="${$(this).find("[data-type=value]").val()}">
                                    ${$(this).find("[data-type=label]").val()}
                                </option>
                            `);
                        });
                    }
                }

                if(menu_items.includes("title")) {
                    view.find("[v-title]").text(menu.find("[data-name=title]").val());
                }

                if(menu_items.includes("keyname")) {
                    view.find("[v-elem]").attr("keyname", menu.find("[data-name=keyname]").val());
                }

                if(menu_items.includes("required")) {
                    if(menu.find("[data-name=required]").is(":checked")) {
                        view.find("[v-elem]").prop("required", true);
                    } else {
                        view.find("[v-elem]").prop("required", false);
                    }
                }

                if(menu_items.includes("placeholder")) {
                    view.find("[v-elem]").attr("placeholder", menu.find("[data-name=placeholder]").val());
                }

                if(menu_items.includes("value")) {
                    view.find("[v-elem]").val(menu.find("[data-name=value]").val());
                }

                if(menu_items.includes("min")) {
                    view.find("[v-elem]").attr("min", menu.find("[data-name=min]").val());
                }

                if(menu_items.includes("max")) {
                    view.find("[v-elem]").attr("max", menu.find("[data-name=max]").val());
                }

                if(menu_items.includes("step")) {
                    view.find("[v-elem]").attr("step", menu.find("[data-name=step]").val());
                }

                if(menu_items.includes("content")) {
                    view.find("[v-elem]").html(menu.find("[data-name=content]").val());
                }

                if(menu_items.includes("maxlength")) {
                    view.find("[v-elem]").attr("maxlength", menu.find("[data-name=maxlength]").val());
                }

                if(menu_items.includes("rows")) {
                    view.find("[v-elem]").attr("rows", menu.find("[data-name=rows]").val());
                }
            }
        }
    }
    // Atualizando o Menu de acordo com o objeto
    this.updateMenu = function (element, object = {}) {
        const builder = this;
        const left = this.left_objects;

        const menu = element.find("[menu]");

        for(var i = 0; i < left.length; i++) {
            if(left[i].id == object.id) {
                const menu_items = left[i].menu;
                if(menu_items.includes("options")) {
                    if(object.options) {
                        for(var j = 0; j < object.options.length; j++) {
                            var item = $(builder.template.menu.inputs.sortable_item);
                            item.find("[data-type=label]").val(object.options[j].label);
                            item.find("[data-type=value]").val(object.options[j].value);
                            menu.find("ul").append(item);
                        }
                    }
                }

                if(menu_items.includes("title")) {
                    menu.find("[data-name=title]").val(object.title);
                }

                if(menu_items.includes("keyname")) {
                    menu.find("[data-name=keyname]").val(object.keyname);
                }

                if(menu_items.includes("required")) {
                    if(object.required) {
                        menu.find("[data-name=required]").prop("checked", true);
                    } else {
                        menu.find("[data-name=required]").prop("checked", false);
                    }
                }

                if(menu_items.includes("placeholder")) {
                    menu.find("[data-name=placeholder]").val(object.placeholder);
                }

                if(menu_items.includes("value")) {
                    menu.find("[data-name=value]").val(object.value);
                }

                if(menu_items.includes("min")) {
                    menu.find("[data-name=min]").val(object.min);
                }

                if(menu_items.includes("max")) {
                    menu.find("[data-name=max]").val(object.max);
                }

                if(menu_items.includes("step")) {
                    menu.find("[data-name=step]").val(object.step);
                }

                if(menu_items.includes("content")) {
                    menu.find("[data-name=content]").val(object.content);
                }

                if(menu_items.includes("maxlength")) {
                    menu.find("[data-name=maxlength]").val(object.maxlength);
                }

                if(menu_items.includes("rows")) {
                    menu.find("[data-name=rows]").val(object.rows);
                }
            }
        }
    }
    // Carregando os objetos (função usável)
    this.loadData = function (objects = []) {
        this.addMapObjects(objects);
    }
    // Salvando e retornando a array
    this.save = function () {
        var array = new Array();

        this.draggable_area.children("li").each(function () {
            var data = $(this).data();
            var object = {};

            var configs = $(this).find("[data-name]");

            object.id = data.id;
            configs.each(function () {
                if($(this).attr("data-name") == "required") {
                    if($(this).is(":checked")) {
                        object.required = true;
                    } else {
                        object.required = false;
                    }
                } else if($(this).attr("data-name") == "other") {
                    if($(this).is(":checked")) {
                        object.other = true;
                    } else {
                        object.other = false;
                    }
                } else if($(this).attr("data-name") == "options") {
                    var options = new Array();
                    $(this).find("li").each(function () {
                        options.push({
                            label: $(this).find("input[data-type=label]").val(),
                            value: $(this).find("input[data-type=value]").val()
                        });
                    });

                    object.options = options;
                } else {
                    object[$(this).attr("data-name")] = $(this).val();
                }
            });

            array.push(object);
        });

        return array;
    }
    // Iniciando o essencial
    this.layout();
    this.setViewFields();
    this.setMenuFields();
    this.setObjectArea(this.left_objects);
}