<section class="slider-area slider-area2">
    <div class="slider-active">
        <!-- Single Slider -->
        <div class="single-slider slider-height2" style="background-color: <?= $page["bg_color"] ?>;">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-11 col-md-12">
                        <div class="hero__caption hero__caption2">
                            <h1 data-animation="<?= $page["title"]["animation"]["effect"] ?>" 
                                data-delay="<?= $page["title"]["animation"]["delay"]  ?>">
                                <?= $page["title"]["text"] ?>
                            </h1>
                            <p data-animation="<?= $page["subtitle"]["animation"]["effect"] ?>" 
                                data-delay="<?= $page["subtitle"]["animation"]["delay"]  ?>">
                                <?= $page["subtitle"]["text"] ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>