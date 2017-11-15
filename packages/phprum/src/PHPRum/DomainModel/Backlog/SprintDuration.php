<?php
declare(strict_types=1);

namespace PHPRum\DomainModel\Backlog;


use MyCLabs\Enum\Enum;

class SprintDuration extends Enum
{
    const ONE_WEEK = '1_week';
    const TWO_WEEKS = '2_week';
    const THREE_WEEKS = '3_week';
    const FOUR_WEEKS = '4_week';
}