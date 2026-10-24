<?php

namespace App\Providers;

use App\Domain\AI\Agents\Products\ProductsAgent;
use App\Domain\AI\Agents\Registry\AgentRegistry;
use App\Domain\AI\Contracts\EmbeddingProvider;
use App\Domain\AI\Contracts\ImageEmbeddingProvider;
use App\Domain\AI\Contracts\VectorStore;
use App\Domain\AI\Tools\Registry\ToolRegistry;
use App\Integrations\Infinity\InfinityEmbeddingProvider;
use App\Integrations\Qdrant\QdrantVectorStore;
use App\Integrations\SigLIP\SigLIPEmbeddingProvider;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            EmbeddingProvider::class,
            InfinityEmbeddingProvider::class
        );

        $this->app->bind(
            ImageEmbeddingProvider::class,
            SigLIPEmbeddingProvider::class
        );

        $this->app->bind(
            VectorStore::class,
            QdrantVectorStore::class
        );

        $this->app->singleton(
            ToolRegistry::class
        );

        $this->app->singleton(
            AgentRegistry::class
        );
    }

    /**
     * Bootstrap services.
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $agentRegistry = $this->app->make(
            AgentRegistry::class
        );

        $agentRegistry->register(
            'products',
            ProductsAgent::class,
        );
    }
}
