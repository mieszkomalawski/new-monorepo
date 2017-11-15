<?php
declare(strict_types=1);

namespace PHPRum\DomainModel\Backlog;


use MyCLabs\Enum\Enum;

class ItemStatus extends Enum
{
    const IN_PROGRESS = 'in_progress';
    const NEW = 'new';
    const DONE = 'done';

    /**
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->equals(ItemStatus::DONE());
    }
}