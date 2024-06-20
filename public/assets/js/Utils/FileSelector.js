export default class FileSelector 
{
    #currentId = 0;
    #filepaths = null;
    #isMultiple = false;
    #listElem = null;
    #onAdd = null;
    #onAddSuccess = null;
    #onEdit = null;
    #onEditSuccess = null;
    #onRemove = null;
    #mediaLibrary = null;

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

    constructor(listElem, mediaLibrary) 
    {
        this.#listElem = $(listElem);
        this.#mediaLibrary = mediaLibrary;
    }

    setAsSingle() 
    {
        this.#isMultiple = false;
        this.#filepaths = {};
        return this;
    }

    setAsMultiple() 
    {
        this.#isMultiple = true;
        this.#filepaths = [];
        return this;
    }

    onAdd(func = function() {}) 
    {
        this.#onAdd = func;
        return this;
    }

    onAddSuccess(func = function() {}) 
    {
        this.#onAddSuccess = func;
        return this;
    }

    onEdit(func = function() {}) 
    {
        this.#onEdit = func;
        return this;
    }

    onEditSuccess(func = function() {}) 
    {
        this.#onEditSuccess = func;
        return this;
    }

    onRemove(func = function() {}) 
    {
        this.#onRemove = func;
        return this;
    }

    loadFiles(filepaths) 
    {
        if(filepaths) {
            if(this.#isMultiple) {
                for(const [index, value] of Object.entries(filepaths)) {
                    this.#addToList(this.#getNextId(), value.uri, value.url);
                }
            } else {
                this.#addToList(this.#getNextId(), filepaths.uri, filepaths.url);
            }
        }

        return this;
    }

    #getNextId() 
    {
        this.#currentId++;
        return this.#currentId;
    }

    cleanFiles() 
    {
        this.#filepaths = null;
        this.#currentId = 0;
        return this;
    }

    getList() 
    {
        return this.#filepaths;
    }

    getURIList() 
    {
        if(this.#filepaths) {
            if(this.#isMultiple) {
                return this.#filepaths.map((file) => file.uri);
            } else {
                return this.#filepaths.uri;
            }
        }

        return null;
    }

    getURLList() 
    {
        if(this.#filepaths) {
            if(this.#isMultiple) {
                return this.#filepaths.map((file) => file.url);
            } else {
                return this.#filepaths.url;
            }
        }

        return null;
    }

    render() 
    {
        this.#listElem.children().remove();
        this.#listElem.append(this.#getListWrapperElement());
        this.#listElem.children(":first").append(this.#getAddSlotElement());

        if(this.#filepaths) {
            if(this.#isMultiple) {
                var filepaths = this.#filepaths;
            } else {
                var filepaths = [this.#filepaths];
            }

            for(const [index, value] of Object.entries(filepaths)) {
                this.#addFile(value.id, value.uri, value.url);
            }
        }
        
        return this;
    }

    #getListWrapperElement() 
    {
        return $(`<div class="d-flex flex-wrap justify-content-center"></div>`);
    }

    #getAddSlotElement() 
    {
        const object = this;
        const elem = $(`
            <div style="height: 114px; width: 114px; position: relative; cursor: pointer;" 
                class="img-thumbnail d-flex justify-content-around align-items-center mx-2 mb-2">
                <i class="icofont-plus text-primary"></i>
            </div>
        `);

        elem.click(function() {
            if(object.#onAdd) {
                object.#onAdd(object, elem, object.#getNextId());
            } else {
                object.#mediaLibrary.onSuccess(function(uri, url) {
                    if(object.#onAddSuccess) {
                        object.#onAddSuccess(object, elem, uri, url);
                    } else {
                        object.addToSelector(uri, url);
                    }
                }).show();
            }
        });

        return elem;
    }

    #addFile(id, fileURI, fileURL) 
    {
        const object = this;
        const elem = object.#getFileElement(fileURI, fileURL);

        if(object.#isMultiple) {
            elem.find("[data-act=edit]").click(function() {
                if(object.#onEdit) {
                    object.#onEdit(object, elem, id);
                } else {
                    object.#mediaLibrary.onSuccess(function(uri, url) {
                        if(object.#onEditSuccess) {
                            object.#onEditSuccess(object, elem, id, uri, url);
                        } else {
                            object.updateOnSelector(elem, id, uri, url);
                        }
                    }).show();
                }
            });

            elem.find("[data-act=remove]").click(function() {
                if(object.#onRemove) {
                    object.#onRemove(object, elem, id, fileURI, fileURL);
                } else {
                    object.removeOnSelector(elem, id, fileURI, fileURL);
                }
            });

            elem.insertBefore(object.#listElem.children(":first").children(":last"));
        } else {
            elem.find("[data-act=edit]").click(function() {
                if(object.#onEdit) {
                    object.#onEdit(object, elem, id);
                } else {
                    object.#mediaLibrary.onSuccess(function(uri, url) {
                        if(object.#onEditSuccess) {
                            object.#onEditSuccess(object, elem, id, uri, url);
                        } else {
                            object.updateOnSelector(elem, id, uri, url);
                        }
                    }).show();
                }
            });

            elem.find("[data-act=remove]").click(function() {
                if(object.#onRemove) {
                    object.#onRemove(object, elem, id, fileURI, fileURL);
                } else {
                    object.removeOnSelector(elem, id, fileURI, fileURL);
                }
            });

            object.#listElem.children(":first").children().remove();
            object.#listElem.children(":first").append(elem);
        }
    }

    #getFileElement(fileURI, fileURL) 
    {
        const newFileElement = $(`
            <div style="position: relative; width: 114px; height: auto; border-radius: 15px;" class="mx-2 mb-2">
                <button type="button" class="btn btn-sm btn-outline-primary" 
                    style="position: absolute; cursor: pointer; display: none;" data-act="edit">
                    <i class="icofont-edit"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" 
                    style="position: absolute; cursor: pointer; left: 30px; display: none;" data-act="remove">
                    <i class="icofont-trash"></i>
                </button>
            </div>
        `);


        const filePreviewElement = this.#getFilePreview(fileURI, fileURL);
        newFileElement.append(filePreviewElement);
        this.#setFilePreviewEvents(newFileElement);
        return newFileElement;
    }

    #getFilePreview(fileURI, fileURL) 
    {
        var extension = fileURI.split(".").pop();
        if(['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
            return $(`
                <div data-area="file-preview">
                    <img class="img-thumbnail" style="width: 114px; height: 114px;" src="${fileURL}">
                    <small style="word-break: break-word;">${fileURI.split('/').pop()}</small>
                </div>
            `);
        } else {
            return $(`
                <div data-area="file-preview">
                    <div class="img-thumbnail d-flex justify-content-around align-items-center" style="width: 114px; height: 114px;">
                        <i class="${this.#extensionsList[extension]?.type} ${this.#extensionsList[extension]?.color}" 
                            style="font-size: 90px;"></i>
                    </div>
                    <small style="word-break: break-word;">${fileURI.split('/').pop()}</small>
                </div>
            `);
        }
    }

    #setFilePreviewEvents(filePreviewElement) 
    {
        filePreviewElement.mouseover(function() {
            $(this).find('button').show();
        });
        filePreviewElement.mouseleave(function() {
            $(this).find('button').hide();
        });

        return filePreviewElement;
    }

    addToSelector(uri, url) 
    {
        this.#addFile(this.#getNextId(), uri, url);
        this.#addToList(this.#getCurrentId(), uri, url);

        return this;
    }

    #addToList(id, fileURI, fileURL) 
    {
        if(this.#isMultiple) {
            if(!this.#filepaths) {
                this.#filepaths = [];
            }

            this.#filepaths.push({
                id: id,
                uri: fileURI,
                url: fileURL
            });
        } else {
            this.#filepaths = {
                id: id,
                uri: fileURI,
                url: fileURL
            };
        }

        return this;
    }

    #getCurrentId() 
    {
        return this.#currentId;
    }

    updateOnSelector(elem, id, uri, url) 
    {
        elem.children(":last").remove();
        elem.append(this.#getFilePreview(uri, url));
        this.#setFilePreviewEvents(elem);
        this.#editFromList(id, uri, url);

        return this;
    }

    #editFromList(id, fileURI, fileURL) 
    {
        if(this.#isMultiple) {
            const offset = this.#filepaths.findIndex((i) => {
                return i.id === id;
            });
            this.#filepaths[offset].uri = fileURI;
            this.#filepaths[offset].url = fileURL;
        } else {
            this.#filepaths.uri = fileURI;
            this.#filepaths.url = fileURL;
        }
        
        return this;
    }

    removeOnSelector(elem, id) 
    {
        if(this.#isMultiple) {
            elem.remove();
            this.#removeFromList(id);
        } else {
            elem.remove();
            this.#listElem.children(":first").append(this.#getAddSlotElement());
            this.#removeFromList(id);
        }

        return this;
    }

    #removeFromList(id) 
    {
        if(this.#isMultiple) {
            const offset = this.#filepaths.findIndex((i) => {
                return i.id === id;
            });
            this.#filepaths.splice(offset, 1);
        } else {
            this.#filepaths = {};
        }
        return this;
    }
}