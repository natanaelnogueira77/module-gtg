<ul id="left-menu" class="nav nav-pills flex-column mb-md-auto mb-0 align-items-center 
    align-items-md-start overflow-auto vh-100 position-fixed">
    <?php 
        if($component->hasItems()) {
            foreach($component->getItems() as $item) {
                $this->insert('components/main-layout/left-menu-item', ['component' => $item]);
            }
        }
    ?>
</ul>