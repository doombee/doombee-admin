<?php

namespace App\Domain\AI\Services;

use App\Domain\AI\Contracts\EmbeddingProvider;
use App\Domain\AI\Contracts\VectorStore;
use App\Domain\Catalog\DTO\CategoryHierarchyData;

final readonly class CategoryEmbeddingService
{
    public function __construct(
        private EmbeddingProvider $embeddingProvider,
        private VectorStore       $vectorStore,
    ) {}

    public function index(
        int $storeId,
        CategoryHierarchyData $category,
    ): void {

        $textEmbedding = $this->embeddingProvider->embed(
            'passage: ' . $category->path
        );

        $this->vectorStore->upsert(
            collection: 'categories',
            id: $this->createPointId(
                $storeId,
                $category->category->externalId
            ),
            vectors: [
                'text' => $textEmbedding->vector,
            ],
            payload: [
                'type'        => 'category',
                'store_id'    => $storeId,
                'external_id' => $category->category->externalId,
                'name'        => $category->category->name,
                'parent_id'   => $category->category->parentId,
                'path'        => $category->path,
            ],
        );
    }

    private function createPointId(
        int $storeId,
        string $externalId,
    ): string {
        return md5(
            $storeId . '-' . $externalId
        );
    }
}
