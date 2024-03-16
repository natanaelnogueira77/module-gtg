<div>
    <?php $this->insert('components/data-table/pagination', ['component' => $component->getPagination()]); ?>
    <table class="<?= $component->getStyles() ?>">
        <?php 
            $this->insert('components/data-table/header', ['component' => $component->getTableHeader()]);
            $this->insert('components/data-table/body', ['component' => $component->getTableBody()]);
        ?>
    </table>
</div>