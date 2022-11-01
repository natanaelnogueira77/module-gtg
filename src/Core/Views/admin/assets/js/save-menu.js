class MenuContent {
    constructor(config = {}) {
        this.list = $(config.list);
        this.add_button = $(config.add_button);
        this.data_attrs = {
            button: "data-button",
            area: "data-area",
            field: "data-field"
        };

        this.data_names = {
            desc: "desc",
            url: "url",
            icon: "icon",
            type: "type"
        };

        this.data_areas = {
            item: "item",
            title: "title",
            edit: "edit",
            child: "child"
        };

        this.data_buttons = {
            trash: "trash",
            edit: "edit"
        }
        
        this.addEvents();
    }

    addEvents() {
        const object = this;

        object.list.attr(object.data_attrs.area, object.data_areas.child);
        object.list.sortable({
            connectWith: `[${object.data_attrs.area}="${object.data_areas.child}"]`
        });
        object.list.disableSelection();

        object.add_button.click(function () {
            object.add();
        });
    }

    add(data = {}) {
        const object = this;

        const attrs = object.data_attrs;
        const names = object.data_names;
        const areas = object.data_areas;
        const buttons = object.data_buttons;

        const elem = $(`
            <li class="list-group-item-action list-group-item" style="cursor: move;" ${attrs.area}="${areas.item}">
                <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left mr-3" ${attrs.area}="${areas.title}">Novo Item</div>
                        <div class="widget-content-right">
                            <button type="button" class="mr-2 btn-icon btn-icon-only btn btn-outline-danger mb-1" 
                                ${attrs.button}="${buttons.trash}">
                                <i class="icofont-trash btn-icon-wrapper"> </i></button>
                            <button type="button" class="mr-2 btn-icon btn-icon-only btn btn-outline-primary mb-1" 
                                ${attrs.button}="${buttons.edit}">
                                <i class="icofont-edit btn-icon-wrapper"> </i></button>
                        </div>
                    </div>
                    <div style="display: none;" ${attrs.area}="${areas.edit}">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Nome</label>
                                <input type="text" placeholder="Informe o Nome do Item..." class="form-control form-control-sm" 
                                    maxlength="50" ${attrs.field}="${names.desc}" value="Novo Item">
                            </div>
                            <div class="form-group col-md-6">
                                <label>URL</label>
                                <input type="text" placeholder="Informe o Link desse Item..." class="form-control form-control-sm" 
                                    maxlength="255" ${attrs.field}="${names.url}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Ícone</label>
                                <input type="text" placeholder="Informe o Ícone deste Ítem..." class="form-control form-control-sm" 
                                    maxlength="50" ${attrs.field}="${names.icon}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tipo</label>
                                <select class="form-control form-control-sm" ${attrs.field}="${names.type}">
                                    <option value="item">Ítem</option>
                                    <option value="heading">Cabeçalho</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="list-group" style="padding: 15px; background: #eeeeee" ${attrs.area}="${areas.child}"></ul>
            </li>
        `);

        elem.find(`[${attrs.area}="${areas.child}"]`).sortable({
            connectWith: `[${attrs.area}="${areas.child}"]`
        });

        elem.find(`[${attrs.field}=${names.desc}]`).keyup(function () {
            var title = $(this).val();
            elem.find(`[${attrs.area}=${areas.title}]`).text(title);
        });

        elem.find(`[${attrs.button}=${buttons.trash}]`).click(function () {
            elem.remove();
        });

        elem.find(`[${attrs.button}=${buttons.edit}]`).click(function () {
            var area = elem.find(`[${attrs.area}=${areas.edit}]`);
            if($(this).attr("active")) {
                elem.find(`[${attrs.area}=${areas.edit}]`).first().hide("fast");

                $(this).removeAttr("active");
                $(this).addClass(`btn-outline-primary`).removeClass(`btn-primary`);
            } else {
                elem.find(`[${attrs.area}=${areas.edit}]`).first().show("fast");

                $(this).attr("active", true);
                $(this).removeClass(`btn-outline-primary`).addClass(`btn-primary`);
            }
        });

        if(data) {
            if(data.desc) {
                elem.find(`[${attrs.area}=${areas.title}]`).text(data.desc);
                elem.find(`[${attrs.field}=${names.desc}]`).val(data.desc);
            }

            if(data.url) {
                elem.find(`[${attrs.field}=${names.url}]`).val(data.url);
            }

            if(data.icon) {
                elem.find(`[${attrs.field}=${names.icon}]`).val(data.icon);
            }

            if(data.type) {
                elem.find(`[${attrs.field}=${names.type}]`).val(data.type);
            }

            if(data.level) {
                elem.find(`[${attrs.area}="${areas.child}"]`).attr("level", data.level);
            }
        }

        if(data && data.level >= 2) {
            var selector = "";
            selector += `[${attrs.area}=${areas.child}][level=${data.level - 1}] `;
            this.list.find(selector).last().append(elem);
        } else {
            this.list.append(elem);
        }
    }

    loadData(data = []) {
        if(data) {
            for(var i = 0; i < data.length; i++) {
                this.add(data[i]);
            }
        }
    }

    save() {
        const attrs = this.data_attrs;
        const names = this.data_names;
        const areas = this.data_areas;

        var data = new Array();

        this.list.find(`[${attrs.area}=${areas.item}]`).each(function() {
            data.push({
                desc: $(this).find(`[${attrs.field}=${names.desc}]`).val(), 
                url: $(this).find(`[${attrs.field}=${names.url}]`).val(), 
                icon: $(this).find(`[${attrs.field}=${names.icon}]`).val(), 
                type: $(this).find(`[${attrs.field}=${names.type}]`).val(), 
                level: $(this).parents(`[${attrs.area}=${areas.child}]`).length
            });
        });

        return data;
    }
}

$(function () {
    const app = new App();
    const menu_content = new MenuContent({
        list: "#menu-content",
        add_button: "#add-menu-item"
    });

    if(menuData) {
        menu_content.loadData(menuData);
    }

    $("form#save-menu").submit(function (e) {
        e.preventDefault();
        var form = $(this);

        var result = menu_content.save();
        var result_string = JSON.stringify(result);
        form.find("[name=content]").val(result_string);

        app.callAjax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: form.serialize(),
            success: function(response) {
                var errors = {};
                if(response.errors) {
                    errors = response.errors;
                }

                app.showFormErrors(form, errors, 'name');

                if(response.link) window.location.href = response.link;
            }
        });
    });
});