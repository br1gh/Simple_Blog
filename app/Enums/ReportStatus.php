<?php

namespace App\Enums;

class ReportStatus
{
    public const REJECTED = -1;
    public const PENDING = 0;
    public const ENFORCED = 1;

    public static function getStatusFromString(string $statusName): int
    {
        switch ($statusName) {
            case 'rejected':
                return self::REJECTED;
            case 'enforced':
                return self::ENFORCED;
            default:
                return self::PENDING;
        }
    }
}
