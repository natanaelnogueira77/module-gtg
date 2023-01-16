<?php 
    $this->layout("themes/courses-master/_theme", [
        'title' => 'Contato | ' . SITE
    ]);
?>

<?php 
    $this->insert('themes/courses-master/components/title', [
        'bg_color' => '#6DB3F2',
        'title' => [
            'text' => 'Contato',
            'animation' => ['effect' => 'bounceIn', 'delay' => '.2s']
        ],
        'subtitle' => [
            'text' => 'Para entrar em contato com a Equipe de Suporte do gtg Software para maiores 
                esclarecimentos, preencha o formulário abaixo com seu Nome, Email, Assunto e Mensagem e clique em Enviar. Seu Feedback 
                é altamente apreciado.',
            'animation' => ['effect' => 'bounceIn', 'delay' => '.5s']
        ]
    ]);
?>

<main>
    <section class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="contact-title">Entre em Contato</h2>
                </div>

                <div class="col-lg-8">
                    <form class="form-contact" action="<?= $router->route('contact.index') ?>" method="post">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control <?= $errors['subject'] ? 'is-invalid' : '' ?>" 
                                        name="subject" id="subject" type="text" 
                                        onfocus="this.placeholder = ''" onblur="this.placeholder = 'Qual é o Assunto?'" 
                                        placeholder="Qual é o Assunto?" value="<?= $subject ?>">
                                    <div class="invalid-feedback"><?= $errors['subject'] ?></div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control <?= $errors['name'] ? 'is-invalid' : '' ?>" 
                                        name="name" id="name" type="text" 
                                        onfocus="this.placeholder = ''" onblur="this.placeholder = 'Digite seu Nome'" 
                                        placeholder="Digite seu Nome" value="<?= isset($user) ? $user->name : $name ?>">
                                    <div class="invalid-feedback"><?= $errors['name'] ?></div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control <?= $errors['email'] ? 'is-invalid' : '' ?>" 
                                        name="email" id="email" type="email" 
                                        onfocus="this.placeholder = ''" onblur="this.placeholder = 'Digite seu Email'" 
                                        placeholder="Digite seu Email" value="<?= isset($user) ? $user->email : $email ?>">
                                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <textarea class="form-control w-100 <?= $errors['message'] ? 'is-invalid' : '' ?>" 
                                        name="message" id="message" cols="30" rows="9" 
                                        onfocus="this.placeholder = ''" onblur="this.placeholder = 'Digite sua Mensagem'" 
                                        placeholder=" Digite sua Mensagem"><?= $message ?></textarea>
                                    <div class="invalid-feedback"><?= $errors['message'] ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-3">
                            <input type="submit" class="button button-contactForm boxed-btn" value="Enviar">
                        </div>
                    </form>
                </div>

                <div class="col-lg-3 offset-lg-1">
                    <div class="media contact-info">
                        <span class="contact-info__icon"><i class="ti-email"></i></span>
                        <div class="media-body">
                            <p>Envie-nos sua dúvida a qualquer hora!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>