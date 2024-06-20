<?php 

namespace Models\Lists;

class UsersList extends ActiveRecordList 
{
    public function __construct(
        protected int $limit = 10,
        protected int $pageToShow = 1,
        protected string $orderBy = 'id',
        protected string $orderType = 'ASC',
        protected ?string $searchTerm = null,
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

    public function getUserType(): ?int 
    {
        return $this->userType;
    }
}