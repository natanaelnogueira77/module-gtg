<?php 

namespace Views\Components\DataTables;

use GTG\MVC\Application;
use Models\Lists\UsersList;
use Views\Components\{
    Material\ActionsDropdown,
    Material\CircleAvatarTitleSubtitle,
    DataTable\Body, 
    DataTable\Cell, 
    DataTable\Filters,
    DataTable\Head,
    DataTable\Header,
    DataTable\Pagination,
    DataTable\Row,
    DataTable\Table as DataTable, 
    Material\DropdownItem
};
use Views\Widget;

class UsersDataTable extends Widget
{
    public function __construct(
        public readonly UsersList $list,
        public readonly ?array $models = null
    )
    {}

    public function __toString(): string 
    {
        return new DataTable(
            list: $this->list, 
            header: new Header(
                heads: [
                    new Head(columnName: 'actions', content: _('Ações')),
                    new Head(columnName: 'name', content: _('Nome'), isSortable: true),
                    new Head(columnName: 'email', content: _('Email'), isSortable: true),
                    new Head(columnName: 'created_at', content: _('Criado em'), isSortable: true)
                ]
            ),
            body: new Body(
                rows: $this->models ? array_map(fn($model) => new Row(
                    cells: [
                        new Cell(content: new ActionsDropdown(
                            children: [
                                new DropdownItem(
                                    type: 'action',
                                    content: _('Editar Usuário'),
                                    attributes: [
                                        'dt-event' => 'edit',
                                        'data-method' => 'get',
                                        'data-action' => Application::getInstance()->router->route('users.show', ['user_id' => $model->id]),
                                        'data-modal-title' => $model->name
                                    ]
                                ),
                                new DropdownItem(
                                    type: 'action',
                                    content: _('Excluir Usuário'),
                                    attributes: [
                                        'dt-event' => 'delete',
                                        'data-method' => 'delete',
                                        'data-action' => Application::getInstance()->router->route('users.delete', ['user_id' => $model->id]),
                                        'data-confirm-message' => sprintf(
                                            _('Você tem certeza de que deseja excluir o usuário %s?'), 
                                            $model->name
                                        )
                                    ]
                                )
                            ]
                        )),
                        new Cell(content: new CircleAvatarTitleSubtitle(
                            imageUrl: $model->getAvatarURL(),
                            title: $model->name,
                            subtitle: $model->getUserType()
                        )),
                        new Cell(content: $model->email),
                        new Cell(content: $model->getCreatedAtDateTime()->format('d/m/Y'))
                    ]
                ), $this->models) : null,
                noRowsMessage: _('Nenhum usuário foi encontrado!')
            )
        );
    }
}