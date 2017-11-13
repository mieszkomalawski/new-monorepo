<?php

namespace PHPRum\DomainModel\Backlog\Exception;

class StatusNotAllowed extends InvalidActionException
{
    /**
     * @param string $status
     * @param array  $allowedStatuses
     *
     * @return static
     */
    public static function create(string $status, array $allowedStatuses): self
    {
        return new static('Status '.$status.' not allowed, must be one of: '.implode(
            ',',
                $allowedStatuses
        ));
    }
}
