<?php


namespace BacklogBundle\Repository;


use BacklogBundle\BacklogBundle;
use BacklogBundle\Entity\Backlog;
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
     * @param int $userId
     * @param int $page
     * @param ItemSearchQuery $itemSearchQuery
     * @param int $perPage
     * @return PaginationInterface
     */
    public function getByPage(
        int $userId,
        int $page,
        ItemSearchQuery $itemSearchQuery,
        int $perPage = BacklogBundle::MAX_ITEMS_PER_PAGE
    ): PaginationInterface {


        $queryBuilder = $this->createQueryBuilder('Items');
        $queryBuilder = $queryBuilder
            ->select()
            ->where('Items.creator = ?1');

        $itemSearchQuery->addConditions($queryBuilder);

        $query = $queryBuilder
            ->getQuery();

        $query->setParameter(1, $userId);

        $itemSearchQuery->bindParams($query);

        return $this->paginator->paginate(
            $query,
            $page,
            $perPage,
            // lower integer == higher priority
            ['defaultSortFieldName' => 'Items.priority', 'defaultSortDirection' => 'asc']
        );
    }

    /**
     * @param int $userId
     * @return Backlog
     */
    public function getFullBacklog(int $userId): Backlog
    {
        $queryBuilder = $this->createQueryBuilder('Items');
        $query = $queryBuilder
            ->select()
            ->where('Items.creator = ?1')
            ->getQuery();
        $query->setParameter(1, $userId);
        $items = $query->execute();

        return new Backlog($items);
    }

}