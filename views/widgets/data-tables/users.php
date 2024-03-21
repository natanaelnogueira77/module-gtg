<?php 

use Src\Views\Components\{ CircularImageWithTitleAndSubtitle, Dropdown, DropdownMenu, DropdownMenuItem };
use Src\Views\Components\DataTable\{ DataTable, Body, Cell, Filters, Head, Header, Row };

$this->insert('components/data-table/table', [
    'component' => new DataTable(
        activeRecordList: $usersList,
        tableHeader: new Header(
            heads: [
                new Head(
                    columnId: 'actions', 
                    text: _('Actions'),
                    isSortable: false
                ),
                new Head(
                    columnId: 'id', 
                    text: _('ID'),
                    isSortable: true
                ),
                new Head(
                    columnId: 'name', 
                    text: _('Name'),
                    isSortable: true
                ),
                new Head(
                    columnId: 'email', 
                    text: _('Email'),
                    isSortable: true
                ),
                new Head(
                    columnId: 'created_at', 
                    text: _('Created At'),
                    isSortable: true
                )
            ]
        ),
        tableBody: new Body(
            noRowsMessage: _('No user was found!'),
            rows: $models ? array_map(function($model) use ($router) {
                return new Row(
                    cells: [
                        new Cell(
                            columnId: 'actions', 
                            text: $this->fetch('components/dropdown', [
                                'component' => new Dropdown(
                                    buttonColor: 'primary',
                                    buttonSize: 'sm',
                                    text: _('Actions'),
                                    menu: new DropdownMenu(
                                        items: [
                                            new DropdownMenuItem(
                                                type: DropdownMenuItem::HEADER_TYPE,
                                                text: _('Actions')
                                            ),
                                            new DropdownMenuItem(
                                                type: DropdownMenuItem::BUTTON_TYPE,
                                                text: _('Edit User'),
                                                attributes: [
                                                    'data-action' => $router->route('users.show', ['user_id' => $model->id]),
                                                    'data-method' => 'get',
                                                    'data-modal-title' => sprintf(_('Edit User - %s'), $model->name),
                                                    'dt-event' => 'edit'
                                                ]
                                            ),
                                            new DropdownMenuItem(
                                                type: DropdownMenuItem::BUTTON_TYPE,
                                                text: _('Delete User'),
                                                attributes: [
                                                    'data-action' => $router->route('users.delete', ['user_id' => $model->id]),
                                                    'data-method' => 'delete',
                                                    'data-confirm-message' => sprintf(_('Are you sure you want to delete the user %s?'), $model->name),
                                                    'dt-event' => 'delete'
                                                ]
                                            ),
                                        ]
                                    )
                                )
                            ])
                        ),
                        new Cell(
                            columnId: 'id', 
                            text: '#' . $model->id
                        ),
                        new Cell(
                            columnId: 'name', 
                            text: $this->fetch('components/circular-image-with-title-and-subtitle', [
                                'component' => new CircularImageWithTitleAndSubtitle(
                                    imageURL: $model->getAvatarURL(),
                                    title: $model->name,
                                    subtitle: $model->getUserType()
                                )
                            ])
                        ),
                        new Cell(
                            columnId: 'email', 
                            text: $model->email
                        ),
                        new Cell(
                            columnId: 'created_at', 
                            text: $model->getCreatedAtDateTime()->format('d/m/Y')
                        )
                    ]
                );
            }, $models) : null,
            headersCount: 5
        ),
        filters: new Filters()
    )
]);