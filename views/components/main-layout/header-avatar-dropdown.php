<div class="dropdown text-end">
    <a href="#" class="d-block <?= $component->getAvatarLinkColor() ?> text-decoration-none dropdown-toggle" 
        data-bs-toggle="dropdown" aria-expanded="false">
        <img src="<?= $component->getAvatarImageURL() ?>" width="40" height="40" class="rounded-circle">
    </a>

    <ul class="dropdown-menu text-small">
        <?php 
            if($component->hasItems()) {
                foreach($component->getItems() as $item) {
                    $this->insert('components/dropdown-menu-item', ['component' => $item]);
                }
            }
        ?>
    </ul>
</div>