<?php

namespace BacklogBundle\Repository;

use BacklogBundle\Entity\Epic;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class ItemSearchQuery
{
    const STATUS_PARAMETER_INDEX = 2;
    const EPIC_PARAMETER_INDEX = 3;
    /**
     * @var string
     */
    private $status;

    /**
     * @var Epic
     */
    private $epic;

    /**
     * @var int
     */
    private $labelId;

    public function addConditions(QueryBuilder $queryBuilder)
    {
        if (is_null($this->status)) {
            $queryBuilder->andWhere('Items.status != ?2');
        } else {
            $queryBuilder->andWhere('Items.status = ?2');
        }

        if (!is_null($this->epic)) {
            $queryBuilder->andWhere('Items.epic = ?3');
        }
    }

    public function bindParams(Query $query)
    {
        if (is_null($this->status)) {
            $query->setParameter(self::STATUS_PARAMETER_INDEX, 'done');
        } else {
            $query->setParameter(self::STATUS_PARAMETER_INDEX, $this->status);
        }

        if (!is_null($this->epic)) {
            $query->setParameter(self::EPIC_PARAMETER_INDEX, $this->epic);
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

    /**
     * @param Epic $epic
     */
    public function setEpic(Epic $epic)
    {
        $this->epic = $epic;
    }

    /**
     * @return Epic
     */
    public function getEpic(): ?Epic
    {
        return $this->epic;
    }
}
