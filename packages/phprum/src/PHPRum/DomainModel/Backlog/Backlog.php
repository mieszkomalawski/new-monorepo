<?php

namespace PHPRum\DomainModel\Backlog;

use BacklogBundle\Entity\User;

class Backlog
{
    /**
     * @var Item[]
     */
    private $items;

    /**
     * Backlog constructor.
     * @param Item[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    /**
     * @param $name
     * @param User $user
     * @return Item
     */
    public function createItem(string $name, User $user): Item
    {
        $item = $this->doGetItem($name, $user);
        $this->items[] = $item;
        // get item of lowest priority ( higher int == lower prio ) and set new item with +1
        $highestPriority = array_reduce($this->items, function ($highestPriority, Item $item) {
            if ($item->getPriority() > $highestPriority) {
                return $item->getPriority();
            }
            return $highestPriority;
        }, 0);
        $item->setPriority($highestPriority + 1);
        return $item;
    }

    /**
     * @param $name
     * @param User $user
     * @return Item
     */
    protected function doGetItem(string $name, User $user): Item
    {
        return new Item($name, $user);
    }

    public function changeItemPriority(int $itemId, int $priority)
    {
        $itemUpdated = $this->getItemById($itemId);

        $originalPriority = $itemUpdated->getPriority();
        $itemUpdated->setPriority($priority);

        // get all items with same or lower priority
        $itemsAffected = array_filter($this->items, function (Item $item) use ($itemUpdated, $originalPriority) {
            $priority = $item->getPriority();
            return (
                $itemUpdated->getId() !== $item->getId() &&
                $priority >= $itemUpdated->getPriority() &&
                $priority <= $originalPriority
            );
        });

        array_walk($itemsAffected, function (Item $item) {
            $item->lowerPriority();
        });
    }

    /**
     * @param int $itemId
     */
    protected function getItemById(int $itemId): Item
    {
        $items = array_filter($this->items, function (Item $item) use ($itemId) {
            return $item->getId() === $itemId;
        });
        return array_shift($items);
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }


}