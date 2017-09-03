<?php


namespace BacklogBundle\Service;


use Doctrine\ORM\EntityRepository;

class CreatorJailer
{
    /**
     * @param int $userId
     * @return callable
     */
    public function getJailingQuery(int $userId) : callable
    {
        $epicQuery = function (EntityRepository $er) use ($userId) {
            $queryBuilder = $er->createQueryBuilder('x');
            return $queryBuilder
                ->select()
                ->where($queryBuilder->expr()->eq('x.creator', '?1'))
                ->setParameter(1, $userId);
        };
        return $epicQuery;
    }
}