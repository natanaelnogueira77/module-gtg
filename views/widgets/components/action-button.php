<button type="button" class="btn btn-<?= $color ?> btn-<?= $size ?>" <?= $attributes ? implode(' ', array_map(
        fn($key, $value) => $key ? "{$key}=\"{$value}\"" : "{$value}", 
        array_keys($attributes), 
        array_values($attributes)
    )) : '' ?>>
    <?= $content ?>
</button>