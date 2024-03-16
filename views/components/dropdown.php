<div class="<?= $component->getStyles() ?>">
    <button class="<?= $component->getButtonStyles() ?>" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <?= $component->getText() ?>
    </button>

    <?php $this->insert('components/dropdown-menu', ['component' => $component->getDropdownMenu()]) ?>
</div>