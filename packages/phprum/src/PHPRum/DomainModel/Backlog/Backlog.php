<?php

namespace PHPRum\DomainModel\Backlog;

use BacklogBundle\Entity\User;
use PHPRum\DomainModel\Backlog\Exception\ItemNotFoundException;

class Backlog
{
    /**
     * @var CompoundItem[]
     */
    private $items;

    /**
     * Backlog constructor.
     *
     * @param CompoundItem[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @param CompoundItem $item
     */
    public function addItem(CompoundItem $item): void
    {
        $this->items[] = $item;
    }

    /**
     * @param $name
     * @param User $user
     *
     * @return CompoundItem
     */
    public function createItem(string $name, User $user): CompoundItem
    {
        $item = $this->doGetItem($name, $user);
        $this->items[] = $item;
        // get item of lowest priority ( higher int == lower prio ) and set new item with +1
        $highestPriority = array_reduce(
            $this->items,
            function ($highestPriority, CompoundItem $item) {
                if ($item->getPriority() > $highestPriority) {
                    return $item->getPriority();
                }

                return $highestPriority;
            },
            0
        );
        $item->setPriority($highestPriority + 1);

        return $item;
    }

    /**
     * @param string $name
     * @param User   $user
     *
     * @return CompoundItem
     */
    protected function doGetItem(string $name, User $user): CompoundItem
    {
        return new CompoundItem($name, $user);
    }

    /**
     * @param int $itemId
     * @param int $priority
     */
    public function changeItemPriority(int $itemId, int $priority): void
    {
        $itemUpdated = $this->getItemById($itemId);

        $originalPriority = $itemUpdated->getPriority();
        $itemUpdated->setPriority($priority);

        // get all items with same or lower priority
        $itemsAffected = array_filter(
            $this->items,
            function (CompoundItem $item) use ($itemUpdated, $originalPriority) {
                $priority = $item->getPriority();

                return
                    $itemUpdated->getId() !== $item->getId() &&
                    $priority >= $itemUpdated->getPriority() &&
                    $priority <= $originalPriority
                ;
            }
        );

        if (!empty($itemsAffected)) {
            array_walk($itemsAffected, function (CompoundItem $item) {
                $item->lowerPriority();
            });
        }
    }

    /**
     * @param int $itemId
     *
     * @return CompoundItem
     *
     * @throws ItemNotFoundException
     */
    protected function getItemById(int $itemId): CompoundItem
    {
        $items = array_filter($this->items, function (CompoundItem $item) use ($itemId) {
            return $item->getId() === $itemId;
        });
        if (empty($items)) {
            throw new ItemNotFoundException($itemId);
        }

        return array_shift($items);
    }

    /**
     * @return CompoundItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
