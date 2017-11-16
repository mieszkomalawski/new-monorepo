<?php


namespace BacklogBundle\Form;


use PHPRum\DomainModel\Backlog\ItemStatus;
use Symfony\Component\Form\DataTransformerInterface;

class ItemStatusTransformer implements DataTransformerInterface
{
    public function transform($itemStatus)
    {
        if ($itemStatus instanceof ItemStatus) {
            return $itemStatus->getValue();
        }
        return $itemStatus;
    }

    public function reverseTransform($status)
    {
        if (is_null($status)) {
            return ItemStatus::NEW();
        }
        if ($status instanceof ItemStatus) {
            return $status;
        }
        return new ItemStatus($status);
    }

}