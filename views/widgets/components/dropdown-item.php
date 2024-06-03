<?php if($type == 'action'): ?>
<button type="button" tabindex="0" class="dropdown-item" <?= $attributes ? implode(' ', array_map(
        fn($key, $value) => $key ? "{$key}=\"{$value}\"" : "{$value}", 
        array_keys($attributes), 
        array_values($attributes)
    )) : '' ?>>
    <?= $content ?>
</button>
<?php elseif($type == 'link'): ?>
<a href="<?= $url ?>" tabindex="0" class="dropdown-item" <?= $attributes ? implode(' ', array_map(
        fn($key, $value) => $key ? "{$key}=\"{$value}\"" : "{$value}", 
        array_keys($attributes), 
        array_values($attributes)
    )) : '' ?>>
    <?= $content ?>
</a>
<?php elseif($type == 'header'): ?>
<h6 tabindex="-1" class="dropdown-header"><?= $content ?></h6>
<?php elseif($type == 'divider'): ?>
<div class="dropdown-divider"></div>
<?php endif; ?>