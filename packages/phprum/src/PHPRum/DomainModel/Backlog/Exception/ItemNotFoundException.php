<?php

namespace PHPRum\DomainModel\Backlog\Exception;

class ItemNotFoundException extends BacklogException
{
    const ITEM_NOT_FOUND = 1;

    /**
     * ItemNotFoundException constructor.
     */
    public function __construct(int $id)
    {
        parent::__construct('Item not found by id: '.$id, self::ITEM_NOT_FOUND);
    }
}
