<?php 
    $this->layout("themes/main/_theme", [
        'title' => sprintf('Get in Touch | %s', $appData['app_name'])
    ]);
?>

<main>
    <section>
        <h2>Entre em Contato</h2>

        <form action="<?= $router->route('contact.index') ?>" method="post">
            <input name="subject" id="subject" type="text" 
                placeholder="What is the subject?" value="<?= $contactForm->subject ?>">
            <small>
                <?= $contactForm->hasError('subject') ? $contactForm->getFirstError('subject') : '' ?>
            </small>

            <input name="name" id="name" type="text" placeholder="Type your name..." 
                value="<?= isset($user) ? $user->name : $contactForm->name ?>">
            <small>
                <?= $contactForm->hasError('name') ? $contactForm->getFirstError('name') : '' ?>
            </small>

            <input name="email" id="email" type="email" placeholder="Type your email..." 
                value="<?= isset($user) ? $user->email : $contactForm->email ?>">
            <small>
                <?= $contactForm->hasError('email') ? $contactForm->getFirstError('email') : '' ?>
            </small>

            <textarea name="body" id="body" cols="30" rows="9" 
                placeholder="Type your message..."><?= $contactForm->body ?></textarea>
            <small>
                <?= $contactForm->hasError('body') ? $contactForm->getFirstError('body') : '' ?>
            </small>
            
            <input type="submit" value="Send">
        </form>
    </section>
</main>