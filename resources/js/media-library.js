class MediaLibrary {
    // Construtor
    constructor(values = {
        root: "", 
        path: "", 
        fileTypes: ['jpeg', 'jpg', 'png', 'gif'], 
        maxSize: 2,
        load_script: "", 
        add_script: "", 
        del_script: "",
        page_limit: 20
    }) {
        this.app = new App();
        this.root = values.root;
        this.path = values.path;
        this.load = values.load_script;
        this.add = values.add_script;
        this.del = values.del_script;
        this.fileTypes = values.fileTypes;
        this.maxSize = values.maxSize ? values.maxSize : 2;
        this.page_limit = values.page_limit ? values.page_limit : 20;
        this.types_list = {
            jpeg: {
                type: "icofont-file-jpg", 
                color: "text-info"
            },
            jpg: {
                type: "icofont-file-jpg", 
                color: "text-info"
            },
            png: {
                type: "icofont-file-png", 
                color: "text-danger"
            },
            gif: {
                type: "icofont-file-gif", 
                color: "text-primary"
            },
            pdf: {
                type: "icofont-file-pdf", 
                color: "text-danger"
            },
            doc: {
                type: "icofont-file-document", 
                color: "text-primary"
            },
            docx: {
                type: "icofont-file-document", 
                color: "text-primary"
            },
            ppt: {
                type: "icofont-file-presentation", 
                color: "text-danger"
            },
            pptx: {
                type: "icofont-file-presentation", 
                color: "text-danger"
            },
            csv: {
                type: "icofont-file-excel", 
                color: "text-success"
            },
            xls: {
                type: "icofont-file-excel", 
                color: "text-success"
            },
            xlsx: {
                type: "icofont-file-excel", 
                color: "text-success"
            }
        };

        this.filepath = '';
        this.endFunction = function(path) {};
        // Modal
        this.modal = $('#modal-media-library');
        // Lista de Arquivos
        this.files = [];
        // Upload
        this.upload = document.getElementById('ml-upload');
        // Área de Upload
        this.upload_area = document.getElementById('ml-upload-area');
        // Formulário
        this.form = document.getElementById('ml-images-list');
        // Lista
        this.list = document.getElementById('ml-list-group');
        // Texto do Tamanho Máximo
        this.maxSizeText = document.getElementById('ml-maxsize');
        // Arquivo escolhido
        this.choosenFile = document.getElementById('ml-choosen-file').value;
        // Botão de escolher
        this.chooseButton = document.getElementById('ml-choose');
        // Botão de cancelar
        this.cancelButton = document.getElementById('ml-cancel');
        // Barra de Progresso
        this.progressPercentage = document.getElementById('ml-progress');
        this.progressBar = document.getElementById('ml-progress-bar');
        // Tab de Upload de Arquivos
        this.uploadTabButton = document.querySelector("a[href='#ml-tab-1']");
        this.uploadTab = document.getElementById('ml-tab-1');
        // Tab da Biblioteca de Mídia
        this.MLTabButton = document.querySelector("a[href='#ml-tab-2']");
        this.MLTab = document.getElementById('ml-tab-2');
        // Área de Paginação
        this.pagination = document.getElementById("ml-pagination");
        // Registrando os eventos
        this.addEvents();
    }

    openML(params = { accept: [], success: function(path) {}}) {
        if(params.accept) {
            this.fileTypes = params.accept;
        }

        this.endFunction = params.success;

        this.choosenFile = null;
        this.setButtonStatus();
        this.loadFiles(this.load);

        this.modal.modal('show');
    }

    // Registrar Eventos dos formulários e demais objetos
    addEvents() {
        this.upload_area.addEventListener('mouseenter', function() {
            document.getElementById('ml-area').classList.add('bg-info');
            document.getElementById('ml-area').classList.add('text-white');
        });

        this.upload_area.addEventListener('mouseleave', function() {
            document.getElementById('ml-area').classList.remove('bg-info');
            document.getElementById('ml-area').classList.remove('text-white');
        });

        this.upload.addEventListener('dragenter', function() {
            document.getElementById('ml-area').classList.add('bg-info');
        });

        this.upload.addEventListener('dragleave', function() {
            document.getElementById('ml-area').classList.remove('bg-info');
        });

        this.upload.addEventListener('drop', function() {
            document.getElementById('ml-area').classList.remove('bg-info');
        });

        this.upload.addEventListener('change', (event) => {
            this.addFile(this.add, event);
        });

        this.form.onsubmit = event => {
            event.preventDefault();
            var term = this.form.search.value;
            this.choosenFile = null;
            this.setButtonStatus();
            this.loadFiles(this.load, term);
        };
        
        if(!this.choosenFile) {
            this.chooseButton.disabled = true;
        }

        this.chooseButton.onclick = () => {
            if(this.chooseButton.disabled == false) {
                if(this.checkFileExtension(this.choosenFile)) {
                    this.filepath = this.returnFilePath(this.choosenFile);
                    this.endFunction(this.filepath);
                    this.modal.modal('toggle');
                } else {
                    var str = this.fileTypes.join(", ");
                    this.app.showMessage(`A extensão do arquivo que você selecionou não 
                        é permitida aqui! Extensões permitidas: ${str}`, "error");
                }
            }
        };

        this.cancelButton.onclick = () => {
            this.modal.modal('toggle');
        }

        this.setMaxSize(this.maxSize);
    }

    openMLTab() {
        this.uploadTabButton.classList.remove("active", "show");
        this.uploadTab.classList.remove("active", "show");

        this.MLTabButton.classList.add("active", "show");
        this.MLTab.classList.add("active", "show");
    }

    // Adicionar Imagem à lista e ao diretório
    addFile(link, event) {
        var object = this;

        const file = event.target.files[0];
        const filename = file.name;

        if(object.checkFileExtension(filename)) {
            if(file.size > object.maxSize * 1024 * 1024) {
                object.app.showMessage(`O arquivo que você tentou enviar é maior do que ${object.maxSize}MB!`, "error");
            } else {
                var reader = new FileReader();

                reader.readAsDataURL(file);
                reader.onload = function(event) {
                    var base64data = reader.result;
                    var form_data = new FormData();

                    form_data.append('file', file);
                    form_data.append('root', object.root);

                    $.ajax({
                        url: link,
                        type: "POST",
                        data: form_data,
                        dataType: 'json',
                        success: function (response) {
                            if(response.message) {
                                object.app.showMessage(response.message.message, response.message.type);
                            }

                            if(response.success) {
                                object.addFileToList(response.filename);
                                object.chooseFile(response.filename);
                                object.openMLTab();

                                object.progressPercentage.innerHTML = "";
                                object.progressBar.style.width = "0%";
                                object.progressBar.setAttribute('aria-valuenow', 0);
                            }
                        },
                        error: function (response) {
                            object.app.showMessage(`Lamentamos, mas parece que ocorreu um 
                                erro no upload do seu arquivo.`, "error");
                        },
                        contentType : false,
                        processData : false
                    });
                }

                reader.onerror = function(event) {
                    object.app.showMessage("Lamentamos, mas houve uma falha na leitura do arquivo!", "error");
                    reader.abort();
                }

                reader.onprogress = function(data) {
                    if(data.lengthComputable) {                                            
                        var progress = parseInt(((data.loaded / data.total) * 100), 10);
                        
                        object.progressBar.style.width = progress + "%";
                        object.progressBar.setAttribute('aria-valuenow', progress);
                        object.progressPercentage.innerHTML = progress + "%";
                    }
                }
            }
        } else {
            var str = object.fileTypes.join(", ");
            object.app.showMessage(`A extensão do arquivo que você tentou enviar não é permitida aqui! 
                Extensões permitidas: ${str}`, "error");
        }
    }

    // Excluir Imagem da lista e do diretório
    deleteFile(link, filename) {
        var object = this;

        $.ajax({
            url: link,
            type: "DELETE",
            data: { 
                root: this.root,
                name: filename 
            },
            dataType: 'json',
            success: function (response) {
                if(response.message) {
                    object.app.showMessage(response.message.message, response.message.type);
                }
                
                if(response.success) {
                    object.deleteFileFromList(filename);
                    if(object.choosenFile == filename) {
                        object.choosenFile = null;
                        object.setButtonStatus();
                    }
                }
            }
        });
    }

    chooseFile(filename) {
        var allFiles = this.list.querySelectorAll("div[img-name]");
        allFiles.forEach(function (elem) {
            elem.querySelector("div.file-border").classList.remove("border-primary", "border");
        });

        this.choosenFile = filename;
        var file = this.list.querySelector("div[img-name='" + this.choosenFile + "']");
        file.querySelector("div.file-border").classList.add('border-primary', 'border');

        this.setButtonStatus();
    }

    checkFileExtension(filename) {
        var extension = filename.split(".").pop();

        if(this.fileTypes.includes(extension)) {
            return true;
        } else {
            return false;
        }
    }

    // Adiciona HTML da nova imagem
    addFileToList(file_name) {
        if(this.checkFileExtension(file_name)) {
            var fileType = file_name.split(".").pop();
            var imagesTypes = ['jpg', 'jpeg', 'png', 'gif'];
            var object = this;

            let content;
            let div1 = document.createElement('div');
            let div2 = document.createElement('div');
            let div3 = document.createElement('div');
            let icon = document.createElement('i');
            let small = document.createElement('small');
    
            icon.setAttribute('class', "icofont-close text-danger");
            icon.style.fontSize = "22px";
            
            if(imagesTypes.includes(fileType)) {
                content = document.createElement('img');

                content.setAttribute('src', `${this.path ? this.path + '/' : ''}${this.root}/${file_name}`);
                content.setAttribute('class', 'img-thumbnail');
                content.style.width = "112px";
                content.style.height = "112px";
            } else {
                content = document.createElement('i');
                content.setAttribute('class', `${this.types_list[fileType].type} ${this.types_list[fileType].color} text-center mt-4`);
                content.style.fontSize = "90px";
                content.style.width = "112px";
                content.style.height = "112px";
            }
            
            div2.setAttribute('class', 'bg-white p-0 m-0 border border-primary rounded delete');
            div2.style.position = 'absolute';
            div2.style.top = 0;
            div2.style.right = 0;
            div2.style.display = 'none';
    
            div1.setAttribute('class', 'mb-2 mr-2');
            div1.setAttribute('img-name', file_name);
            div1.style.position = 'relative';
            div1.style.cursor = 'pointer';
            div1.style.width = "120px";

            div3.setAttribute('class', 'file-border d-flex align-items-center align-middle');
            div3.style.width = "114px";
            div3.style.height = "114px";
            small.innerHTML = file_name;
    
            div2.appendChild(icon);
            div3.appendChild(content);
            div1.appendChild(div3);
            div1.appendChild(small);
            div1.appendChild(div2);
            
            div1.addEventListener('mouseover', function() {
                div2.style.display = 'block';
            });
    
            div1.addEventListener('mouseleave', function() {
                div2.style.display = 'none';
            });
    
            div2.addEventListener('click', function () {
                div1.classList.add("bg-transparent");
                object.deleteFile(object.del, file_name);
            });
    
            div1.addEventListener('click', function () {
                var filename = div1.getAttribute("img-name");
                object.chooseFile(filename);
            });
    
            this.list.insertBefore(div1, this.list.firstChild);
        }
    }

    deleteFileFromList(img_name) {
        var elem = this.list.querySelector("div[img-name='" + img_name + "']");
        if(elem) elem.remove();
    }

    // Carregar Lista de Imagens
    loadFiles(link, search = null, page = 1) {
        var object = this;

        $.ajax({
            url: link,
            type: "GET",
            data: { 
                root: object.root,
                search: search,
                page: page,
                limit: object.page_limit
            },
            dataType: 'json',
            success: function(response) {
                if(response.message) {
                    object.app.showMessage(response.message.message, response.message.type);
                }

                object.pagination.querySelector("ul.pagination").innerHTML = "";
                object.list.innerHTML = "";

                if(response.files) {
                    object.files = response.files;
                    for(var i = 0; i < object.files.length; i++) {
                        object.addFileToList(object.files[i]);
                    }
                }

                if(response.pages && response.pages > 1) {
                    object.setPagination(response.pages, page);
                }
            }
        });
    }

    setPagination(pages, curr_page) {
        const object = this;
        if(pages) {
            const pagination = this.pagination.querySelector("ul.pagination");
            if(curr_page > 1) {
                pagination.innerHTML += `
                    <li class="page-item">
                        <button class="page-link" aria-label="Anterior" data-page="${curr_page - 1}">
                            <span aria-hidden="true">«</span><span class="sr-only">Anterior</span>
                        </button>
                    </li>
                `;
            }

            if(curr_page >= 6) {
                for(var i = curr_page - 4; i <= pages && i <= curr_page + 5; i++) {
                    pagination.innerHTML += `
                        <li class="page-item ${curr_page == i ? "active" : ""}">
                            <button class="page-link" aria-label="Página ${i}" data-page="${i}">
                                <span aria-hidden="true">${i}</span><span class="sr-only">Página ${i}</span>
                            </button>
                        </li>
                    `;
                }
            } else {
                for(var i = 1; i <= pages && i <= 10; i++) {
                    pagination.innerHTML += `
                        <li class="page-item ${curr_page == i ? "active" : ""}">
                            <button class="page-link" aria-label="Página ${i}" data-page="${i}">
                                <span aria-hidden="true">${i}</span><span class="sr-only">Página ${i}</span>
                            </button>
                        </li>
                    `;
                }
            }

            if(pages > curr_page) {
                pagination.innerHTML += `
                    <li class="page-item">
                        <button class="page-link" aria-label="Próxima" data-page="${parseInt(curr_page) + 1}">
                            <span aria-hidden="true">»</span><span class="sr-only">Próxima</span>
                        </button>
                    </li>
                `;
            }

            pagination.querySelectorAll("[data-page]").forEach(function (elem) {
                elem.addEventListener("click", function () {
                    var pageNum = parseInt(elem.getAttribute("data-page"));
                    object.loadFiles(object.load, object.form.search.value, pageNum);
                });
            });
        }
    }

    setButtonStatus() {
        if(this.choosenFile) {
            this.chooseButton.disabled = false;
        } else {
            this.chooseButton.disabled = true;
        }
    }

    setMaxSize(size) {
        this.maxSizeText.innerHTML = `Tamanho Máximo Permitido: ${size}MB`;
    }

    returnFilePath(filename) {
        return this.root + "/" + filename;
    }
}