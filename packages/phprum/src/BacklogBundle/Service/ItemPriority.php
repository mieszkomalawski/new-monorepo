<?php


namespace BacklogBundle\Service;


use BacklogBundle\Repository\ItemRepository;
use Doctrine\Common\Persistence\ObjectManager;

class ItemPriority
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * ItemPriority constructor.
     * @param ObjectManager $objectManager
     * @param ItemRepository $itemRepository
     */
    public function __construct(ObjectManager $objectManager, ItemRepository $itemRepository)
    {
        $this->objectManager = $objectManager;
        $this->itemRepository = $itemRepository;
    }

    /**
     * @param int $userId
     * @param int $itemId
     * @param int $newPriority
     */
    public function changeItemPriority(int $userId, int $itemId, int $newPriority) : void
    {
        $backlog = $this->itemRepository->getFullBacklog($userId);

        $backlog->changeItemPriority($itemId, $newPriority);

        foreach ($backlog->getItems() as $item) {
            $this->objectManager->persist($item);
        }
        $this->objectManager->flush();
    }
}