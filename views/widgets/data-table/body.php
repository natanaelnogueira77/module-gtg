<tbody>
    <?php 
        if($body['rows']):
            foreach($body['rows'] as $cells) {
                $this->insert('widgets/data-table/row', ['cells' => $cells]);
            }
        else:
    ?>
    <td class="align-middle text-center" colspan="<?= $body['headersCount'] ?>">
        <?= $body['noRowsMessage'] ?? _('Nenhum dado correspondente foi encontrado!') ?>
    </td>
    <?php endif; ?>
</tbody>