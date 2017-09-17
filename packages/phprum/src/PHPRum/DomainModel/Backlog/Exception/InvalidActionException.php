<?php


namespace PHPRum\DomainModel\Backlog\Exception;


class InvalidActionException extends BacklogException
{
    const CANNOT_ADD_SUBITEM_TO_DONE = 2;

    const CANNOT_FINISH = 3;

    const CANNOT_START = 4;

    public static function createCannotAddSubTask() : InvalidActionException
    {
        return new static('Cannot add subtask to item that is already done', self::CANNOT_ADD_SUBITEM_TO_DONE);
    }

    public static function createCannotFinishTask() : InvalidActionException
    {
        return new static('Cannot finish task when subtask are not finished', self::CANNOT_FINISH);
    }

    public static function cannotStartBlockedTask(string $blockedBy) : InvalidActionException
    {
        return new static('Cannot start task that is blocked by: ' . $blockedBy , self::CANNOT_START);
    }
}