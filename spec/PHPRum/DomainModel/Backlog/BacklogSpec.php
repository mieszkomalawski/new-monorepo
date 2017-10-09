<?php

namespace spec\PHPRum\DomainModel\Backlog;

use BacklogBundle\Entity\User;
use PHPRum\DomainModel\Backlog\Exception\ItemNotFoundException;
use PhpSpec\ObjectBehavior;

class BacklogSpec extends ObjectBehavior
{
    public function it_should_create_first_item_with_priority_zero(User $user)
    {
        $this->beConstructedWith([]);

        $item1 = $this->createItem('item1', $user);

        $item1->getPriority()->shouldBe(1);
    }

    public function it_should_create_next_item_with_lower_priority(User $user)
    {
        $this->beConstructedWith([]);

        $item1 = $this->createItem('item1', $user);
        $item2 = $this->createItem('item2', $user);
        $item3 = $this->createItem('item3', $user);

        $item1->getPriority()->shouldBe(1);
        $item2->getPriority()->shouldBe(2);
        $item3->getPriority()->shouldBe(3);
    }

    public function it_should_throw_exception_when_trying_to_reorder_unexisting_item(User $user)
    {
        $this->beConstructedWith([]);

        $this->shouldThrow(ItemNotFoundException::class)->duringChangeItemPriority(3, 2);
    }

    public function it_should_reorder_item_priorities(User $user)
    {
        $this->beConstructedWith([]);

        $item1 = $this->createItem('item1', $user);
        $item2 = $this->createItem('item2', $user);
        $item3 = $this->createItem('item3', $user);
        $item4 = $this->createItem('item4', $user);

        $item1->setId(1);
        $item2->setId(2);
        $item3->setId(3);
        $item4->setId(4);


        $this->changeItemPriority(3, 2);

        $item1->getPriority()->shouldBe(1);
        $item2->getPriority()->shouldBe(3);
        $item3->getPriority()->shouldBe(2);
        $item4->getPriority()->shouldBe(4);
    }


}
