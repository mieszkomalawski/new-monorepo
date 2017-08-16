<?php

namespace PHPRum\DomainModel\Backlog;

class Backlog
{
    /**
     * @var Item[]
     */
    private $items;

    /**
     * @param Item $item
     */
    public function addItem(Item $item) : void
    {
        $this->items[] = $item;
    }
}