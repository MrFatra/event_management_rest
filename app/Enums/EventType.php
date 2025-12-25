<?php

namespace App\Enums;

enum EventType: string
{
    case Seminar  = 'seminar';
    case Webinar  = 'webinar';
    case Workshop = 'workshop';

    public function orderCode(): string
    {
        return match ($this) {
            self::Seminar  => 'SEM',
            self::Webinar  => 'WEB',
            self::Workshop => 'WKS',
        };
    }
}
