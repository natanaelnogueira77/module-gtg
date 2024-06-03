<?php if($item['isHeading'] == true): ?> 
<li class="app-sidebar__heading"><?= $item['text'] ?></li>
<?php else: ?>
<li>
    <a href="<?= $item['url'] ?>" class="<?= in_array($router->route($router->current()->name), array_merge(
            $item['children'] ? array_map(fn($child) => $child['url'], $item['children']) : [], 
            [$item['url']]
        )) ? 'mm-active' : '' ?>">
        <?php if($item['iconClass']): ?>
        <i class="metismenu-icon <?= $item['iconClass'] ?>"></i>
        <?php endif; ?>

        <?= $item['text'] ?>
        <?php if($item['children']): ?>
        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
        <?php endif; ?>
    </a>
    <?php if($item['children']): ?>
    <ul>
        <?php 
            foreach($item['children'] as $child) {
                $this->insert('widgets/layouts/dashboard/left-nav-item', ['item' => $child]);
            }
        ?>
    </ul>
    <?php endif; ?>
</li>
<?php endif; ?>