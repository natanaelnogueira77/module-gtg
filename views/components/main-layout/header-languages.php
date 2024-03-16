<?php 
    use Src\Views\Components\DropdownMenu;
    use Src\Views\Components\DropdownMenuItem;
?>
<div class="dropdown text-end me-2">
    <a data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
        <img width="42" class="rounded-circle" src="<?= $component->getCurrentLanguageImageURL() ?>" alt="">
    </a>

    <?php 
        $this->insert('components/dropdown-menu', [
            'component' => new DropdownMenu(
                items: array_map(function($language) {
                    return new DropdownMenuItem(
                        type: DropdownMenuItem::LINK_TYPE,
                        text: $language->getName(),
                        url: $language->getURL()
                    );
                }, $component->getLanguages())
            )
        ]);
    ?>
</div>