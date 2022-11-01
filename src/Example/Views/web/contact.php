<main>
    <section class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="contact-title">Entre em Contato</h2>
                </div>
                <div class="col-lg-8">
                    <form class="form-contact" action="#" method="post">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control <?= $errors['contato_assunto'] ? 'is-invalid' : '' ?>" 
                                        name="contato_assunto" id="contato_assunto" type="text" 
                                        onfocus="this.placeholder = ''" onblur="this.placeholder = 'Digite o Assunto'" 
                                        placeholder="Digite o Assunto" value="<?= $contato_assunto ?>">
                                    <div class="invalid-feedback">
                                        <?= $errors['contato_assunto'] ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control <?= $errors['contato_nome'] ? 'is-invalid' : '' ?>" 
                                        name="contato_nome" id="contato_nome" type="text" 
                                        onfocus="this.placeholder = ''" onblur="this.placeholder = 'Digite seu nome'" 
                                        placeholder="Digite seu nome" value="<?= isset($user) ? $user->usu_nome : $contato_nome ?>">
                                    <div class="invalid-feedback">
                                        <?= $errors['contato_nome'] ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control <?= $errors['contato_email'] ? 'is-invalid' : '' ?>" 
                                        name="contato_email" id="contato_email" type="email" 
                                        onfocus="this.placeholder = ''" onblur="this.placeholder = 'Digite seu Email'" 
                                        placeholder="Email" value="<?= isset($user) ? $user->usu_email : $contato_email ?>">
                                    <div class="invalid-feedback">
                                        <?= $errors['contato_email'] ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <textarea class="form-control w-100 <?= $errors['contato_mensagem'] ? 'is-invalid' : '' ?>" 
                                        name="contato_mensagem" id="contato_mensagem" cols="30" rows="9" 
                                        onfocus="this.placeholder = ''" onblur="this.placeholder = 'Escreva sua Mensagem'" 
                                        placeholder=" Escreva sua Mensagem"><?= $contato_mensagem ?></textarea>
                                    <div class="invalid-feedback">
                                        <?= $errors['contato_mensagem'] ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-3">
                            <input type="submit" class="button button-contactForm boxed-btn" value="Enviar" name="submitting">
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