<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?= url("resources/views/themes/architect-ui/main.css") ?>">
    <link rel="stylesheet" href="<?= url("public/assets/css/custom.css") ?>">
    <link rel="stylesheet" href="<?= url("public/assets/css/icofont.min.css") ?>">
    <link rel="stylesheet" href="<?= url("public/assets/css/toastr.min.css") ?>">
    <link rel="stylesheet" href="https//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <?= $this->section("css"); ?>
    <link rel="shortcut icon" href="<?= $shortcutIcon ?>" type="image/png">
    <title><?= $title ?></title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="ajax_load">
        <div class="ajax_load_box">
            <div class="ajax_load_box_circle">
                <div class="ajax_rotation"></div>
                <img src="<?= $shortcutIcon ?>" alt="">
            </div>
            <div class="ajax_load_box_title"><?= $loadingText ?></div>
        </div>
    </div>

    <div class="app-container app-theme-white body-tabs-shadow <?= !$noLeft ? 'fixed-sidebar' : '' ?> fixed-header">
        <?php 
            if(!$noHeader) {
                $this->insert("themes/architect-ui/partials/header", $header);
            }
        ?>
        <div class="app-main">
            <?php 
                if(!$noLeft) {
                    $this->insert("themes/architect-ui/partials/left", $left); 
                }
            ?>
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <?= $this->section("content"); ?>
                </div>
                <?php 
                    if(!$noFooter) {
                        $this->insert("themes/architect-ui/partials/footer", $footer);
                    }
                ?>
            </div>
        </div>
    </div>
    <?php 
        $this->insert("themes/architect-ui/partials/scripts");
        $this->insert("components/tinymce", [
            'mlAdd' => $router->route('mediaLibrary.add'),
            'mlLoad' => $router->route('mediaLibrary.load'),
            'mlDelete' => $router->route('mediaLibrary.delete'),
            'storeAt' => $storeAt,
            'path' => ROOT
        ]);
        echo $this->section("scripts");
        echo $this->section("modals");
        $this->insert("themes/messages");
        $this->insert("components/media-library", [
            'mlAdd' => $router->route('mediaLibrary.add'),
            'mlLoad' => $router->route('mediaLibrary.load'),
            'mlDelete' => $router->route('mediaLibrary.delete'),
            'storeAt' => $storeAt,
            'path' => ROOT
        ]);

        if($user) {
            $this->insert('components/expired-session', [
                'action' => $router->route('login.check'),
                'return' => $router->route('login.index'),
                'check' => $router->route('login.expired')
            ]);
        }
    ?>
</body>
</html>