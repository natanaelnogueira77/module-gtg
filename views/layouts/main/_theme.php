<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php 
        loadStyle('Themes/CoursesMaster/bootstrap');
        loadStyle('Themes/CoursesMaster/owl.carousel');
        loadStyle('Themes/CoursesMaster/slicknav');
        loadStyle('Themes/CoursesMaster/flaticon');
        loadStyle('Themes/CoursesMaster/progressbar_barfiller');
        loadStyle('Themes/CoursesMaster/gijgo');
        loadStyle('Themes/CoursesMaster/animate');
        loadStyle('Themes/CoursesMaster/animated-headline');
        loadStyle('Themes/CoursesMaster/magnific-popup');
        loadStyle('Themes/CoursesMaster/fontawesome-all');
        loadStyle('Themes/CoursesMaster/themify-icons');
        loadStyle('Themes/CoursesMaster/slick');
        loadStyle('Themes/CoursesMaster/nice-select');
        loadStyle('Themes/CoursesMaster/style');
        loadStyle('icofont');
        loadStyle('toastr');
        loadStyle('style');
    ?>
    <link rel="shortcut icon" href="<?= $view->logoIconUrl ?>" type="image/png">
    <title><?= $view->title ?></title>
</head>
<body>
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <img src="<?= $view->logoIconUrl ?>" alt="">
                </div>
            </div>
        </div>
    </div>
    
    <?php 
        echo $view->getBody();

        loadScript('Themes/CoursesMaster/vendor/modernizr');
        loadScript('Themes/CoursesMaster/vendor/jquery');
        loadScript('Themes/CoursesMaster/popper');
        loadScript('Themes/CoursesMaster/bootstrap');
        loadScript('Themes/CoursesMaster/jquery.slicknav');

        loadScript('Themes/CoursesMaster/jquery.vide');
        loadScript('Themes/CoursesMaster/owl.carousel');
        loadScript('Themes/CoursesMaster/slick');
        loadScript('Themes/CoursesMaster/wow');
        loadScript('Themes/CoursesMaster/animated.headline');
        loadScript('Themes/CoursesMaster/jquery.magnific-popup');

        loadScript('Themes/CoursesMaster/gijgo');
        loadScript('Themes/CoursesMaster/jquery.nice-select');
        loadScript('Themes/CoursesMaster/jquery.sticky');
        loadScript('Themes/CoursesMaster/jquery.barfiller');

        loadScript('Themes/CoursesMaster/jquery.counterup');
        loadScript('Themes/CoursesMaster/waypoints');
        loadScript('Themes/CoursesMaster/jquery.countdown');
        loadScript('Themes/CoursesMaster/hover-direction-snake');

        loadScript('Themes/CoursesMaster/contact');
        loadScript('Themes/CoursesMaster/jquery.form');
        loadScript('Themes/CoursesMaster/jquery.validate');
        loadScript('Themes/CoursesMaster/mail-script');
        loadScript('Themes/CoursesMaster/jquery.ajaxchimp');

        loadScript('Themes/CoursesMaster/plugins');
        loadScript('Themes/CoursesMaster/main');

        loadScript('toastr');
        loadScript('jquery.maskedinput');
        loadScript('tinymce');

        loadScript('Utils/Config');

        echo $view->getScripts();
    ?>
</body>
</html>