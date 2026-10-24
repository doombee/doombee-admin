<?php

namespace App\Domain\AI\Services;

use App\Domain\AI\Contracts\EmbeddingProvider;
use App\Domain\AI\Contracts\ImageEmbeddingProvider;
use App\Domain\AI\Contracts\VectorStore;
use App\Domain\AI\Enums\ImageIndexingMode;
use App\Domain\Catalog\DTO\ProductData;

class ProductEmbeddingService
{
    public function __construct(
        private readonly EmbeddingProvider $embeddingProvider,
        private readonly ImageEmbeddingProvider $imageEmbeddingProvider,
        private readonly VectorStore $vectorStore,
    ) {}

    public function index(
        int $storeId,
        ProductData $product,
        ImageIndexingMode $imageIndexingMode = ImageIndexingMode::MAIN_ONLY,
    ): void
    {

        $textEmbedding = $this->embeddingProvider->embed(
            'passage: ' . $this->buildText($product)
        );

        $vectors = [
            'text' => $textEmbedding->vector,
        ];

        /*
         * The principal image belongs to the first point together with the text
         */
        if (!empty($product->images)) {
            $mainImageEmbedding = $this->imageEmbeddingProvider->embed(
                $product->images[0]
            );

            $vectors['image'] = $mainImageEmbedding->vector;
        }

        $this->vectorStore->upsert(
            collection: 'products',
            id: $this->createPointId(
                $storeId,
                $product->externalId
            ),
            vectors: $vectors,
            payload: [
                'type' => 'product',
                'store_id' => $storeId,
                'external_id' => $product->externalId,
                'sku' => $product->sku,
                'name' => $product->name,
                'price' => $product->price,
                'currency' => $product->currency,
                'categories' => $product->categories,
                'category_ids' => $product->categoryIds,
                'image_url' => $product->images[0] ?? null,
                'image_index' => 0,
            ],
        );

        /*
         * If only the main image should be indexed,
         * we are done.
         */
        if ($imageIndexingMode === ImageIndexingMode::MAIN_ONLY) {
            return;
        }

        /*
         * The next images have a separate point each
         */
        foreach (array_slice($product->images, 1) as $index => $imageUrl) {
            $imageIndex = $index + 1;

            $imageEmbedding = $this->imageEmbeddingProvider->embed(
                $imageUrl
            );

            $this->vectorStore->upsert(
                collection: 'products',
                id: $this->createImagePointId(
                    $storeId,
                    $product->externalId,
                    $imageIndex
                ),
                vectors: [
                    'image' => $imageEmbedding->vector,
                ],
                payload: [
                    'type' => 'image',
                    'store_id' => $storeId,
                    'external_id' => $product->externalId,
                    'image_url' => $imageUrl,
                    'image_index' => $imageIndex,
                ],
            );
        }
    }

    private function buildText(ProductData $product): string
    {
        return implode("\n", array_filter([
            $product->name,
            $product->description,
        ]));
        //$product->manufacturer,
        //implode(', ', $product->categories),
    }

    private function createPointId(
        int $storeId,
        string $externalId,
    ): string {
        return md5(
            $storeId.'-'.$externalId
        );
    }

    private function createImagePointId(
        int $storeId,
        string $externalId,
        int $imageIndex,
    ): string {
        return md5(
            $storeId.'-'.$externalId.'-image-'.$imageIndex
        );
    }
}
