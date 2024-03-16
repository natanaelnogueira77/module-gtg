<script>
    $(function () {
        const loginImageId = <?= json_encode($widget->getLoginImageId()) ?>;
        const logoId = <?= json_encode($widget->getLogoId()) ?>;
        const logoIconId = <?= json_encode($widget->getLogoIconId()) ?>;

        const mediaLibrary = new MediaLibrary();

        const FSLogo = new FileSelector(`#${logoId}`, mediaLibrary.setAllowedExtensions(['jpg', 'jpeg', 'png']));
        if($(`#${logoId}`).data('url')) {
            FSLogo.loadFiles({
                url: $(`#${logoId}`).data('url'),
                uri: $(`#${logoId}`).data('uri')
            });
        }
        FSLogo.render();
        
        const FSLogoIcon = new FileSelector(`#${logoIconId}`, mediaLibrary.setAllowedExtensions(['jpg', 'jpeg', 'png']));
        if($(`#${logoIconId}`).data('url')) {
            FSLogoIcon.loadFiles({
                url: $(`#${logoIconId}`).data('url'),
                uri: $(`#${logoIconId}`).data('uri')
            });
        }
        FSLogoIcon.render();
        
        const FSLoginImage = new FileSelector(`#${loginImageId}`, mediaLibrary.setAllowedExtensions(['jpg', 'jpeg', 'png']));
        if($(`#${loginImageId}`).data('url')) {
            FSLoginImage.loadFiles({
                url: $(`#${loginImageId}`).data('url'),
                uri: $(`#${loginImageId}`).data('uri')
            });
        }
        FSLoginImage.render();

        App.getDynamicForm($("#<?= $widget->getFormId() ?>")).beforeSend(function() {
            this.formData['logoURI'] = FSLogo.getURIList();
            this.formData['logoIconURI'] = FSLogoIcon.getURIList();
            this.formData['loginImageURI'] = FSLoginImage.getURIList();
            return this;
        }).onSuccess(function(instance, response) {
            window.location.reload();
        }).setDoubleClickPrevention(
            $("button[type='submit'][form='<?= $widget->getFormId() ?>']")
        ).apply();
    });
</script>