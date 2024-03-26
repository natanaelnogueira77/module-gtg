<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php 
        loadStyle('bootstrap');
        loadStyle('Themes/Main/style');
        loadStyle('icofont');
        loadStyle('toastr');
    ?>
    <?= $this->section('styles'); ?>
    <link rel="shortcut icon" href="<?= $layout->getLogoIconURL() ?>" type="image/png">
    <title><?= $layout->getTitle() ?></title>
</head>
<body>
    <?php 
        if($layout->getHeader()) {
            $this->insert('components/main-layout/header', ['component' => $layout->getHeader()]);
        }
    ?>

    <main class="container-fluid p-0 w-100">
        <div class="d-flex flex-nowrap">
            <?php 
                if($layout->getLeft()) {
                    $this->insert('components/main-layout/left', ['component' => $layout->getLeft()]);
                }
            ?>
            <div id="main-content" class="<?= $layout->getLeft() ? 'col-11 col-md-9 col-xl-10' : 'col-12' ?> 
                min-vh-100 d-flex flex-column">
                <?= $this->section('content') ?>
                <?php 
                    if($layout->getFooter()) {
                        $this->insert('components/main-layout/footer', ['component' => $layout->getFooter()]);
                    }
                ?>
            </div>
        </div>
    </main>

    <?= $this->section('modals') ?>

    <?php 
        if($session->getAuth()) {
            $this->insert('modals/expired-session-login', [
                'formId' => 'expired-session-login-form',
                'modalId' => 'expired-session-login-modal'
            ]); 
        }
    ?>

    <?php 
        loadScript('jquery');
        loadScript('popper');
        loadScript('bootstrap');
        loadScript('toastr');
        loadScript('jquery.maskedinput');
        loadScript('tinymce');

        loadScript('Themes/Main/left-sidebar-toggle');

        loadScript('Utils/DynamicForm');
        loadScript('Utils/DataTable');
        loadScript('Utils/FileSelector');
        loadScript('Utils/MediaLibrary');
        loadScript('Utils/App');
        loadScript('Utils/Config');
    ?>
    
    <?php if($session->getAuth()): ?>
    <script>
        MediaLibrary.setAddFileRoute(<?php echo json_encode($router->route('mediaLibrary.addFile')) ?>);
        MediaLibrary.setRemoveFileRoute(<?php echo json_encode($router->route('mediaLibrary.deleteFile')) ?>);
        MediaLibrary.setLoadFilesRoute(<?php echo json_encode($router->route('mediaLibrary.loadFiles')) ?>);
        MediaLibrary.setErrorMessages(<?php echo json_encode([
            'allowed_extensions' => _('The file extension is not allowed here! Allowed extensions: '),
            'size_limit' => sprintf(_('The file you tried to send is bigger than %sMB!'), '{size}'),
            'failed_to_read' => _("We're sorry, but there was an error when reading the file!")
        ]) ?>);
    </script>
    <?php endif; ?>

    <?php 
        $this->insert('scripts/messages.js');
        if($session->getAuth()) {
            $this->insert('scripts/notifications.js');
            $this->insert('scripts/expired-session-login.js', [
                'formId' => 'expired-session-login-form',
                'modalId' => 'expired-session-login-modal',
            ]);
        }
    ?>

    <?= $this->section('scripts'); ?>
</body>
</html>