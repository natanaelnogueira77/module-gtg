<script>
    $(function () {
        const table = $("#users");
        const filters_form = $("#filters");

        const mediaLibrary = new MediaLibrary();
        const FSLogo = (new FileSelector(
            '#logo-area', 
            mediaLibrary.setFileTypes(['jpg', 'jpeg', 'png'])
        ))
        <?php if($configData['logo']): ?>
        .loadFiles({
            uri: <?php echo json_encode($configData['logo']['uri']) ?>,
            url: <?php echo json_encode($configData['logo']['url']) ?>
        })
        <?php endif; ?>
        .render();

        const FSLogoIcon = (new FileSelector(
            '#logo-icon-area', 
            mediaLibrary.setFileTypes(['jpg', 'jpeg', 'png'])
        ))
        <?php if($configData['logo_icon']): ?>
        .loadFiles({
            uri: <?php echo json_encode($configData['logo_icon']['uri']) ?>,
            url: <?php echo json_encode($configData['logo_icon']['url']) ?>
        })
        <?php endif; ?>
        .render();

        const FSLoginImg = (new FileSelector(
            '#login-img-area', 
            mediaLibrary.setFileTypes(['jpg', 'jpeg', 'png'])
        ))
        <?php if($configData['login_img']): ?>
        .loadFiles({
            uri: <?php echo json_encode($configData['login_img']['uri']) ?>,
            url: <?php echo json_encode($configData['login_img']['url']) ?>
        })
        <?php endif; ?>
        .render();

        const dataTable = App.table(table, table.data('action'));
        dataTable.defaultParams(App.form(filters_form).objectify()).filtersForm(filters_form)
        .setMsgFunc((msg) => App.showMessage(msg.message, msg.type)).loadOnChange().addAction((table) => {
            table.find("[data-act=delete]").click(function () {
                var data = $(this).data();

                if(confirm(<?php echo json_encode(_('Deseja realmente excluir este usuário?')) ?>)) {
                    App.callAjax({
                        url: data.action,
                        type: data.method,
                        success: function (response) {
                            dataTable.load();
                        }
                    });
                }
            });
        }).load();

        const DFSystem = App.form($("#system")).setBeforeAjax(function () {
            this.formData['logo'] = FSLogo.getURIList();
            this.formData['logo_icon'] = FSLogoIcon.getURIList();
            this.formData['login_img'] = FSLoginImg.getURIList();
            return this;
        }).setObjectify(true).setSuccessCallback(function (instance, response) {
            window.location.reload();
        }).apply();

        $("[data-info=users]").click(function() {
            var data = $(this).data();
            $("#panel_users").show('fast');
            
            dataTable.params({ user_type: data.id }).load();

            $('html,body').animate({ scrollTop: $("#panels_top").offset().top }, 'slow');
        });
    });
</script>