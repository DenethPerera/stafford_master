<?php

namespace App\Enums;

enum OrderStatus: string
{
    case DRAFT = 'Draft';
    case SENT = 'Sent';
    case PARTIAL = 'Partial';
    case COMPLETED = 'Completed';
    case CANCELLED = 'Cancelled';
    case APPROVED = 'Approved';
    case REJECTED = 'Rejected';
    case ACTIVE = 'Active';
    case INACTIVE = 'Inactive';

    /**
     * Get the Bootstrap 5 / Metronic badge classes.
     */
    public function color(): string
    {
        return match ($this) {
            self::ACTIVE, self::APPROVED, self::COMPLETED => 'bg-light-success text-success',
            self::DRAFT, self::PARTIAL => 'bg-light-warning text-warning',
            self::SENT => 'bg-light-primary text-primary',
            self::INACTIVE, self::CANCELLED, self::REJECTED => 'bg-light-danger text-danger',
        };
    }

    /**
     * Get the display label.
     */
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::SENT => 'Sent',
            self::PARTIAL => 'Partial',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
        };
    }
}
