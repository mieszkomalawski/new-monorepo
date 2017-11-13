<?php

namespace BacklogBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use PHPRum\DomainModel\Backlog\BacklogOwner;

/**
 * Class User.
 */
class User extends BaseUser implements BacklogOwner
{
    /**
     * @var
     */
    protected $id;
}
