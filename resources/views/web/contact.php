<?php 
    $lang = getLang()->setFilepath('views/web/contact')->getContent();
    $this->layout("themes/courses-master/_theme", [
        'title' => $lang->get('title', ['site_name' => SITE])
    ]);
?>

<?php 
    $this->insert('themes/courses-master/components/title', [
        'bg_color' => '#6DB3F2',
        'title' => [
            'text' => $lang->get('title2'),
            'animation' => ['effect' => 'bounceIn', 'delay' => '.2s']
        ],
        'subtitle' => [
            'text' => $lang->get('subtitle'),
            'animation' => ['effect' => 'bounceIn', 'delay' => '.5s']
        ]
    ]);
?>

<main>
    <section class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="contact-title"><?= $lang->get('contact.title') ?></h2>
                </div>

                <div class="col-lg-8">
                    <form class="form-contact" action="<?= $router->route('contact.index') ?>" method="post">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control <?= $errors['subject'] ? 'is-invalid' : '' ?>" 
                                        name="subject" id="subject" type="text" 
                                        onfocus="this.placeholder = ''" 
                                        onblur="this.placeholder = '<?= $lang->get('contact.subject.placeholder') ?>'" 
                                        placeholder="<?= $lang->get('contact.subject.placeholder') ?>" value="<?= $subject ?>">
                                    <div class="invalid-feedback"><?= $errors['subject'] ?></div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control <?= $errors['name'] ? 'is-invalid' : '' ?>" 
                                        name="name" id="name" type="text" 
                                        onfocus="this.placeholder = ''" 
                                        onblur="this.placeholder = '<?= $lang->get('contact.name.placeholder') ?>'" 
                                        placeholder="<?= $lang->get('contact.name.placeholder') ?>" 
                                        value="<?= isset($user) ? $user->name : $name ?>">
                                    <div class="invalid-feedback"><?= $errors['name'] ?></div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control <?= $errors['email'] ? 'is-invalid' : '' ?>" 
                                        name="email" id="email" type="email" 
                                        onfocus="this.placeholder = ''" 
                                        onblur="this.placeholder = '<?= $lang->get('contact.email.placeholder') ?>'" 
                                        placeholder="<?= $lang->get('contact.email.placeholder') ?>" 
                                        value="<?= isset($user) ? $user->email : $email ?>">
                                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <textarea class="form-control w-100 <?= $errors['message'] ? 'is-invalid' : '' ?>" 
                                        name="message" id="message" cols="30" rows="9" 
                                        onfocus="this.placeholder = ''" 
                                        onblur="this.placeholder = '<?= $lang->get('contact.message.placeholder') ?>'" 
                                        placeholder=" <?= $lang->get('contact.message.placeholder') ?>"><?= $message ?></textarea>
                                    <div class="invalid-feedback"><?= $errors['message'] ?></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-flex justify-content-around">
                                    <div class="g-recaptcha" data-sitekey="<?= RECAPTCHA['site_key'] ?>"></div>
                                </div>

                                <small class="text-danger"><?= $errors['recaptcha'] ?></small>
                            </div>
                        </div>
                        
                        <div class="form-group mt-3">
                            <input type="submit" class="button button-contactForm boxed-btn" 
                                value="<?= $lang->get('contact.submit.value') ?>">
                        </div>
                    </form>
                </div>

                <div class="col-lg-3 offset-lg-1">
                    <div class="media contact-info">
                        <span class="contact-info__icon"><i class="ti-email"></i></span>
                        <div class="media-body">
                            <p><?= $lang->get('contact.info1') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>