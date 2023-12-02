<div class="table-responsive-lg" style="overflow-y: visible;">
    <table class="align-middle mb-0 table table-borderless table-striped table-hover">
        <?php 
            $this->insert('_components/data-table-thead', [
                'headers' => [
                    'actions' => ['text' => _('Ações')],
                    'id' => ['text' => _('ID'), 'sort' => true],
                    'name' => ['text' => _('Nome'), 'sort' => true],
                    'email' => ['text' => _('Email'), 'sort' => true],
                    'created_at' => ['text' => _('Criado em'), 'sort' => true]
                ],
                'order' => [
                    'selected' => $order,
                    'type' => $orderType
                ]
            ]);
        ?>
        <tbody>
            <?php if($data): ?>
            <?php foreach($data as $object): ?>
            <?php $params = ['user_id' => $object->id]; ?>
            <tr>
                <td class="align-middle">
                    <div class="dropup d-inline-block">
                        <button type="button" aria-haspopup="true" aria-expanded="false" 
                            data-toggle="dropdown" class="dropdown-toggle btn btn-sm btn-primary">
                            <?= _('Ações') ?>
                        </button>
                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu">
                            <h6 tabindex="-1" class="dropdown-header"><?= _('Ações') ?></h6>
                            <a href="<?= $router->route('admin.users.edit', $params) ?>" 
                                type="button" tabindex="0" class="dropdown-item">
                                <?= _('Editar Usuário') ?>
                            </a>

                            <button type="button" tabindex="0" class="dropdown-item" 
                                data-act="delete" data-method="delete" 
                                data-action="<?= $router->route('admin.users.delete', $params) ?>">
                                <?= _('Excluir Usuário') ?>
                            </button>
                        </div>
                    </div>
                </td>
                <td class="align-middle">#<?= $object->id ?></td>
                <td class="align-middle">
                    <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left mr-3">
                                <div class="widget-content-left">
                                    <img width="40" class="rounded-circle" src="<?= $object->getAvatarURL() ?>">
                                </div>
                            </div>
                            <div class="widget-content-left">
                                <div class="widget-heading"><?= $object->name ?></div>
                                <div class="widget-subheading opacity-7">
                                    <?= $object->userType->name_sing ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="align-middle"><?= $object->email ?></td>
                <td class="align-middle"><?= $object->getCreatedAtDateTime()->format('d/m/Y') ?></td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <td class="align-middle text-center" colspan="5">
                <?= _('Nenhum usuário foi encontrado!') ?>
            </td>
            <?php endif; ?>
        </tbody>
    </table>
</div>