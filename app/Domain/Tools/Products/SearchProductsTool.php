<?php

namespace App\Domain\Tools\Products;

use App\Domain\AI\Tools\Contracts\ToolInterface;
use App\Domain\AI\Tools\DTO\ToolDefinition;
use App\Domain\AI\Tools\DTO\ToolResult;

class SearchProductsTool implements ToolInterface
{
    public function __construct(
        protected ProductSearchService $searchService,
    ) {}

    public function definition(): ToolDefinition
    {
        return new ToolDefinition(
            name: 'search_products',

            description: 'Searches the product catalog using a natural language query.',

            agent: 'products',


            parameters: [
                'query' => [
                    'type' => 'string',
                    'description' => 'The product search query.',
                    'required' => true,
                ],
            ],

            returns: [
                'type' => 'array',
                'description' => 'A list of matching products.',
            ],

            keywords: [
                'cerca',
                'cercare',
                'trova',
                'trovare',
                'mostra',
                'disponibile',
                'catalogo',
            ],

            documentation: <<<'DOC'
Use this tool when the user wants to find one or more products.

Examples:
- "I am looking for a black jacket."
- "Show me running shoes."
- "Do you have red Nike shoes?"
- "Find me a laptop under 1000 euros."

Do not use this tool when the user asks for information
about a specific known order, account, refund or support issue.
DOC
        );
    }

    public function execute(array $arguments, int $storeId): ToolResult
    {
        $products = $this->searchService->search(
            $arguments['query'],
            $storeId,
            10
        );

        return ToolResult::success([
            'query' => $arguments['query'],
            'products' => $products,
        ]);
    }
}
