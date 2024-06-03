<div class="text-center">
    <div class="badge badge-<?= $color ?>" <?= $attributes ? implode(' ', array_map(
            fn($key, $value) => $key ? "{$key}=\"{$value}\"" : "{$value}", array_keys($attributes), array_values($attributes)
        )) : '' ?>>
        <?= $content ?>
    </div>
</div>