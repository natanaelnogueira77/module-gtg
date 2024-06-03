<ul class="header-menu nav">
    <?php 
        if($items) {
            foreach($items as $item) {
                $this->insert('widgets/layouts/dashboard/header-nav-item', ['item' => $item]);
            }
        }
    ?>
</ul>  