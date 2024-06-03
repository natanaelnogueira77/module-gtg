<ul class="vertical-nav-menu">
    <?php 
        if($items) {
            foreach($items as $item) {
                $this->insert('widgets/layouts/dashboard/left-nav-item', ['item' => $item]);
            }
        }
    ?>
</ul>