<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?= url("resources/views/themes/courses-master/assets/css/bootstrap.min.css") ?>">
    <link rel="stylesheet" href="<?= url("resources/views/themes/courses-master/assets/css/owl.carousel.min.css") ?>">
    <link rel="stylesheet" href="<?= url("resources/views/themes/courses-master/assets/css/slicknav.css") ?>">
    <link rel="stylesheet" href="<?= url("resources/views/themes/courses-master/assets/css/flaticon.css") ?>">
    <link rel="stylesheet" href="<?= url("resources/views/themes/courses-master/assets/css/progressbar_barfiller.css") ?>">
    <link rel="stylesheet" href="<?= url("resources/views/themes/courses-master/assets/css/gijgo.css") ?>">
    <link rel="stylesheet" href="<?= url("resources/views/themes/courses-master/assets/css/animate.min.css") ?>">
    <link rel="stylesheet" href="<?= url("resources/views/themes/courses-master/assets/css/animated-headline.css") ?>">
    <link rel="stylesheet" href="<?= url("resources/views/themes/courses-master/assets/css/magnific-popup.css") ?>">
    <link rel="stylesheet" href="<?= url("resources/views/themes/courses-master/assets/css/fontawesome-all.min.css") ?>">
    <link rel="stylesheet" href="<?= url("resources/views/themes/courses-master/assets/css/themify-icons.css") ?>">
    <link rel="stylesheet" href="<?= url("resources/views/themes/courses-master/assets/css/slick.css") ?>">
    <link rel="stylesheet" href="<?= url("resources/views/themes/courses-master/assets/css/nice-select.css") ?>">
    <link rel="stylesheet" href="<?= url("resources/views/themes/courses-master/assets/css/style.css") ?>">
    <link rel="stylesheet" href="<?= url("public/assets/css/icofont.min.css") ?>">
    <link rel="stylesheet" href="<?= url("public/assets/css/toastr.min.css") ?>">
    <link rel="stylesheet" href="<?= url("public/assets/css/custom.css") ?>">
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

    <?php 
        $this->insert("themes/courses-master/partials/preloader", $preloader);
        if(!$noHeader) {
            $this->insert("themes/courses-master/partials/header", $header);
        }
    ?>

    <?= $this->section("content"); ?>

    <?php 
        if(!$noFooter) {
            $this->insert("themes/courses-master/partials/footer", $footer);
        }
        $this->insert("themes/courses-master/partials/scripts");
        $this->insert("components/tinymce");
    ?>

    <?= $this->section("scripts"); ?>
    <?= $this->section("modals"); ?>
    
    <?php $this->insert("themes/messages"); ?>
</body>
</html>