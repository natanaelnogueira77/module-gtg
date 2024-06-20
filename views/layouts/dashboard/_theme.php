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

        echo $view->getStyles();

        loadScript('Utils/Config');
    ?>
    <script>
        LIBRARY.set('messages', <?= json_encode($view->getMessages()) ?>);
    </script>
    <link rel="shortcut icon" href="<?= $view->logoIconUrl ?>" type="image/png">
    <title><?= $view->title ?></title>
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow <?= $view->left ? 'fixed-sidebar' : '' ?> fixed-header">
        <?= $view->getHeader() ?>

        <div class="app-main">
            <?= $view->getLeft() ?>
            <div class="app-main__outer">
                <?php if($view->backgroundImageUrl): ?>
                <div class="full-background" style="background-image: url('<?= $view->backgroundImageUrl ?>')"></div>
                <?php endif; ?>
                <div class="app-main__inner">
                    <?= $view->getBody() ?>
                </div>
                <?= $view->getFooter() ?>
            </div>
        </div>
    </div>

    <?php    
        echo $view->getModals();

        loadScript('jquery');
        loadScript('jquery.ui');
        loadScript('popper');
        loadScript('bootstrap');
        loadScript('Themes/ArchitectUI/main');
        loadScript('toastr');
        loadScript('jquery.maskedinput');
        loadScript('tinymce');

        echo $view->getScripts();
    ?>
</body>
</html>