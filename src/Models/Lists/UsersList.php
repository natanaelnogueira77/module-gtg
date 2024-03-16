<?php 

namespace Src\Models\Lists;

class UsersList extends ActiveRecordList 
{
    public function __construct(
        private int $limit = 10,
        private int $pageToShow = 1,
        private string $orderBy = 'id',
        private string $orderType = 'ASC',
        private ?string $searchTerm = null,
        private ?int $userType = null
    ) 
    {
        parent::__construct(
            limit: $this->limit,
            pageToShow: $this->pageToShow,
            orderBy: $this->orderBy,
            orderType: $this->orderType,
            searchTerm: $this->searchTerm
        );
    }

    public function getFilters(): array 
    {
        return array_merge(
            $this->searchTerm ? [
                'search' => [
                    'term' => $this->searchTerm,
                    'columns' => ['name', 'email', 'slug']
                ]
            ] : [], 
            $this->userType ? ['user_type' => $this->userType] : []
        );
    }
}