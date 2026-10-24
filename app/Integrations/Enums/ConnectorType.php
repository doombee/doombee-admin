<?php

namespace App\Integrations\Enums;

enum ConnectorType: string
{
    case Shopify = 'shopify';
    case Shopware = 'shopware';
    case WooCommerce = 'woocommerce';
}
