<div id="left-sidebar" class="<?= $component->getStyles() ?>" 
    style="z-index: 1; height: calc(-75px + 100vh); top: 75px;">
    <aside class="<?= $component->getAsideStyles() ?>">
        <?php $this->insert('components/main-layout/left-menu', ['component' => $component->getMenu()]); ?>
    </aside>
</div>