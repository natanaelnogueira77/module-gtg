<main class="login-body" data-vide-bg="<?= url($background) ?>">
    <form class="form-default" action="#" method="POST">
        <div class="login-form mt-5">
            <div class="logo-login">
                <a href="#">
                    <img src="<?= url($shortcutIcon) ?>" alt="">
                </a>
            </div>
            <h2>Redefinir Senha</h2>
            <?php if(!isset($code)): ?>
            <div class="form-input">
                <label for="redefine_email">Email</label>
                <input type="email" id="redefine_email" name="redefine_email" placeholder="Digite seu email" 
                    class="form-control <?= $errors['redefine_email'] ? 'is-invalid' : '' ?>" 
                    value="<?= $redefine_email ?>" required>
                <div class="invalid-feedback">
                    <?= $errors['redefine_email'] ?>
                </div>
            </div>
            <div class="form-input pt-30">
                <input type="submit" name="submitting" value="Enviar">
            </div>
            <?php else: ?>
            <div class="form-input">
                <label for="redefine_senha">Senha</label>
                <input type="password" id="redefine_senha" name="redefine_senha" placeholder="Digite sua nova senha" 
                    class="form-control <?= $errors['redefine_senha'] ? 'is-invalid' : '' ?>" required>
                <div class="invalid-feedback">
                    <?= $errors['redefine_senha'] ?>
                </div>
            </div>
            <div class="form-input">
                <label for="confirm_redefine_senha">Confirmar Senha</label>
                <input type="password" id="confirm_redefine_senha" name="confirm_redefine_senha" 
                    class="form-control <?= $errors['confirm_redefine_senha'] ? 'is-invalid' : '' ?>"
                    placeholder="Digite novamente sua senha" required>
                <div class="invalid-feedback">
                    <?= $errors['confirm_redefine_senha'] ?>
                </div>
            </div>
            <div class="form-input pt-30">
                <input type="submit" name="submitting" value="Redefinir">
            </div>
            <?php endif ?>
        </div>
    </form>
</main>