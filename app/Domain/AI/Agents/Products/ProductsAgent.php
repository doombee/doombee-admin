<?php

namespace App\Domain\AI\Agents\Products;

use App\Domain\AI\Agents\Contracts\AgentInterface;
use App\Domain\AI\Agents\DTO\AgentDefinition;
use App\Domain\Tools\Products\SearchProductsTool;

class ProductsAgent implements AgentInterface
{
    public function definition(): AgentDefinition
    {
        return new AgentDefinition(
            name: 'products',

            description: 'Handles product discovery and product-related requests.',

            keywords: [
                'prodotto',
                'prodotti',
                'cerca prodotto',
                'catalogo',
                'disponibilità',
            ],

            documentation: <<<'DOC'
The products agent handles requests related to discovering
and obtaining information about products.

Typical requests include:
- finding products
- searching by product characteristics
- checking product information
- comparing products

The agent should not handle existing orders, refunds,
account management or general support requests.
DOC
        );
    }

    public function tools(): array
    {
        return [
            SearchProductsTool::class,
        ];
    }
}
