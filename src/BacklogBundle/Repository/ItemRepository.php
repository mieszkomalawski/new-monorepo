<?php


namespace BacklogBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;

class ItemRepository extends EntityRepository implements PaginatorAwareInterface
{
    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @param Paginator $paginator
     */
    public function setPaginator(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return PaginationInterface
     */
    public function getByPage(int $userId, int $page, int $perPage): PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder('Items');
        $query = $queryBuilder->select()->where('Items.creator = ?1')->getQuery();
        $query->setParameter(1, $userId);

        return $this->paginator->paginate(
            $query,
            $page,
            $perPage,
            ['defaultSortFieldName' => 'Items.priority', 'defaultSortDirection' => 'desc']
        );
    }

}