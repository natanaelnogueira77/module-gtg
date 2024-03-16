class MediaLibrary 
{
    static #addFileRoute;
    static #removeFileRoute;
    static #loadFilesRoute;
    static #errorMessages = [];

    #modal;

    #choosenFile = null;
    #maxSizeInMB = 2;
    #filesPerPage = 30;
    #allowedExtensions = ['jpeg', 'jpg', 'png', 'gif'];
    #onSuccess = function(uri, url) {};
    #files = [];

    #uploadTab;
    #imageCaptureTab;
    #MLTab;

    #uploadTabButton;
    #imageCaptureTabButton;
    #MLTabButton;

    #upload;
    #uploadArea;
    #maxSizeText;
    #progressPercentage;
    #progressBar;

    #MLVideo;
    #MLCanvas;
    #MLSnap;
    #MLSaveSnap;
    #MLDiscardSnap;
    #MLCameraSelect;
    #MLCurrentCameraArea;
    #MLCapturedImageArea;

    #form;
    #list;
    #chooseButton;
    #cancelButton;
    #pagination;
    #MLCleanSearch;

    #extensionsList = {
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

    constructor() 
    {
        this.#modal = $('#media-library-modal');

        this.#uploadTab = document.getElementById('ml-tab-1');
        this.#imageCaptureTab = document.getElementById('ml-tab-2');
        this.#MLTab = document.getElementById('ml-tab-3');

        this.#uploadTabButton = document.querySelector("a[href='#ml-tab-1']");
        this.#imageCaptureTabButton = document.querySelector("a[href='#ml-tab-2']");
        this.#MLTabButton = document.querySelector("a[href='#ml-tab-3']");

        this.#upload = document.getElementById('ml-upload');
        this.#uploadArea = document.getElementById('ml-upload-area');
        this.#maxSizeText = document.getElementById('ml-maxsize');
        this.#progressPercentage = document.getElementById('ml-progress');
        this.#progressBar = document.getElementById('ml-progress-bar');

        this.#MLVideo = document.getElementById('ml-video');
        this.#MLCanvas = document.getElementById('ml-canvas');
        this.#MLSnap = document.getElementById('ml-snap');
        this.#MLSaveSnap = document.getElementById('ml-save-snap');
        this.#MLDiscardSnap = document.getElementById('ml-discard-snap');
        this.#MLCameraSelect = document.getElementById('ml-camera-select');
        this.#MLCurrentCameraArea = document.getElementById('ml-current-camera-area');
        this.#MLCapturedImageArea = document.getElementById('ml-captured-image-area');

        this.#form = document.getElementById('ml-images-list');
        this.#list = document.getElementById('ml-list-group');
        this.#chooseButton = document.getElementById('ml-choose');
        this.#cancelButton = document.getElementById('ml-cancel');
        this.#pagination = document.getElementById('ml-pagination');
        this.#MLCleanSearch = document.getElementById('ml-clean-search');
        
        this.#addEvents();
    }

    static setAddFileRoute(route) 
    {
        MediaLibrary.#addFileRoute = route;
    }

    static setRemoveFileRoute(route) 
    {
        MediaLibrary.#removeFileRoute = route;
    }

    static setLoadFilesRoute(route) 
    {
        MediaLibrary.#loadFilesRoute = route;
    }

    static setErrorMessages(errorMessages) 
    {
        MediaLibrary.#errorMessages = errorMessages;
    }

    setMaxSizeInMB(maxSizeInMB) 
    {
        this.#maxSizeInMB = maxSizeInMB;
        return this;
    }

    setFilesPerPage(filesPerPage) 
    {
        this.#filesPerPage = filesPerPage;
        return this;
    }

    setAllowedExtensions(allowedExtensions) 
    {
        this.#allowedExtensions = allowedExtensions;
        return this;
    }

    onSuccess(callback) 
    {
        this.#onSuccess = callback;
        return this;
    }

    show()
    {
        this.#setForShowing();
        this.#modal.modal('show');
    }

    #setForShowing() 
    {
        this.#choosenFile = null;
        this.#updateChooseButton();
        this.#loadFiles();
        this.#maxSizeText.innerHTML = `Max allowed size: ${this.#maxSizeInMB}MB`;
    }

    #addEvents() 
    {
        this.#initCamera();

        this.#uploadArea.addEventListener('mouseenter', function() {
            document.getElementById('ml-area').classList.add('bg-info');
            document.getElementById('ml-area').classList.add('text-white');
        });

        this.#uploadArea.addEventListener('mouseleave', function() {
            document.getElementById('ml-area').classList.remove('bg-info');
            document.getElementById('ml-area').classList.remove('text-white');
        });

        this.#upload.addEventListener('dragenter', function() {
            document.getElementById('ml-area').classList.add('bg-info');
        });

        this.#upload.addEventListener('dragleave', function() {
            document.getElementById('ml-area').classList.remove('bg-info');
        });

        this.#upload.addEventListener('drop', function() {
            document.getElementById('ml-area').classList.remove('bg-info');
        });

        this.#upload.addEventListener('change', (event) => {
            this.#addFile(event);
        });

        this.#form.onsubmit = event => {
            event.preventDefault();
            var term = this.#form.search.value;
            this.#choosenFile = null;
            this.#updateChooseButton();
            this.#loadFiles(term);
        };

        this.#MLCleanSearch.onclick = () => {
            this.#choosenFile = null;
            this.#form.search.value = null;
            this.#updateChooseButton();
            this.#loadFiles();
        };
        
        if(!this.#choosenFile) {
            this.#chooseButton.disabled = true;
        }

        this.#chooseButton.onclick = () => {
            if(this.#chooseButton.disabled == false) {
                if(this.#checkFileExtension(this.#choosenFile.name)) {
                    this.#onSuccess(this.#choosenFile.path, this.#choosenFile.link);
                    this.#modal.modal('toggle');
                } else {
                    var str = this.#allowedExtensions.join(", ");
                    App.showMessage(MediaLibrary.#errorMessages['allowed_extensions'] + str, 'error');
                }
            }
        };

        this.#cancelButton.onclick = () => {
            this.#modal.modal('toggle');
        };
    }

    async #initCamera() 
    {
        const object = this;
        try {
            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = devices.filter(device => device.kind === 'videoinput');
            const options = videoDevices.map(videoDevice => {
                let optionElem = document.createElement('option');
                optionElem.setAttribute('value', videoDevice.deviceId);
                optionElem.innerHTML = videoDevice.label;
                return optionElem;
            });

            if(options) {
                options.forEach(function (elem, i) {
                    object.#MLCameraSelect.appendChild(elem);
                });

                const stream = await navigator.mediaDevices.getUserMedia({ 
                    audio: false, 
                    video: {
                        deviceId: options[0].value
                    }
                });
                window.stream = stream;
                object.#MLVideo.srcObject = stream;

                await new Promise(resolve => object.#MLVideo.onloadedmetadata = resolve);

                object.#MLCanvas.width = object.#MLVideo.videoWidth;
                object.#MLCanvas.height = object.#MLVideo.videoHeight;

                var context = object.#MLCanvas.getContext('2d');
                object.#MLSnap.addEventListener('click', function () {
                    context.drawImage(object.#MLVideo, 0, 0, object.#MLVideo.videoWidth, object.#MLVideo.videoHeight);
                    object.#MLCurrentCameraArea.style.display = 'none';
                    object.#MLCapturedImageArea.style.display = 'block';
                    object.#MLSnap.style.display = 'none';
                    object.#MLSaveSnap.style.display = 'inline';
                    object.#MLDiscardSnap.style.display = 'inline';
                });

                object.#MLDiscardSnap.addEventListener('click', function () {
                    object.#MLCapturedImageArea.style.display = 'none';
                    object.#MLCurrentCameraArea.style.display = 'block';
                    object.#MLSnap.style.display = 'inline';
                    object.#MLSaveSnap.style.display = 'none';
                    object.#MLDiscardSnap.style.display = 'none';
                });

                object.#MLSaveSnap.addEventListener('click', function () {
                    object.#MLSaveSnap.setAttribute('disabled', true);
                    var dataURL = object.#MLCanvas.toDataURL();
                    var byteString = atob(dataURL.split(',')[1]);
                    var mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0];

                    var ab = new ArrayBuffer(byteString.length);
                    var ia = new Uint8Array(ab);
                    for (var i = 0; i < byteString.length; i++) {
                        ia[i] = byteString.charCodeAt(i);
                    }

                    const file = new Blob([ab], {
                        type: mimeString
                    });
                    file.name = 'image-capture.png';

                    var formData = new FormData();
                    formData.append('file', file);

                    $.ajax({
                        url: MediaLibrary.#addFileRoute,
                        type: "post",
                        data: formData,
                        dataType: 'json',
                        success: function (response) {
                            if(response.message) {
                                App.showMessage(response.message[1], response.message[0]);
                            }

                            object.#addFileToList(response.file);
                            object.#chooseFile(response.file);
                            object.#openMediaLibraryTab();
                        },
                        error: function (response) {
                            if(response.responseJSON && response.responseJSON.message) {
                                App.showMessage(response.responseJSON.message[1], "error");
                            }
                        },
                        complete: function () {
                            object.#MLSaveSnap.removeAttribute('disabled');
                        },
                        contentType : false,
                        processData : false
                    });
                });
            }

            object.#MLCameraSelect.onchange = () => {
                object.#setCameraByDeviceId(object.#MLCameraSelect.value);
            }
        } catch(e) {
            console.log(e.toString());
        }
    }

    async #setCameraByDeviceId(deviceId) 
    {
        const object = this;

        try {
            const stream = await navigator.mediaDevices.getUserMedia({ 
                video: {
                    deviceId: deviceId
                }
            });
            window.stream = stream;
            object.#MLVideo.srcObject = stream;
            await new Promise(resolve => object.#MLVideo.onloadedmetadata = resolve);

            object.#MLCanvas.width = object.#MLVideo.videoWidth;
            object.#MLCanvas.height = object.#MLVideo.videoHeight;
            object.#MLSaveSnap.setAttribute('disabled', true);
        } catch(e) {
            console.log(e.toString());
        }
    }

    #openMediaLibraryTab() 
    {
        this.#imageCaptureTabButton.classList.remove("active", "show");
        this.#imageCaptureTab.classList.remove("active", "show");

        this.#uploadTabButton.classList.remove("active", "show");
        this.#uploadTab.classList.remove("active", "show");

        this.#MLTabButton.classList.add("active", "show");
        this.#MLTab.classList.add("active", "show");
    }

    #addFile(event) 
    {
        var object = this;

        const file = event.target.files[0];
        const filename = file.name;

        if(object.#checkFileExtension(filename)) {
            if(file.size > object.#maxSizeInMB * 1024 * 1024) {
                App.showMessage(
                    MediaLibrary.#errorMessages['size_limit'].replace('{size}', object.#maxSizeInMB), 'error'
                );
            } else {
                var reader = new FileReader();

                reader.readAsDataURL(file);
                reader.onload = function(event) {
                    var base64data = reader.result;
                    var formData = new FormData();

                    formData.append('file', file);

                    $.ajax({
                        url: MediaLibrary.#addFileRoute,
                        type: "post",
                        data: formData,
                        dataType: 'json',
                        success: function (response) {
                            if(response.message) {
                                App.showMessage(response.message[1], response.message[0]);
                            }

                            object.#addFileToList(response.file);
                            object.#chooseFile(response.file);
                            object.#openMediaLibraryTab();

                            object.#progressPercentage.innerHTML = "";
                            object.#progressBar.style.width = "0%";
                            object.#progressBar.setAttribute('aria-valuenow', 0);
                        },
                        error: function (response) {
                            if(response.responseJSON && response.responseJSON.message) {
                                App.showMessage(response.responseJSON.message[1], "error");
                            }
                        },
                        contentType : false,
                        processData : false
                    });
                }

                reader.onerror = function(event) {
                    App.showMessage(MediaLibrary.#errorMessages['failed_to_read'], 'error');
                    reader.abort();
                }

                reader.onprogress = function(data) {
                    if(data.lengthComputable) {                                            
                        var progress = parseInt(((data.loaded / data.total) * 100), 10);
                        
                        object.#progressBar.style.width = progress + "%";
                        object.#progressBar.setAttribute('aria-valuenow', progress);
                        object.#progressPercentage.innerHTML = progress + "%";
                    }
                }
            }
        } else {
            var str = object.#allowedExtensions.join(", ");
            App.showMessage(MediaLibrary.#errorMessages['allowed_extensions'] + str, 'error');
        }
    }

    #deleteFile(filename) 
    {
        var object = this;

        $.ajax({
            url: MediaLibrary.#removeFileRoute,
            type: "delete",
            data: { name: filename },
            dataType: 'json',
            success: function (response) {
                if(response.message) {
                    App.showMessage(response.message[1], response.message[0]);
                }
                
                object.#deleteFileFromList(filename);
                if(object.#choosenFile?.name == filename) {
                    object.#choosenFile = null;
                    object.#updateChooseButton();
                }
            },
            error: function (response) {
                if(response.responseJSON && response.responseJSON.message) {
                    App.showMessage(response.responseJSON.message[1], response.responseJSON.message[0]);
                }
            }
        });
    }

    #chooseFile(file) 
    {
        var allFiles = this.#list.querySelectorAll("div[img-name]");
        allFiles.forEach(function (elem) {
            elem.querySelector("div.file-border").classList.remove("border-primary", "border");
        });

        this.#choosenFile = file;
        var file = this.#list.querySelector("div[img-name='" + this.#choosenFile.name + "']");
        file.querySelector("div.file-border").classList.add('border-primary', 'border');

        this.#updateChooseButton();
    }

    #checkFileExtension(filename) 
    {
        var extension = filename.split(".").pop();

        if(this.#allowedExtensions.includes(extension)) {
            return true;
        } else {
            return false;
        }
    }

    #addFileToList(file) 
    {
        if(this.#checkFileExtension(file.name)) {
            var fileExtension = file.name.split(".").pop();
            var imagesTypes = ['jpg', 'jpeg', 'png', 'gif'];
            var object = this;

            let content;
            let div1 = document.createElement('div');
            let button1 = document.createElement('button');
            let div3 = document.createElement('div');
            let icon = document.createElement('i');
            let small = document.createElement('small');
    
            icon.setAttribute('class', "icofont-trash");
            
            if(imagesTypes.includes(fileExtension)) {
                content = document.createElement('img');

                content.setAttribute('src', file.link);
                content.setAttribute('class', 'img-thumbnail');
                content.style.width = "112px";
                content.style.height = "112px";
            } else {
                content = document.createElement('i');
                content.setAttribute('class', `${this.#extensionsList[fileExtension].type} ${this.#extensionsList[fileExtension].color} text-center mt-4`);
                content.style.fontSize = "90px";
                content.style.width = "112px";
                content.style.height = "112px";
            }
            
            button1.setAttribute('class', 'btn btn-sm btn-outline-danger delete');
            button1.setAttribute('type', 'button');
            button1.style.position = 'absolute';
            button1.style.top = 0;
            button1.style.right = 0;
            button1.style.display = 'none';
    
            div1.setAttribute('class', 'mb-2 mr-2');
            div1.setAttribute('img-name', file.name);
            div1.style.position = 'relative';
            div1.style.cursor = 'pointer';
            div1.style.width = "120px";

            div3.setAttribute('class', 'file-border d-flex align-items-center align-middle');
            div3.style.width = "114px";
            div3.style.height = "114px";
            small.innerHTML = file.name;
    
            button1.appendChild(icon);
            div3.appendChild(content);
            div1.appendChild(div3);
            div1.appendChild(small);
            div1.appendChild(button1);
            
            div1.addEventListener('mouseover', function() {
                button1.style.display = 'block';
            });
    
            div1.addEventListener('mouseleave', function() {
                button1.style.display = 'none';
            });
    
            button1.addEventListener('click', function () {
                div1.classList.add("bg-transparent");
                object.#deleteFile(file.name);
            });
    
            div1.addEventListener('click', function () {
                var imageName = div1.getAttribute("img-name");
                object.#chooseFile({
                    name: imageName,
                    path: file.path,
                    link: file.link
                });
            });

            this.#list.insertBefore(div1, this.#list.firstChild);
        }
    }

    #deleteFileFromList(img_name) 
    {
        var elem = this.#list.querySelector("div[img-name='" + img_name + "']");
        if(elem) elem.remove();
    }

    #loadFiles(search = null, page = 1) 
    {
        var object = this;

        $.ajax({
            url: MediaLibrary.#loadFilesRoute,
            type: "get",
            data: { 
                search: search,
                page: page,
                limit: object.#filesPerPage
            },
            dataType: 'json',
            success: function(response) {
                if(response.message) {
                    App.showMessage(response.message[1], response.message[0]);
                }

                object.#pagination.querySelector("ul.pagination").innerHTML = "";
                object.#list.innerHTML = "";

                if(response.files) {
                    object.#files = response.files;
                    for(var i = 0; i < object.#files.length; i++) {
                        object.#addFileToList(object.#files[i]);
                    }
                }

                if(response.pages && response.pages > 1) {
                    object.#setPagination(response.pages, page);
                }
            },
            error: function (response) {
                if(response.responseJSON && response.responseJSON.message) {
                    App.showMessage(response.responseJSON.message[1], response.responseJSON.message[0]);
                }
            }
        });
    }

    #setPagination(pages, curr_page) 
    {
        const object = this;
        if(pages) {
            const pagination = object.#pagination.querySelector("ul.pagination");
            if(curr_page > 1) {
                pagination.innerHTML += `
                    <li class="page-item">
                        <button class="page-link" aria-label="Previous" 
                            data-page="${curr_page - 1}">
                            <span aria-hidden="true">«</span><span class="sr-only">
                                Previous
                            </span>
                        </button>
                    </li>
                `;
            }

            if(curr_page >= 6) {
                for(var i = curr_page - 4; i <= pages && i <= curr_page + 5; i++) {
                    pagination.innerHTML += `
                        <li class="page-item ${curr_page == i ? "active" : ""}">
                            <button class="page-link" aria-label="Page ${i}" data-page="${i}">
                                <span aria-hidden="true">${i}</span><span class="sr-only">Page ${i}</span>
                            </button>
                        </li>
                    `;
                }
            } else {
                for(var i = 1; i <= pages && i <= 10; i++) {
                    pagination.innerHTML += `
                        <li class="page-item ${curr_page == i ? "active" : ""}">
                            <button class="page-link" aria-label="Page ${i}" data-page="${i}">
                                <span aria-hidden="true">${i}</span><span class="sr-only">Page ${i}</span>
                            </button>
                        </li>
                    `;
                }
            }

            if(pages > curr_page) {
                pagination.innerHTML += `
                    <li class="page-item">
                        <button class="page-link" aria-label="Next" 
                            data-page="${parseInt(curr_page) + 1}">
                            <span aria-hidden="true">»</span><span class="sr-only">
                                Next
                            </span>
                        </button>
                    </li>
                `;
            }

            pagination.querySelectorAll("[data-page]").forEach(function (elem) {
                elem.addEventListener("click", function () {
                    var pageNum = parseInt(elem.getAttribute("data-page"));
                    object.#loadFiles(object.#form.search.value, pageNum);
                });
            });
        }
    }

    #updateChooseButton() 
    {
        if(this.#choosenFile) {
            this.#chooseButton.disabled = false;
        } else {
            this.#chooseButton.disabled = true;
        }
    }
}