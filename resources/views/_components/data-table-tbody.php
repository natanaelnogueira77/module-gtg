<tbody>
    <?php if($data): ?>
    <?php foreach($data as $row): ?>
    <tr>
        <?php foreach($headers as $info => $head): ?>
        <td class="align-middle"><?= $row[$info] ?></td>
        <?php endforeach ?>
    </tr>
    <?php endforeach; ?>
    <?php else: ?>
    <td class="align-middle text-center" colspan="<?= is_array($headers) ? count($headers) : 0 ?>">
        <?= _('Nenhum dado correspondente foi encontrado!') ?>
    </td>
    <?php endif; ?>
</tbody>