<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php 
        loadStyle('Themes/ArchitectUI/main');
        loadStyle('icofont');
        loadStyle('toastr');
        loadStyle('style');
        loadStyle('jquery-ui');
        echo $this->section('css');
    ?>
    <link rel="shortcut icon" href="<?= $theme->getLogoIconURL() ?>" type="image/png">
    <title><?= $theme->getTitle() ?></title>
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow <?= $theme->hasLeft() ? 'fixed-sidebar' : '' ?> fixed-header">
        <?php 
            if($theme->hasHeader()) {
                $this->insert('widgets/layouts/dashboard/header', $theme->getHeader()); 
            }
        ?>

        <div class="app-main">
            <?php 
                if($theme->hasLeft()) {
                    $this->insert('widgets/layouts/dashboard/left', $theme->getLeft()); 
                }
            ?>
            <div class="app-main__outer">
                <div class="full-background" style="background-image: url('<?= $theme->getBackgroundImageURL() ?>')"></div>
                <div class="app-main__inner">
                    <?= $this->section('content'); ?>
                </div>
                <?php 
                    if($theme->hasFooter()) {
                        $this->insert('widgets/layouts/dashboard/footer', $theme->getFooter()); 
                    }
                ?>
            </div>
        </div>
    </div>

    <?php 
        echo $this->section('modals');

        if($theme->isLogged()) {
            $this->insert('widgets/modals/expired-session-login');
        }

        loadScript('jquery');
        loadScript('jquery.ui');
        loadScript('popper');
        loadScript('bootstrap');
        loadScript('Themes/ArchitectUI/main');
        loadScript('toastr');
        loadScript('jquery.maskedinput');
        loadScript('tinymce');

        loadScript('Utils/DynamicForm');
        loadScript('Utils/DataTable');
        loadScript('Utils/FileSelector');
        loadScript('Utils/MediaLibrary');
        loadScript('Utils/App');
        loadScript('Utils/Config');

        $this->insert('scripts/media-library-config.js');
        $this->insert('scripts/messages.js');
        if($theme->isLogged()) {
            $this->insert('scripts/notifications.js');
        }
        $this->insert('scripts/tinymce.js');

        echo $this->section('scripts');
    ?>
</body>
</html>