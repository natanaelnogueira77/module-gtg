<main class="login-body" data-vide-bg="<?= url($background) ?>">
    <form class="form-default" action="#" method="POST">
        <div class="login-form mt-5">
            <div class="logo-login">
                <a href="#">
                    <img src="<?= url($shortcutIcon) ?>" alt="">
                </a>
            </div>
            <h2>Entrar</h2>
            <div class="form-input">
                <label for="name">Email</label>
                <input type="email" id="login_email" name="login_email" 
                    placeholder="Digite seu email" value="<?= $login_email ?>" required>
            </div>
            <div class="form-input">
                <label for="name">Senha</label>
                <input type="password" id="login_senha" name="login_senha" 
                    placeholder="Digite sua senha" value="<?= $login_senha ?>" required>
            </div>
            <div class="form-input pt-30">
                <input type="submit" name="entrar" value="Entrar">
            </div>
            <a href="<?= url('redefinir-senha') ?>" class="forget">Esqueceu a Senha?</a>
        </div>
    </form>
</main>