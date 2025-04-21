<?php

namespace App\Constants;

class Constants
{
    const ROUND_PRECISION = 2;
    const REVIEW_MAX_RATING = 5;

    const RESERVATION_STATUS_BOOKED = 1;
    const RESERVATION_STATUS_COMPLETED = 2;
    const RESERVATION_STATUS_CANCELLED = 3;

    const REVIEW_PER_PAGE = 25;

    const ROLE_ADMIN = 1;
    const ROLE_REPRESENTATIVE = 2;
    const ROLE_USER = 3;

    public const PAYMENT_METHOD_CARD = 'card';
    public const PAYMENT_METHOD_CASH = 'cash';
}