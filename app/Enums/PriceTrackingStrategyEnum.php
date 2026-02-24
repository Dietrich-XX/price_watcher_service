<?php

declare(strict_types=1);

namespace App\Enums;

enum PriceTrackingStrategyEnum: string
{
    case PARSING = 'parsing';
    case GRAPHQL_REMOTE_API = 'graphql_remote_api';
}
