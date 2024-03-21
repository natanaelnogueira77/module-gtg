<div id="left-sidebar" class="<?= $component->getStyles() ?>" style="z-index: 1;">
    <aside class="<?= $component->getAsideStyles() ?>" style="top: 76px;">
        <?php $this->insert('components/main-layout/left-menu', ['component' => $component->getMenu()]); ?>
    </aside>
</div>