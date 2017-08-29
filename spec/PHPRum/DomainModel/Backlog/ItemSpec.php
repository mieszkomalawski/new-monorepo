<?php

namespace spec\PHPRum\DomainModel\Backlog;

use BacklogBundle\Entity\User;
use PHPRum\DomainModel\Backlog\Item;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ItemSpec extends ObjectBehavior
{
    function let(User $user)
    {
        $this->beConstructedWith('test-item', $user);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Item::class);
    }
}
