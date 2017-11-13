<?php

namespace PHPRum\DomainModel\Backlog\Event;

use PHPRum\Event;

class ItemAdded implements Event
{
    const NAME = 'BacklogItemAdded';

    /**
     * @var string
     */
    private $itemName;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * ItemAdded constructor.
     *
     * @param string $itemName
     */
    public function __construct(string $itemName)
    {
        $this->itemName = $itemName;
        $this->date = new \DateTime();
    }

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @return string
     */
    public function getItemName(): string
    {
        return $this->itemName;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }
}
