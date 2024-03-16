<ul tabindex="-1" class="<?= $component->getStyles() ?>">
    <?php 
        if($component->hasItems()) {
            foreach($component->getItems() as $item) {
                $this->insert('components/dropdown-menu-item', ['component' => $item]);
            }
        }
    ?>
</ul>