<ul id="left-menu" class="<?= $component->getStyles() ?>">
    <?php 
        if($component->hasItems()) {
            foreach($component->getItems() as $item) {
                $this->insert('components/main-layout/left-menu-item', ['component' => $item]);
            }
        }
    ?>
</ul>