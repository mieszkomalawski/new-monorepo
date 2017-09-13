<?php


namespace BacklogBundle\Repository;


use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class ItemSearchQuery
{
    /**
     * @var string
     */
    private $status;

    /**
     * @var int
     */
    private $epicId;

    /**
     * @var int
     */
    private $labelId;

    public function addConditions(QueryBuilder $queryBuilder)
    {
        if(is_null($this->status)){
            $queryBuilder->andWhere('Items.status != ?2');
        }else{
            $queryBuilder->andWhere('Items.status = ?2');
        }
    }

    public function bindParams(Query $query)
    {
        if(is_null($this->status)){
            $query->setParameter(2, 'done');
        }else{
            $query->setParameter(2, $this->status);
        }
    }

    /**
     * @return string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getEpicId(): ?string
    {
        return $this->epicId;
    }

    /**
     * @param string $epicId
     */
    public function setEpicId(string $epicId)
    {
        $this->epicId = $epicId;
    }

    /**
     * @return string
     */
    public function getLabelId(): ?string
    {
        return $this->labelId;
    }

    /**
     * @param string $labelId
     */
    public function setLabelId(string $labelId)
    {
        $this->labelId = $labelId;
    }

}