<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= url('public/assets/css/bootstrap.css') ?>">
    <link rel="stylesheet" href="<?= url('public/assets/css/custom.css') ?>">
    <link rel="stylesheet" href="<?= url('public/assets/css/icofont.css') ?>">
    <link rel="stylesheet" href="<?= url('public/assets/css/toastr.css') ?>">
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

    <main class="container-fluid p-0">
        <div class="d-flex flex-nowrap">
            <?php 
                if($layout->getLeft()) {
                    $this->insert('components/main-layout/left', ['component' => $layout->getLeft()]);
                }
            ?>
            <div class="w-100 min-vh-100 d-flex flex-column">
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
    
    <script src="<?= url('public/assets/js/jquery.js') ?>"></script>
    <script src="<?= url('public/assets/js/popper.js') ?>"></script>
    <script src="<?= url('public/assets/js/bootstrap.js') ?>"></script>
    <script src="<?= url('public/assets/js/toastr.js') ?>"></script>
    <script src="<?= url('public/assets/js/jquery.maskedinput.js') ?>"></script>
    <script src="<?= url('public/assets/js/tinymce.js') ?>"></script>

    <script src="<?= url('public/themes/main/js/left-sidebar-toggle.js') ?>"></script>

    <script src="<?= url('public/assets/js/Utils/DynamicForm.js') ?>"></script>
    <script src="<?= url('public/assets/js/Utils/DataTable.js') ?>"></script>
    <script src="<?= url('public/assets/js/Utils/FileSelector.js') ?>"></script>
    <script src="<?= url('public/assets/js/Utils/MediaLibrary.js') ?>"></script>
    <script src="<?= url('public/assets/js/Utils/App.js') ?>"></script>
    <script src="<?= url('public/assets/js/Utils/Config.js') ?>"></script>
    
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