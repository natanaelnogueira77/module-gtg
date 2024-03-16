<ul class="nav col-auto me-auto mb-2 justify-content-center mb-md-0">
    <?php 
        if($component->hasItems()) {
            foreach($component->getItems() as $item) {
                $this->insert('components/main-layout/header-menu-item', ['component' => $item]);
            }
        }
    ?>
</ul>