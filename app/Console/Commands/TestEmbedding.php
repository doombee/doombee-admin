<?php

namespace App\Console\Commands;

use App\Domain\AI\Contracts\EmbeddingProvider;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:test-embedding')]
#[Description('Command description')]
class TestEmbedding extends Command
{
    protected $signature = 'ai:test-embedding';

    public function handle(
        EmbeddingProvider $provider
    ) {
        $embedding = $provider->embed(
            'Scarpa da running nera uomo'
        );

        dump([
            'dimension' => count($embedding->vector),
            'model' => $embedding->model,
        ]);
    }
}
