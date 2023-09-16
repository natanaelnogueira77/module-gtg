<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?= url("public/themes/architect-ui/main.css") ?>">
    <link rel="stylesheet" href="<?= url("public/assets/css/custom.css") ?>">
    <link rel="stylesheet" href="<?= url("public/assets/css/icofont.min.css") ?>">
    <link rel="stylesheet" href="<?= url("public/assets/css/toastr.min.css") ?>">
    <link rel="stylesheet" href="https//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <?= $this->section("css"); ?>
    <link rel="shortcut icon" href="<?= $theme->logo_icon ?>" type="image/png">
    <title><?= $theme->title ?></title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="ajax_load">
        <div class="ajax_load_box">
            <div class="ajax_load_box_circle">
                <div class="ajax_rotation"></div>
                <img src="<?= $theme->logo_icon ?>" alt="">
            </div>
            <div class="ajax_load_box_title"><?= $theme->loading_text ?></div>
        </div>
    </div>

    <div class="app-container app-theme-white body-tabs-shadow <?= $theme->has_left ? 'fixed-sidebar' : '' ?> fixed-header">
        <?php 
            if($theme->has_header) {
                $this->insert("themes/architect-ui/partials/header", ['theme' => $theme]);
            }
        ?>
        <div class="app-main">
            <?php 
                if($theme->has_left) {
                    $this->insert("themes/architect-ui/partials/left", ['theme' => $theme]); 
                }
            ?>
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <?= $this->section("content"); ?>
                </div>
                <?php 
                    if($theme->has_footer) {
                        $this->insert("themes/architect-ui/partials/footer", ['theme' => $theme]);
                    }
                ?>
            </div>
        </div>
    </div>

    <script src="<?= url("public/assets/js/jquery-3.0.0.min.js") ?>"></script>
    <script src="<?= url("public/assets/js/bootstrap.min.js") ?>"></script>
    <script src="<?= url("public/assets/js/bootstrap.bundle.min.js") ?>"></script>
    <script src="<?= url("public/themes/architect-ui/assets/scripts/main.js") ?>"></script>
    <script src="<?= url("public/assets/js/toastr.min.js") ?>"></script>
    <script src="<?= url("public/assets/js/jquery-ui.js") ?>"></script>
    <script src="<?= url("public/assets/js/jquery.ui.touch-punch.js") ?>"></script>
    <script src="<?= url("public/assets/js/jquery.maskedinput.min.js") ?>"></script>
    <script src="<?= url("public/assets/js/tinymce.min.js") ?>"></script>
    <script src="<?= url("public/assets/js/data-table.js") ?>"></script>
    <script src="<?= url("public/assets/js/app.js") ?>"></script>
    <script>
        $(function () {
            const app = new App();
            <?php 
            foreach(['success', 'error', 'info'] as $type):
                if($session->getFlash($type)): 
                ?>
                app.showMessage(
                    <?php echo json_encode($session->getFlash($type)) ?>, 
                    <?php echo json_encode($type) ?>,
                    5000, 
                    5000, 
                    5000, 
                    'toast-bottom-right'
                );
                <?php 
                endif;  
            endforeach;
            ?>
        });
    </script>
    <?php 
        $this->insert("components/tinymce", [
            'mlAdd' => $router->route('mediaLibrary.add'),
            'mlLoad' => $router->route('mediaLibrary.load'),
            'mlDelete' => $router->route('mediaLibrary.delete'),
            'storeAt' => $theme->store_at,
            'path' => url()
        ]);
        
        echo $this->section("scripts");
        echo $this->section("modals");

        $this->insert("components/media-library", [
            'mlAdd' => $router->route('mediaLibrary.add'),
            'mlLoad' => $router->route('mediaLibrary.load'),
            'mlDelete' => $router->route('mediaLibrary.delete'),
            'storeAt' => $theme->store_at,
            'path' => url()
        ]);

        if($session->getAuth()) {
            $this->insert('components/expired-session');
        }
    ?>
</body>
</html>