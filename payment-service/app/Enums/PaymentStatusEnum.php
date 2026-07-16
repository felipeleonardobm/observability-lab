<?php

namespace App\Enums;

enum PaymentStatusEnum: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case APPROVED = 'approved';
    case DENIED = 'denied';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
}