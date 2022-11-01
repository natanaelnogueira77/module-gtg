<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?= url("themes/architect-ui/main.css") ?>">
    <link rel="stylesheet" href="<?= url("public/assets/css/custom.css") ?>">
    <link rel="stylesheet" href="<?= url("public/assets/css/icofont.min.css") ?>">
    <link rel="stylesheet" href="<?= url("public/assets/css/toastr.min.css") ?>">
    <link rel="stylesheet" href="https//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <?= $this->section("css"); ?>
    <link rel="shortcut icon" href="<?= url($shortcutIcon) ?>" type="image/png">
    <title><?= $title ?></title>
</head>
<body>
    <div class="ajax_load">
        <div class="ajax_load_box">
            <div class="ajax_load_box_circle">
                <div class="ajax_rotation"></div>
                <img src="<?= url($shortcutIcon) ?>" alt="">
            </div>
            <div class="ajax_load_box_title">Aguarde, carregando!</div>
        </div>
    </div>

    <div class="app-container app-theme-white body-tabs-shadow <?= !$noLeft ? 'fixed-sidebar' : '' ?> fixed-header">
        <?php 
            if(!$noHeader) {
                $this->insert("template/header", $header);
            }
        ?>
        <div class="app-main">
            <?php 
                if(!$noLeft) {
                    $this->insert("template/left", $left); 
                }
            ?>
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <?php 
                        $this->insert("template/title", ["page" => $page]);
                        echo $this->section("content");
                    ?>
                </div>
                <?php 
                    if(!$noFooter) {
                        $this->insert("template/footer", $footer);
                    }
                ?>
            </div>
        </div>
    </div>
    <?php 
        $this->insert("template/scripts");
        echo $this->section("scripts");
        echo $this->section("modals");
        require_once(__DIR__ . "/../templates/messages.php");
        if($user) {
            $this->insert("template/expired-session", [
                "expiredSession" => $expiredSession
            ]);
        }
        require_once(__DIR__ . "/../templates/media-library.php");
    ?>
</body>
</html>