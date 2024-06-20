<?php if($view->isAction()): ?>
<button type="button" tabindex="0" class="dropdown-item" <?= $view->getAttributes() ?>><?= $view->content ?></button>
<?php elseif($view->isLink()): ?>
<a href="<?= $view->url ?>" tabindex="0" class="dropdown-item" <?= $view->getAttributes() ?>><?= $view->content ?></a>
<?php elseif($view->isHeader()): ?>
<h6 tabindex="-1" class="dropdown-header"><?= $view->content ?></h6>
<?php elseif($view->isDivider()): ?>
<div class="dropdown-divider"></div>
<?php endif; ?>