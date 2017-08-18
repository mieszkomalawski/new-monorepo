<?php


namespace BacklogBundle\Entity;


use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package BacklogBundle\Entity
 */
class User extends BaseUser
{
    /**
     * @var
     */
    protected $id;
}