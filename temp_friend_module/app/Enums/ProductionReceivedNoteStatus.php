<?php

namespace App\Enums;

enum ProductionReceivedNoteStatus: string
{
    case PENDING = 'Pending';
    case PROCESSING = 'Processing';
    case COMPLETED = 'Completed';

    /**
     * Get the Bootstrap 5 / Metronic badge classes.
     */
    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'bg-light-warning text-warning',
            self::PROCESSING => 'bg-light-primary text-primary',
            self::COMPLETED => 'bg-light-success text-success',
        };
    }

    /**
     * Get the display label.
     */
    public function label(): string
    {
        return $this->value;
    }
}
