<tbody>
    <?php 
        if($view->getRows()):
            echo $view->getRows();
        else:
    ?>
    <td class="align-middle text-center" colspan="<?= $view->headsCount ?>">
        <?= $view->noRowsMessage ?? _('Nenhum dado correspondente foi encontrado!') ?>
    </td>
    <?php endif; ?>
</tbody>