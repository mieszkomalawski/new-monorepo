<?php


namespace PHPRum\DomainModel\Backlog\Exception;


class InvalidEstimate extends InvalidActionException
{
    /**
     * @param int $estimate
     * @param array $allowedEstimates
     * @return static
     */
    public static function create(int $estimate, array $allowedEstimates) : InvalidEstimate
    {
        return new static('Estimate ' . $estimate . ' not allowed, must be one of: ' . implode(',',
                $allowedEstimates));
    }
}