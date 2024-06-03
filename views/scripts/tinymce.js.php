<script>
    $(function() {
        tinymce.init({
            selector:'textarea.tinymce',
            language: <?= json_encode($session->getLanguage()[1] == 'es_ES' ? 'es' : $session->getLanguage()[1]) ?>,
            plugins: ['image', 'table'],
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            a11y_advanced_options: true,
            images_file_types: 'jpg,jpeg,png,svg,webp',
            <?php if($session->getAuth()): ?>
            images_upload_handler: function(blobInfo, success, failure, progress) {
                var xhr, formData;

                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', <?= json_encode($router->route('mediaLibrary.addFile')) ?>);

                xhr.upload.onprogress = function (e) {
                    progress(e.loaded / e.total * 100);
                };

                xhr.onload = function() {
                    var json;

                    if(xhr.status === 403) {
                        failure(<?= json_encode(_('Erro de HTTP: ')) ?> + xhr.status, { remove: true });
                        return;
                    }

                    if(xhr.status < 200 || xhr.status >= 300) {
                        failure(<?= json_encode(_('Erro de HTTP: ')) ?> + xhr.status);
                        return;
                    }

                    json = JSON.parse(xhr.responseText);

                    if(!json || typeof json.file.link != 'string') {
                        failure(<?= json_encode(_('JSON Inválido: ')) ?> + xhr.responseText);
                        return;
                    }

                    success(json.file.link);
                };

                xhr.onerror = function() {
                    failure(<?= json_encode(_('O upload da imagem falhou! Código: ')) ?> + xhr.status);
                };

                formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                xhr.send(formData);
            },
            image_list: function(list_success) {
                const images = [];
                $.ajax({
                    url: <?= json_encode($router->route('mediaLibrary.loadFiles')) ?>,
                    type: 'get',
                    data: {
                        limit: 1000,
                        page: 1
                    },
                    dataType: 'json',
                    success: function(response) {
                        if(response.files) {
                            for(var i = 0; i < response.files.length; i++) {
                                images.push({
                                    title: response.files[i].name, 
                                    value: response.files[i].link
                                });
                            }
                        }

                        list_success(images);
                    }
                });
            }
            <?php endif; ?>
        });
    });
</script>