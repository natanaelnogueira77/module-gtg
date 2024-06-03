<div class="dropup d-inline-block">
    <button type="button" aria-haspopup="true" aria-expanded="false" 
        data-toggle="dropdown" class="dropdown-toggle btn btn-sm btn-primary">
        <?= _('Ações') ?>
    </button>

    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu">
        <h6 tabindex="-1" class="dropdown-header"><?= _('Ações') ?></h6>
        <?php 
        if($items): 
            foreach($items as $item):
                if($item['type'] == 'action'):
                ?>
                <button type="button" tabindex="0" class="dropdown-item" 
                    <?= $item['attributes'] ? implode(' ', array_map(
                        fn($key, $value) => $key ? "{$key}=\"{$value}\"" : "{$value}", 
                        array_keys($item['attributes']), 
                        array_values($item['attributes'])
                    )) : '' ?>>
                    <?= $item['content'] ?>
                </button>
                <?php elseif($item['type'] == 'link'): ?>
                <a href="<?= $item['url'] ?>" tabindex="0" class="dropdown-item" 
                    <?= $item['attributes'] ? implode(' ', array_map(
                        fn($key, $value) => $key ? "{$key}=\"{$value}\"" : "{$value}", 
                        array_keys($item['attributes']), 
                        array_values($item['attributes'])
                    )) : '' ?>>
                    <?= $item['content'] ?>
                </a>
                <?php elseif($item['type'] == 'header'): ?>
                <h6 tabindex="-1" class="dropdown-header"><?= $item['content'] ?></h6>
                <?php elseif($item['type'] == 'divider'): ?>
                <div class="dropdown-divider"></div>
                <?php 
                endif;
            endforeach;
        endif; 
        ?>
    </div>
</div>