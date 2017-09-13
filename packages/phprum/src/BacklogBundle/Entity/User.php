<?php


namespace BacklogBundle\Entity;


use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use PHPRum\DomainModel\Backlog\BacklogOwner;

/**
 * Class User
 * @package BacklogBundle\Entity
 */
class User extends BaseUser implements BacklogOwner
{
    /**
     * @var
     */
    protected $id;
}