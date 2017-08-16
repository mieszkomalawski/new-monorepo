<?php


namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;

class BacklogItemRepository extends EntityRepository implements PaginatorAwareInterface
{
    /**
     * @var Paginator
     */
    private $paginator;

    public function setPaginator(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return PaginationInterface
     */
    public function findByPage(int $page, int $perPage) : PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder('Items');
        $query = $queryBuilder->select('Item')->getQuery();

        return $this->paginator->paginate($query, $page, $perPage);
    }

}