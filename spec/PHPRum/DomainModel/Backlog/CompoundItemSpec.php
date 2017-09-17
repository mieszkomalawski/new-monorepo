<?php

namespace spec\PHPRum\DomainModel\Backlog;

use BacklogBundle\Entity\User;
use PHPRum\DomainModel\Backlog\Exception\InvalidActionException;
use PHPRum\DomainModel\Backlog\Exception\InvalidEstimate;
use PHPRum\DomainModel\Backlog\CompoundItem;
use PHPRum\DomainModel\Backlog\Sprint;
use PHPRum\DomainModel\Backlog\SubItem;
use PhpSpec\ObjectBehavior;

class CompoundItemSpec extends ObjectBehavior
{
    function let(User $user)
    {
        $this->beConstructedWith('test-item', $user);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CompoundItem::class);
    }

    function it_can_be_estimated()
    {
        $this->setEstimate(3);
        $this->getEstimate()->shouldBeLike(3);
    }

    function it_cant_be_estimated()
    {
        $this->shouldThrow(InvalidEstimate::class)->duringSetEstimate(4);
    }

    function it_can_remove_estimate()
    {
        $this->setEstimate(3);
        $this->getEstimate()->shouldBeLike(3);
        $this->setEstimate(0);
        $this->getEstimate()->shouldBeEqualTo(null);
    }

    function it_can_remove_priority()
    {
        $this->setPriority(10);
        $this->getPriority()->shouldBeLike(10);
        $this->setPriority(0);
        $this->getPriority()->shouldBeEqualTo(null);
    }

    function it_can_change_status()
    {
        $this->setStatus(CompoundItem::STATUS_NEW);
        $this->setStatus(CompoundItem::STAUS_IN_PROGRESS);
        $this->setStatus(CompoundItem::STATUS_DONE);
    }

    function it_cant_create_sub_item_if_done()
    {
        $this->setStatus(CompoundItem::STATUS_DONE);
        $this->shouldThrow(\Exception::class)->duringCreateSubItem('sub-item-name');
    }

    function it_can_create_sub_item()
    {
        $this->setStatus(CompoundItem::STATUS_NEW);
        /** @var SubItem $subItem */
        $subItem = $this
            ->createSubItem('new-sub-item')
            ->shouldBeAnInstanceOf(SubItem::class);

        $this->getSubItems()->shouldBe([$subItem]);
    }

    function it_cant_finish_item_that_has_unfinished_sub_item()
    {
        $this->createSubItem('sub1')->setStatus(CompoundItem::STATUS_DONE);
        $this->createSubItem('sub2')->setStatus(CompoundItem::STAUS_IN_PROGRESS);

        $this->shouldThrow(InvalidActionException::class)->duringSetStatus(CompoundItem::STATUS_DONE);
    }

    function it_can_be_added_to_sprint(Sprint $sprint)
    {
        $sub1 = $this->createSubItem('sub1');
        $sub2 = $this->createSubItem('sub2');

        $this->addToSprint($sprint);

        $sub1->getSprint()->shouldBe($sprint);
        $sub2->getSprint()->shouldBe($sprint);
    }

    function it_can_be_removed_from_sprint(Sprint $sprint)
    {
        $sub1 = $this->createSubItem('sub1');
        $sub2 = $this->createSubItem('sub2');

        $this->addToSprint($sprint);

        $this->removeFromSprint();

        $sub1->getSprint()->shouldBe(null);
        $sub2->getSprint()->shouldBe(null);
    }

    public function it_cannot_add_sub_item_to_done_item()
    {
        $this->done();
        $this->shouldThrow(InvalidActionException::class)->duringCreateSubItem('sub1');
    }
}
