<?php

namespace BacklogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PHPRum\DomainModel\Backlog\CompoundItem;

class Sprint extends \PHPRum\DomainModel\Backlog\Sprint
{
    /**
     * @var ArrayCollection
     */
    protected $items;

    protected function doAddToItems(CompoundItem $item): void
    {
        $this->items->add($item);
    }

    /**
     * @return int
     */
    public function getTotalPoints(): int
    {
        return array_reduce($this->items->getIterator()->getArrayCopy(), function (int $carry, CompoundItem $item) {
            $carry += $item->getEstimate();

            return $carry;
        }, 0);
    }

    protected function createNexSprint(): \PHPRum\DomainModel\Backlog\Sprint
    {
        return new self($this->duration, $this->creator);
    }
}
