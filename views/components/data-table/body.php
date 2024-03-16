<tbody>
    <?php 
        if($component->hasRows()) :
            foreach($component->getRows() as $row):
                $this->insert('components/data-table/row', ['component' => $row]);
            endforeach;
    ?>
    <?php else: ?>
    <td class="align-middle text-center" colspan="<?= $component->getHeadersCount() ?>">
        <?= $component->getNoRowsMessage() ?>
    </td>
    <?php endif; ?>
</tbody>