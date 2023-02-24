<?php 
    $this->layout("themes/courses-master/_theme", [
        'title' => sprintf(_('Contato | %s'), SITE)
    ]);
?>

<?php 
    $this->insert('themes/courses-master/components/title', [
        'bg_color' => '#6DB3F2',
        'title' => [
            'text' => _('Contato'),
            'animation' => ['effect' => 'bounceIn', 'delay' => '.2s']
        ],
        'subtitle' => [
            'text' => _('Para entrar em contato conosco para maiores esclarecimentos, preencha o formulário abaixo com seu 
                Nome, Email, Assunto e Mensagem e clique em Enviar. Seu Feedback é altamente apreciado.'),
            'animation' => ['effect' => 'bounceIn', 'delay' => '.5s']
        ]
    ]);
?>

<main>
    <section class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="contact-title"><?= _('Entre em Contato') ?></h2>
                </div>

                <div class="col-lg-8">
                    <form id="contact-form" class="form-contact" action="<?= $router->route('contact.index') ?>" method="post">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control <?= $errors['subject'] ? 'is-invalid' : '' ?>" 
                                        name="subject" id="subject" type="text" 
                                        onfocus="this.placeholder = ''" 
                                        onblur="this.placeholder = '<?= _('Qual é o Assunto?') ?>'" 
                                        placeholder="<?= _('Qual é o Assunto?') ?>" value="<?= $subject ?>">
                                    <div class="invalid-feedback"><?= $errors['subject'] ?></div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control <?= $errors['name'] ? 'is-invalid' : '' ?>" 
                                        name="name" id="name" type="text" 
                                        onfocus="this.placeholder = ''" 
                                        onblur="this.placeholder = '<?= _('Digite seu Nome') ?>'" 
                                        placeholder="<?= _('Digite seu Nome') ?>" 
                                        value="<?= isset($user) ? $user->name : $name ?>">
                                    <div class="invalid-feedback"><?= $errors['name'] ?></div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control <?= $errors['email'] ? 'is-invalid' : '' ?>" 
                                        name="email" id="email" type="email" 
                                        onfocus="this.placeholder = ''" 
                                        onblur="this.placeholder = '<?= _('Digite seu Email') ?>'" 
                                        placeholder="<?= _('Digite seu Email') ?>" 
                                        value="<?= isset($user) ? $user->email : $email ?>">
                                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <textarea class="form-control w-100 <?= $errors['message'] ? 'is-invalid' : '' ?>" 
                                        name="message" id="message" cols="30" rows="9" 
                                        onfocus="this.placeholder = ''" 
                                        onblur="this.placeholder = '<?= _('Digite sua Mensagem') ?>'" 
                                        placeholder=" <?= _('Digite sua Mensagem') ?>"><?= $message ?></textarea>
                                    <div class="invalid-feedback"><?= $errors['message'] ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-3">
                            <input type="submit" data-sitekey="<?= RECAPTCHA['site_key'] ?>"
                                data-callback='onSubmit' data-action='submit' 
                                class="g-recaptcha button button-contactForm boxed-btn" value="<?= _('Enviar') ?>">
                        </div>
                    </form>
                </div>

                <div class="col-lg-3 offset-lg-1">
                    <div class="media contact-info">
                        <span class="contact-info__icon"><i class="ti-email"></i></span>
                        <div class="media-body">
                            <p><?= _('Envie-nos sua dúvida a qualquer hora!') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php $this->start('scripts'); ?>
<script>
    function onSubmit(token) {
        document.getElementById("contact-form").submit();
    }
</script>
<?php $this->end(); ?>