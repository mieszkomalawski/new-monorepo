<?php


namespace PHPRum\DomainModel\Backlog;


use MyCLabs\Enum\Enum;

class ItemStatus extends Enum
{
    const IN_PROGRESS = 'in_progress';
    const NEW = 'new';
    const DONE = 'done';
}